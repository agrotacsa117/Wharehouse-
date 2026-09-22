<?php

declare(strict_types=1);

/**
 * Crea (o reutiliza) el usuario de prueba y N filas frescas de
 * warehouse_inventory (una por producto distinto) para la variante de
 * carrito multi-producto del test de concurrencia.
 *
 * Uso: php scripts/concurrency/seed-cart.php <SCENARIO> [numProducts=3] [initialQtyEach=100]
 * SCENARIO: OUT | SALE | RELOCATION | LOCATION_UPDATE | TRANSFER
 *
 * Imprime una linea JSON al final con todo lo que
 * k6/concurrency-cart-test.js necesita como variables de entorno.
 */

require __DIR__.'/db.php';
require __DIR__.'/lib/test_user.php';

$scenario = $argv[1] ?? null;
$numProducts = isset($argv[2]) ? (int) $argv[2] : 3;
$initialQtyEach = isset($argv[3]) ? (int) $argv[3] : 100;

$valid = ['OUT', 'SALE', 'RELOCATION', 'LOCATION_UPDATE'];
if (! in_array($scenario, $valid, true)) {
    fwrite(STDERR, 'Escenario invalido. Usa uno de: '.implode(', ', $valid)."\n");
    exit(1);
}

// product_id reales ya presentes en warehouse_inventory para warehouse_id=25
// ("Productos TACSA") en la BD de prueba -- FKs validas sin tocar otras tablas.
$availableProductIds = ['tac036', 'tac007', 'tac039', 'tac049'];
if ($numProducts < 1 || $numProducts > count($availableProductIds)) {
    fwrite(STDERR, 'numProducts debe estar entre 1 y '.count($availableProductIds)."\n");
    exit(1);
}

// Cantidad distinta a retirar por producto, para que el carrito ejerza
// "distintos productos y cantidades" en vez de repetir el mismo monto.
$amountsCycle = [10, 15, 20, 25];

$pdo = makePdo();

// --- Usuario de prueba (idempotente) ---
['email' => $testEmail, 'password' => $testPassword, 'userId' => $testUserId] = ensureTestUser($pdo);

// --- N filas de inventario, una por producto ---
$warehouseId = 25;
$products = [];

$movementCountStmt = $pdo->prepare('SELECT COUNT(*) FROM warehouse_inventory_movements');

for ($i = 0; $i < $numProducts; $i++) {
    $productId = $availableProductIds[$i];
    $amount = $amountsCycle[$i % count($amountsCycle)];
    $lotNumber = 'K6-CART-'.$productId.'-'.time();

    $pdo->prepare('
        INSERT INTO warehouse_inventory
            (warehouse_id, product_id, rack, _level, warehouse_name, quantity,
             lot_number, reason, expiration_date, manufacturing_date, active_inventory,
             created_at, updated_at)
        VALUES (?, ?, 1, 1, ?, ?, ?, ?, DATE_ADD(NOW(), INTERVAL 1 YEAR), DATE_SUB(NOW(), INTERVAL 30 DAY), 1, NOW(), NOW())
    ')->execute([
        $warehouseId,
        $productId,
        'TacsaFato; 1 lt.',
        $initialQtyEach,
        $lotNumber,
        'k6 concurrency cart seed',
    ]);

    $inventoryId = (int) $pdo->lastInsertId();

    // Movimiento de entrada (IN), igual al que registra
    // WarehouseInventoryServiceImplementation::create() cuando se da de alta
    // inventario via el endpoint real (WareouseInventoryController::store),
    // para que el seed deje la misma huella en warehouse_inventory_movements
    // que dejaria un alta real antes de correr el escenario de concurrencia.
    $movementCountStmt->execute();
    $folio = 'MOV-'.str_pad((string) ((int) $movementCountStmt->fetchColumn() + 1), 6, '0', STR_PAD_LEFT);

    $pdo->prepare('
        INSERT INTO warehouse_inventory_movements
            (folio, warehouse_inventory_id, movement_type, quantity, reason, user_id,
             is_reversed, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, 0, NOW(), NOW())
    ')->execute([
        $folio,
        $inventoryId,
        'IN',
        $initialQtyEach,
        'k6 concurrency cart seed',
        $testUserId,
    ]);

    $products[] = [
        'inventoryId' => $inventoryId,
        'productId' => $productId,
        'initialQty' => $initialQtyEach,
        'amount' => $amount,
        'lotNumber' => $lotNumber,
    ];
}

$payload = [
    'scenario' => $scenario,
    'warehouseId' => $warehouseId,
    'products' => $products,
    'testEmail' => $testEmail,
    'testPassword' => $testPassword,
    // Campos extra requeridos por tipo de movimiento (ver WarehouseOutputDtoMapper).
    'destWarehouseId' => 22, // "Insecticidas" -- distinto al warehouse_id origen, usado por RELOCATION
    'newRack' => 3,
    'newLevel' => 2,
    'newRackR' => 5,
    'newLevelR' => 1,
    'destWarehouseName' => 'campeche', // opcion valida del select de TRANSFER en create.blade.php
    'folioTransfer' => random_int(100000, 999999),
    'invoiceSap' => random_int(1000, 9999),
];

// Mismo motivo que en seed.php: evitar que warnings de stdout ensucien el
// JSON que leen los scripts de orquestacion de PowerShell.
$outFile = __DIR__."/last-seed-cart-{$scenario}.json";
file_put_contents($outFile, json_encode($payload, JSON_PRETTY_PRINT));

fwrite(STDERR, "Seed cart OK -> {$outFile}\n");
echo json_encode($payload, JSON_PRETTY_PRINT), "\n";
