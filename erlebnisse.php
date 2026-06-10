<?php
// Zentrale Datenbankverbindung einbinden
require 'db_connect.php';

// Datenbankabfrage: alle verfügbaren Gewürzerlebnisse laden
$stmt = $pdo->query("SELECT gewuerzerlebnis_id, titel, preis_chf, ort, dauer_minuten FROM gewuerzerlebnisse WHERE verfuegbar = 1 ORDER BY gewuerzerlebnis_id");
$erlebnisse = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="de">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>flavr. – Erlebnisse</title>
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <header>
      <div class="top-bar">
        <div class="logo">
          <a href="index.php"
            ><img src="images/flavr-logo.webp" alt="flavr." class="logo-img"
          /></a>
        </div>
        <a href="shop.php" class="bestellen-link">Bestellen</a>
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
      <h1>Kochkurse & Tastings</h1>
      <p>Entdecke die Welt der Gewürze, live und interaktiv.</p>
      <div class="event-grid">
        <?php foreach ($erlebnisse as $erlebnis): ?>
        <div class="event-card">
          <div class="event-image">
            <img
              src="erlebnisbild.php?id=<?php echo $erlebnis['gewuerzerlebnis_id']; ?>"
              alt="<?php echo htmlspecialchars($erlebnis['titel']); ?>"
              loading="lazy"
            />
          </div>
          <div class="event-title"><?php echo htmlspecialchars($erlebnis['titel']); ?></div>
          <div class="product-price">ab <?php echo number_format($erlebnis['preis_chf'], 0); ?> CHF</div>
          <div class="event-meta">
            📍 <?php echo htmlspecialchars($erlebnis['ort']); ?><br />
            ⏱️ <?php echo intval($erlebnis['dauer_minuten']); ?> Minuten
          </div>
          <button class="btn btn-outline">Buchen</button>
        </div>
        <?php endforeach; ?>
      </div>
    </main>

    <footer>
      <div class="footer-container">
        <div class="footer-column">
          <h4>flavr.</h4>
          <p>Gewürze mit Charakter – handverlesen & fair.</p>
        </div>
        <div class="footer-column">
          <h4>Kontakt & Öffnungszeiten</h4>
          <p>
            📧 hello@flavr.ch<br />📞 +41 78 123 45 67<br />🕒 Mo–Fr:
            09:00–18:00<br />Sa: 10:00–16:00
          </p>
        </div>
        <div class="footer-column">
          <h4>Folge uns</h4>
          <div class="social-icons">
            <a href="#">Instagram</a><a href="#">TikTok</a>
          </div>
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
