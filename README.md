# 🍽️ Oppskriftsamling

En moderne webside for å organisere og håndtere dine oppskrifter, bygget med PHP og MySQL.

## ✨ Funksjoner

- **📝 Oppskriftshåndtering**: Legg til, rediger og slett oppskrifter
- **🔍 Søk og filtrering**: Finn oppskrifter raskt med søk og kategorifiltrering
- **📱 Responsivt design**: Fungerer perfekt på alle enheter
- **🗄️ Database-lagring**: Sikker lagring i MySQL-database
- **🎨 Moderne UI**: Attraktivt design med smooth animasjoner

## 🚀 Live Demo

**Produksjonsversjon:** https://hjellum.net/oppskrifter/

## 🛠️ Teknisk Stack

- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **API**: RESTful API med JSON
- **Design**: Moderne CSS med Flexbox/Grid

## 📁 Prosjektstruktur

```
/
├── index.html              # Hovedside
├── styles.css              # Styling
├── script.js               # Frontend JavaScript
├── .htaccess               # Apache-konfigurasjon
├── config/
│   ├── database.php        # Database-konfigurasjon
│   └── database.production.php
├── models/
│   └── Recipe.php          # Oppskriftsmodell
├── api/
│   └── recipes.php         # API-endepunkter
├── database/
│   └── schema.sql          # Database-skjema
├── deploy.sh               # Deployment-script
└── DEPLOYMENT.md           # Detaljert deployment-guide
```

## 🗄️ Database Schema

```sql
CREATE TABLE recipes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category ENUM('forrett', 'hovedrett', 'dessert', 'snacks', 'drikke') NOT NULL,
    time INT NOT NULL,
    servings INT NOT NULL,
    ingredients TEXT NOT NULL,
    instructions TEXT NOT NULL,
    image_url VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## 🔧 Lokal utvikling

### Forutsetninger
- PHP 7.4+
- MySQL 5.7+
- Web server (Apache/Nginx)

### Installasjon

1. **Klon prosjektet**
```bash
git clone [repository-url]
cd oppskriftsamling
```

2. **Sett opp database**
```bash
# Opprett database
mysql -u root -p
CREATE DATABASE oppskriftsamling CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Importer skjema
mysql -u root -p oppskriftsamling < database/schema.sql
```

3. **Konfigurer database**
```bash
# Kopier produksjonskonfigurasjon
cp config/database.production.php config/database.php

# Rediger med dine databaseopplysninger
nano config/database.php
```

4. **Start server**
```bash
php -S localhost:8000
```

5. **Åpne i nettleser**
```
http://localhost:8000
```

## 🌐 Deployment til hjellum.net

### Automatisk deployment
```bash
# Kjør deployment-script
./deploy.sh
```

### Manuell deployment
Se `DEPLOYMENT.md` for detaljerte instruksjoner.

## 📚 API Dokumentasjon

### Endepunkter

**Base URL:** `https://hjellum.net/oppskrifter/api/recipes.php`

#### Hent alle oppskrifter
```http
GET /api/recipes.php
```

#### Søk oppskrifter
```http
GET /api/recipes.php?search=pasta&category=hovedrett
```

#### Hent spesifikk oppskrift
```http
GET /api/recipes.php?id=1
```

#### Opprett ny oppskrift
```http
POST /api/recipes.php
Content-Type: application/json

{
    "name": "Spaghetti Carbonara",
    "category": "hovedrett",
    "time": 20,
    "servings": 4,
    "ingredients": "400g spaghetti\n200g bacon\n4 egg",
    "instructions": "1. Kok pasta\n2. Stek bacon",
    "image_url": "https://example.com/image.jpg"
}
```

#### Oppdater oppskrift
```http
PUT /api/recipes.php
Content-Type: application/json

{
    "id": 1,
    "name": "Oppdatert navn",
    ...
}
```

#### Slett oppskrift
```http
DELETE /api/recipes.php?id=1
```

### Response Format

```json
{
    "success": true,
    "message": "Operation successful",
    "data": [...]
}
```

## 🎨 Kategorier

- **Forrett**: Appetittvekker og salater
- **Hovedrett**: Hovedmåltider
- **Dessert**: Såte retter og kaker
- **Snacks**: Småretter og snacks
- **Drikke**: Drikker og cocktails

## 🔒 Sikkerhet

- **Input-validering**: All brukerinput valideres
- **SQL-injection beskyttelse**: Prepared statements
- **XSS-beskyttelse**: HTML escaping
- **CORS-konfigurasjon**: Sikker cross-origin requests

## 🧪 Testing

### API Testing
```bash
# Test GET
curl https://hjellum.net/oppskrifter/api/recipes.php

# Test POST
curl -X POST https://hjellum.net/oppskrifter/api/recipes.php \
  -H "Content-Type: application/json" \
  -d '{"name":"Test","category":"hovedrett","time":30,"servings":4,"ingredients":"Test","instructions":"Test"}'
```

## 📈 Ytelse

- **Database-indeksering**: Optimaliserte queries
- **Caching**: Browser-caching for statiske filer
- **Komprimering**: Gzip-komprimering
- **Responsivt design**: Optimalisert for mobile enheter

## 🤝 Bidrag

1. Fork prosjektet
2. Opprett feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit endringene (`git commit -m 'Add some AmazingFeature'`)
4. Push til branch (`git push origin feature/AmazingFeature`)
5. Åpne Pull Request

## 📄 Lisens

Dette prosjektet er lisensiert under MIT-lisensen - se `LICENSE` filen for detaljer.

## 👨‍💻 Utvikler

Utviklet med ❤️ for å gjøre oppskriftshåndtering enklere og mer organisert.

## 📞 Support

Hvis du støter på problemer eller har spørsmål:
1. Sjekk `DEPLOYMENT.md` for deployment-spørsmål
2. Se API-dokumentasjonen over
3. Kontroller PHP og MySQL-loggfiler
4. Opprett en issue i prosjektet

---

**Nettadresse:** https://hjellum.net/oppskrifter/  
**API:** https://hjellum.net/oppskrifter/api/recipes.php