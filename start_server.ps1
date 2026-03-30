# Pantau Cuaca Auto-Server Script
# Jalankan dengan: .\start_server.ps1

param(
    [int]$Port = 8000,
    [string]$HostIP = "127.0.0.1"
)

$projectPath = "C:\laragon\www\PANTAU_CUACA"
$phpPath = "C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe"

Write-Host "🚀 Starting Pantau Cuaca Server..." -ForegroundColor Green
Write-Host "📍 URL: http://$HostIP`:$Port" -ForegroundColor Cyan
Write-Host "📁 Path: $projectPath" -ForegroundColor Yellow
Write-Host "🔄 Auto-restart enabled (Ctrl+C to stop)" -ForegroundColor Magenta
Write-Host ""

Set-Location $projectPath

# Function to check if server is responding
function Test-Server {
    try {
        $response = Invoke-WebRequest -Uri "http://$HostIP`:$Port" -TimeoutSec 5 -UseBasicParsing
        return $true
    } catch {
        return $false
    }
}

# Main server loop with auto-restart
while ($true) {
    Write-Host "$(Get-Date -Format 'yyyy-MM-dd HH:mm:ss') - Starting server..." -ForegroundColor Gray

    try {
        & $phpPath -S "$HostIP`:$Port" -t public
    } catch {
        Write-Host "❌ Server crashed! Restarting in 3 seconds..." -ForegroundColor Red
        Start-Sleep -Seconds 3
    }
}