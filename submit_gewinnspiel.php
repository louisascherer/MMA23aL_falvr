<?php
header('Content-Type: application/json');
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Methode nicht erlaubt']);
    exit;
}

$vorname = trim($_POST['vorname'] ?? '');
$nachname = trim($_POST['nachname'] ?? '');
$email = trim($_POST['email'] ?? '');
$referrer = trim($_POST['referrer'] ?? '');
$akzeptiert = isset($_POST['terms']) && $_POST['terms'] === 'true';

$errors = [];
if (strlen($vorname) < 2) $errors[] = 'Vorname zu kurz';
if (strlen($nachname) < 2) $errors[] = 'Nachname zu kurz';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Ungültige E-Mail';
if (empty($referrer)) $errors[] = 'Keine Quelle gewählt';
if (!$akzeptiert) $errors[] = 'AGB nicht akzeptiert';

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['error' => $errors]);
    exit;
}

$name = $vorname . ' ' . $nachname;

// Prüfen, ob E-Mail bereits existiert
$stmt = $pdo->prepare('SELECT kunden_id FROM kunden WHERE email = ?');
$stmt->execute([$email]);
$existing = $stmt->fetch();

if ($existing) {
    $kunden_id = $existing['kunden_id'];
    $message = 'Sie sind bereits registriert. Trotzdem vielen Dank für Ihre Teilnahme!';
} else {
    // Neuen Kunden anlegen
    $plainPassword = bin2hex(random_bytes(8));
    $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('
        INSERT INTO kunden (name, email, telefon, adresse, postleitzahl, passwort, newsletter, registrierungsdatum)
        VALUES (?, ?, ?, ?, ?, ?, 0, NOW())
    ');
    $telefon = '';   // optional
    $adresse = '';   // optional
    $plz = '';
    $stmt->execute([$name, $email, $telefon, $adresse, $plz, $hashedPassword]);
    $kunden_id = $pdo->lastInsertId();
    $message = 'Vielen Dank für Ihre Teilnahme am Gewinnspiel!';
}

echo json_encode(['success' => true, 'message' => $message, 'kunden_id' => $kunden_id]);
