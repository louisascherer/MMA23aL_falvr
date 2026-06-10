<!doctype html>
<html lang="de">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>flavr. – Über uns</title>
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
      <div style="text-align: center; margin: 2rem 0">
        <span
          style="background: #f0e4d4; padding: 0.2rem 1rem; border-radius: 40px"
          >Qualität</span
        >
        <h1>Was flavr. besonders macht</h1>
        <p>
          Jedes Gewürz wird sorgfältig ausgewählt und geprüft. Wir arbeiten nur
          mit den besten Quellen.
        </p>
      </div>
      <div class="features">
        <div class="feature">
          <h3>100% natürliche Gewürze</h3>
          <p>Keine Zusatzstoffe, keine Kompromisse.</p>
        </div>
        <div class="feature">
          <h3>Schneller & zuverlässiger Versand</h3>
          <p>Deine Sendung kommt frisch und sicher an.</p>
        </div>
        <div class="feature">
          <h3>Kreative Blends für neue Abenteuer</h3>
          <p>Entdecke Mischungen, die deine Küche inspirieren.</p>
        </div>
      </div>
      <button
        class="btn"
        id="entdeckenBtn"
        style="display: block; margin: 0 auto 2rem"
      >
        Entdecken
      </button>
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
    <script>
      // Einfache Klick-Meldung für den Entdecken-Button
      var entdeckenBtn = document.getElementById("entdeckenBtn");
      if (entdeckenBtn) {
        entdeckenBtn.addEventListener("click", function () {
          alert("Besuche unseren Shop für die neuesten Kreationen.");
        });
      }
    </script>
  </body>
</html>
