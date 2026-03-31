<?php
session_start();

// Check if user is logged in
if (empty($_SESSION['logged_in'])) {
    header('Location: ../index.php');
    exit();
}

require_once '../db.php';

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $libelle = $_POST['libelle'] ?? '';
    
    // Validation
    if (empty($libelle)) {
        $error = 'Le libellé du tag est obligatoire.';
    } else {
        try {
            // Check if tag already exists
            $check_query = "SELECT id_tag FROM tag WHERE LOWER(libelle) = LOWER(:libelle)";
            $check_stmt = $pdo->prepare($check_query);
            $check_stmt->bindParam(':libelle', $libelle);
            $check_stmt->execute();
            
            if ($check_stmt->fetch()) {
                $error = 'Ce tag existe déjà.';
            } else {
                // Insert tag
                $insert_query = "INSERT INTO tag (libelle) VALUES (:libelle)";
                $stmt = $pdo->prepare($insert_query);
                $stmt->bindParam(':libelle', $libelle);
                $stmt->execute();
                
                $success = 'Tag ajouté avec succès!';
                header('Location: tag-liste.html?success=Tag ajouté avec succès');
                exit();
            }
        } catch (PDOException $e) {
            $error = 'Erreur lors de l\'ajout du tag: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Tag - Panneau d'Administration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-container">
        <header>
            <div class="header-content">
            <h1>Ajouter un Tag</h1>            <nav>
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
                    <label for="libelle">Libellé du tag *</label>
                    <input type="text" id="libelle" name="libelle" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                    <a href="tag-liste.html" class="btn">Annuler</a>
                </div>
            </form>
        </main>

        <footer>
            <p>&copy; 2024 Iran War CMS</p>
        </footer>
    </div>
</body>
</html>
