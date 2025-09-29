# Bidra til Oppskriftsamling

Takk for at du vurderer å bidra til Oppskriftsamling! 🎉

## 🚀 Kom i gang

### Forutsetninger
- PHP 7.4 eller høyere
- MySQL 5.7 eller høyere
- Git
- Composer (valgfritt)

### Oppsett for utvikling

1. **Fork og klon repository**
```bash
git clone https://github.com/[ditt-brukernavn]/oppskriftsamling.git
cd oppskriftsamling
```

2. **Sett opp database**
```bash
# Opprett database
mysql -u root -p -e "CREATE DATABASE oppskriftsamling CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Importer skjema
mysql -u root -p oppskriftsamling < database/schema.sql
```

3. **Konfigurer database**
```bash
# Kopier og rediger konfigurasjon
cp config/database.production.php config/database.php
# Rediger config/database.php med dine databaseopplysninger
```

4. **Start utviklingsserver**
```bash
php -S localhost:8000
```

5. **Test applikasjonen**
Åpne http://localhost:8000 i nettleseren din.

## 🔄 Bidragsprosess

### 1. Opprett en issue
- Beskriv problemet eller funksjonen du vil jobbe med
- Sjekk at det ikke allerede finnes lignende issues

### 2. Fork og klon
- Fork repository
- Klon din fork lokalt
- Opprett en ny branch for din feature/bugfix

```bash
git checkout -b feature/amazing-feature
```

### 3. Utvikle
- Skriv kode som følger eksisterende stil
- Legg til kommentarer der det er nødvendig
- Test endringene dine grundig

### 4. Test
```bash
# PHP syntax check
find . -name "*.php" -exec php -l {} \;

# Test database connection
php -r "require_once 'config/database.php'; \$db = new Database(); echo \$db->testConnection() ? 'OK' : 'FAILED';"
```

### 5. Commit
```bash
git add .
git commit -m "Add: amazing new feature"
```

### 6. Push og opprett Pull Request
```bash
git push origin feature/amazing-feature
```

Deretter opprett en Pull Request på GitHub.

## 📝 Kode-stil

### PHP
- Følg PSR-12 standard
- Bruk beskrivende variabel- og funksjonsnavn
- Legg til kommentarer for kompleks logikk
- Bruk type hints der det er mulig

```php
// Bra
public function getRecipeById(int $id): ?array
{
    // Implementation
}

// Dårlig
public function get($id)
{
    // Implementation
}
```

### JavaScript
- Bruk ES6+ funksjoner
- Følg camelCase for variabler og funksjoner
- Legg til kommentarer for kompleks logikk
- Bruk const/let i stedet for var

```javascript
// Bra
const recipeManager = new RecipeManager();
const fetchRecipes = async () => { /* ... */ };

// Dårlig
var manager = new RecipeManager();
function get() { /* ... */ }
```

### CSS
- Bruk BEM metodikk for CSS-klasser
- Organiser CSS i logiske seksjoner
- Bruk konsistente enheter (rem/px)
- Kommenter komplekse regler

```css
/* Bra */
.recipe-card {
    /* Base styles */
}

.recipe-card__title {
    /* Title styles */
}

.recipe-card--featured {
    /* Featured variant */
}

/* Dårlig */
.card {
    /* Unclear purpose */
}
```

## 🐛 Rapportere bugs

Når du rapporterer bugs, inkluder:

1. **Beskrivelse**: Hva skjedde?
2. **Forventet oppførsel**: Hva skulle skje?
3. **Steg for å reprodusere**: Hvordan kan vi se buggen?
4. **Miljø**: PHP-versjon, nettleser, OS
5. **Skjermbilder**: Hvis relevant

### Bug rapport mal
```markdown
**Beskrivelse**
En kort beskrivelse av buggen.

**Steg for å reprodusere**
1. Gå til '...'
2. Klikk på '...'
3. Scroll ned til '...'
4. Se feil

**Forventet oppførsel**
En klar beskrivelse av hva du forventet skulle skje.

**Miljø:**
- OS: [f.eks. Windows 10]
- Nettleser: [f.eks. Chrome 91]
- PHP-versjon: [f.eks. 8.0]

**Tilleggsinformasjon**
Eventuelle andre detaljer om problemet.
```

## ✨ Foreslå nye funksjoner

Nye funksjoner er velkommen! Beskriv:

1. **Problemet**: Hvilket problem løser funksjonen?
2. **Løsningen**: Hvordan foreslår du å løse det?
3. **Alternativer**: Er det andre måter å løse det på?
4. **Ytterligere kontekst**: Skjermbilder, mockups, etc.

## 📋 Pull Request Guidelines

### Før du sender inn PR:
- [ ] Koden følger eksisterende stil
- [ ] Du har testet endringene
- [ ] Du har oppdatert dokumentasjon hvis nødvendig
- [ ] Du har lagt til kommentarer for kompleks logikk
- [ ] PHP syntax er gyldig
- [ ] Ingen sensitive data er inkludert

### PR beskrivelse skal inkludere:
- Hva endringen gjør
- Hvorfor endringen er nødvendig
- Hvordan du har testet endringen
- Skjermbilder (hvis UI-endringer)
- Eventuelle breaking changes

## 🏷️ Labels

Vi bruker følgende labels for issues og PRs:

- `bug`: Noe som ikke fungerer som forventet
- `enhancement`: Ny funksjonalitet eller forbedring
- `documentation`: Forbedringer til dokumentasjon
- `good first issue`: Bra for nye bidragsytere
- `help wanted`: Vi trenger hjelp med dette
- `question`: Mer informasjon er påkrevd

## 💬 Diskusjon

- **GitHub Issues**: For bugs og funksjonsforespørsler
- **GitHub Discussions**: For generelle spørsmål og ideer

## 📜 Lisens

Ved å bidra til dette prosjektet godtar du at bidragene dine vil bli lisensiert under MIT-lisensen.

## 🙏 Takk!

Takk for at du bidrar til å gjøre Oppskriftsamling bedre for alle! 🎉

---

**Spørsmål?** Opprett en issue eller start en diskusjon!