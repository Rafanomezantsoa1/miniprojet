<?php
session_start();

// Check if user is logged in
if (empty($_SESSION['logged_in'])) {
    header('Location: ../index.php');
    exit();
}

require_once '../db.php';

// Get all contents with type information, grouped by page and parent
try {
    $query = "SELECT c.id_contenu, c.texte, c.slug, c.id_parent, c.ordre, t.nom_type, t.balise, p.slug as page_slug, p.id_page
              FROM contenu c
              LEFT JOIN type t ON c.id_type = t.id_type
              LEFT JOIN page p ON c.id_page = p.id_page
              ORDER BY p.id_page, c.id_parent, c.ordre, c.id_contenu";
    
    $stmt = $pdo->query($query);
    $contents = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = 'Erreur lors du chargement des contenus: ' . $e->getMessage();
    $contents = [];
}

// Build hierarchy
$pages_content = [];
foreach ($contents as $content) {
    $page = $content['page_slug'] ?: 'Sans page';
    if (!isset($pages_content[$page])) {
        $pages_content[$page] = [];
    }
    $pages_content[$page][] = $content;
}

// Function to display hierarchy recursively
function displayHierarchy($contents, $parentId = null, $level = 0) {
    $children = array_filter($contents, fn($c) => $c['id_parent'] === $parentId);
    
    foreach ($children as $content):
        $indent = str_repeat('—', $level);
        ?>
        <tr class="level-<?php echo $level; ?>">
            <td><?php echo htmlspecialchars($content['id_contenu']); ?></td>
            <td>
                <span class="type-badge type-<?php echo strtolower(str_replace(' ', '-', $content['nom_type'] ?? 'default')); ?>">
                    <?php echo htmlspecialchars($content['nom_type'] ?? 'N/A'); ?>
                </span>
            </td>
            <td class="content-preview">
                <?php if ($level > 0): ?>
                    <span class="indent-marker"><?php echo $indent; ?></span>
                <?php endif; ?>
                <?php echo htmlspecialchars(substr($content['texte'], 0, 60)); ?>
                <?php if (strlen($content['texte']) > 60): ?>...<?php endif; ?>
            </td>
            <td class="actions-cell">
                <a href="contenu-modifier-<?php echo $content['id_contenu']; ?>.html" class="btn-edit-small">Modifier</a>
                <a href="contenu-supprimer-<?php echo $content['id_contenu']; ?>.html" class="btn-delete-small" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce contenu ?')">Supprimer</a>
            </td>
        </tr>
        
        <?php 
        // Recursively display children
        displayHierarchy($contents, $content['id_contenu'], $level + 1);
    endforeach;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contenus - Administration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-container">
        <header>
            <div class="header-content">
                <h1>Gestion des Contenus</h1>
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
            <div class="page-header">
                <h2>Liste des contenus</h2>
                <a href="contenu-ajouter.html" class="btn-primary">+ Nouveau contenu</a>
            </div>

            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if (empty($pages_content)): ?>
                <div class="empty-state">
                    <p>Aucun contenu trouvé.</p>
                    <a href="contenu-ajouter.html" class="btn-primary">Créer le premier contenu</a>
                </div>
            <?php else: ?>
                <?php foreach ($pages_content as $page_name => $page_contents): ?>
                    <div class="page-section">
                        <h3 class="page-title"><?php echo htmlspecialchars($page_name); ?></h3>
                        
                        <table class="content-table">
                            <thead>
                                <tr>
                                    <th width="60">ID</th>
                                    <th width="140">Type</th>
                                    <th>Contenu</th>
                                    <th width="180">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php displayHierarchy($page_contents, null, 0); ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </main>

        <footer>
            <p>&copy; 2024 Iran War CMS - Panneau d'administration</p>
        </footer>
    </div>
</body>
</html>