<#
.SYNOPSIS
    Corre UN escenario de concurrencia end-to-end: seed -> k6 -> verificacion SQL.

.EXAMPLE
    ./scripts/concurrency/run-scenario.ps1 -Scenario OUT
    ./scripts/concurrency/run-scenario.ps1 -Scenario TRANSFER -Vus 30 -Amount 10 -InitialQty 100
#>
param(
    [Parameter(Mandatory = $true)]
    [ValidateSet('OUT', 'SALE', 'RELOCATION', 'LOCATION_UPDATE')]
    [string]$Scenario,

    [int]$Vus = 20,
    [int]$Amount = 10,
    [int]$InitialQty = 100,
    [string]$BaseUrl = 'http://127.0.0.1:8000'
)

$ErrorActionPreference = 'Stop'
$repoRoot = Split-Path -Parent (Split-Path -Parent $PSScriptRoot)
Set-Location $repoRoot

Write-Host "=== [$Scenario] Seeding datos de prueba (initial_qty=$InitialQty) ===" -ForegroundColor Cyan
php -d display_errors=stderr scripts/concurrency/seed.php $Scenario $InitialQty | Out-Null

$seedFile = "scripts/concurrency/last-seed-$Scenario.json"
if  (-not (Test-Path $seedFile)) {
    throw "No se genero $seedFile -- revisa la salida de seed.php arriba."
}

$seed = Get-Content $seedFile -Raw | ConvertFrom-Json

Write-Host "Fila de prueba: warehouse_inventory.id=$($seed.inventoryId), quantity=$($seed.initialQty)" -ForegroundColor DarkGray

Write-Host "=== [$Scenario] Lanzando k6: $Vus VUs concurrentes x $Amount unidades c/u ===" -ForegroundColor Cyan
$k6Env = @(
    "-e", "BASE_URL=$BaseUrl",
    "-e", "EMAIL=$($seed.testEmail)",
    "-e", "PASSWORD=$($seed.testPassword)",
    "-e", "SCENARIO=$Scenario",
    "-e", "INVENTORY_ID=$($seed.inventoryId)",
    "-e", "VUS=$Vus",
    "-e", "AMOUNT=$Amount",
    "-e", "DEST_WAREHOUSE_ID=$($seed.destWarehouseId)",
    "-e", "NEW_RACK=$($seed.newRack)",
    "-e", "NEW_LEVEL=$($seed.newLevel)",
    "-e", "NEW_RACK_R=$($seed.newRackR)",
    "-e", "NEW_LEVEL_R=$($seed.newLevelR)",
    "-e", "DEST_WAREHOUSE_NAME=$($seed.destWarehouseName)",
    "-e", "FOLIO_TRANSFER=$($seed.folioTransfer)",
    "-e", "INVOICE_SAP=$($seed.invoiceSap)"
)

k6 run @k6Env k6/concurrency-test.js
if ($LASTEXITCODE -ne 0) {
    Write-Warning "k6 termino con codigo $LASTEXITCODE (revisa checks/errores arriba); igual se corre la verificacion SQL."
}

Write-Host "=== [$Scenario] Verificando estado final en BD ===" -ForegroundColor Cyan
php -d display_errors=stderr scripts/concurrency/verify.php $Scenario $seed.inventoryId $InitialQty $Amount
