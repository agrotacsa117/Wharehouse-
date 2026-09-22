<?php

declare(strict_types=1);

/**
 * Crea (o reutiliza) el usuario de prueba y una fila fresca de
 * warehouse_inventory para UN escenario de concurrencia.
 *
 * Uso: php scripts/concurrency/seed.php <SCENARIO> [initial_qty]
 * SCENARIO: OUT | SALE | RELOCATION | LOCATION_UPDATE | TRANSFER
 *
 * Imprime una linea JSON al final con todo lo que k6/concurrency-test.js
 * necesita como variables de entorno.
 */

require __DIR__.'/db.php';
require __DIR__.'/lib/test_user.php';

$scenario = $argv[1] ?? null;
$initialQty = isset($argv[2]) ? (int) $argv[2] : 100;

$valid = ['OUT', 'SALE', 'RELOCATION', 'LOCATION_UPDATE', 'TRANSFER'];
if (! in_array($scenario, $valid, true)) {
    fwrite(STDERR, 'Escenario invalido. Usa uno de: '.implode(', ', $valid)."\n");
    exit(1);
}

$pdo = makePdo();

// --- Usuario de prueba (idempotente) ---
['email' => $testEmail, 'password' => $testPassword] = ensureTestUser($pdo);

// --- Fila de inventario dedicada al escenario ---
// warehouse_id=25 ("Productos TACSA") / product_id='tac036' ya existen en la DB de prueba.
$warehouseId = 25;
$productId = 'tac036';
$lotNumber = 'K6-'.$scenario.'-'.time();

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
    $initialQty,
    $lotNumber,
    'k6 concurrency seed',
]);

$inventoryId = (int) $pdo->lastInsertId();

$payload = [
    'scenario' => $scenario,
    'inventoryId' => $inventoryId,
    'initialQty' => $initialQty,
    'warehouseId' => $warehouseId,
    'productId' => $productId,
    'lotNumber' => $lotNumber,
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

// El PHP CLI de esta maquina imprime warnings de arranque (sqlsrv desalineado)
// directo a stdout, lo que ensuciaria un parseo de JSON por stdout. Por eso
// el resultado se escribe tambien a un archivo de punto fijo que los scripts
// de orquestacion (PowerShell) leen en vez de parsear stdout.
$outFile = __DIR__."/last-seed-{$scenario}.json";
file_put_contents($outFile, json_encode($payload, JSON_PRETTY_PRINT));

fwrite(STDERR, "Seed OK -> {$outFile}\n");
echo json_encode($payload, JSON_PRETTY_PRINT), "\n";
