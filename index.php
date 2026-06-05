<?php

$host = 'Smma23aL.bbzwinf.ch';
$port = 3306;
$db   = 'louisa-scherer_plesk';
$user = 'louisa';
$pass = '!Q*L7n5haDfggl4q';

try {

    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$db;charset=utf8",
        $user,
        $pass
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );
} catch (PDOException $e) {

    die('Connection failed: ' . $e->getMessage());
}

$stmt = $pdo->query(
    'SELECT email, vorname, nachname FROM benutzer'
);

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pdo = null;
