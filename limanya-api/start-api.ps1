$phpRoot = 'C:\Users\THINKPAD\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.2_Microsoft.Winget.Source_8wekyb3d8bbwe'
$phpExe = Join-Path $phpRoot 'php.exe'
$apiDir = 'C:\Users\THINKPAD\limanya-groupe\limanya-api'
$phpIni = Join-Path $apiDir 'php.ini'

if (-not (Test-Path $phpExe)) {
    Write-Error "PHP executable not found at $phpExe"
    exit 1
}

if (-not (Test-Path $phpIni)) {
    Write-Error "PHP ini file not found at $phpIni"
    exit 1
}

Write-Host "Starting Limanya API on http://127.0.0.1:8000"
$env:PHPRC = $phpRoot
Set-Location $apiDir
& $phpExe -c $phpIni -S 127.0.0.1:8000
