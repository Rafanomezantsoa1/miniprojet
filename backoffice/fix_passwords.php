<?php
/**
 * Script de réparation des mots de passe
 * À exécuter UNE FOIS pour corriger les hashes dans la BD
 * 
 * URL: http://localhost/Miniprojet/backoffice/fix_passwords.php
 */

// Activer l'affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db.php';

echo "<h1>🔐 Réparation des mots de passe</h1>";
echo "<hr>";

// Données correctes
$users_data = [
    ['nom' => 'admin', 'mdp' => 'admin123', 'id_role' => 1],
    ['nom' => 'user', 'mdp' => 'user123', 'id_role' => 2],
];

try {
    echo "<h2>Génération et mise à jour des hashes...</h2>";
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>Utilisateur</th><th>Mot de passe</th><th>Hash généré</th><th>Statut</th></tr>";
    
    foreach ($users_data as $user) {
        $nom = $user['nom'];
        $mdp = $user['mdp'];
        $id_role = $user['id_role'];
        
        // Générer le hash
        $hash = password_hash($mdp, PASSWORD_BCRYPT);
        
        // Vérifier que le hash fonctionne
        $verify = password_verify($mdp, $hash);
        
        // Mettre à jour en BD
        $query = "UPDATE utilisateur SET mdp = :mdp WHERE nom = :nom";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':mdp', $hash);
        $stmt->bindParam(':nom', $nom);
        $stmt->execute();
        
        $status = $verify ? '✅ OK' : '❌ ERREUR';
        
        echo "<tr>";
        echo "<td><strong>$nom</strong></td>";
        echo "<td>$mdp</td>";
        echo "<td><code>" . substr($hash, 0, 20) . "...</code></td>";
        echo "<td>$status</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<hr>";
    echo "<h2>✅ Mise à jour réussie!</h2>";
    echo "<p><strong>Mots de passe correctifs:</strong></p>";
    echo "<ul>";
    echo "<li><strong>admin</strong> / <strong>admin123</strong> (Administrateur)</li>";
    echo "<li><strong>user</strong> / <strong>user123</strong> (Utilisateur)</li>";
    echo "</ul>";
    
    echo "<hr>";
    echo "<p><a href='login.html' style='font-size: 16px; padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px;'>→ Aller à la page de connexion</a></p>";
    
} catch (PDOException $e) {
    echo "<h2>❌ Erreur!</h2>";
    echo "<p><strong>Message d'erreur:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Vérification finale
echo "<hr>";
echo "<h2>Vérification finale:</h2>";
try {
    $query = "SELECT id, nom, mdp FROM utilisateur ORDER BY id";
    $stmt = $pdo->query($query);
    $users = $stmt->fetchAll();
    
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>Nom</th><th>Hash en BD</th></tr>";
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>" . $user['id'] . "</td>";
        echo "<td>" . $user['nom'] . "</td>";
        echo "<td><code>" . substr($user['mdp'], 0, 30) . "...</code></td>";
        echo "</tr>";
    }
    echo "</table>";
} catch (PDOException $e) {
    echo "Erreur: " . htmlspecialchars($e->getMessage());
}

echo "<hr>";
echo "<p style='color: #999; font-size: 12px;'>Vous pouvez supprimer ce fichier après avoir vérifié que la connexion fonctionne.</p>";
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f5f5f5;
        }
        h1, h2 {
            color: #333;
        }
        table {
            border-collapse: collapse;
            background: white;
            margin: 20px 0;
        }
        th {
            background: #667eea;
            color: white;
        }
        code {
            background: #f0f0f0;
            padding: 2px 5px;
            border-radius: 3px;
            font-family: monospace;
        }
        a {
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>
</html>
