<?php
require_once 'db_connect.php';

if (!$pdo) {
    die("Keine Datenbankverbindung");
}
// Aktive Produkte aus der Datenbank laden
$stmt = $pdo->query("SELECT produkt_id, name, beschreibung, preis_chf, herkunft, menge, lagerbestand FROM produkte WHERE aktiv = 1 ORDER BY name");
$produkte = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            <div class="logo"><a href="index.html"><img src="images/flavr-logo.webp" alt="flavr." class="logo-img" /></a></div>
            <div style="display: flex; gap: 1rem; align-items: center">
                <button id="cartBtn" class="cart-button">🛒 Warenkorb (<span id="cartCount">0</span>)</button>
                <a href="shop.php" class="bestellen-link">Bestellen</a>
            </div>
            <div class="hamburger"><span></span><span></span><span></span></div>
        </div>
        <div class="nav-links">
            <a href="index.html">Home</a>
            <a href="shop.php">Shop</a>
            <a href="erlebnisse.html">Erlebnisse</a>
            <a href="ueber-uns.html">Über uns</a>
        </div>
    </header>

    <main class="container">
        <h1>Unsere Gewürze</h1>
        <p>Handverlesene Qualität – direkt aus der Mühle.</p>
        <div class="product-grid">
            <?php foreach ($produkte as $produkt):
                // Bildpfad aus dem images-Ordner (Dateinamen: kleingeschrieben, Leerzeichen durch _ ersetzt, .jpg)
                $bildName = strtolower(str_replace(' ', '_', $produkt['name'])) . '.jpg';
                $bildPfad = 'images/' . $bildName;
                // Fallback, falls Bild nicht existiert (optional)
                if (!file_exists($bildPfad)) $bildPfad = 'images/placeholder.jpg';
            ?>
                <div class="product-card"
                    data-produkt-id="<?= $produkt['produkt_id'] ?>"
                    data-name="<?= htmlspecialchars($produkt['name']) ?>"
                    data-price="<?= $produkt['preis_chf'] ?>"
                    data-image="<?= $bildPfad ?>">
                    <div class="product-image"><img src="<?= $bildPfad ?>" alt="<?= htmlspecialchars($produkt['name']) ?>" loading="lazy"></div>
                    <div class="product-title"><?= htmlspecialchars($produkt['name']) ?></div>
                    <div class="product-price"><?= number_format($produkt['preis_chf'], 2) ?> CHF</div>
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
            <form id="checkoutForm">
                <div class="form-group"><label>Vor- und Nachname *</label><input type="text" id="checkoutName" required></div>
                <div class="form-group"><label>E-Mail *</label><input type="email" id="checkoutEmail" required></div>
                <div class="form-group"><label>Adresse *</label><input type="text" id="checkoutAdresse" required></div>
                <div class="form-group"><label>Postleitzahl *</label><input type="text" id="checkoutPLZ" required></div>
                <div class="form-group"><label>Telefon (optional)</label><input type="text" id="checkoutTel"></div>
                <div class="form-group"><label>Zahlungsart *</label>
                    <select id="checkoutPayment" required>
                        <option value="Kreditkarte">Kreditkarte</option>
                        <option value="PayPal">PayPal</option>
                        <option value="TWINT">TWINT</option>
                        <option value="Rechnung">Rechnung</option>
                    </select>
                </div>
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