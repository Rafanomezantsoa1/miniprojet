<?php
session_start();

// Check if user is logged in
if (empty($_SESSION['logged_in'])) {
    header('Location: ../index.php');
    exit();
}

require_once '../db.php';

$id_page = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$error = '';
$success = '';

// Fetch page
try {
    $query = "SELECT id_page, slug FROM page WHERE id_page = :id_page";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_page', $id_page, PDO::PARAM_INT);
    $stmt->execute();
    $page = $stmt->fetch();
    
    if (!$page) {
        header('Location: page-liste.html');
        exit();
    }
} catch (PDOException $e) {
    $error = 'Erreur: ' . $e->getMessage();
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $slug = $_POST['slug'] ?? '';
    
    if (empty($slug)) {
        $error = 'Le slug est obligatoire.';
    } else {
        try {
            // Check if new slug already exists (excluding current page)
            $check_query = "SELECT COUNT(*) as count FROM page WHERE slug = :slug AND id_page != :id_page";
            $stmt = $pdo->prepare($check_query);
            $stmt->bindParam(':slug', $slug);
            $stmt->bindParam(':id_page', $id_page, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch();
            
            if ($result['count'] > 0) {
                $error = 'Ce slug existe déjà.';
            } else {
                $update_query = "UPDATE page SET slug = :slug WHERE id_page = :id_page";
                $stmt = $pdo->prepare($update_query);
                $stmt->bindParam(':slug', $slug);
                $stmt->bindParam(':id_page', $id_page, PDO::PARAM_INT);
                $stmt->execute();
                
                $success = 'Page modifiée avec succès!';
                $page['slug'] = $slug;
            }
        } catch (PDOException $e) {
            $error = 'Erreur: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une Page - Panneau d'Administration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-container">
        <header>
            <div class="header-content">
            <h1>Modifier une Page</h1>
            <nav>
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
                    <label for="slug">Slug (identifiant unique) *</label>
                    <input type="text" id="slug" name="slug" value="<?php echo htmlspecialchars($page['slug']); ?>" required>
                    <small>Format: texte en minuscules, tirets autorisés</small>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Modifier</button>
                    <a href="page-liste.html" class="btn">Annuler</a>
                </div>
            </form>
        </main>

        <footer>
            <p>&copy; 2024 Iran War CMS - Panneau d'Administration</p>
        </footer>
    </div>
</body>
</html>
