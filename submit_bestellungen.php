<?php
header('Content-Type: application/json');
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Methode nicht erlaubt']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Ungültige JSON-Daten']);
    exit;
}

$name = trim($input['kunde']['name'] ?? '');
$email = trim($input['kunde']['email'] ?? '');
$adresse = trim($input['kunde']['adresse'] ?? '');
$plz = trim($input['kunde']['postleitzahl'] ?? '');
$telefon = trim($input['kunde']['telefon'] ?? '');
$zahlungsart = trim($input['zahlungsart'] ?? 'unbekannt');
$gesamtpreis = floatval($input['gesamtpreis'] ?? 0);
$produkte = $input['produkte'] ?? [];

if (empty($name) || empty($email) || empty($adresse) || empty($plz) || empty($produkte)) {
    http_response_code(400);
    echo json_encode(['error' => 'Unvollständige Bestelldaten']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Ungültige E-Mail']);
    exit;
}

// Kunde anlegen oder vorhandenen nutzen
$stmt = $pdo->prepare('SELECT kunden_id FROM kunden WHERE email = ?');
$stmt->execute([$email]);
$kunde = $stmt->fetch();

if ($kunde) {
    $kunden_id = $kunde['kunden_id'];
} else {
    $plainPassword = bin2hex(random_bytes(8));
    $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('
        INSERT INTO kunden (name, email, telefon, adresse, postleitzahl, passwort, newsletter, registrierungsdatum)
        VALUES (?, ?, ?, ?, ?, ?, 0, NOW())
    ');
    $stmt->execute([$name, $email, $telefon, $adresse, $plz, $hashedPassword]);
    $kunden_id = $pdo->lastInsertId();
}

// Bestellung anlegen
$stmt = $pdo->prepare('
    INSERT INTO bestellungen (kunden_id, bestelldatum, gesamtpreis_chf, zahlungsart, lieferadresse, status)
    VALUES (?, NOW(), ?, ?, ?, "offen")
');
$stmt->execute([$kunden_id, $gesamtpreis, $zahlungsart, $adresse]);
$bestellung_id = $pdo->lastInsertId();

// Bestellpositionen einfügen (mit produkt_id)
foreach ($produkte as $item) {
    $produkt_id = intval($item['produkt_id']);
    $anzahl = intval($item['anzahl']);
    // Preis aus Datenbank holen (sicherheitshalber)
    $stmt = $pdo->prepare('SELECT preis_chf FROM produkte WHERE produkt_id = ?');
    $stmt->execute([$produkt_id]);
    $preis = $stmt->fetchColumn();
    if (!$preis) {
        http_response_code(400);
        echo json_encode(['error' => "Produkt ID $produkt_id nicht gefunden"]);
        exit;
    }
    $stmtPos = $pdo->prepare('INSERT INTO bestellpositionen (bestellung_id, produkt_id, anzahl, preis_chf) VALUES (?, ?, ?, ?)');
    $stmtPos->execute([$bestellung_id, $produkt_id, $anzahl, $preis]);
}

echo json_encode(['success' => true, 'bestellung_id' => $bestellung_id, 'message' => 'Bestellung erfolgreich aufgegeben!']);
