<?php
session_start();

// Check if user is logged in and is admin
if (empty($_SESSION['logged_in'])) {
    header('Location: index.html');
    exit();
}

if ($_SESSION['user_role'] !== 'admin') {
    header('Location: admin-dashboard.html');
    exit();
}

require_once '../db.php';

$error = '';
$success = '';

// Get all roles
$roles = [];
try {
    $query = "SELECT id_role, libelle FROM role ORDER BY libelle";
    $stmt = $pdo->query($query);
    $roles = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = 'Erreur lors du chargement des rôles: ' . $e->getMessage();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $mdp = $_POST['mdp'] ?? '';
    $id_role = $_POST['id_role'] ?? 2; // Default to 'user' role
    
    // Validation
    if (empty($nom) || empty($mdp)) {
        $error = 'Le nom d\'utilisateur et le mot de passe sont obligatoires.';
    } else if (strlen($mdp) < 6) {
        $error = 'Le mot de passe doit contenir au moins 6 caractères.';
    } else {
        try {
            // Hash the password
            $mdp_hash = password_hash($mdp, PASSWORD_BCRYPT);
            
            // Insert new user
            $query = "INSERT INTO utilisateur (nom, mdp, id_role) VALUES (:nom, :mdp, :id_role)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':mdp', $mdp_hash);
            $stmt->bindParam(':id_role', $id_role, PDO::PARAM_INT);
            $stmt->execute();
              $success = 'Utilisateur créé avec succès!';
            header('Location: utilisateur-liste.html');
            exit();
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate') !== false) {
                $error = 'Ce nom d\'utilisateur existe déjà.';
            } else {
                $error = 'Erreur lors de la création: ' . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Utilisateur - Panneau d'Administration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-container">
        <header>
            <div class="header-content">
            <h1>Ajouter un Utilisateur</h1>            <nav>
                <ul>
                    <li><a href="admin-dashboard.html">Accueil</a></li>
                    <li><a href="page-liste.html">Pages</a></li>
                    <li><a href="contenu-liste.html">Contenus</a></li>
                    <li><a href="tag-liste.html">Tags</a></li>
                    <li><a href="image-ajouter.html">Images</a></li>
                    <li><a href="utilisateur-liste.html">Utilisateurs</a></li>
                    <li><a href="logout.html">Déconnecter</a></li>
                </ul>
            </nav>
            </div>
        </header>

        <main>
            <?php if (!empty($error)): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <form method="POST" action="" class="admin-form">
                <div class="form-group">
                    <label for="nom">Nom d'utilisateur *</label>
                    <input type="text" id="nom" name="nom" required>
                </div>

                <div class="form-group">
                    <label for="mdp">Mot de passe *</label>
                    <input type="password" id="mdp" name="mdp" required minlength="6">
                    <small>Minimum 6 caractères</small>
                </div>                <div class="form-group">
                    <label for="id_role">Rôle</label>
                    <select id="id_role" name="id_role">
                        <?php foreach ($roles as $role): ?>
                            <option value="<?php echo $role['id_role']; ?>" <?php echo $role['id_role'] == 2 ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($role['libelle']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Créer l'utilisateur</button>
                    <a href="utilisateur-liste.html" class="btn">Annuler</a>
                </div>
            </form>
        </main>

        <footer>
            <p>&copy; 2026 Iran War CMS</p>
        </footer>
    </div>
</body>
</html>
