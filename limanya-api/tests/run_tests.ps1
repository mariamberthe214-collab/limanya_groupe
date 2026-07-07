# Run all API tests (PowerShell)
# Usage: Open PowerShell as Administrator and run: .\run_tests.ps1

$base = "http://localhost:8000"

# Vérifier que php est disponible
$php = Get-Command php -ErrorAction SilentlyContinue
if (-not $php) {
    Write-Host "PHP introuvable dans le PATH. Installez PHP ou exécutez depuis XAMPP."
    exit 1
}

# Démarrer le serveur PHP intégré
$proc = Start-Process -FilePath php -ArgumentList "-S localhost:8000 -t ..\limanya-api" -NoNewWindow -PassThru
Start-Sleep -Seconds 1
Write-Host "Serveur PHP démarré (PID $($proc.Id)). Lancement des tests..."

$tests = @(
    @{ method = 'GET';  url = "$base/actualites" },
    @{ method = 'POST'; url = "$base/actualites"; body = '{"titre":"T1","contenu":"C1","image":"i.jpg","date_publication":"2026-06-23 12:00:00"}' },
    @{ method = 'GET';  url = "$base/realisations" },
    @{ method = 'POST'; url = "$base/realisations"; body = '{"titre":"R1","description":"D1","image":"i.jpg","lieu":"Ville","date_realisation":"2026-06-01"}' },
    @{ method = 'GET';  url = "$base/devis" },
    @{ method = 'POST'; url = "$base/devis"; body = '{"nom":"Client","telephone":"0564613584","email":"c@c.com","service":"Forage","message":"Projet"}' },
    @{ method = 'GET';  url = "$base/contacts" },
    @{ method = 'POST'; url = "$base/contacts"; body = '{"nom":"Client","telephone":"0564613584","email":"c@c.com","message":"Bonjour"}' },
    @{ method = 'GET';  url = "$base/equipements" },
    @{ method = 'POST'; url = "$base/equipements"; body = '{"nom":"Foreuse","description":"Machine","image":"img.jpg","disponibilite":"disponible"}' }
)

foreach ($t in $tests) {
    try {
        if ($t.method -eq 'GET') {
            $resp = Invoke-RestMethod -Method Get -Uri $t.url -ErrorAction Stop
            Write-Host "[OK] GET $($t.url) -> OK"
        } else {
            $resp = Invoke-RestMethod -Method Post -Uri $t.url -ContentType 'application/json' -Body $t.body -ErrorAction Stop
            Write-Host "[OK] POST $($t.url) -> OK"
        }
    } catch {
        Write-Host "[ERROR] $($t.method) $($t.url) -> $($_.Exception.Message)"
    }
}

# Arrêter le serveur
Stop-Process -Id $proc.Id -Force
Write-Host "Tests terminés. Serveur arrêté." 
