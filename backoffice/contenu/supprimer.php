<?php
session_start();

// Check if user is logged in
if (empty($_SESSION['logged_in'])) {
    header('Location: ../index.php');
    exit();
}

require_once '../db.php';

$id_contenu = $_GET['id'] ?? null;

if (!$id_contenu) {
    header('Location: contenu-liste.html');
    exit();
}

try {
    // Delete associated tags
    $delete_tags_query = "DELETE FROM contenu_tag WHERE id_contenu = :id_contenu";
    $stmt = $pdo->prepare($delete_tags_query);
    $stmt->bindParam(':id_contenu', $id_contenu, PDO::PARAM_INT);
    $stmt->execute();
    
    // Delete associated images
    $delete_images_query = "DELETE FROM image WHERE id_contenu = :id_contenu";
    $stmt = $pdo->prepare($delete_images_query);
    $stmt->bindParam(':id_contenu', $id_contenu, PDO::PARAM_INT);
    $stmt->execute();
      // Delete content
    $delete_content_query = "DELETE FROM contenu WHERE id_contenu = :id_contenu";
    $stmt = $pdo->prepare($delete_content_query);
    $stmt->bindParam(':id_contenu', $id_contenu, PDO::PARAM_INT);
    $stmt->execute();
    
    header('Location: contenu-liste.html?success=Contenu supprimé avec succès');
    exit();
} catch (PDOException $e) {
    header('Location: contenu-liste.html?error=Erreur lors de la suppression');
    exit();
}
?>
