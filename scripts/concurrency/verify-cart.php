<?php

declare(strict_types=1);

/**
 * Veredicto de la corrida de concurrencia multi-producto: repite, por cada
 * fila de warehouse_inventory sembrada por seed-cart.php, la misma
 * verificacion que verify.php hace para una sola fila, y agrega un veredicto
 * global.
 *
 * Uso: php scripts/concurrency/verify-cart.php <SCENARIO> <ruta-al-json-de-seed>
 */

require __DIR__.'/db.php';

[$scenario, $seedFile] = [$argv[1] ?? null, $argv[2] ?? null];

if (! $scenario || ! $seedFile) {
    fwrite(STDERR, "Uso: php verify-cart.php <SCENARIO> <ruta-al-json-de-seed>\n");
    exit(1);
}

if (! is_file($seedFile)) {
    fwrite(STDERR, "No existe el archivo de seed: {$seedFile}\n");
    exit(1);
}

$seed = json_decode((string) file_get_contents($seedFile), true, 512, JSON_THROW_ON_ERROR);
$products = $seed['products'] ?? [];

if (! $products) {
    fwrite(STDERR, "El seed no tiene 'products'.\n");
    exit(1);
}

$pdo = makePdo();
$relocationTypes = ['RELOCATION', 'LOCATION_UPDATE'];
$isRelocation = in_array($scenario, $relocationTypes, true);

$finalQtyStmt = $pdo->prepare('SELECT quantity FROM warehouse_inventory WHERE id = ?');
$countStmt = $pdo->prepare($isRelocation
    ? '
        SELECT COUNT(*), COALESCE(SUM(quantity), 0)
        FROM warehouse_inventory_movements
        WHERE relocated_from_inventory_id = ? AND movement_type = ?
    '
    : '
        SELECT COUNT(*), COALESCE(SUM(quantity), 0)
        FROM warehouse_inventory_movements
        WHERE warehouse_inventory_id = ? AND movement_type = ?
    ');

$overallOk = true;

foreach ($products as $product) {
    $inventoryId = (int) $product['inventoryId'];
    $productId = $product['productId'];
    $initialQty = (int) $product['initialQty'];
    $amount = (int) $product['amount'];

    $finalQtyStmt->execute([$inventoryId]);
    $finalQty = (int) $finalQtyStmt->fetchColumn();

    $countStmt->execute([$inventoryId, $scenario]);
    [$successCount, $sumWithdrawn] = $countStmt->fetch(PDO::FETCH_NUM);
    $successCount = (int) $successCount;
    $sumWithdrawn = (int) $sumWithdrawn;

    $maxSafeSuccesses = intdiv($initialQty, $amount);
    $expectedFinalQty = $initialQty - ($maxSafeSuccesses * $amount);

    $oversold = $finalQty < 0;
    $tooManySuccesses = $successCount > $maxSafeSuccesses;
    $mismatchedMath = ($initialQty - $sumWithdrawn) !== $finalQty;

    $rowOk = ! ($oversold || $tooManySuccesses);
    $overallOk = $overallOk && $rowOk;
    $verdict = $rowOk ? 'OK (lock sostuvo la fila)' : 'VIOLACION (race condition)';

    echo "== {$scenario} producto={$productId} (warehouse_inventory.id={$inventoryId}) ==\n";
    echo "Cantidad inicial:            {$initialQty}\n";
    echo "Cantidad por request:        {$amount}\n";
    echo "Max exitos seguros:          {$maxSafeSuccesses}\n";
    echo "Exitos registrados:          {$successCount}\n";
    echo "Suma retirada (movimientos): {$sumWithdrawn}\n";
    echo "Cantidad final en BD:        {$finalQty}\n";
    echo "Cantidad final esperada:     {$expectedFinalQty}\n";
    if ($mismatchedMath) {
        echo "ADVERTENCIA: initial_qty - suma_retirada != cantidad final en BD ".
             "(posible lost update: dos requests leyeron el mismo valor antes de escribir).\n";
    }
    echo "VEREDICTO: {$verdict}\n\n";
}

$overallVerdict = $overallOk ? 'OK (todas las filas sostuvieron el lock)' : 'VIOLACION (al menos una fila con race condition)';
echo "=== VEREDICTO AGREGADO ({$scenario}, ".count($products)." productos): {$overallVerdict} ===\n";
