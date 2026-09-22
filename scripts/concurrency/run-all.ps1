<#
.SYNOPSIS
    Corre los 5 escenarios de concurrencia (OUT, SALE, RELOCATION, LOCATION_UPDATE,
    TRANSFER) uno tras otro contra el servidor local y arma el resumen final.

.EXAMPLE
    ./scripts/concurrency/run-all.ps1
    ./scripts/concurrency/run-all.ps1 -Vus 30 -Amount 15 -InitialQty 150
#>
param(
    [int]$Vus = 20,
    [int]$Amount = 10,
    [int]$InitialQty = 100,
    [string]$BaseUrl = 'http://127.0.0.1:8000'
)

$ErrorActionPreference = 'Stop'
$scenarios = @('OUT', 'SALE', 'RELOCATION', 'LOCATION_UPDATE', 'TRANSFER')
$results = @()

foreach ($scenario in $scenarios) {
    Write-Host ""
    Write-Host "############################################################" -ForegroundColor Yellow
    Write-Host "  ESCENARIO: $scenario" -ForegroundColor Yellow
    Write-Host "############################################################" -ForegroundColor Yellow

    $output = & "$PSScriptRoot/run-scenario.ps1" -Scenario $scenario -Vus $Vus -Amount $Amount -InitialQty $InitialQty -BaseUrl $BaseUrl 2>&1 | Tee-Object -Variable capturedOutput
    $verdictLine = $capturedOutput | Select-String -Pattern 'VEREDICTO:' | Select-Object -Last 1

    $results += [PSCustomObject]@{
        Scenario = $scenario
        Veredicto = if ($verdictLine) { ($verdictLine -replace '.*VEREDICTO:\s*', '') } else { 'SIN VEREDICTO (revisar log arriba)' }
    }
}

Write-Host ""
Write-Host "==================== RESUMEN FINAL ====================" -ForegroundColor Green
$results | Format-Table -AutoSize
