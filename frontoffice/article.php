<?php
// article.php - Page de détail d'un article avec ses paragraphes
require_once 'db.php';

function sanitize_slug($slug) {
    return preg_replace('/[^a-z0-9-]/', '', strtolower($slug));
}

$subtitle_slug = isset($_GET['slug']) ? sanitize_slug($_GET['slug']) : null;
$subtitle_id = 0;

// Si on a un slug, chercher l'ID du sous-titre
if ($subtitle_slug) {
    try {
        $stmt = $pdo->prepare("SELECT id_contenu FROM contenu WHERE slug = ? LIMIT 1");
        $stmt->execute([$subtitle_slug]);
        $result = $stmt->fetch();
        if ($result) {
            $subtitle_id = $result['id_contenu'];
        }
    } catch (PDOException $e) {
        // Erreur de requête
    }
}

// Si pas d'ID valide, rediriger
if (!$subtitle_id) {
    header('Location: index.php');
    exit;
}

try {
    // Récupérer le sous-titre
    $stmt_subtitle = $pdo->prepare("
        SELECT c.id_contenu, c.texte, c.slug, c.id_page, c.id_type, 
               (SELECT STRING_AGG(DISTINCT tg.libelle, ',') FROM tag tg 
                JOIN contenu_tag ct ON tg.id_tag = ct.id_tag 
                WHERE ct.id_contenu = c.id_contenu) as tags,
               (SELECT i.path FROM image i WHERE i.id_contenu = c.id_contenu LIMIT 1) as image
        FROM contenu c
        WHERE c.id_contenu = ?
    ");
    $stmt_subtitle->execute([$subtitle_id]);
    $subtitle = $stmt_subtitle->fetch();
    
    if (!$subtitle) {
        header('Location: index.php');
        exit;
    }
    
    // Récupérer les paragraphes enfants
    $stmt_paragraphs = $pdo->prepare("
        SELECT c.id_contenu, c.texte, c.ordre,
               (SELECT STRING_AGG(DISTINCT tg.libelle, ',') FROM tag tg 
                JOIN contenu_tag ct ON tg.id_tag = ct.id_tag 
                WHERE ct.id_contenu = c.id_contenu) as tags,
               (SELECT i.path FROM image i WHERE i.id_contenu = c.id_contenu LIMIT 1) as image
        FROM contenu c
        WHERE c.id_parent = ?
        ORDER BY c.ordre, c.id_contenu
    ");
    $stmt_paragraphs->execute([$subtitle_id]);
    $paragraphs = $stmt_paragraphs->fetchAll();
    
    // Récupérer la page pour la navigation
    $stmt_page = $pdo->prepare("SELECT slug FROM page WHERE id_page = ?");
    $stmt_page->execute([$subtitle['id_page']]);
    $page = $stmt_page->fetch();
    
    // Récupérer toutes les pages pour la navigation
    $pages_query = "SELECT id_page, slug FROM page ORDER BY id_page";
    $pages_stmt = $pdo->query($pages_query);
    $pages = $pages_stmt->fetchAll();
    
} catch (PDOException $e) {
    die('Erreur: ' . $e->getMessage());
}

$subtitle_tags = !empty($subtitle['tags']) ? explode(',', $subtitle['tags']) : [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($subtitle['texte']); ?> - Iran War Analysis</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="site-header">
        <div class="header-container">
            <div class="logo-area">
                <span class="site-logo">IWA</span>
                <div>
                    <h1 class="site-title">Iran War Analysis</h1>
                    <p class="site-tagline">Analyse géopolitique</p>
                </div>
            </div>
            <nav class="main-nav">
                <?php foreach ($pages as $p): ?>
                    <a href="page-<?php echo htmlspecialchars(sanitize_slug($p['slug'])); ?>" 
                       class="nav-link <?php echo $p['id_page'] == $subtitle['id_page'] ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars(ucfirst(str_replace('-', ' ', $p['slug']))); ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        </div>
    </header>

    <main class="main-container">
        <div class="container">
            <!-- Back Button -->
            <div class="article-back">
                <a href="page-<?php echo htmlspecialchars(sanitize_slug($page['slug'])); ?>" class="back-link">← Retour</a>
            </div>

            <!-- Article Header -->
            <article class="article-detail">
                <?php if (!empty($subtitle['image'])): ?>
                    <div class="article-image-header">
                        <img src="<?php echo htmlspecialchars($subtitle['image']); ?>" alt="<?php echo htmlspecialchars($subtitle['texte']); ?>" loading="lazy">
                    </div>
                <?php endif; ?>
                
                <header class="article-header">
                    <h1 class="article-title"><?php echo htmlspecialchars($subtitle['texte']); ?></h1>
                    
                    <?php if (!empty($subtitle_tags)): ?>
                        <div class="article-tags">
                            <?php foreach ($subtitle_tags as $tag): ?>
                                <span class="tag-badge"><?php echo htmlspecialchars(trim($tag)); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </header>

                <!-- Paragraphes -->
                <div class="article-content">
                    <?php if (!empty($paragraphs)): ?>
                        <?php foreach ($paragraphs as $para): 
                            $para_tags = !empty($para['tags']) ? explode(',', $para['tags']) : [];
                        ?>
                            <section class="paragraph-section">
                                <?php if (!empty($para['image'])): ?>
                                    <figure class="paragraph-figure">
                                        <img src="<?php echo htmlspecialchars($para['image']); ?>" alt="Illustration" loading="lazy">
                                    </figure>
                                <?php endif; ?>
                                
                                <div class="paragraph-body">
                                    <p><?php echo nl2br(htmlspecialchars($para['texte'])); ?></p>
                                    
                                    <?php if (!empty($para_tags)): ?>
                                        <div class="paragraph-tags">
                                            <?php foreach ($para_tags as $tag): ?>
                                                <span class="tag-badge-small"><?php echo htmlspecialchars(trim($tag)); ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </section>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="no-content">Aucun contenu pour cet article.</p>
                    <?php endif; ?>
                </div>
            </article>
        </div>
    </main>

    <footer class="site-footer">
        <div class="footer-container">
            <p>&copy; 2026 Iran War Analysis Platform</p>
        </div>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>