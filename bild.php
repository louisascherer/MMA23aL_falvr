<?php
// bild.php – gibt ein Produktbild direkt aus der Datenbank aus.
// Aufruf im HTML zum Beispiel: <img src="bild.php?id=5">
require 'db_connect.php';

// Die Produkt-Nummer aus der Adresse holen und in eine Zahl umwandeln
$produktId = intval($_GET['id']);

// Datenbankabfrage: Hauptbild zu diesem Produkt holen (Prepared Statement)
$stmt = $pdo->prepare('SELECT bilddaten FROM produktbilder WHERE produkt_id = ? AND ist_hauptbild = 1 LIMIT 1');
$stmt->execute(array($produktId));
$bild = $stmt->fetch(PDO::FETCH_ASSOC);

// Wenn ein Bild gefunden wurde: als WebP-Bild ausgeben
if ($bild) {
    header('Content-Type: image/webp');
    echo $bild['bilddaten'];
} else {
    // Kein Bild vorhanden: Fehlercode zurückgeben
    http_response_code(404);
}
