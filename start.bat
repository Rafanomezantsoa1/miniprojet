@echo off
REM Script pour démarrer le projet et ouvrir les pages
REM Utilisé sur Windows

echo.
echo 🚀 Démarrage du projet Iran War CMS...
echo.

REM Attendre 2 secondes
timeout /t 2 /nobreak

REM Vérifier si Docker est disponible
docker --version >nul 2>&1
if errorlevel 1 (
    echo ❌ Docker n'est pas installé ou non accessible
    pause
    exit /b 1
)

REM Ouvrir les pages dans le navigateur par défaut
echo 📱 Ouverture des pages dans le navigateur...

start http://localhost:8080/backoffice/
timeout /t 1 /nobreak
start http://localhost:8080/frontoffice/

echo.
echo ✨ Les pages se sont ouvertes dans votre navigateur!
echo.
echo 📍 URLs disponibles:
echo    - Backoffice (Admin): http://localhost:8080/backoffice/
echo    - Frontoffice (Public): http://localhost:8080/frontoffice/
echo.
echo Identifiants par défaut:
echo    - Nom d'utilisateur: admin
echo    - Mot de passe: admin123
echo.
pause
