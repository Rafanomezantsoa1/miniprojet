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

// Get all tags
try {
    $query = "SELECT id_tag, libelle FROM tag ORDER BY libelle";
    $stmt = $pdo->query($query);
    $tags = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = 'Erreur lors du chargement des tags: ' . $e->getMessage();
    $tags = [];
}

// Handle delete via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    
    try {
        // Delete associated contenu_tag entries first
        $delete_assoc_query = "DELETE FROM contenu_tag WHERE id_tag = :id_tag";
        $stmt = $pdo->prepare($delete_assoc_query);
        $stmt->bindParam(':id_tag', $delete_id, PDO::PARAM_INT);
        $stmt->execute();
        
        // Delete tag
        $delete_query = "DELETE FROM tag WHERE id_tag = :id_tag";
        $stmt = $pdo->prepare($delete_query);
        $stmt->bindParam(':id_tag', $delete_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $success = 'Tag supprimé avec succès';
        // Refresh page
        header('Location: tag-liste.html?success=Tag supprimé avec succès');
        exit();
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
    <title>Liste des Tags - Panneau d'Administration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-container">        <header>
        <div class="header-content">
            <h1>Liste des Tags</h1>            <nav>
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
                <h2 style="margin: 0; font-size: 1.5em; color: #2c3e50;">Gestion des Tags</h2>
                <a href="tag-ajouter.html" class="btn btn-primary">+ Ajouter un nouveau tag</a>
            </div>

            <?php if (!empty($error)): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <?php if (empty($tags)): ?>
                <p>Aucun tag trouvé.</p>
                <p><a href="tag-ajouter.html">Ajouter le premier tag</a></p>
            <?php else: ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Libellé</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tags as $tag): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($tag['id_tag']); ?></td>
                                <td><?php echo htmlspecialchars($tag['libelle']); ?></td>
                                <td>
                                    <form method="POST" action="" style="display: inline;">
                                        <input type="hidden" name="delete_id" value="<?php echo $tag['id_tag']; ?>">
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr?')">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </main>

        <footer>
            <p>&copy; 2024 Iran War CMS</p>
        </footer>
    </div>
</body>
</html>
