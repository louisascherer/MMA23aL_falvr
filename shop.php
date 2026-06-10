<?php
// Zentrale Datenbankverbindung einbinden
require 'db_connect.php';

// Datenbankabfrage: alle Kategorien laden (für die Filter-Leiste und die Überschriften)
$stmt = $pdo->query("SELECT kategorie_id, name FROM kategorien ORDER BY kategorie_id");
$kategorien = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Gewählte Kategorie aus der Adresse lesen (0 = alle Kategorien anzeigen)
$aktuelleKategorie = intval($_GET['kategorie'] ?? 0);

// Wie viele Produkte pro Kategorie in der Übersicht ("Alle") gezeigt werden
$vorschauAnzahl = 3;

// Wie viele Produkte pro Seite gezeigt werden, wenn eine Kategorie gewählt ist
$proSeite = 12;

// Aktuelle Seitenzahl aus der Adresse lesen (Standard: Seite 1)
$seite = intval($_GET['seite'] ?? 1);
if ($seite < 1) {
    $seite = 1;
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
        <h1>Unsere Gewürze</h1>
        <p>Handverlesene Qualität – direkt aus der Mühle.</p>

        <!-- Filter-Leiste: nach Kategorie filtern (Seite lädt jeweils neu) -->
        <div class="filter-bar">
            <a href="shop.php" class="btn btn-outline<?php if ($aktuelleKategorie == 0) { echo ' active'; } ?>">Alle</a>
            <?php foreach ($kategorien as $kat): ?>
            <a href="shop.php?kategorie=<?php echo $kat['kategorie_id']; ?>" class="btn btn-outline<?php if ($aktuelleKategorie == $kat['kategorie_id']) { echo ' active'; } ?>"><?php echo htmlspecialchars($kat['name']); ?></a>
            <?php endforeach; ?>
        </div>

        <?php
        // Merker, ob überhaupt Produkte angezeigt wurden
        $etwasGezeigt = false;

        // Über alle Kategorien gehen und ihre Produkte unter einer Überschrift anzeigen
        foreach ($kategorien as $kat):
            // Wenn gefiltert wird und diese Kategorie nicht gewählt ist: überspringen
            if ($aktuelleKategorie != 0 && $aktuelleKategorie != $kat['kategorie_id']) {
                continue;
            }

            // Datenbankabfrage: aktive Produkte dieser Kategorie laden (Prepared Statement)
            $sql = "SELECT produkt_id, name, preis_chf FROM produkte WHERE aktiv = 1 AND kategorie_id = ? ORDER BY name";
            $seitenAnzahl = 1;
            if ($aktuelleKategorie == 0) {
                // In der Übersicht ("Alle") nur die ersten paar Produkte laden (entlastet die Datenbank)
                $sql = $sql . " LIMIT " . intval($vorschauAnzahl);
            } else {
                // Gefilterte Ansicht: Produkte auf Seiten von je 12 aufteilen
                // Zuerst zählen, wie viele Produkte diese Kategorie hat
                $stmtAnzahl = $pdo->prepare("SELECT COUNT(*) FROM produkte WHERE aktiv = 1 AND kategorie_id = ?");
                $stmtAnzahl->execute(array($kat['kategorie_id']));
                $gesamt = $stmtAnzahl->fetchColumn();
                $seitenAnzahl = intval(ceil($gesamt / $proSeite));
                // Aktuelle Seite nicht über die letzte Seite hinaus
                if ($seite > $seitenAnzahl) {
                    $seite = $seitenAnzahl;
                }
                if ($seite < 1) {
                    $seite = 1;
                }
                // Nur die Produkte der aktuellen Seite laden
                $start = ($seite - 1) * $proSeite;
                $sql = $sql . " LIMIT " . intval($proSeite) . " OFFSET " . intval($start);
            }
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($kat['kategorie_id']));
            $produkte = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Kategorie nur anzeigen, wenn sie auch Produkte hat
            if (count($produkte) > 0):
                $etwasGezeigt = true;
        ?>
        <h2 class="kategorie-titel"><?php echo htmlspecialchars($kat['name']); ?></h2>
        <div class="product-grid">
            <?php foreach ($produkte as $produkt):
                // Bildadresse: das Bild kommt direkt aus der Datenbank über bild.php
                $bildPfad = 'bild.php?id=' . $produkt['produkt_id'];
            ?>
                <div class="product-card">
                    <!-- Bild, Name und Preis führen zur Detailseite -->
                    <a class="produkt-link" href="produkt.php?id=<?php echo $produkt['produkt_id']; ?>">
                        <div class="product-image"><img src="<?php echo $bildPfad; ?>" alt="<?php echo htmlspecialchars($produkt['name']); ?>" loading="lazy"></div>
                        <div class="product-title"><?php echo htmlspecialchars($produkt['name']); ?></div>
                        <div class="product-price"><?php echo number_format($produkt['preis_chf'], 2); ?> CHF</div>
                    </a>
                    <button class="btn btn-outline">In den Warenkorb</button>
                </div>
            <?php endforeach; ?>
            <?php if ($aktuelleKategorie == 0): ?>
            <!-- Letzte Kachel in der Übersicht: führt zur ganzen Kategorie -->
            <a class="product-card show-all-card" href="shop.php?kategorie=<?php echo $kat['kategorie_id']; ?>">
                <span class="show-all-text">Alle anzeigen →</span>
            </a>
            <?php endif; ?>
        </div>

        <?php if ($aktuelleKategorie != 0 && $seitenAnzahl > 1): ?>
        <!-- Seiten-Navigation: nur wenn eine Kategorie gewählt ist und es mehrere Seiten gibt -->
        <div class="pagination">
            <?php for ($p = 1; $p <= $seitenAnzahl; $p++): ?>
            <a href="shop.php?kategorie=<?php echo $kat['kategorie_id']; ?>&seite=<?php echo $p; ?>" class="<?php if ($p == $seite) { echo 'active'; } ?>"><?php echo $p; ?></a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
        <?php
            endif;
        endforeach;

        // Falls nichts angezeigt wurde (z.B. ungültige Kategorie in der Adresse)
        if (!$etwasGezeigt):
        ?>
        <p>Keine Produkte gefunden.</p>
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
