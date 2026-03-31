<?php
// index.php - Page d'accueil du portail d'analyse
require_once 'db.php';

// Helper function to sanitize slug
function sanitize_slug($slug) {
    return preg_replace('/[^a-z0-9-]/', '', strtolower($slug));
}

// Get all pages
$pages_query = "SELECT id_page, slug FROM page ORDER BY id_page";
$pages_stmt = $pdo->query($pages_query);
$pages = $pages_stmt->fetchAll();

// Get selected page from URL
$page_slug = isset($_GET['page_slug']) ? sanitize_slug($_GET['page_slug']) : null;
$page_id = isset($_GET['page']) ? (int)$_GET['page'] : null;

if ($page_slug) {
    foreach ($pages as $p) {
        if (sanitize_slug($p['slug']) === $page_slug) {
            $page_id = $p['id_page'];
            break;
        }
    }
}

// If no page selected, use first one
if (!$page_id && !empty($pages)) {
    $page_id = $pages[0]['id_page'];
}

$selected_page = null;
foreach ($pages as $p) {
    if ($p['id_page'] == $page_id) {
        $selected_page = $p;
        break;
    }
}

// Fetch all content with their images and tags
$content_query = "
    SELECT 
        c.id_contenu, 
        c.texte, 
        c.id_type, 
        c.id_parent, 
        c.ordre,
        c.slug,
        t.nom_type, 
        t.balise,
        (SELECT STRING_AGG(i.path, ',') FROM image i WHERE i.id_contenu = c.id_contenu) as images,
        (SELECT STRING_AGG(DISTINCT tg.libelle, ',') FROM tag tg JOIN contenu_tag ct ON tg.id_tag = ct.id_tag WHERE ct.id_contenu = c.id_contenu) as tags
    FROM contenu c
    LEFT JOIN type t ON c.id_type = t.id_type
    WHERE c.id_page = :id_page
    ORDER BY c.id_parent, c.ordre, c.id_contenu
";

$content_stmt = $pdo->prepare($content_query);
$content_stmt->bindParam(':id_page', $page_id, PDO::PARAM_INT);
$content_stmt->execute();
$all_contents = $content_stmt->fetchAll();

// Separate content by type
$main_titles = array_filter($all_contents, fn($c) => $c['id_type'] == 1);
$subtitles = array_filter($all_contents, fn($c) => $c['id_type'] == 2);
$paragraphs = array_filter($all_contents, fn($c) => $c['id_type'] == 3);

// Group images and content by ID for easy access
$page_images = [];
$all_contents_by_id = [];
foreach ($all_contents as $content) {
    if (!empty($content['images'])) {
        $page_images[$content['id_contenu']] = explode(',', $content['images']);
    }
    $all_contents_by_id[$content['id_contenu']] = $content;
}

// Function to render main title (hero)
function renderMainTitle($title, $page_images) {
    if (!$title) return '';
    
    $hero_images = isset($page_images[$title['id_contenu']]) ? $page_images[$title['id_contenu']] : [];
    
    ob_start();
    ?>
    <div class="hero-section">
        <?php if (!empty($hero_images)): ?>
            <div class="hero-image">
                <img src="<?php echo htmlspecialchars(trim($hero_images[0])); ?>" alt="Image principale">
            </div>
        <?php endif; ?>
        <h1 class="page-main-title"><?php echo htmlspecialchars($title['texte']); ?></h1>
    </div>
    <?php
    return ob_get_clean();
}

// Function to render subtitle cards (small cards)
function renderSubtitleCards($subtitles, $page_images, $all_contents) {
    if (empty($subtitles)) return '';
    
    ob_start();
    ?>
    <div class="subtitle-cards-grid">
        <?php foreach ($subtitles as $subtitle): 
            $subtitle_images = isset($page_images[$subtitle['id_contenu']]) ? $page_images[$subtitle['id_contenu']] : [];
            $subtitle_slug = !empty($subtitle['slug']) ? $subtitle['slug'] : 'contenu-' . $subtitle['id_contenu'];
            
            // Get tags for this subtitle
            $subtitle_tags = isset($all_contents[$subtitle['id_contenu']]['tags']) && !empty($all_contents[$subtitle['id_contenu']]['tags']) 
                ? explode(',', $all_contents[$subtitle['id_contenu']]['tags']) 
                : [];
        ?>
        <div class="subtitle-card">
            <?php if (!empty($subtitle_images)): ?>
                <div class="subtitle-card-image">
                    <img src="<?php echo htmlspecialchars(trim($subtitle_images[0])); ?>" alt="Image">
                </div>
            <?php else: ?>
                <div class="subtitle-card-placeholder">📄</div>
            <?php endif; ?>
            <div class="subtitle-card-content">
                <h3 class="subtitle-card-title"><?php echo htmlspecialchars($subtitle['texte']); ?></h3>
                <?php if (!empty($subtitle_tags)): ?>
                    <div class="card-tags">
                        <?php foreach ($subtitle_tags as $tag): ?>
                            <span class="tag-badge"><?php echo htmlspecialchars(trim($tag)); ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <a href="article-<?php echo htmlspecialchars($subtitle_slug); ?>" class="voir-plus-link">Lire l'article →</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}

// Function to render paragraphs for a specific subtitle (AJAX or inline)
function renderParagraphsForSubtitle($subtitle_id, $all_paragraphs, $page_images) {
    $related_paragraphs = array_filter($all_paragraphs, fn($p) => $p['id_parent'] == $subtitle_id);
    if (empty($related_paragraphs)) return '<p class="no-paragraphs">Aucun paragraphe pour cet article.</p>';
    
    $html = '<div class="paragraphs-list" data-subtitle-id="' . $subtitle_id . '">';
    foreach ($related_paragraphs as $para) {
        $para_images = isset($page_images[$para['id_contenu']]) ? $page_images[$para['id_contenu']] : [];
        $html .= '<div class="paragraph-item">';
        if (!empty($para_images)) {
            $html .= '<div class="paragraph-image"><img src="' . htmlspecialchars(trim($para_images[0])) . '" alt="Image"></div>';
        }
        $html .= '<p class="paragraph-text">' . htmlspecialchars($para['texte']) . '</p>';
        $html .= '</div>';
    }
    $html .= '</div>';
    return $html;
}

// Check if we need to return JSON for AJAX request
if (isset($_GET['ajax']) && $_GET['ajax'] == 1 && isset($_GET['subtitle_id'])) {
    $subtitle_id = (int)$_GET['subtitle_id'];
    $paragraphs_html = renderParagraphsForSubtitle($subtitle_id, $paragraphs, $page_images);
    header('Content-Type: application/json');
    echo json_encode(['html' => $paragraphs_html]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iran War Analysis - <?php echo $selected_page ? htmlspecialchars($selected_page['slug']) : 'Actualités'; ?></title>
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
                <?php foreach ($pages as $page): ?>
                    <a href="page-<?php echo htmlspecialchars(sanitize_slug($page['slug'])); ?>.html" 
                       class="nav-link <?php echo $page_id == $page['id_page'] ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars(ucfirst(str_replace('-', ' ', $page['slug']))); ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        </div>
    </header>

    <main class="main-container">
        <div class="container">
            <?php if ($selected_page && !empty($all_contents)): ?>
                
                <!-- Main Title (Hero) -->
                <?php 
                $main_title = !empty($main_titles) ? reset($main_titles) : null;
                if ($main_title):
                    echo renderMainTitle($main_title, $page_images);
                endif;
                ?>
                
                <!-- Subtitle Cards Grid -->
                <?php 
                $subtitles_array = array_values($subtitles);
                if (!empty($subtitles_array)):
                    echo renderSubtitleCards($subtitles_array, $page_images, $all_contents_by_id);
                endif;
                ?>
                
            <?php elseif ($selected_page): ?>
                <div class="no-content-card">
                    <p>Aucun contenu trouvé pour cette page.</p>
                </div>
            <?php else: ?>
                <div class="no-content-card">
                    <p>Sélectionnez une rubrique pour afficher son contenu.</p>
                </div>
            <?php endif; ?>
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