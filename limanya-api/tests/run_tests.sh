#!/usr/bin/env bash
# Run API tests (bash)
BASEDIR=$(dirname "$0")/..
BASEURL="http://localhost:8000"

# Start PHP built-in server
php -S localhost:8000 -t "$BASEDIR/limanya-api" >/dev/null 2>&1 &
PHP_PID=$!
sleep 1

echo "Serveur PHP démarré (PID $PHP_PID). Lancement des tests..."

run_get() {
  url="$1"
  code=$(curl -s -o /dev/null -w "%{http_code}" "$url")
  echo "GET $url -> HTTP $code"
}

run_post() {
  url="$1"; data="$2"
  code=$(curl -s -o /dev/null -w "%{http_code}" -X POST -H "Content-Type: application/json" -d "$data" "$url")
  echo "POST $url -> HTTP $code"
}

run_get "$BASEURL/actualites"
run_post "$BASEURL/actualites" '{"titre":"T1","contenu":"C1","image":"i.jpg","date_publication":"2026-06-23 12:00:00"}'
run_get "$BASEURL/realisations"
run_post "$BASEURL/realisations" '{"titre":"R1","description":"D1","image":"i.jpg","lieu":"Ville","date_realisation":"2026-06-01"}'
run_get "$BASEURL/devis"
run_post "$BASEURL/devis" '{"nom":"Client","telephone":"0564613584","email":"c@c.com","service":"Forage","message":"Projet"}'
run_get "$BASEURL/contacts"
run_post "$BASEURL/contacts" '{"nom":"Client","telephone":"0564613584","email":"c@c.com","message":"Bonjour"}'
run_get "$BASEURL/equipements"
run_post "$BASEURL/equipements" '{"nom":"Foreuse","description":"Machine","image":"img.jpg","disponibilite":"disponible"}'

# Stop server
kill $PHP_PID
echo "Tests terminés. Serveur arrêté."