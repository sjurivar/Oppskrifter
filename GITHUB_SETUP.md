# 🐙 GitHub Setup Guide

Denne guiden hjelper deg med å publisere Oppskriftsamling på GitHub.

## 📋 Steg-for-steg instruksjoner

### 1. Opprett GitHub Repository

1. Gå til [GitHub.com](https://github.com) og logg inn
2. Klikk på **"New repository"** (grønn knapp)
3. Fyll ut:
   - **Repository name**: `oppskriftsamling`
   - **Description**: `En moderne webside for å organisere og håndtere oppskrifter`
   - **Visibility**: Velg **Public** (anbefalt for open source)
   - **Initialize**: Ikke huk av noen av boksene (vi har allerede filene)
4. Klikk **"Create repository"**

### 2. Koble lokal repository til GitHub

```bash
# Gå til prosjektmappen
cd /workspace

# Legg til remote origin (erstatt 'ditt-brukernavn' med ditt faktiske GitHub-brukernavn)
git remote add origin https://github.com/ditt-brukernavn/oppskriftsamling.git

# Sjekk at remote er lagt til
git remote -v
```

### 3. Push til GitHub

```bash
# Push til main branch
git branch -M main
git push -u origin main
```

### 4. Konfigurer GitHub Repository

Etter at repository er opprettet:

#### 4.1 Legg til beskrivelse og tags
1. Gå til repository-siden på GitHub
2. Klikk på **"About"** seksjonen (høyre side)
3. Legg til:
   - **Website**: `https://hjellum.net/oppskrifter/`
   - **Topics**: `php`, `mysql`, `recipes`, `cooking`, `web-app`, `javascript`

#### 4.2 Aktiver GitHub Pages (valgfritt)
1. Gå til **Settings** → **Pages**
2. Under **Source**, velg **"Deploy from a branch"**
3. Velg **"main"** branch og **"/ (root)"** folder
4. Klikk **"Save"**

#### 4.3 Konfigurer Issues og Wiki
1. Gå til **Settings** → **General**
2. Under **Features**, huk av:
   - ✅ Issues
   - ✅ Wiki (valgfritt)
   - ✅ Discussions (valgfritt)

### 5. Opprett første Release

1. Gå til **"Releases"** → **"Create a new release"**
2. Fyll ut:
   - **Tag version**: `v1.0.0`
   - **Release title**: `Oppskriftsamling v1.0.0`
   - **Description**: 
     ```
     🎉 Første release av Oppskriftsamling!
     
     ## Funksjoner
     - 📝 Oppskriftshåndtering med CRUD-operasjoner
     - 🔍 Søk og filtrering
     - 📱 Responsivt design
     - 🗄️ MySQL database-integrasjon
     - 🔒 Sikkerhet med input-validering
     
     ## Teknisk
     - PHP 7.4+ backend
     - MySQL database
     - RESTful API
     - Modern JavaScript (ES6+)
     - Responsive CSS
     ```

### 6. GitHub Actions (automatisk testing)

GitHub Actions er allerede konfigurert i `.github/workflows/deploy.yml`. Den vil automatisk:
- Teste PHP-syntaks på flere versjoner
- Validere composer.json
- Kjøre grunnleggende tester

### 7. GitHub Repository URL

Din repository vil være tilgjengelig på:
```
https://github.com/ditt-brukernavn/oppskriftsamling
```

## 🔧 GitHub Repository Features

### Issues
- Bruk for å rapportere bugs
- Foreslå nye funksjoner
- Spør om hjelp

### Pull Requests
- Bidra med kode
- Foreslå forbedringer
- Code review

### Wiki (valgfritt)
- Detaljert dokumentasjon
- Brukerguider
- API-dokumentasjon

### Discussions
- Generelle spørsmål
- Ideer og forslag
- Samfunnsdiskusjoner

## 📊 GitHub Insights

Etter noen commits kan du se:
- **Insights** → **Contributors**: Hvem som har bidratt
- **Insights** → **Traffic**: Antall visninger og kloner
- **Insights** → **Community**: Repository-helse

## 🔗 Lenker og Badges

Etter publicering kan du bruke disse lenkene:

### Repository Badges
```markdown
[![GitHub release](https://img.shields.io/github/release/ditt-brukernavn/oppskriftsamling.svg)](https://github.com/ditt-brukernavn/oppskriftsamling/releases)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)](https://php.net)
```

### Direkte lenker
- **Repository**: `https://github.com/ditt-brukernavn/oppskriftsamling`
- **Issues**: `https://github.com/ditt-brukernavn/oppskriftsamling/issues`
- **Releases**: `https://github.com/ditt-brukernavn/oppskriftsamling/releases`

## 🚀 Fremtidige oppdateringer

For å oppdatere repository:

```bash
# Gjør endringer i koden
git add .
git commit -m "Beskrivelse av endringene"
git push origin main
```

## 📝 README Tips

README.md er allerede konfigurert med:
- ✅ Beskrivelse og badges
- ✅ Live demo link
- ✅ Installasjonsinstruksjoner
- ✅ API-dokumentasjon
- ✅ Bidragsinstruksjoner
- ✅ Lisensinformasjon

## 🆘 Hjelp

Hvis du støter på problemer:

1. **Git push feiler**: Sjekk at du har riktige tilganger til repository
2. **GitHub Actions feiler**: Sjekk at alle PHP-filer har gyldig syntaks
3. **Issues ikke vises**: Aktiver Issues i repository settings

---

**🎉 Gratulerer!** Din oppskriftsamling er nå tilgjengelig på GitHub!

**Repository URL**: `https://github.com/ditt-brukernavn/oppskriftsamling`