<?php
// db_connect.php – Zentrale Datenbankverbindung
$host = 'flavr.Smma23aL.bbzwinf.ch';
$port = 3306;
$db   = 'louisa-scherer_';
$user = 'louisa';
$pass = '!Q*L7n5haDfggl4q';

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
        $user,
        $pass
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    die(json_encode(['error' => 'Datenbankverbindung fehlgeschlagen: ' . $e->getMessage()]));
}
