#!/bin/bash

# FTP Deployment Script for Oppskriftsamling
# Dette scriptet uploader alle filer til hjellum.net via FTP

echo "🚀 FTP Deployment til hjellum.net"
echo "================================="

# Konfigurer disse verdiene for din FTP-server
FTP_SERVER="ftp.hjellum.net"          # Endre til din FTP-server
FTP_USER="ditt_brukernavn"            # Endre til ditt FTP-brukernavn
FTP_PASS="ditt_passord"               # Endre til ditt FTP-passord
REMOTE_DIR="/public_html/oppskrifter/" # Endre til riktig mappe

# Farger for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Funksjon for å vise meldinger
log_info() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

log_warn() {
    echo -e "${YELLOW}[WARN]${NC} $1"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Sjekk at lftp er installert
if ! command -v lftp &> /dev/null; then
    log_error "lftp er ikke installert. Installer det med:"
    echo "Ubuntu/Debian: sudo apt-get install lftp"
    echo "macOS: brew install lftp"
    echo "CentOS/RHEL: sudo yum install lftp"
    exit 1
fi

# Opprett deployment-mappe
log_info "Forbereder filer for deployment..."
mkdir -p ftp-deploy

# Kopier filer til deployment-mappe
log_info "Kopierer filer..."
cp index.html ftp-deploy/
cp styles.css ftp-deploy/
cp script.js ftp-deploy/
cp .htaccess ftp-deploy/

# Opprett mapper og kopier PHP-filer
mkdir -p ftp-deploy/{config,models,api,database}
cp config/database.production.php ftp-deploy/config/database.php
cp models/Recipe.php ftp-deploy/models/
cp api/recipes.php ftp-deploy/api/
cp database/schema.sql ftp-deploy/database/

log_info "Filer forberedt i ftp-deploy/ mappen"

# Vis filstruktur
echo ""
log_info "Filstruktur som skal uploades:"
tree ftp-deploy/ 2>/dev/null || find ftp-deploy/ -type f | sed 's/^/  /'

# Bekreft deployment
echo ""
read -p "Er du klar til å uploade til $FTP_SERVER? (y/N): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    log_warn "Deployment avbrutt"
    exit 0
fi

# Upload filer via FTP
log_info "Starter FTP-upload til $FTP_SERVER..."

lftp -c "
set ftp:ssl-allow no
set ssl:verify-certificate no
open ftp://$FTP_USER:$FTP_PASS@$FTP_SERVER
cd $REMOTE_DIR
lcd ftp-deploy

# Upload hovedfiler
put index.html
put styles.css
put script.js
put .htaccess

# Upload mapper og filer
mirror -R config/ config/
mirror -R models/ models/
mirror -R api/ api/
mirror -R database/ database/

# Sett tillatelser
chmod 644 index.html
chmod 644 styles.css
chmod 644 script.js
chmod 644 .htaccess
chmod 644 config/database.php
chmod 644 models/Recipe.php
chmod 644 api/recipes.php
chmod 644 database/schema.sql

chmod 755 config/
chmod 755 models/
chmod 755 api/
chmod 755 database/

quit
"

if [ $? -eq 0 ]; then
    log_info "FTP-upload fullført!"
else
    log_error "FTP-upload feilet!"
    exit 1
fi

# Rydd opp
log_info "Rydder opp lokale filer..."
rm -rf ftp-deploy/

echo ""
log_info "🎉 Deployment fullført!"
echo ""
echo "📱 Test applikasjonen på: https://hjellum.net/oppskrifter/"
echo "🔗 API-endepunkt: https://hjellum.net/oppskrifter/api/recipes.php"
echo ""
echo "📝 Neste steg:"
echo "1. Konfigurer database i config/database.php på serveren"
echo "2. Importer database/schema.sql til MySQL"
echo "3. Test alle funksjoner i nettleseren"
echo ""
echo "🐛 Hvis noe ikke fungerer:"
echo "- Sjekk PHP error logs"
echo "- Verifiser database-tilkobling"
echo "- Kontroller fil-tillatelser"