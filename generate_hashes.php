<?php
// Générer les vrais hashes BCRYPT pour admin123 et user123
$password_admin = 'admin123';
$password_user = 'user123';

$hash_admin = password_hash($password_admin, PASSWORD_BCRYPT);
$hash_user = password_hash($password_user, PASSWORD_BCRYPT);

echo "Hash pour admin123: " . $hash_admin . "\n";
echo "Hash pour user123: " . $hash_user . "\n";

// Test verification
echo "\nTests de verification:\n";
echo "Admin verify: " . (password_verify('admin123', $hash_admin) ? 'OK' : 'FAIL') . "\n";
echo "User verify: " . (password_verify('user123', $hash_user) ? 'OK' : 'FAIL') . "\n";
?>
