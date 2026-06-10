<?php
// submit_gewinnspiel.php – nimmt das Gewinnspiel-Formular entgegen (einfaches POST)
require 'db_connect.php';

// Die Felder aus dem Formular holen
$vorname = trim($_POST['vorname'] ?? '');
$nachname = trim($_POST['nachname'] ?? '');
$email = trim($_POST['email'] ?? '');
$referrer = trim($_POST['referrer'] ?? '');
$akzeptiert = isset($_POST['terms']);

// Sicherheits-Prüfung auf dem Server (JavaScript prüft schon vorher)
$fehler = false;
if (strlen($vorname) < 2) {
    $fehler = true;
}
if (strlen($nachname) < 2) {
    $fehler = true;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $fehler = true;
}
if ($referrer === '') {
    $fehler = true;
}
if (!$akzeptiert) {
    $fehler = true;
}

// Bei Fehler zurück zur Startseite mit Hinweis
if ($fehler) {
    header('Location: index.php?gewinnspiel=fehler');
    exit;
}

$name = $vorname . ' ' . $nachname;

// Datenbankabfrage: prüfen, ob diese E-Mail schon als Kunde existiert
$stmt = $pdo->prepare('SELECT kunden_id FROM kunden WHERE email = ?');
$stmt->execute(array($email));
$vorhandenerKunde = $stmt->fetch(PDO::FETCH_ASSOC);

// Wenn der Kunde neu ist: anlegen (Teilnehmer brauchen kein Login, daher Platzhalter)
if (!$vorhandenerKunde) {
    $stmt = $pdo->prepare('INSERT INTO kunden (name, email, telefon, adresse, postleitzahl, passwort, newsletter, registrierungsdatum) VALUES (?, ?, ?, ?, ?, ?, 0, NOW())');
    $stmt->execute(array($name, $email, '', '', '', 'kein-login'));
}

// Zurück zur Startseite mit Erfolgsmeldung
header('Location: index.php?gewinnspiel=ok');
exit;
