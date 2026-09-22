<?php

declare(strict_types=1);

/**
 * Veredicto de la corrida de concurrencia contra UNA fila de warehouse_inventory.
 *
 * Uso: php scripts/concurrency/verify.php <SCENARIO> <inventory_id> <initial_qty> <amount_per_request>
 *
 * Compara:
 *  - cantidad final en BD contra la cantidad final esperada si el lock funciona,
 *  - cantidad de movimientos exitosos registrados contra el maximo posible sin sobreventa.
 * Si alguno se sale de lo esperado -> evidencia de race condition (lockForUpdate no protegio la fila).
 */

require __DIR__.'/db.php';

[$scenario, $inventoryId, $initialQty, $amount] = [
    $argv[1] ?? null,
    isset($argv[2]) ? (int) $argv[2] : null,
    isset($argv[3]) ? (int) $argv[3] : null,
    isset($argv[4]) ? (int) $argv[4] : null,
];

if (! $scenario || ! $inventoryId || ! $initialQty || ! $amount) {
    fwrite(STDERR, "Uso: php verify.php <SCENARIO> <inventory_id> <initial_qty> <amount_per_request>\n");
    exit(1);
}

$pdo = makePdo();

$finalQty = $pdo->prepare('SELECT quantity FROM warehouse_inventory WHERE id = ?');
$finalQty->execute([$inventoryId]);
$finalQty = $finalQty->fetchColumn();

$relocationTypes = ['RELOCATION', 'LOCATION_UPDATE'];
if (in_array($scenario, $relocationTypes, true)) {
    $countStmt = $pdo->prepare('
        SELECT COUNT(*), COALESCE(SUM(quantity), 0)
        FROM warehouse_inventory_movements
        WHERE relocated_from_inventory_id = ? AND movement_type = ?
    ');
} else {
    $countStmt = $pdo->prepare('
        SELECT COUNT(*), COALESCE(SUM(quantity), 0)
        FROM warehouse_inventory_movements
        WHERE warehouse_inventory_id = ? AND movement_type = ?
    ');
}
$countStmt->execute([$inventoryId, $scenario]);
[$successCount, $sumWithdrawn] = $countStmt->fetch(PDO::FETCH_NUM);
$successCount = (int) $successCount;
$sumWithdrawn = (int) $sumWithdrawn;

$maxSafeSuccesses = intdiv($initialQty, $amount);
$expectedFinalQty = $initialQty - ($maxSafeSuccesses * $amount);

$oversold = $finalQty < 0;
$tooManySuccesses = $successCount > $maxSafeSuccesses;
$mismatchedMath = ($initialQty - $sumWithdrawn) !== (int) $finalQty;

$verdict = ($oversold || $tooManySuccesses) ? 'VIOLACION (race condition)' : 'OK (lock sostuvo la fila)';

echo "== {$scenario} (warehouse_inventory.id={$inventoryId}) ==\n";
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
echo "VEREDICTO: {$verdict}\n";
