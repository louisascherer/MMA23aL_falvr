<?php
// Zentrale Datenbankverbindung einbinden
require 'db_connect.php';

// Produkt-Nummer aus der Adresse holen und in eine Zahl umwandeln
$id = intval($_GET['id'] ?? 0);

// Datenbankabfrage: alle Infos zum Produkt aus der View "produkt_details" laden.
// Die View verbindet Produkt, Kategorie und Zutaten schon in der Datenbank,
// deshalb braucht es hier KEIN JOIN im Seitencode (Prepared Statement).
$stmt = $pdo->prepare("SELECT produkt_id, produkt_name, beschreibung, preis_chf, herkunft, menge, lagerbestand, kategorie_name, zutaten FROM produkt_details WHERE produkt_id = ? AND aktiv = 1");
$stmt->execute(array($id));
$produkt = $stmt->fetch(PDO::FETCH_ASSOC);

// Den Zutaten-Text (z.B. "Anis, Gewuerze") in einzelne Zutaten zerlegen
$zutatenListe = array();
if ($produkt && $produkt['zutaten']) {
    $zutatenListe = explode(', ', $produkt['zutaten']);
}
?>
<!doctype html>
<html lang="de">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>flavr. – <?php if ($produkt) { echo htmlspecialchars($produkt['produkt_name']); } else { echo 'Produkt'; } ?></title>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <header>
        <div class="top-bar">
            <div class="logo"><a href="index.php"><img src="images/flavr-logo.webp" alt="flavr." class="logo-img" /></a></div>
            <div style="display: flex; gap: 1rem; align-items: center">
                <button class="cart-button">🛒 Warenkorb</button>
                <a href="shop.php" class="bestellen-link">Bestellen</a>
            </div>
            <div class="hamburger"><span></span><span></span><span></span></div>
        </div>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="shop.php">Shop</a>
            <a href="erlebnisse.php">Erlebnisse</a>
            <a href="ueber-uns.php">Über uns</a>
        </div>
    </header>

    <main class="container">
        <?php if (!$produkt): ?>
        <!-- Kein Produkt mit dieser Nummer gefunden -->
        <h1>Produkt nicht gefunden</h1>
        <p>Dieses Produkt gibt es nicht (mehr).</p>
        <a href="shop.php" class="btn btn-outline">← Zurück zum Shop</a>
        <?php else: ?>

        <!-- Zurück-Link oberhalb der Detailkarte -->
        <a href="shop.php" class="zurueck-link">← Zurück zum Shop</a>

        <div class="product-card product-detail">
            <div class="produkt-detail-bild">
                <img src="bild.php?id=<?php echo $produkt['produkt_id']; ?>" alt="<?php echo htmlspecialchars($produkt['produkt_name']); ?>">
            </div>
            <div class="produkt-detail-info">
                <?php if ($produkt['kategorie_name']): ?>
                <span class="produkt-eyebrow"><?php echo htmlspecialchars($produkt['kategorie_name']); ?></span>
                <?php endif; ?>
                <h1><?php echo htmlspecialchars($produkt['produkt_name']); ?></h1>
                <div class="produkt-preis"><?php echo number_format($produkt['preis_chf'], 2); ?> CHF</div>
                <p class="produkt-beschreibung"><?php echo htmlspecialchars($produkt['beschreibung']); ?></p>

                <dl class="produkt-fakten">
                    <div><dt>Herkunft</dt><dd><?php echo htmlspecialchars($produkt['herkunft']); ?></dd></div>
                    <div><dt>Menge</dt><dd><?php echo htmlspecialchars($produkt['menge']); ?></dd></div>
                    <div><dt>Auf Lager</dt><dd><?php echo intval($produkt['lagerbestand']); ?> Stück</dd></div>
                </dl>

                <?php if (count($zutatenListe) > 0): ?>
                <div class="zutaten-block">
                    <span class="zutaten-titel">Zutaten</span>
                    <div class="produkt-zutaten">
                        <?php foreach ($zutatenListe as $zutat): ?>
                        <span class="zutat-chip"><?php echo htmlspecialchars($zutat); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <button class="btn">In den Warenkorb</button>
            </div>
        </div>
        <?php endif; ?>
    </main>

    <footer>
        <div class="footer-container">
            <div class="footer-column">
                <h4>flavr.</h4>
                <p>Gewürze mit Charakter – handverlesen & fair.</p>
            </div>
            <div class="footer-column">
                <h4>Kontakt & Öffnungszeiten</h4>
                <p>📧 hello@flavr.ch<br>📞 +41 78 123 45 67<br>🕒 Mo–Fr: 09:00–18:00<br>Sa: 10:00–16:00</p>
            </div>
            <div class="footer-column">
                <h4>Folge uns</h4>
                <div class="social-icons"><a href="#">Instagram</a><a href="#">TikTok</a></div>
            </div>
            <div class="footer-column">
                <h4>Gruppenmitglieder</h4>
                <ul>
                    <li>Leonie Walker</li>
                    <li>Olivia Vieli</li>
                    <li>Louisa Scherer</li>
                </ul>
            </div>
            <div class="disclaimer">
                <p>⚠️ Schulprojekt – fiktive Inhalte</p>
                <p>&copy; 2026 flavr.</p>
            </div>
        </div>
    </footer>
    <script src="script.js"></script>
</body>

</html>
