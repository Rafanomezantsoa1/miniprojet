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

// Get all contents for the dropdown
try {
    $contents_query = "SELECT id_contenu, slug FROM contenu ORDER BY slug";
    $contents = $pdo->query($contents_query)->fetchAll();
} catch (PDOException $e) {
    $error = 'Erreur lors du chargement des contenus: ' . $e->getMessage();
    $contents = [];
}

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_contenu = $_POST['id_contenu'] ?? null;
    
    // Validation
    if (empty($id_contenu)) {
        $error = 'Veuillez sélectionner un contenu.';
    } elseif (empty($_FILES['image']['name'])) {
        $error = 'Veuillez sélectionner une image.';
    } else {
        // Validate file type
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        
        if (!in_array($_FILES['image']['type'], $allowed_types)) {
            $error = 'Type de fichier non autorisé. Utilisez JPEG, PNG, GIF ou WEBP.';
        } elseif ($_FILES['image']['size'] > 5 * 1024 * 1024) { // 5MB max
            $error = 'Le fichier est trop volumineux (max 5MB).';        } else {
            try {
                // Create images directories if they don't exist
                $backoffice_upload_dir = '../images/';
                $frontoffice_upload_dir = '../../frontoffice/images/';
                
                if (!is_dir($backoffice_upload_dir)) {
                    mkdir($backoffice_upload_dir, 0755, true);
                }
                if (!is_dir($frontoffice_upload_dir)) {
                    mkdir($frontoffice_upload_dir, 0755, true);
                }
                
                // Generate unique filename
                $filename = uniqid() . '_' . basename($_FILES['image']['name']);
                $backoffice_filepath = $backoffice_upload_dir . $filename;
                $frontoffice_filepath = $frontoffice_upload_dir . $filename;
                
                // Move uploaded file to backoffice
                if (move_uploaded_file($_FILES['image']['tmp_name'], $backoffice_filepath)) {
                    // Copy file to frontoffice
                    copy($backoffice_filepath, $frontoffice_filepath);
                    
                    // Store path in database (relative path)
                    $db_path = 'images/' . $filename;
                    
                    $insert_query = "INSERT INTO image (id_contenu, path) VALUES (:id_contenu, :path)";
                    $stmt = $pdo->prepare($insert_query);
                    $stmt->bindParam(':id_contenu', $id_contenu, PDO::PARAM_INT);
                    $stmt->bindParam(':path', $db_path);
                    $stmt->execute();
                    
                    $success = 'Image téléchargée avec succès!';
                } else {
                    $error = 'Erreur lors du téléchargement du fichier.';
                }
            } catch (PDOException $e) {
                $error = 'Erreur lors de l\'enregistrement en base de données: ' . $e->getMessage();
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
    <title>Ajouter une Image - Panneau d'Administration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-container">
        <header>
            <div class="header-content">
            <h1>Télécharger une Image</h1>            <nav>
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
                <h2 style="margin: 0; font-size: 1.5em; color: #2c3e50;">Télécharger une Image</h2>
            </div>

            <?php if (!empty($error)): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <form method="POST" action="" class="admin-form" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="id_contenu">Contenu *</label>
                    <select id="id_contenu" name="id_contenu" required>
                        <option value="">-- Sélectionner un contenu --</option>
                        <?php foreach ($contents as $content): ?>
                            <option value="<?php echo $content['id_contenu']; ?>">
                                <?php echo htmlspecialchars($content['slug']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="image">Image *</label>
                    <input type="file" id="image" name="image" accept="image/*" required>
                    <small>Format accepté: JPEG, PNG, GIF, WEBP (max 5MB)</small>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Télécharger</button>
                    <a href="../admin-dashboard.html" class="btn">Annuler</a>
                </div>
            </form>
        </main>

        <footer>
            <p>&copy; 2024 Iran War CMS</p>
        </footer>
    </div>
</body>
</html>
