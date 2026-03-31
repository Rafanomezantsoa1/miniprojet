<?php
try {
    $host = 'db';        // nom du service docker
    $port = '5432';
    $dbname = 'monprojet';
    $user = 'postgres';
    $password = 'postgres';

    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

    $pdo = new PDO($dsn, $user, $password);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erreur connexion DB : " . $e->getMessage());
}
?>