<?php
session_start();

// Check if user is logged in and is admin
if (empty($_SESSION['logged_in'])) {
    header('Location: index.html');
    exit();
}

// Check if user is admin
if ($_SESSION['user_role'] !== 'admin') {
    header('Location: admin-dashboard.html');
    exit();
}

require_once '../db.php';

$error = '';
$success = '';

// Get all users
try {
    $query = "SELECT u.id, u.nom, r.libelle as role 
              FROM utilisateur u
              LEFT JOIN role r ON u.id_role = r.id_role
              ORDER BY u.id_role DESC, u.nom ASC";
    $stmt = $pdo->query($query);
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = 'Erreur lors du chargement des utilisateurs: ' . $e->getMessage();
    $users = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs - Panneau d'Administration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-container">
        <header>
            <div class="header-content">
                <h1>Gestion des Utilisateurs</h1>
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
            <div class="users-container">
                <?php if (!empty($error)): ?>
                    <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <div class="page-header">
                    <h2>Liste des Utilisateurs</h2>
                    <a href="utilisateur-ajouter.html" class="btn-primary">+ Nouvel utilisateur</a>
                </div>

                <?php if (!empty($users)): ?>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th width="60">ID</th>
                                    <th>Nom d'utilisateur</th>
                                    <th width="120">Rôle</th>
                                    <th width="200">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?php echo $user['id']; ?></td>
                                        <td class="user-name"><?php echo htmlspecialchars($user['nom']); ?></td>
                                        <td>
                                            <span class="role-badge role-<?php echo $user['role']; ?>">
                                                <?php echo $user['role'] === 'admin' ? 'Administrateur' : 'Utilisateur'; ?>
                                            </span>
                                        </td>
                                        <td class="actions-cell">
                                            <div class="action-buttons">
                                                <a href="utilisateur-modifier-<?php echo $user['id']; ?>.html" class="btn-icon btn-edit" title="Modifier">
                                                    ✏️ Modifier
                                                </a>
                                                <?php if ($user['id'] !== $_SESSION['user_id']): ?>
                                                    <a href="utilisateur-supprimer-<?php echo $user['id']; ?>.html" class="btn-icon btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')" title="Supprimer">
                                                        🗑️ Supprimer
                                                    </a>
                                                <?php else: ?>
                                                    <span class="current-user-badge">Vous</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <p>Aucun utilisateur trouvé.</p>
                        <a href="utilisateur-ajouter.html" class="btn-primary">Créer le premier utilisateur</a>
                    </div>
                <?php endif; ?>
            </div>
        </main>

        <footer>
            <p>&copy; 2026 Iran War CMS - Panneau d'administration</p>
        </footer>
    </div>
</body>
</html>