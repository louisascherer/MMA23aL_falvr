<?php
// erlebnisbild.php – gibt ein Erlebnis-Bild direkt aus der Datenbank aus.
// Aufruf im HTML zum Beispiel: <img src="erlebnisbild.php?id=1">
require 'db_connect.php';

// Die Erlebnis-Nummer aus der Adresse holen und in eine Zahl umwandeln
$erlebnisId = intval($_GET['id']);

// Datenbankabfrage: Hauptbild zu diesem Erlebnis holen (Prepared Statement)
$stmt = $pdo->prepare('SELECT bilddaten FROM gewuerzerlebnisbilder WHERE gewuerzerlebnis_id = ? AND ist_hauptbild = 1 LIMIT 1');
$stmt->execute(array($erlebnisId));
$bild = $stmt->fetch(PDO::FETCH_ASSOC);

// Wenn ein Bild gefunden wurde: als WebP-Bild ausgeben
if ($bild) {
    header('Content-Type: image/webp');
    echo $bild['bilddaten'];
} else {
    // Kein Bild vorhanden: Fehlercode zurückgeben
    http_response_code(404);
}
