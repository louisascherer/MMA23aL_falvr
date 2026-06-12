<?php
// submit_newsletter.php – nimmt das Newsletter-Formular aus dem Footer entgegen (einfaches POST)
// Gleiche Logik wie beim Gewinnspiel: Eingabe prüfen und einen Kunden mit newsletter = 1 anlegen.
require 'db_connect.php';

// Die E-Mail aus dem Formular holen.
// "?? ''" heisst: falls das Feld fehlt, wird ein leerer Text genommen.
$email = trim($_POST['email'] ?? '');

// Sicherheits-Prüfung auf dem Server (JavaScript prüft schon vorher)
$fehler = false;
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $fehler = true;
}

// Bei Fehler zurück zur Startseite mit Hinweis
if ($fehler) {
    header('Location: index.php?newsletter=fehler#newsletterForm');
    exit;
}

// Datenbankabfrage: prüfen, ob diese E-Mail schon als Kunde existiert
$stmt = $pdo->prepare('SELECT kunden_id FROM kunden WHERE email = ?');
$stmt->execute(array($email));
$vorhandenerKunde = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$vorhandenerKunde) {
    // Kunde ist neu: anlegen (kein Name/Login nötig, daher Platzhalter, newsletter = 1)
    $stmt = $pdo->prepare('INSERT INTO kunden (name, email, telefon, adresse, postleitzahl, passwort, newsletter, registrierungsdatum) VALUES (?, ?, ?, ?, ?, ?, 1, NOW())');
    $stmt->execute(array('Newsletter-Abonnent', $email, '', '', '', 'kein-login'));
} else {
    // Kunde existiert schon: nur den Newsletter aktivieren
    $stmt = $pdo->prepare('UPDATE kunden SET newsletter = 1 WHERE email = ?');
    $stmt->execute(array($email));
}

// Zurück zur Startseite mit Erfolgsmeldung
header('Location: index.php?newsletter=ok#newsletterForm');
exit;
