<?php
// Zentrale Datenbankverbindung einbinden
require 'db_connect.php';

// Datenbankabfrage: 5 Produkte für den Bestseller-Slider laden
$stmt = $pdo->query("SELECT produkt_id, name FROM produkte WHERE aktiv = 1 ORDER BY produkt_id LIMIT 5");
$bestseller = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prüfen, ob gerade erfolgreich am Gewinnspiel teilgenommen wurde
$gewinnspielOk = false;
if (isset($_GET['gewinnspiel']) && $_GET['gewinnspiel'] === 'ok') {
    $gewinnspielOk = true;
}
// Prüfen, ob die Server-Prüfung des Gewinnspiels fehlgeschlagen ist
$gewinnspielFehler = false;
if (isset($_GET['gewinnspiel']) && $_GET['gewinnspiel'] === 'fehler') {
    $gewinnspielFehler = true;
}
?>
<!doctype html>
<html lang="de">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>flavr. – Gewürze mit Charakter</title>
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <!-- Parallax-Hintergrundcontainer -->
    <div class="parallax-bg" id="parallaxBg"></div>

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

    <main>
      <div class="container">
        <!-- Hero-Bereich -->
        <section>
          <div class="hero">
            <div class="hero-content">
              <h1>Gewürze, die den Unterschied machen</h1>
              <p>
                Entdecken Sie unsere sorgfältig ausgewählten Gewürze und
                kreativen Blends. Pur im Geschmack, minimalistisch im Design.
              </p>
              <div class="hero-buttons">
                <a href="shop.php" class="btn">Einkaufen</a>
                <button class="btn btn-outline" id="moreInfoBtn">
                  Mehr erfahren
                </button>
              </div>
            </div>
            <div class="hero-image">Gewürzvielfalt</div>
          </div>
        </section>

        <!-- Image Slider (Sortiment) – Bilder kommen aus der Datenbank -->
        <section>
          <h2 style="text-align: center">Entdecke unsere Bestseller</h2>
          <div class="slider-container">
            <button class="slider-btn prev" id="prevBtn">❮</button>
            <div class="slider-track" id="sliderTrack">
              <?php foreach ($bestseller as $produkt): ?>
              <div class="slider-slide">
                <img src="bild.php?id=<?php echo $produkt['produkt_id']; ?>" alt="<?php echo htmlspecialchars($produkt['name']); ?>" />
                <div class="slide-caption"><?php echo htmlspecialchars($produkt['name']); ?></div>
              </div>
              <?php endforeach; ?>
            </div>
            <button class="slider-btn next" id="nextBtn">❯</button>
          </div>
          <div class="slider-dots" id="sliderDots"></div>
        </section>

        <!-- Promo-Teaser -->
        <section>
          <div class="promo-teaser">
            <h3>✨ NEU: Gewürz-Abo</h3>
            <p>
              Jeden Monat eine neue Überraschungsmischung –
              <strong>10% Rabatt</strong> für Neukunden!
            </p>
            <a href="shop.php" class="btn" style="margin-top: 0.5rem"
              >Zum Abo</a
            >
          </div>
        </section>

        <section>
          <div class="flavor-section">
            <h2>Dein Geschmack verdient mehr</h2>
            <p>
              Starte jetzt dein kulinarisches Abenteuer mit reinen & ehrlichen
              Gewürzen.
            </p>
            <div>
              <button class="btn" id="interessantBtn">Sehr interessant</button>
              <button class="btn btn-outline" id="effizientBtn">
                Sehr effizient
              </button>
            </div>
          </div>
        </section>

        <!-- Glücksrad + Formular -->
        <section>
          <h2 style="text-align: center">Gewürz-Glücksrad</h2>
          <p style="text-align: center">
            Drehe am Rad und gewinne tolle Preise!
          </p>
          <div class="wheel-form-grid">
            <div class="wheel-card">
              <canvas id="wheelCanvas" width="400" height="400"></canvas>
              <button id="spinWheelBtn" class="btn spin-btn">Drehen</button>
              <div class="prize-meta">
                Jeden Monat neue Preise<br />Viel Glück!
              </div>
            </div>
            <div class="form-card">
              <h3>Gewinnspiel teilnehmen</h3>
              <p>Fülle das Formular aus und nimm teil.</p>
              <?php if ($gewinnspielOk): ?>
              <!-- Bestätigung nach erfolgreicher Teilnahme -->
              <div class="success-msg">✅ Vielen Dank für deine Teilnahme!</div>
              <?php endif; ?>
              <?php if ($gewinnspielFehler): ?>
              <!-- Hinweis, wenn die Eingaben nicht gültig waren -->
              <div class="error-msg">⚠️ Bitte fülle das Formular vollständig aus.</div>
              <?php endif; ?>
              <form id="contestForm" action="submit_gewinnspiel.php" method="post" novalidate>
                <div class="form-group">
                  <label>Vorname *</label
                  ><input type="text" id="vorname" name="vorname" placeholder="Max" />
                  <div class="error-msg" id="errorVorname"></div>
                </div>
                <div class="form-group">
                  <label>Nachname *</label
                  ><input type="text" id="nachname" name="nachname" placeholder="Mustermann" />
                  <div class="error-msg" id="errorNachname"></div>
                </div>
                <div class="form-group">
                  <label>E-Mail *</label
                  ><input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="max@example.com"
                  />
                  <div class="error-msg" id="errorEmail"></div>
                </div>
                <div class="form-group">
                  <label>Woher kennen Sie flavr.? *</label
                  ><select id="referrer" name="referrer">
                    <option value="">-- Bitte wählen --</option>
                    <option value="social">Social Media</option>
                    <option value="freunde">Freunde & Familie</option>
                    <option value="suche">Websuche</option>
                  </select>
                  <div class="error-msg" id="errorReferrer"></div>
                </div>
                <div class="form-group checkbox-group">
                  <input type="checkbox" id="terms" name="terms" value="true" /><label
                    >Ich akzeptiere die Teilnahmebedingungen.*</label
                  >
                </div>
                <div class="error-msg" id="errorTerms"></div>
                <button type="submit" class="btn" style="width: 100%">
                  Teilnehmen
                </button>
                <div id="formFeedback"></div>
              </form>
            </div>
          </div>
        </section>

        <section id="ueber-uns">
          <div style="text-align: center">
            <span
              style="
                background: #f0e4d4;
                padding: 0.2rem 1rem;
                border-radius: 40px;
              "
              >Qualität</span
            >
            <h2>Was flavr. besonders macht</h2>
            <p>Jedes Gewürz wird sorgfältig ausgewählt und geprüft.</p>
          </div>
          <div class="features">
            <div class="feature">
              <h3>100% natürliche Gewürze</h3>
              <p>Keine Zusatzstoffe.</p>
            </div>
            <div class="feature">
              <h3>Schneller Versand</h3>
              <p>Frisch und sicher.</p>
            </div>
            <div class="feature">
              <h3>Kreative Blends</h3>
              <p>Für neue Abenteuer.</p>
            </div>
          </div>
        </section>
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
          <p>
            ⚠️ Dies ist ein Schulprojekt und keine reale Website. Alle Inhalte
            sind fiktiv.
          </p>
          <p>&copy; 2026 flavr. – Gemeinsam genießen</p>
        </div>
      </div>
    </footer>

    <script src="script.js"></script>
    <script>
      // Einfache Klick-Meldungen für die drei Info-Buttons
      var moreInfoBtn = document.getElementById("moreInfoBtn");
      if (moreInfoBtn) {
        moreInfoBtn.addEventListener("click", function () {
          alert("Erfahre mehr über unsere Gewürze.");
        });
      }
      var interessantBtn = document.getElementById("interessantBtn");
      if (interessantBtn) {
        interessantBtn.addEventListener("click", function () {
          alert("Danke fürs Feedback!");
        });
      }
      var effizientBtn = document.getElementById("effizientBtn");
      if (effizientBtn) {
        effizientBtn.addEventListener("click", function () {
          alert("Schneller Versand – garantiert.");
        });
      }
    </script>
  </body>
</html>
