<?php
session_start();

// Check if user is logged in and is admin
if (empty($_SESSION['logged_in'])) {
    header('Location: index.html');
    exit();
}

if ($_SESSION['user_role'] !== 'admin') {
    header('Location: admin-dashboard.html');
    exit();
}

require_once '../db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: utilisateur-liste.html');
    exit();
}

// Prevent self-deletion
if ($id == $_SESSION['user_id']) {
    header('Location: utilisateur-liste.html?error=Vous ne pouvez pas supprimer votre propre compte');
    exit();
}

try {
    // Delete user
    $query = "DELETE FROM utilisateur WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
      header('Location: utilisateur-liste.html?success=Utilisateur supprimé');
    exit();
} catch (PDOException $e) {
    header('Location: utilisateur-liste.html?error=' . urlencode($e->getMessage()));
    exit();
}
