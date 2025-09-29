# 🚀 FTP Deployment Guide - hjellum.net

Denne guiden hjelper deg med å deploye Oppskriftsamling til hjellum.net via FTP.

## 📋 Forutsetninger

Du trenger:
- **FTP-klient** (FileZilla, WinSCP, eller lignende)
- **FTP-opplysninger** for hjellum.net
- **MySQL-database** tilgjengelig
- **PHP-støtte** på serveren

## 🔑 FTP-opplysninger

Du trenger disse opplysningene fra din webhotell-leverandør:
```
FTP Server: ftp.hjellum.net (eller din server)
Port: 21 (standard) eller 22 (SFTP)
Username: [ditt_brukernavn]
Password: [ditt_passord]
Remote Directory: /public_html/oppskrifter/ (eller /www/oppskrifter/)
```

## 📁 Filstruktur for FTP

### Mappestruktur på serveren:
```
/public_html/oppskrifter/
├── index.html
├── styles.css
├── script.js
├── .htaccess
├── config/
│   └── database.php
├── models/
│   └── Recipe.php
├── api/
│   └── recipes.php
└── database/
    └── schema.sql
```

## 🛠️ Steg-for-steg FTP Deployment

### Steg 1: Forbered filer lokalt

1. **Opprett deployment-mappe**
```bash
mkdir ftp-deploy
cd ftp-deploy
```

2. **Kopier nødvendige filer**
```bash
# Fra prosjektmappen
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
```

### Steg 2: Konfigurer database

Rediger `ftp-deploy/config/database.php` med dine databaseopplysninger:

```php
<?php
class Database {
    private $host = 'localhost';           // Din MySQL-server
    private $db_name = 'oppskriftsamling'; // Database-navn
    private $username = 'ditt_brukernavn'; // MySQL-brukernavn
    private $password = 'ditt_passord';    // MySQL-passord
    private $charset = 'utf8mb4';
    
    // Resten av klassen forblir uendret...
}
```

### Steg 3: FTP-upload med FileZilla

#### 3.1 Koble til serveren
1. Åpne **FileZilla**
2. Skriv inn FTP-opplysningene:
   - **Host**: `ftp.hjellum.net`
   - **Username**: `[ditt_brukernavn]`
   - **Password**: `[ditt_passord]`
   - **Port**: `21` (eller `22` for SFTP)
3. Klikk **"Quickconnect"**

#### 3.2 Naviger til riktig mappe
- **Remote site**: Gå til `/public_html/oppskrifter/` (eller `/www/oppskrifter/`)
- **Local site**: Gå til din `ftp-deploy` mappe

#### 3.3 Upload filer
1. **Velg alle filer** i local site (venstre side)
2. **Drag & drop** til remote site (høyre side)
3. **Vent på upload** til alle filer er overført

### Steg 4: Sett riktige tillatelser

Etter upload, sett disse tillatelsene via FTP-klienten:

```
index.html: 644
styles.css: 644
script.js: 644
.htaccess: 644
config/database.php: 644
models/Recipe.php: 644
api/recipes.php: 644
database/schema.sql: 644

config/: 755
models/: 755
api/: 755
database/: 755
```

### Steg 5: Database-setup

#### 5.1 Opprett database
Logg inn på din MySQL-administrasjon (cPanel, phpMyAdmin, etc.):

```sql
CREATE DATABASE oppskriftsamling CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### 5.2 Importer skjema
1. Gå til **phpMyAdmin**
2. Velg `oppskriftsamling` database
3. Klikk **"Import"**
4. Last opp `database/schema.sql` filen
5. Klikk **"Go"**

### Steg 6: Test applikasjonen

1. **Åpne nettleser**
2. **Gå til**: `https://hjellum.net/oppskrifter/`
3. **Test funksjoner**:
   - ✅ Side laster
   - ✅ Oppskrifter vises
   - ✅ Kan legge til ny oppskrift
   - ✅ Søk fungerer
   - ✅ API-responser

## 🔧 Alternativ: Automatisk FTP-script

### Windows (PowerShell)
```powershell
# FTP-deployment script
$ftpServer = "ftp.hjellum.net"
$ftpUser = "ditt_brukernavn"
$ftpPass = "ditt_passord"
$remoteDir = "/public_html/oppskrifter/"

# Upload filer
$files = @("index.html", "styles.css", "script.js", ".htaccess")
foreach ($file in $files) {
    ftp -n $ftpServer << EOF
    user $ftpUser $ftpPass
    cd $remoteDir
    put $file
    quit
EOF
}
```

### Linux/macOS (bash)
```bash
#!/bin/bash
# FTP deployment script

FTP_SERVER="ftp.hjellum.net"
FTP_USER="ditt_brukernavn"
FTP_PASS="ditt_passord"
REMOTE_DIR="/public_html/oppskrifter/"

# Upload filer
lftp -c "
open ftp://$FTP_USER:$FTP_PASS@$FTP_SERVER
cd $REMOTE_DIR
mput index.html styles.css script.js .htaccess
mirror -R config/ config/
mirror -R models/ models/
mirror -R api/ api/
mirror -R database/ database/
quit
"
```

## 🐛 Feilsøking

### Vanlige problemer:

#### 1. "Connection refused"
- Sjekk FTP-server adresse
- Prøv port 22 (SFTP) i stedet for 21
- Kontroller at FTP er aktivert på kontoen

#### 2. "Permission denied"
- Sjekk brukernavn/passord
- Kontroller at du har skrivetilgang til mappen
- Prøv å opprette `/oppskrifter/` mappen manuelt

#### 3. "Database connection failed"
- Sjekk databaseopplysninger i `config/database.php`
- Kontroller at MySQL-tjenesten kjører
- Verifiser brukerrettigheter

#### 4. "404 Not Found"
- Sjekk at filer er lastet opp til riktig mappe
- Kontroller `.htaccess` er lastet opp
- Verifiser at `index.html` finnes

#### 5. "500 Internal Server Error"
- Sjekk PHP error logs
- Kontroller PHP-versjon (minimum 7.4)
- Verifiser at alle PHP-filer er komplette

## 📞 Support

Hvis du støter på problemer:

1. **Sjekk webhotell-logger**
   - Error logs
   - Access logs
   - PHP error logs

2. **Test tilkoblinger**
   - FTP-tilkobling
   - Database-tilkobling
   - PHP-funksjonalitet

3. **Kontakt webhotell-support**
   - Spør om PHP-versjon
   - Bekreft MySQL-tilgang
   - Verifiser mappestrukturen

## 🔄 Oppdateringer

For fremtidige oppdateringer:

1. **Gjør endringer** i koden
2. **Upload kun endrede filer** via FTP
3. **Test funksjonaliteten**
4. **Sjekk at alt fungerer**

---

**🌐 Nettadresse**: https://hjellum.net/oppskrifter/  
**📧 Support**: Kontakt webhotell-leverandør for tekniske spørsmål