#!/bin/bash

# Script pour démarrer le projet et ouvrir les pages
# Utilisé sur macOS et Linux

echo "🚀 Démarrage du projet Iran War CMS..."

# Ouvrir les pages dans le navigateur
sleep 2

# Vérifier si les conteneurs tournent
if ! docker ps | grep -q "apache_php"; then
    echo "⚠️  Les conteneurs ne sont pas actifs. Assurez-vous que 'docker-compose up' est lancé."
    exit 1
fi

echo "✅ Conteneurs détectés"

# Sur macOS
if [[ "$OSTYPE" == "darwin"* ]]; then
    echo "📱 Ouverture des pages sur macOS..."
    open "http://localhost:8080/backoffice/"
    sleep 1
    open "http://localhost:8080/frontoffice/"
fi

# Sur Linux
if [[ "$OSTYPE" == "linux-gnu"* ]]; then
    echo "📱 Ouverture des pages sur Linux..."
    xdg-open "http://localhost:8080/backoffice/" &
    sleep 1
    xdg-open "http://localhost:8080/frontoffice/" &
fi

echo "✨ Les pages se sont ouvertes dans votre navigateur!"
echo ""
echo "📍 URLs disponibles:"
echo "   - Backoffice (Admin): http://localhost:8080/backoffice/"
echo "   - Frontoffice (Public): http://localhost:8080/frontoffice/"
echo ""
echo "Identifiants par défaut:"
echo "   - Nom d'utilisateur: admin"
echo "   - Mot de passe: admin123"
