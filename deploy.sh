#!/bin/bash

# Deployment script for oppskriftsamling
# Kjør dette scriptet for å deploye til hjellum.net

echo "🚀 Deploying oppskriftsamling to hjellum.net..."

# Sjekk at vi er i riktig mappe
if [ ! -f "index.html" ]; then
    echo "❌ Error: index.html not found. Make sure you're in the project root."
    exit 1
fi

# Opprett backup av eksisterende filer (hvis de finnes)
echo "📦 Creating backup..."
if [ -d "/var/www/html/oppskrifter" ]; then
    cp -r /var/www/html/oppskrifter /var/www/html/oppskrifter_backup_$(date +%Y%m%d_%H%M%S)
fi

# Opprett mappestruktur
echo "📁 Creating directory structure..."
mkdir -p /var/www/html/oppskrifter/{config,models,api,database}

# Kopier filer
echo "📋 Copying files..."
cp index.html /var/www/html/oppskrifter/
cp styles.css /var/www/html/oppskrifter/
cp script.js /var/www/html/oppskrifter/
cp .htaccess /var/www/html/oppskrifter/

# Kopier PHP-filer
cp config/database.php /var/www/html/oppskrifter/config/
cp models/Recipe.php /var/www/html/oppskrifter/models/
cp api/recipes.php /var/www/html/oppskrifter/api/
cp database/schema.sql /var/www/html/oppskrifter/database/

# Sett riktige tillatelser
echo "🔐 Setting permissions..."
chmod 644 /var/www/html/oppskrifter/*.html
chmod 644 /var/www/html/oppskrifter/*.css
chmod 644 /var/www/html/oppskrifter/*.js
chmod 644 /var/www/html/oppskrifter/*.php
chmod 644 /var/www/html/oppskrifter/config/*.php
chmod 644 /var/www/html/oppskrifter/models/*.php
chmod 644 /var/www/html/oppskrifter/api/*.php
chmod 755 /var/www/html/oppskrifter/config/
chmod 755 /var/www/html/oppskrifter/models/
chmod 755 /var/www/html/oppskrifter/api/
chmod 755 /var/www/html/oppskrifter/database/

# Test PHP-syntaks
echo "🧪 Testing PHP syntax..."
php -l /var/www/html/oppskrifter/config/database.php
php -l /var/www/html/oppskrifter/models/Recipe.php
php -l /var/www/html/oppskrifter/api/recipes.php

if [ $? -eq 0 ]; then
    echo "✅ PHP syntax check passed"
else
    echo "❌ PHP syntax errors found"
    exit 1
fi

# Test database-tilkobling
echo "🗄️ Testing database connection..."
php -r "
require_once '/var/www/html/oppskrifter/config/database.php';
\$db = new Database();
if (\$db->testConnection()) {
    echo 'Database connection: OK\n';
} else {
    echo 'Database connection: FAILED\n';
    exit(1);
}
"

if [ $? -eq 0 ]; then
    echo "✅ Database connection test passed"
else
    echo "❌ Database connection failed"
    echo "⚠️  Please check your database configuration in config/database.php"
fi

echo ""
echo "🎉 Deployment completed!"
echo "📱 Your app is now available at: https://hjellum.net/oppskrifter/"
echo "🔗 API endpoint: https://hjellum.net/oppskrifter/api/recipes.php"
echo ""
echo "📝 Next steps:"
echo "1. Update database credentials in /var/www/html/oppskrifter/config/database.php"
echo "2. Import database schema if not already done: mysql -u user -p < database/schema.sql"
echo "3. Test the application in your browser"
echo ""
echo "🔍 For troubleshooting, check:"
echo "- PHP error logs"
echo "- Apache error logs"
echo "- Database connection"