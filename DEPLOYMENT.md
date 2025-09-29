# Deployment Guide - Oppskriftsamling på hjellum.net

## 📋 Forutsetninger

Du trenger:
- MySQL-database tilgjengelig på hjellum.net
- PHP 7.4 eller høyere
- Web server (Apache/Nginx) med PHP-støtte

## 🗄️ Database Setup

### 1. Opprett database
Logg inn på din MySQL-database og kjør:

```sql
-- Importer databaseskjemaet
mysql -u [brukernavn] -p < database/schema.sql
```

Eller manuelt:
1. Opprett database: `oppskriftsamling`
2. Importer innholdet fra `database/schema.sql`

### 2. Oppdater databasekonfigurasjon
Rediger `config/database.php` med riktige databaseopplysninger:

```php
private $host = 'localhost'; // eller din database-server
private $db_name = 'oppskriftsamling';
private $username = 'din_brukernavn';
private $password = 'ditt_passord';
```

## 📁 Filstruktur på Server

Upload alle filer til `/oppskrifter/` mappen på hjellum.net:

```
/oppskrifter/
├── index.html
├── styles.css
├── script.js
├── config/
│   └── database.php
├── models/
│   └── Recipe.php
├── api/
│   └── recipes.php
└── database/
    └── schema.sql
```

## 🚀 Deployment Steps

### 1. Upload filer
```bash
# Via FTP/SFTP eller cPanel File Manager
# Last opp alle filer til /oppskrifter/ mappen
```

### 2. Sett riktige tillatelser
```bash
chmod 644 *.html *.css *.js *.php
chmod 755 config/ models/ api/ database/
```

### 3. Test tilkobling
Gå til `https://hjellum.net/oppskrifter/` og sjekk at siden laster.

### 4. Test API
Test API-endepunktet:
```bash
curl https://hjellum.net/oppskrifter/api/recipes.php
```

## 🔧 Feilsøking

### Vanlige problemer:

**1. Database-tilkobling feiler**
- Sjekk databaseopplysninger i `config/database.php`
- Kontroller at MySQL-tjenesten kjører
- Verifiser brukerrettigheter

**2. 404-feil på API**
- Sjekk at `api/recipes.php` er tilgjengelig
- Kontroller `.htaccess` regler hvis nødvendig

**3. CORS-feil**
- Sjekk at `Access-Control-Allow-Origin` header er satt riktig
- For testing, kan du midlertidig sette til `*`

**4. PHP-feil**
- Aktiver error reporting i PHP
- Sjekk PHP-loggfiler
- Kontroller PHP-versjon (minimum 7.4)

## 📝 .htaccess (valgfritt)

Hvis du trenger URL-rewriting, legg til `.htaccess`:

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^api/(.*)$ api/$1 [L]
```

## 🔒 Sikkerhet

### Produksjonsretting:
1. **Skjul databaseopplysninger**
   - Flytt sensitive data til miljøvariabler
   - Bruk `.env` filer (ikke commit til git)

2. **Validering**
   - All input valideres allerede i PHP
   - XSS-beskyttelse med `htmlspecialchars()`

3. **Rate limiting**
   - Vurder å legge til rate limiting for API-endepunkter

4. **HTTPS**
   - Sørg for at hele siden kjører på HTTPS

## 🧪 Testing

### Lokal testing:
```bash
# Start PHP server
php -S localhost:8000

# Test i nettleser
http://localhost:8000
```

### API testing:
```bash
# Test GET
curl http://localhost:8000/api/recipes.php

# Test POST
curl -X POST http://localhost:8000/api/recipes.php \
  -H "Content-Type: application/json" \
  -d '{"name":"Test","category":"hovedrett","time":30,"servings":4,"ingredients":"Test","instructions":"Test"}'
```

## 📊 Vedlikehold

### Regelmessige oppgaver:
1. **Backup database** - minst månedlig
2. **Oppdater PHP** - hold oppdatert
3. **Monitor logs** - sjekk for feil
4. **Performance** - optimaliser database queries hvis nødvendig

## 🆘 Support

Hvis du støter på problemer:
1. Sjekk PHP error logs
2. Test database-tilkobling
3. Verifiser fil-tillatelser
4. Kontroller nettverkstilkobling

---

**Nettadresse:** https://hjellum.net/oppskrifter/
**API:** https://hjellum.net/oppskrifter/api/recipes.php