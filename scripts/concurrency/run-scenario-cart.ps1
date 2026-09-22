<#
.SYNOPSIS
    Corre UN escenario de concurrencia end-to-end con carrito MULTI-PRODUCTO:
    seed-cart -> k6 (carrito con varios items) -> verificacion SQL por fila.

.EXAMPLE
    ./scripts/concurrency/run-scenario-cart.ps1 -Scenario OUT
    ./scripts/concurrency/run-scenario-cart.ps1 -Scenario TRANSFER -Vus 30 -NumProducts 4 -InitialQty 100
#>
param(
    [Parameter(Mandatory = $true)]
    [ValidateSet('OUT', 'SALE', 'RELOCATION', 'LOCATION_UPDATE')]
    [string]$Scenario,

    [int]$Vus = 20,
    [int]$NumProducts = 3,
    [int]$InitialQty = 100,
    [string]$BaseUrl = 'http://127.0.0.1:8000'
)

$ErrorActionPreference = 'Stop'
$repoRoot = Split-Path -Parent (Split-Path -Parent $PSScriptRoot)
Set-Location $repoRoot

Write-Host "=== [$Scenario] Seeding carrito ($NumProducts productos, initial_qty=$InitialQty c/u) ===" -ForegroundColor Cyan
php -d display_errors=stderr scripts/concurrency/seed-cart.php $Scenario $NumProducts $InitialQty | Out-Null

$seedFile = "scripts/concurrency/last-seed-cart-$Scenario.json"
if (-not (Test-Path $seedFile)) {
    throw "No se genero $seedFile -- revisa la salida de seed-cart.php arriba."
}

$seed = Get-Content $seedFile -Raw | ConvertFrom-Json

Write-Host "Productos sembrados:" -ForegroundColor DarkGray
foreach ($product in $seed.products) {
    Write-Host "  - warehouse_inventory.id=$($product.inventoryId) productId=$($product.productId) qty=$($product.initialQty) amount=$($product.amount)" -ForegroundColor DarkGray
}

Write-Host "=== [$Scenario] Lanzando k6: $Vus VUs concurrentes, carrito de $($seed.products.Count) items c/u ===" -ForegroundColor Cyan
# El array de productos se lee desde el propio archivo de seed (via open() en
# el script k6) en vez de pasarse por -e: las comillas embebidas de un JSON
# complejo se corrompen al cruzar la linea de comandos hacia un exe nativo
# (k6.exe) en PowerShell/Windows.
$seedFileAbsolute = (Resolve-Path $seedFile).Path
 
$k6Env = @(
    "-e", "BASE_URL=$BaseUrl",
    "-e", "EMAIL=$($seed.testEmail)",
    "-e", "PASSWORD=$($seed.testPassword)",
    "-e", "SCENARIO=$Scenario",
    "-e", "SEED_FILE=$seedFileAbsolute",
    "-e", "VUS=$Vus",
    "-e", "DEST_WAREHOUSE_ID=$($seed.destWarehouseId)",
    "-e", "NEW_RACK=$($seed.newRack)",
    "-e", "NEW_LEVEL=$($seed.newLevel)",
    "-e", "NEW_RACK_R=$($seed.newRackR)",
    "-e", "NEW_LEVEL_R=$($seed.newLevelR)",
    "-e", "DEST_WAREHOUSE_NAME=$($seed.destWarehouseName)",
    "-e", "FOLIO_TRANSFER=$($seed.folioTransfer)",
    "-e", "INVOICE_SAP=$($seed.invoiceSap)"
)

k6 run @k6Env k6/concurrency-cart-test.js
if ($LASTEXITCODE -ne 0) {
    Write-Warning "k6 termino con codigo $LASTEXITCODE (revisa checks/errores arriba); igual se corre la verificacion SQL."
}

Write-Host "=== [$Scenario] Verificando estado final en BD (por producto) ===" -ForegroundColor Cyan
php -d display_errors=stderr scripts/concurrency/verify-cart.php $Scenario $seedFile
