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

// Get available types and pages
try {
    $types_query = "SELECT id_type, nom_type FROM type ORDER BY nom_type";
    $types = $pdo->query($types_query)->fetchAll();
    
    $pages_query = "SELECT id_page, slug FROM page ORDER BY slug";
    $pages = $pdo->query($pages_query)->fetchAll();
    
    $tags_query = "SELECT id_tag, libelle FROM tag ORDER BY libelle";
    $tags = $pdo->query($tags_query)->fetchAll();
} catch (PDOException $e) {
    $error = 'Erreur lors du chargement des données: ' . $e->getMessage();
    $types = [];
    $pages = [];
    $tags = [];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $texte = $_POST['texte'] ?? '';
    $slug = $_POST['slug'] ?? '';
    $id_type = $_POST['id_type'] ?? null;
    $id_page = $_POST['id_page'] ?? null;
    $selected_tags = $_POST['tags'] ?? [];
      // Validation
    if (empty($texte) || empty($slug) || empty($id_type) || empty($id_page)) {
        $error = 'Le texte, le slug, le type et la page sont obligatoires.';
    } else {
        try {
            // Get parent from form (can be null)
            $id_parent = !empty($_POST['id_parent']) ? (int)$_POST['id_parent'] : null;
            
            // Get ordre from form (default to 0)
            $ordre = !empty($_POST['ordre']) ? (int)$_POST['ordre'] : 0;
            
            // Insert content
            $insert_query = "INSERT INTO contenu (texte, slug, id_type, id_page, id_parent, ordre) 
                             VALUES (:texte, :slug, :id_type, :id_page, :id_parent, :ordre)
                             RETURNING id_contenu";
            
            $stmt = $pdo->prepare($insert_query);
            $stmt->bindParam(':texte', $texte);
            $stmt->bindParam(':slug', $slug);
            $stmt->bindParam(':id_type', $id_type, PDO::PARAM_INT);
            $stmt->bindParam(':id_page', $id_page, PDO::PARAM_INT);
            $stmt->bindParam(':id_parent', $id_parent, PDO::PARAM_INT);
            $stmt->bindParam(':ordre', $ordre, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch();
            $id_contenu = $result['id_contenu'];
            
            // Insert tags
            if (!empty($selected_tags)) {
                $tag_query = "INSERT INTO contenu_tag (id_contenu, id_tag) VALUES (:id_contenu, :id_tag)";
                $tag_stmt = $pdo->prepare($tag_query);
                
                foreach ($selected_tags as $id_tag) {
                    $tag_stmt->bindParam(':id_contenu', $id_contenu, PDO::PARAM_INT);
                    $tag_stmt->bindParam(':id_tag', $id_tag, PDO::PARAM_INT);
                    $tag_stmt->execute();
                }
            }
              // Handle multiple image uploads
            $upload_success_count = 0;
            if (!empty($_FILES['images']['name'][0])) {
                $backoffice_upload_dir = '../images/';
                $frontoffice_upload_dir = '../../frontoffice/images/';
                
                if (!is_dir($backoffice_upload_dir)) {
                    mkdir($backoffice_upload_dir, 0755, true);
                }
                if (!is_dir($frontoffice_upload_dir)) {
                    mkdir($frontoffice_upload_dir, 0755, true);
                }
                
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $max_size = 5 * 1024 * 1024; // 5MB
                
                for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
                    if (!empty($_FILES['images']['name'][$i])) {
                        $file_type = $_FILES['images']['type'][$i];
                        $file_size = $_FILES['images']['size'][$i];
                        $tmp_file = $_FILES['images']['tmp_name'][$i];
                        $file_name = $_FILES['images']['name'][$i];
                        
                        // Validate file
                        if (!in_array($file_type, $allowed_types)) {
                            continue; // Skip invalid file types
                        }
                        
                        if ($file_size > $max_size) {
                            continue; // Skip oversized files
                        }
                        
                        // Generate unique filename
                        $filename = uniqid() . '_' . basename($file_name);
                        $backoffice_filepath = $backoffice_upload_dir . $filename;
                        $frontoffice_filepath = $frontoffice_upload_dir . $filename;
                        
                        // Move uploaded file to backoffice
                        if (move_uploaded_file($tmp_file, $backoffice_filepath)) {
                            // Copy file to frontoffice
                            copy($backoffice_filepath, $frontoffice_filepath);
                            
                            // Store path in database
                            $db_path = 'images/' . $filename;
                            
                            $image_query = "INSERT INTO image (id_contenu, path) VALUES (:id_contenu, :path)";
                            $image_stmt = $pdo->prepare($image_query);
                            $image_stmt->bindParam(':id_contenu', $id_contenu, PDO::PARAM_INT);
                            $image_stmt->bindParam(':path', $db_path);
                            $image_stmt->execute();
                            
                            $upload_success_count++;
                        }
                    }
                }
            }
            
            $success = 'Contenu ajouté avec succès!';
            if ($upload_success_count > 0) {
                $success .= " ($upload_success_count image(s) téléchargée(s))";
            }
            
            header('Location: contenu-liste.html');
            exit();
        } catch (PDOException $e) {
            $error = 'Erreur lors de l\'ajout du contenu: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Contenu - Panneau d'Administration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-container">
        <header>
            <div class="header-content">
            <h1>Ajouter un Contenu</h1>            <nav>
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
            <?php endif; ?>            <form method="POST" action="" class="admin-form" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="texte">Texte du contenu *</label>
                    <textarea id="texte" name="texte" required rows="10"></textarea>
                </div>

                <div class="form-group">
                    <label for="slug">Slug *</label>
                    <input type="text" id="slug" name="slug" required>
                </div>

                <div class="form-group">
                    <label for="id_type">Type</label>
                    <select id="id_type" name="id_type">
                        <option value="">-- Sélectionner un type --</option>
                        <?php foreach ($types as $type): ?>
                            <option value="<?php echo $type['id_type']; ?>">
                                <?php echo htmlspecialchars($type['nom_type']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>                <div class="form-group">
                    <label for="id_page">Page *</label>
                    <select id="id_page" name="id_page" required onchange="updateParentOptions()">
                        <option value="">-- Sélectionner une page --</option>
                        <?php foreach ($pages as $page): ?>
                            <option value="<?php echo $page['id_page']; ?>">
                                <?php echo htmlspecialchars($page['slug']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>                <!-- Parent selection - shows dynamically based on type -->
                <div class="form-group" id="parent-group" style="display: none;">
                    <label for="id_parent">Contenu parent</label>
                    <div id="parent-info" style="background: #ecf0f1; padding: 12px; border-radius: 6px; margin-bottom: 10px; font-size: 0.9em; color: #555;"></div>
                    <select id="id_parent" name="id_parent">
                        <option value="">-- Aucun parent --</option>
                    </select>
                </div>                <div class="form-group" style="display: none;">
                    <label for="ordre">Ordre d'affichage</label>
                    <input type="number" id="ordre" name="ordre" value="0" min="0" step="1" readonly style="background-color: #ecf0f1; cursor: not-allowed;">
                    <small>Calculé automatiquement selon le type de contenu</small>
                </div>

                <div class="form-group">
                    <label for="tags">Tags</label>
                    <div class="tags-list">
                        <?php foreach ($tags as $tag): ?>
                            <label class="checkbox-label">
                                <input type="checkbox" name="tags[]" value="<?php echo $tag['id_tag']; ?>">
                                <?php echo htmlspecialchars($tag['libelle']); ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="images">Images (optionnel)</label>
                    <input type="file" id="images" name="images[]" accept="image/*" multiple>
                    <small>Vous pouvez sélectionner plusieurs images. Format accepté: JPEG, PNG, GIF, WEBP (max 5MB par image)</small>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                    <a href="contenu-liste.html" class="btn">Annuler</a>
                </div>
            </form>
        </main>        <footer>
            <p>&copy; 2024 Iran War CMS</p>
        </footer>
    </div>    <script>
        // Données des types pour chaque page (sera remplie en PHP)
        const pageContents = <?php 
            $page_data = [];
            foreach ($pages as $page) {
                $q = "SELECT id_contenu, texte, nom_type, id_parent FROM contenu 
                      LEFT JOIN type ON contenu.id_type = type.id_type
                      WHERE id_page = :id_page
                      ORDER BY contenu.id_parent, contenu.id_contenu";
                $s = $pdo->prepare($q);
                $s->bindParam(':id_page', $page['id_page'], PDO::PARAM_INT);
                $s->execute();
                $page_data[$page['id_page']] = $s->fetchAll(PDO::FETCH_ASSOC);
            }
            echo json_encode($page_data);
        ?>;

        const typeSelect = document.getElementById('id_type');
        const pageSelect = document.getElementById('id_page');
        const parentGroup = document.getElementById('parent-group');
        const parentInfo = document.getElementById('parent-info');
        const parentSelect = document.getElementById('id_parent');

        // Fonction pour obtenir le texte du parent d'un contenu
        function getParentHierarchy(contents, contentId) {
            const content = contents.find(c => c.id_contenu === contentId);
            if (!content || !content.id_parent) return '';
            
            const parent = contents.find(c => c.id_contenu === content.id_parent);
            if (!parent) return '';
            
            const parentHierarchy = getParentHierarchy(contents, parent.id_contenu);
            if (parentHierarchy) {
                return parentHierarchy + ' → ' + parent.texte.substring(0, 40) + '...';
            }
            return parent.texte.substring(0, 40) + '...';
        }

        function updateParentOptions() {
            const selectedType = typeSelect.value;
            const selectedPage = pageSelect.value;
            const typeName = typeSelect.options[typeSelect.selectedIndex].text;

            // Clear parent select
            parentSelect.innerHTML = '<option value="">-- Aucun parent --</option>';

            if (!selectedType || !selectedPage) {
                parentGroup.style.display = 'none';
                return;
            }

            const contents = pageContents[selectedPage] || [];

            if (typeName.includes('Titre Principal')) {
                // Titre Principal - no parent needed
                parentGroup.style.display = 'none';
                parentInfo.innerHTML = '✓ Titre principal - pas de parent requis';
            } 
            else if (typeName.includes('Sous Titre')) {
                // Sous Titre - show main titles ONLY
                parentGroup.style.display = 'block';
                parentInfo.innerHTML = '📌 Sélectionnez un Titre Principal comme parent';
                
                const mainTitles = contents.filter(c => c.nom_type === 'Titre Principal');
                
                mainTitles.forEach(title => {
                    const option = document.createElement('option');
                    option.value = title.id_contenu;
                    option.textContent = '📝 ' + title.texte.substring(0, 50) + '...';
                    parentSelect.appendChild(option);
                });

                if (mainTitles.length === 0) {
                    parentInfo.innerHTML = '⚠️ Créez d\'abord un Titre Principal pour cette page';
                    parentSelect.disabled = true;
                } else {
                    parentSelect.disabled = false;
                }
            } 
            else if (typeName.includes('Paragraphe')) {
                // Paragraphe - show sub titles WITH their hierarchy
                parentGroup.style.display = 'block';
                parentInfo.innerHTML = '📌 Sélectionnez un Sous-Titre comme parent';
                
                const subTitles = contents.filter(c => c.nom_type === 'Sous Titre');
                
                subTitles.forEach(subtitle => {
                    const option = document.createElement('option');
                    option.value = subtitle.id_contenu;
                    const hierarchy = getParentHierarchy(contents, subtitle.id_contenu);
                    const displayText = hierarchy ? hierarchy + ' → 📄 ' + subtitle.texte.substring(0, 35) + '...' 
                                                  : '📄 ' + subtitle.texte.substring(0, 50) + '...';
                    option.textContent = displayText;
                    parentSelect.appendChild(option);
                });

                if (subTitles.length === 0) {
                    parentInfo.innerHTML = '⚠️ Créez d\'abord des Sous-Titres pour cette page';
                    parentSelect.disabled = true;
                } else {
                    parentSelect.disabled = false;
                }
            } 
            else if (typeName.includes('Citation')) {
                // Citation - can have sub titles or direct title parent
                parentGroup.style.display = 'block';
                parentInfo.innerHTML = '📌 Sélectionnez un parent (Sous-Titre ou Titre Principal)';
                
                // Show sub titles first
                const subTitles = contents.filter(c => c.nom_type === 'Sous Titre');
                if (subTitles.length > 0) {
                    const optgroup1 = document.createElement('optgroup');
                    optgroup1.label = '📄 Sous-Titres';
                    
                    subTitles.forEach(subtitle => {
                        const option = document.createElement('option');
                        option.value = subtitle.id_contenu;
                        const hierarchy = getParentHierarchy(contents, subtitle.id_contenu);
                        const displayText = hierarchy ? hierarchy + ' → ' + subtitle.texte.substring(0, 35) + '...' 
                                                      : subtitle.texte.substring(0, 50) + '...';
                        option.textContent = displayText;
                        optgroup1.appendChild(option);
                    });
                    parentSelect.appendChild(optgroup1);
                }

                // Then show main titles
                const mainTitles = contents.filter(c => c.nom_type === 'Titre Principal');
                if (mainTitles.length > 0) {
                    const optgroup2 = document.createElement('optgroup');
                    optgroup2.label = '📝 Titres Principaux';
                    
                    mainTitles.forEach(title => {
                        const option = document.createElement('option');
                        option.value = title.id_contenu;
                        option.textContent = title.texte.substring(0, 50) + '...';
                        optgroup2.appendChild(option);
                    });
                    parentSelect.appendChild(optgroup2);
                }

                if (mainTitles.length === 0 && subTitles.length === 0) {
                    parentInfo.innerHTML = '⚠️ Créez d\'abord du contenu parent pour cette page';
                    parentSelect.disabled = true;
                } else {
                    parentSelect.disabled = false;
                }
            }
        }        // Function to auto-calculate ordre based on type
        function updateOrdre() {
            const typeName = typeSelect.options[typeSelect.selectedIndex].text;
            const ordreField = document.getElementById('ordre');
            
            if (typeName.includes('Titre Principal')) {
                ordreField.value = 0;
            } else if (typeName.includes('Sous Titre')) {
                ordreField.value = 1;
            } else if (typeName.includes('Paragraphe')) {
                ordreField.value = 2;
            } else if (typeName.includes('Citation')) {
                ordreField.value = 3;
            }
        }

        // Initialize on load
        document.addEventListener('DOMContentLoaded', () => {
            updateParentOptions();
            updateOrdre();
        });
        typeSelect.addEventListener('change', () => {
            updateParentOptions();
            updateOrdre();
        });
        pageSelect.addEventListener('change', updateParentOptions);
    </script>
</body>
</html>
