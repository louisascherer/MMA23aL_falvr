<?php
// submit_bestellungen.php – nimmt die Bestellung aus dem Warenkorb entgegen (einfaches POST)
require 'db_connect.php';

// Die Kundenfelder aus dem Formular holen
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$adresse = trim($_POST['adresse'] ?? '');
$plz = trim($_POST['postleitzahl'] ?? '');
$telefon = trim($_POST['telefon'] ?? '');
$zahlungsart = trim($_POST['zahlungsart'] ?? '');

// Der Warenkorb kommt als Text (JSON) aus dem versteckten Feld – in ein Array umwandeln
$warenkorbText = $_POST['warenkorb'] ?? '';
$warenkorb = json_decode($warenkorbText, true);

// Sicherheits-Prüfung auf dem Server (JavaScript prüft schon vorher)
$fehler = false;
if ($name === '') {
    $fehler = true;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $fehler = true;
}
if ($adresse === '') {
    $fehler = true;
}
if ($plz === '') {
    $fehler = true;
}
if (!is_array($warenkorb) || count($warenkorb) === 0) {
    $fehler = true;
}

// Bei Fehler zurück zum Shop mit Hinweis
if ($fehler) {
    header('Location: shop.php?bestellung=fehler');
    exit;
}

// Datenbankabfrage: prüfen, ob diese E-Mail schon als Kunde existiert
$stmt = $pdo->prepare('SELECT kunden_id FROM kunden WHERE email = ?');
$stmt->execute(array($email));
$vorhandenerKunde = $stmt->fetch(PDO::FETCH_ASSOC);

if ($vorhandenerKunde) {
    $kunden_id = $vorhandenerKunde['kunden_id'];
} else {
    // Neuen Kunden anlegen (Platzhalter-Passwort, da hier kein Login nötig ist)
    $stmt = $pdo->prepare('INSERT INTO kunden (name, email, telefon, adresse, postleitzahl, passwort, newsletter, registrierungsdatum) VALUES (?, ?, ?, ?, ?, ?, 0, NOW())');
    $stmt->execute(array($name, $email, $telefon, $adresse, $plz, 'kein-login'));
    $kunden_id = $pdo->lastInsertId();
}

// Gesamtpreis sicherheitshalber aus den Datenbank-Preisen berechnen
$gesamtpreis = 0;
foreach ($warenkorb as $artikel) {
    $produkt_id = intval($artikel['produkt_id']);
    $anzahl = intval($artikel['quantity']);

    // Datenbankabfrage: aktuellen Preis des Produkts holen
    $stmt = $pdo->prepare('SELECT preis_chf FROM produkte WHERE produkt_id = ?');
    $stmt->execute(array($produkt_id));
    $preis = $stmt->fetchColumn();

    if ($preis) {
        $gesamtpreis = $gesamtpreis + ($preis * $anzahl);
    }
}

// Datenbankabfrage: die Bestellung anlegen
$stmt = $pdo->prepare('INSERT INTO bestellungen (kunden_id, bestelldatum, gesamtpreis_chf, zahlungsart, lieferadresse, status) VALUES (?, NOW(), ?, ?, ?, "offen")');
$stmt->execute(array($kunden_id, $gesamtpreis, $zahlungsart, $adresse));
$bestellung_id = $pdo->lastInsertId();

// Für jede Zeile im Warenkorb eine Bestellposition anlegen
foreach ($warenkorb as $artikel) {
    $produkt_id = intval($artikel['produkt_id']);
    $anzahl = intval($artikel['quantity']);

    // Datenbankabfrage: aktuellen Preis des Produkts holen
    $stmt = $pdo->prepare('SELECT preis_chf FROM produkte WHERE produkt_id = ?');
    $stmt->execute(array($produkt_id));
    $preis = $stmt->fetchColumn();

    if ($preis) {
        // Datenbankabfrage: die einzelne Position speichern
        $stmt = $pdo->prepare('INSERT INTO bestellpositionen (bestellung_id, produkt_id, anzahl, preis_chf) VALUES (?, ?, ?, ?)');
        $stmt->execute(array($bestellung_id, $produkt_id, $anzahl, $preis));
    }
}

// Zurück zum Shop mit Erfolgsmeldung und Bestellnummer
header('Location: shop.php?bestellung=ok&nr=' . $bestellung_id);
exit;
