<?php
// Zentrale Datenbankverbindung einbinden
require 'db_connect.php';

// Datenbankabfrage: alle aktiven Produkte laden
$stmt = $pdo->query("SELECT produkt_id, name, beschreibung, preis_chf, herkunft, menge, lagerbestand FROM produkte WHERE aktiv = 1 ORDER BY name");
$produkte = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prüfen, ob gerade erfolgreich bestellt wurde
$bestellungOk = false;
$bestellNr = 0;
if (isset($_GET['bestellung']) && $_GET['bestellung'] === 'ok') {
    $bestellungOk = true;
    $bestellNr = intval($_GET['nr']);
}
// Prüfen, ob die Server-Prüfung der Bestellung fehlgeschlagen ist
$bestellungFehler = false;
if (isset($_GET['bestellung']) && $_GET['bestellung'] === 'fehler') {
    $bestellungFehler = true;
}
?>
<!doctype html>
<html lang="de">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>flavr. – Shop</title>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <header>
        <div class="top-bar">
            <div class="logo"><a href="index.php"><img src="images/flavr-logo.webp" alt="flavr." class="logo-img" /></a></div>
            <div style="display: flex; gap: 1rem; align-items: center">
                <button id="cartBtn" class="cart-button">🛒 Warenkorb (<span id="cartCount">0</span>)</button>
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
        <h1>Unsere Gewürze</h1>
        <p>Handverlesene Qualität – direkt aus der Mühle.</p>

        <?php if ($bestellungOk): ?>
        <!-- Bestätigung nach erfolgreicher Bestellung -->
        <div class="success-msg">✅ Vielen Dank für deine Bestellung! (Bestell-Nr. <?php echo $bestellNr; ?>)</div>
        <?php endif; ?>
        <?php if ($bestellungFehler): ?>
        <!-- Hinweis, wenn die Bestelldaten nicht gültig waren -->
        <div class="error-msg">⚠️ Bitte fülle alle Pflichtfelder aus und lege etwas in den Warenkorb.</div>
        <?php endif; ?>

        <div class="product-grid">
            <?php foreach ($produkte as $produkt):
                // Bildadresse: das Bild kommt direkt aus der Datenbank über bild.php
                $bildPfad = 'bild.php?id=' . $produkt['produkt_id'];
            ?>
                <div class="product-card"
                    data-produkt-id="<?php echo $produkt['produkt_id']; ?>"
                    data-name="<?php echo htmlspecialchars($produkt['name']); ?>"
                    data-price="<?php echo $produkt['preis_chf']; ?>"
                    data-image="<?php echo $bildPfad; ?>">
                    <div class="product-image"><img src="<?php echo $bildPfad; ?>" alt="<?php echo htmlspecialchars($produkt['name']); ?>" loading="lazy"></div>
                    <div class="product-title"><?php echo htmlspecialchars($produkt['name']); ?></div>
                    <div class="product-price"><?php echo number_format($produkt['preis_chf'], 2); ?> CHF</div>
                    <button class="btn btn-outline add-to-cart">In den Warenkorb</button>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <!-- Modal für Warenkorb mit Bestellformular -->
    <div id="cartModal" class="cart-modal">
        <div class="cart-modal-content">
            <span class="close-modal">&times;</span>
            <h2>Dein Warenkorb</h2>
            <div id="cartItems"></div>
            <div class="cart-total">Total: <span id="cartTotal">0.00</span> CHF</div>
            <h3 style="margin-top:1.5rem;">Lieferadresse</h3>
            <form id="checkoutForm" action="submit_bestellungen.php" method="post">
                <div class="form-group"><label>Vor- und Nachname *</label><input type="text" id="checkoutName" name="name"></div>
                <div class="form-group"><label>E-Mail *</label><input type="email" id="checkoutEmail" name="email"></div>
                <div class="form-group"><label>Adresse *</label><input type="text" id="checkoutAdresse" name="adresse"></div>
                <div class="form-group"><label>Postleitzahl *</label><input type="text" id="checkoutPLZ" name="postleitzahl"></div>
                <div class="form-group"><label>Telefon (optional)</label><input type="text" id="checkoutTel" name="telefon"></div>
                <div class="form-group"><label>Zahlungsart *</label>
                    <select id="checkoutPayment" name="zahlungsart">
                        <option value="Kreditkarte">Kreditkarte</option>
                        <option value="PayPal">PayPal</option>
                        <option value="TWINT">TWINT</option>
                        <option value="Rechnung">Rechnung</option>
                    </select>
                </div>
                <!-- Verstecktes Feld: hier schreibt JavaScript den Warenkorb als Text hinein -->
                <input type="hidden" id="warenkorbInput" name="warenkorb" value="">
                <button type="submit" class="btn" style="width:100%">Jetzt kaufen</button>
                <div id="checkoutFeedback" style="margin-top:1rem;"></div>
            </form>
        </div>
    </div>

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
