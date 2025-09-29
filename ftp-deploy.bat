@echo off
REM FTP Deployment Script for Oppskriftsamling (Windows)
REM Dette scriptet uploader alle filer til hjellum.net via FTP

echo 🚀 FTP Deployment til hjellum.net
echo =================================

REM Konfigurer disse verdiene for din FTP-server
set FTP_SERVER=ftp.hjellum.net
set FTP_USER=ditt_brukernavn
set FTP_PASS=ditt_passord
set REMOTE_DIR=/public_html/oppskrifter/

echo [INFO] Forbereder filer for deployment...

REM Opprett deployment-mappe
if not exist "ftp-deploy" mkdir ftp-deploy

REM Kopier filer til deployment-mappe
echo [INFO] Kopierer filer...
copy index.html ftp-deploy\
copy styles.css ftp-deploy\
copy script.js ftp-deploy\
copy .htaccess ftp-deploy\

REM Opprett mapper og kopier PHP-filer
if not exist "ftp-deploy\config" mkdir ftp-deploy\config
if not exist "ftp-deploy\models" mkdir ftp-deploy\models
if not exist "ftp-deploy\api" mkdir ftp-deploy\api
if not exist "ftp-deploy\database" mkdir ftp-deploy\database

copy config\database.production.php ftp-deploy\config\database.php
copy models\Recipe.php ftp-deploy\models\
copy api\recipes.php ftp-deploy\api\
copy database\schema.sql ftp-deploy\database\

echo [INFO] Filer forberedt i ftp-deploy\ mappen

REM Vis filstruktur
echo.
echo [INFO] Filer som skal uploades:
dir /s ftp-deploy

REM Bekreft deployment
echo.
set /p confirm="Er du klar til å uploade til %FTP_SERVER%? (y/N): "
if /i not "%confirm%"=="y" (
    echo [WARN] Deployment avbrutt
    pause
    exit /b 0
)

REM Opprett FTP-kommando fil
echo open %FTP_SERVER% > ftp-commands.txt
echo user %FTP_USER% %FTP_PASS% >> ftp-commands.txt
echo cd %REMOTE_DIR% >> ftp-commands.txt
echo binary >> ftp-commands.txt
echo put ftp-deploy\index.html >> ftp-commands.txt
echo put ftp-deploy\styles.css >> ftp-commands.txt
echo put ftp-deploy\script.js >> ftp-commands.txt
echo put ftp-deploy\.htaccess >> ftp-commands.txt
echo put ftp-deploy\config\database.php >> ftp-commands.txt
echo put ftp-deploy\models\Recipe.php >> ftp-commands.txt
echo put ftp-deploy\api\recipes.php >> ftp-commands.txt
echo put ftp-deploy\database\schema.sql >> ftp-commands.txt
echo quit >> ftp-commands.txt

echo [INFO] Starter FTP-upload til %FTP_SERVER%...

REM Kjør FTP-upload
ftp -s:ftp-commands.txt

if %errorlevel% equ 0 (
    echo [INFO] FTP-upload fullført!
) else (
    echo [ERROR] FTP-upload feilet!
    pause
    exit /b 1
)

REM Rydd opp
echo [INFO] Rydder opp lokale filer...
rmdir /s /q ftp-deploy
del ftp-commands.txt

echo.
echo [INFO] 🎉 Deployment fullført!
echo.
echo 📱 Test applikasjonen på: https://hjellum.net/oppskrifter/
echo 🔗 API-endepunkt: https://hjellum.net/oppskrifter/api/recipes.php
echo.
echo 📝 Neste steg:
echo 1. Konfigurer database i config/database.php på serveren
echo 2. Importer database/schema.sql til MySQL
echo 3. Test alle funksjoner i nettleseren
echo.
echo 🐛 Hvis noe ikke fungerer:
echo - Sjekk PHP error logs
echo - Verifiser database-tilkobling
echo - Kontroller fil-tillatelser
echo.
pause