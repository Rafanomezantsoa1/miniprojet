<?php
session_start();

// Check if user is logged in
if (empty($_SESSION['logged_in'])) {
    header('Location: ../index.php');
    exit();
}

require_once '../db.php';

$id_image = $_GET['id'] ?? null;

if (!$id_image) {
    header('Location: ../dashboard.php');
    exit();
}

try {
    // Get image path
    $get_query = "SELECT path FROM image WHERE id_image = :id_image";
    $stmt = $pdo->prepare($get_query);
    $stmt->bindParam(':id_image', $id_image, PDO::PARAM_INT);
    $stmt->execute();
    $image = $stmt->fetch();
    
    if ($image) {
        // Delete file from filesystem
        $file_path = '../' . $image['path'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        
        // Delete image record from database
        $delete_query = "DELETE FROM image WHERE id_image = :id_image";
        $delete_stmt = $pdo->prepare($delete_query);
        $delete_stmt->bindParam(':id_image', $id_image, PDO::PARAM_INT);
        $delete_stmt->execute();
    }
    
    header('Location: admin-dashboard.html?success=Image supprimée avec succès');
    exit();
} catch (PDOException $e) {
    header('Location: admin-dashboard.html?error=Erreur lors de la suppression');
    exit();
}
?>
