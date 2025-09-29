# 🚀 GitHub Actions FTP Deployment Setup

Denne guiden hjelper deg med å sette opp automatisk deployment til hjellum.net via GitHub Actions.

## 🔑 GitHub Secrets Setup

Du må legge til følgende secrets i GitHub repository:

### 1. Gå til GitHub Repository Settings

1. Gå til: `https://github.com/sjurivar/Oppskrifter`
2. Klikk på **"Settings"** tab
3. I venstre meny, klikk **"Secrets and variables"** → **"Actions"**

### 2. Legg til følgende Repository Secrets

Klikk **"New repository secret"** for hver av disse:

#### FTP-opplysninger:
```
Name: FTP_SERVER
Value: ftp.hjellum.net

Name: FTP_USER  
Value: [ditt_ftp_brukernavn]

Name: FTP_PASSWORD
Value: [ditt_ftp_passord]

Name: FTP_REMOTE_DIR
Value: /public_html/oppskrifter/
```

#### Database-opplysninger:
```
Name: DB_HOST
Value: localhost

Name: DB_NAME
Value: oppskriftsamling

Name: DB_USER
Value: [ditt_mysql_brukernavn]

Name: DB_PASS
Value: [ditt_mysql_passord]
```

## 📋 Eksempel på hvordan det ser ut:

```
Repository Secrets:
├── FTP_SERVER = ftp.hjellum.net
├── FTP_USER = sjurivar_ftp
├── FTP_PASSWORD = ditt_sikre_ftp_passord
├── FTP_REMOTE_DIR = /public_html/oppskrifter/
├── DB_HOST = localhost
├── DB_NAME = oppskriftsamling
├── DB_USER = sjurivar_mysql
└── DB_PASS = ditt_sikre_mysql_passord
```

## 🔄 Hvordan det fungerer

### Automatisk deployment:
1. **Push til main branch** → GitHub Actions starter automatisk
2. **Validerer PHP-syntaks** → Sjekker at all PHP-kode er gyldig
3. **Forbereder filer** → Organiserer filer for deployment
4. **Konfigurerer database** → Setter inn dine database-opplysninger
5. **Uploader via FTP** → Sender filer til hjellum.net
6. **Tester deployment** → Verifiserer at alt fungerer

### Manuell deployment:
1. Gå til **"Actions"** tab i GitHub
2. Velg **"FTP Deploy to hjellum.net"**
3. Klikk **"Run workflow"**
4. Velg branch og klikk **"Run workflow"**

## 📊 Deployment Status

Du kan følge deployment-status:
- **GitHub Actions tab** → Se alle kjøringer
- **Grønn hake** = Suksess ✅
- **Rød X** = Feilet ❌
- **Gul sirkel** = Pågår 🔄

## 🧪 Testing av Workflow

### Test GitHub Actions:
1. **Gjør en liten endring** i koden (f.eks. legg til kommentar)
2. **Commit og push** til main branch
3. **Gå til Actions tab** og følg deployment
4. **Test applikasjonen** på https://hjellum.net/oppskrifter/

### Eksempel test-commit:
```bash
# Gjør en liten endring
echo "<!-- Deployed via GitHub Actions -->" >> index.html

# Commit og push
git add index.html
git commit -m "Test: GitHub Actions deployment"
git push origin main
```

## 🔧 Feilsøking

### Vanlige problemer:

#### 1. "FTP connection failed"
- Sjekk FTP_SERVER, FTP_USER, FTP_PASSWORD secrets
- Verifiser at FTP-tilgang er aktivert
- Prøv å koble til manuelt med FileZilla

#### 2. "Database connection failed"
- Sjekk DB_HOST, DB_NAME, DB_USER, DB_PASS secrets
- Kontroller at MySQL-database eksisterer
- Verifiser brukerrettigheter

#### 3. "Permission denied"
- Sjekk FTP_REMOTE_DIR secret
- Kontroller at mappen eksisterer på serveren
- Verifiser skrivetilgang

#### 4. "PHP syntax error"
- Sjekk PHP-filer for syntaksfeil
- Kjør `php -l filename.php` lokalt
- Se GitHub Actions logs for detaljer

## 📈 Workflow Features

### Automatisk testing:
- ✅ PHP syntax validation
- ✅ File structure verification
- ✅ Deployment testing
- ✅ API endpoint testing

### Sikkerhet:
- 🔒 Secrets er kryptert i GitHub
- 🔒 FTP-passord er ikke synlige i logs
- 🔒 Database-opplysninger er beskyttet

### Notifikasjoner:
- 📧 E-post ved suksess/feil (hvis konfigurert)
- 📱 GitHub mobile app notifikasjoner
- 🔔 Repository notifications

## 🎯 Best Practices

### 1. Test først
- Test endringer lokalt før push
- Bruk development branch for store endringer
- Test database-endringer grundig

### 2. Sikkerhet
- Bruk sterke passord for secrets
- Roter passord regelmessig
- Begrens tilgang til repository

### 3. Monitoring
- Sjekk deployment-status regelmessig
- Overvåk applikasjonen etter deployment
- Hold øye med error logs

## 📱 Live Application

Etter vellykket deployment:
- **🌐 Applikasjon**: https://hjellum.net/oppskrifter/
- **🔗 API**: https://hjellum.net/oppskrifter/api/recipes.php
- **📊 Actions**: https://github.com/sjurivar/Oppskrifter/actions

## 🆘 Support

Hvis du støter på problemer:
1. Sjekk GitHub Actions logs
2. Verifiser alle secrets er satt riktig
3. Test FTP-tilkobling manuelt
4. Kontroller server-konfigurasjon

---

**🎉 Etter setup vil alle endringer automatisk deployes til hjellum.net!**