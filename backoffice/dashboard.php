<?php
session_start();

// Check if user is logged in
if (empty($_SESSION['logged_in'])) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Panneau d'Administration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-container">        <header>
        <div class="header-content">
            <h1>Panneau d'Administration - Iran War CMS</h1>            <nav>
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
            <h2>Bienvenue au Panneau d'Administration</h2>
              <section class="dashboard-grid">
                <div class="dashboard-card">
                    <h3>📄 Pages</h3>
                    <p>Gérez les pages principales de votre site</p>
                    <ul>
                        <li><a href="page-liste.html">Voir toutes les pages</a></li>
                        <li><a href="page-ajouter.html">Créer une nouvelle page</a></li>
                    </ul>
                </div>

                <div class="dashboard-card">
                    <h3>📝 Contenu</h3>
                    <p>Gérez les articles et le contenu de votre site</p>
                    <ul>
                        <li><a href="contenu-liste.html">Voir tous les contenus</a></li>
                        <li><a href="contenu-ajouter.html">Ajouter un nouveau contenu</a></li>
                    </ul>
                </div>

                <div class="dashboard-card">
                    <h3>🏷️ Tags</h3>
                    <p>Gérez les tags pour catégoriser votre contenu</p>
                    <ul>
                        <li><a href="tag-liste.html">Voir tous les tags</a></li>
                        <li><a href="tag-ajouter.html">Ajouter un nouveau tag</a></li>
                    </ul>
                </div>                <div class="dashboard-card">
                    <h3>🖼️ Images</h3>
                    <p>Gérez les images associées à votre contenu</p>
                    <ul>
                        <li><a href="image-ajouter.html">Télécharger une image</a></li>
                    </ul>
                </div>

                <div class="dashboard-card">
                    <h3>👥 Utilisateurs</h3>
                    <p>Gérez les comptes d'accès administrateur</p>
                    <ul>
                        <li><a href="utilisateur/liste.php">Voir tous les utilisateurs</a></li>
                        <li><a href="utilisateur/ajouter.php">Créer un nouvel utilisateur</a></li>
                    </ul>
                </div>
        </main>

        <footer>
            <p>&copy; 2024 Iran War CMS - Panneau d'Administration</p>
        </footer>
    </div>
</body>
</html>
