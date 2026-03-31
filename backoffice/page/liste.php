<?php
session_start();

// Check if user is logged in
if (empty($_SESSION['logged_in'])) {
    header('Location: ../index.php');
    exit();
}

require_once '../db.php';

$error = '';
$success = $_GET['success'] ?? '';

// Get all pages
try {
    $query = "SELECT id_page, slug FROM page ORDER BY id_page";
    $stmt = $pdo->query($query);
    $pages = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = 'Erreur lors du chargement des pages: ' . $e->getMessage();
    $pages = [];
}

// Handle delete via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    
    try {
        // First check if page has content
        $check_query = "SELECT COUNT(*) as count FROM contenu WHERE id_page = :id_page";
        $stmt = $pdo->prepare($check_query);
        $stmt->bindParam(':id_page', $delete_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        
        if ($result['count'] > 0) {
            $error = 'Impossible de supprimer: cette page contient du contenu. Supprimez d\'abord le contenu associé.';
        } else {
            $delete_query = "DELETE FROM page WHERE id_page = :id_page";
            $stmt = $pdo->prepare($delete_query);
            $stmt->bindParam(':id_page', $delete_id, PDO::PARAM_INT);
            $stmt->execute();
            
            $success = 'Page supprimée avec succès';
            header('Location: page-liste.html?success=Page supprimée avec succès');
            exit();
        }
    } catch (PDOException $e) {
        $error = 'Erreur lors de la suppression: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Pages - Panneau d'Administration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-container">
        <header>
            <div class="header-content">
            <h1>Liste des Pages</h1>
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
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <h2 style="margin: 0; font-size: 1.5em; color: #2c3e50;">Gestion des Pages</h2>
                <a href="page-ajouter.html" class="btn btn-primary">+ Ajouter une nouvelle page</a>
            </div>

            <?php if (!empty($error)): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <?php if (empty($pages)): ?>
                <p>Aucune page trouvée.</p>
                <p><a href="page-ajouter.html">Ajouter la première page</a></p>
            <?php else: ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Slug</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pages as $page): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($page['id_page']); ?></td>
                                <td><strong><?php echo htmlspecialchars($page['slug']); ?></strong></td>
                                <td style="display: flex; gap: 10px;">
                                    <a href="page-modifier-<?php echo $page['id_page']; ?>.html" class="btn btn-small">Modifier</a>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr?');">
                                        <input type="hidden" name="delete_id" value="<?php echo $page['id_page']; ?>">
                                        <button type="submit" class="btn btn-small btn-danger">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </main>

        <footer>
            <p>&copy; 2024 Iran War CMS - Panneau d'Administration</p>
        </footer>
    </div>
</body>
</html>
