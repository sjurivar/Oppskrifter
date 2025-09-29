# 🚀 FTP Deployment - Enkle instruksjoner

## ✅ Filene er klare!

Alle filer er forberedt i `ftp-deploy/` mappen og klar for upload til hjellum.net.

## 📁 Filene som skal uploades:

```
ftp-deploy/
├── index.html              ✅ Hovedside
├── styles.css              ✅ Styling  
├── script.js               ✅ JavaScript
├── .htaccess               ✅ Apache-konfigurasjon
├── config/
│   └── database.php        ✅ Database-konfigurasjon
├── models/
│   └── Recipe.php          ✅ PHP-klasse
├── api/
│   └── recipes.php         ✅ API-endepunkter
└── database/
    └── schema.sql          ✅ Database-skjema
```

## 🔧 3 enkle steg til deployment:

### Steg 1: Konfigurer database
Rediger `ftp-deploy/config/database.php` med dine opplysninger:
```php
private $host = 'localhost';
private $db_name = 'oppskriftsamling';  
private $username = 'ditt_mysql_brukernavn';
private $password = 'ditt_mysql_passord';
```

### Steg 2: Upload via FTP
1. **Åpne FileZilla** (eller annen FTP-klient)
2. **Koble til hjellum.net** med dine FTP-opplysninger
3. **Naviger til** `/public_html/oppskrifter/` (eller `/www/oppskrifter/`)
4. **Upload hele** `ftp-deploy/` mappen til serveren
5. **Sett tillatelser**:
   - Filer: 644
   - Mapper: 755

### Steg 3: Sett opp database
1. **Gå til phpMyAdmin** (via cPanel eller direkte)
2. **Opprett database**: `oppskriftsamling`
3. **Importer** `database/schema.sql` filen
4. **Test** på https://hjellum.net/oppskrifter/

## 🎯 Alternativ: Bruk deployment-script

### Linux/macOS:
```bash
# Rediger ftp-deploy.sh med dine FTP-opplysninger
nano ftp-deploy.sh

# Kjør scriptet
./ftp-deploy.sh
```

### Windows:
```cmd
REM Rediger ftp-deploy.bat med dine FTP-opplysninger
notepad ftp-deploy.bat

REM Kjør scriptet
ftp-deploy.bat
```

## 🆘 Hjelp trengs?

Se `FTP_DEPLOYMENT.md` for detaljerte instruksjoner og feilsøking.

---

**🌐 Nettadresse**: https://hjellum.net/oppskrifter/  
**📧 Support**: Kontakt webhotell-leverandør