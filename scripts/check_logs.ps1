# Script to check today's logs
$logFile = "writable\logs\log-$(Get-Date -Format 'yyyy-MM-dd').log"

if (Test-Path $logFile) {
    Write-Host "===== LAST 50 LINES OF TODAY'S LOG =====" -ForegroundColor Green
    Get-Content $logFile -Tail 50
} else {
    Write-Host "Log file not found: $logFile" -ForegroundColor Red
}
