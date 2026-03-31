<?php
session_start();

require_once 'db.php';

// Check if user is already logged in
if (!empty($_SESSION['logged_in'])) {
    header('Location: admin-dashboard.html');
    exit();
}

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $mdp = $_POST['mdp'] ?? '';
    
    if (empty($nom) || empty($mdp)) {
        $error = 'Le nom d\'utilisateur et le mot de passe sont obligatoires.';
    } else {
        try {
            // Get user from database with role information
            $query = "SELECT u.id, u.nom, u.mdp, r.libelle as role 
                      FROM utilisateur u
                      LEFT JOIN role r ON u.id_role = r.id_role
                      WHERE u.nom = :nom LIMIT 1";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':nom', $nom);
            $stmt->execute();
            $user = $stmt->fetch();
              // Verify password and check if user is admin
            if ($user && password_verify($mdp, $user['mdp']) && $user['role'] === 'admin') {
                // Login successful
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_nom'] = $user['nom'];
                $_SESSION['user_role'] = $user['role'];
                
                // DEBUG
                error_log("Login réussi pour admin. Session: " . json_encode($_SESSION));
                
                $success = 'Connexion réussie!';
                // Redirect after short delay to ensure session is saved
                sleep(1);
                header('Location: admin-dashboard.html', true, 302);
                exit();
            } else {
                // DEBUG
                error_log("Login échoué. User: " . json_encode($user) . ", Role check: " . ($user['role'] ?? 'NULL'));
                $error = 'Accès refusé. Seul l\'administrateur peut se connecter.';
            }
        } catch (PDOException $e) {
            $error = 'Erreur lors de la connexion: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Iran War CMS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-header">
            <h1>Iran War CMS</h1>
            <p>Panneau d'administration</p>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="nom">Nom d'utilisateur</label>
                <input type="text" id="nom" name="nom" value="admin" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="mdp">Mot de passe</label>
                <input type="password" id="mdp" name="mdp" value="admin123" required>
            </div>
            
            <button type="submit" class="btn-login">Se connecter</button>
        </form>
        
        <div class="login-footer">
            <p>Accès réservé à l'administration</p>
        </div>
    </div>
</body>
</html>