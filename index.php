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

// Das Formular ist am Anfang versteckt: erst nach dem Drehen am Rad sichtbar.
// Nach dem Absenden (Erfolg oder Fehler) soll es aber sichtbar bleiben.
$formVerstecken = true;
if ($gewinnspielOk || $gewinnspielFehler) {
    $formVerstecken = false;
}

// Titel setzen und den gemeinsamen Kopf laden
$seitentitel = 'flavr. Gewürze mit Charakter';
require 'header.php';
?>

<!-- Hero-Bereich (vollflächig schwarz) -->
<section class="hero">
  <div class="hero-inner">
    <div class="hero-content">
      <span class="hero-eyebrow">Gewürze mit Charakter</span>
      <h1>Spice up<br />everything<span class="hero-akzent">.</span></h1>
      <p class="hero-sub">
        Hochwertige Einzelgewürze und kreative Blends, die deinen Gerichten
        Tiefe und Charakter geben. Minimalistisch im Design, pur im Geschmack.
      </p>
      <div class="hero-buttons">
        <a href="shop.php" class="btn">Shop entdecken
          <!-- Icon: arrow-right (lucide.dev) -->
          <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </a>
        <button class="btn-outline" id="moreInfoBtn">Mehr erfahren</button>
      </div>
    </div>
    <div class="hero-media">
      <div class="hero-glaeschen">
        <img class="glas-links" src="images/produktbilder/Paprika.webp" alt="Paprika" />
        <img class="glas-mitte" src="images/produktbilder/rote_Chiliflocken.webp" alt="Rote Chiliflocken" />
        <img class="glas-rechts" src="images/produktbilder/Pimentpfefferkoerner.webp" alt="Pimentpfefferkörner" />
      </div>
    </div>
  </div>
</section>

<!-- Feature-Streifen mit vier Vorteilen -->
<div class="feature-strip">
  <div class="feature-strip-inner">
    <div class="feature-item">
      <!-- Icon: leaf (lucide.dev) -->
      <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
      <div>
        <h3>Natürlich &amp; rein</h3>
        <p>Ohne Zusätze.</p>
      </div>
    </div>
    <div class="feature-item">
      <!-- Icon: globe (lucide.dev) -->
      <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
      <div>
        <h3>Aus aller Welt</h3>
        <p>Sorgfältig ausgewählt.</p>
      </div>
    </div>
    <div class="feature-item">
      <!-- Icon: flame (lucide.dev) -->
      <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg>
      <div>
        <h3>Voller Geschmack</h3>
        <p>Intensiv und echt.</p>
      </div>
    </div>
    <div class="feature-item">
      <!-- Icon: recycle (lucide.dev) -->
      <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 19H4.815a1.83 1.83 0 0 1-1.57-.881 1.785 1.785 0 0 1-.004-1.784L7.196 9.5"/><path d="M11 19h8.203a1.83 1.83 0 0 0 1.556-.89 1.784 1.784 0 0 0 0-1.775l-1.226-2.12"/><path d="m14 16-3 3 3 3"/><path d="M8.293 13.596 7.196 9.5 3.1 10.598"/><path d="m9.344 5.811 1.093-1.892A1.83 1.83 0 0 1 11.985 3a1.784 1.784 0 0 1 1.546.888l3.943 6.843"/><path d="m13.378 9.633 4.096 1.098 1.097-4.096"/></svg>
      <div>
        <h3>Nachhaltig verpackt</h3>
        <p>Mehr Inhalt, weniger Müll.</p>
      </div>
    </div>
  </div>
</div>

<!-- Bestseller-Slider (Bilder kommen aus der Datenbank) -->
<section class="section">
  <div class="container">
    <div class="section-kopf">
      <span class="eyebrow">Unsere Favoriten</span>
      <h2 class="section-titel">Kleine Zutaten.<br />Grosse Wirkung.</h2>
      <p class="section-text">
        Starte mit den Klassikern oder erkunde neue Kombinationen.
      </p>
    </div>

    <div class="slider-container">
      <button class="slider-btn prev" id="prevBtn" aria-label="Zurück">
        <!-- Icon: chevron-left (lucide.dev) -->
        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
      </button>
      <div class="slider-track" id="sliderTrack">
        <?php foreach ($bestseller as $produkt): ?>
        <div class="slider-slide">
          <a href="produkt.php?id=<?php echo $produkt['produkt_id']; ?>">
            <img src="bild.php?id=<?php echo $produkt['produkt_id']; ?>" alt="<?php echo htmlspecialchars($produkt['name']); ?>" />
          </a>
          <div class="slide-caption"><?php echo htmlspecialchars($produkt['name']); ?></div>
        </div>
        <?php endforeach; ?>
      </div>
      <button class="slider-btn next" id="nextBtn" aria-label="Weiter">
        <!-- Icon: chevron-right (lucide.dev) -->
        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
      </button>
    </div>
    <div class="slider-dots" id="sliderDots"></div>

    <div style="text-align: center; margin-top: 2rem">
      <a href="shop.php" class="btn-dark">Alle Produkte ansehen
        <!-- Icon: arrow-right (lucide.dev) -->
        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
      </a>
    </div>
  </div>
</section>

<!-- Glücksrad + Gewinnspiel-Formular -->
<section class="section" style="background: var(--grau-hell)">
  <div class="container">
    <div class="section-kopf">
      <span class="eyebrow">Mitmachen &amp; gewinnen</span>
      <h2 class="section-titel">Gewürz-Glücksrad</h2>
      <p class="section-text">Drehe am Rad und sichere dir deinen Vorteil.</p>
    </div>

    <div class="wheel-form-grid">
      <div class="wheel-card">
        <canvas id="wheelCanvas" width="400" height="400"></canvas>
        <button id="spinWheelBtn" class="btn spin-btn">Drehen</button>
        <div class="prize-meta">Jeden Monat neue Preise. Viel Glück!</div>
      </div>

      <div class="form-card<?php if ($formVerstecken) { echo ' is-hidden'; } ?>" id="gewinnspielCard">
        <h3>Gewinnspiel teilnehmen</h3>
        <!-- Hier zeigt JavaScript nach dem Drehen den Gewinn an -->
        <div class="gewinn-hinweis" id="gewinnHinweis"></div>
        <p>Melde dich beim Newsletter an und erhalte deinen Code per E-Mail.</p>

        <?php if ($gewinnspielOk): ?>
        <!-- Bestätigung nach erfolgreicher Teilnahme -->
        <div class="success-msg">
          <!-- Icon: check-circle (lucide.dev) -->
          <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21.801 10A10 10 0 1 1 17 3.335"/><path d="m9 11 3 3L22 4"/></svg>
          Vielen Dank für deine Teilnahme!
        </div>
        <?php endif; ?>
        <?php if ($gewinnspielFehler): ?>
        <!-- Hinweis, wenn die Eingaben nicht gültig waren -->
        <div class="error-msg">
          <!-- Icon: alert-triangle (lucide.dev) -->
          <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
          Bitte fülle das Formular vollständig aus.
        </div>
        <?php endif; ?>

        <form id="contestForm" action="submit_gewinnspiel.php" method="post" novalidate>
          <div class="form-group">
            <label>Vorname *</label>
            <input type="text" id="vorname" name="vorname" placeholder="Max" />
            <div class="error-msg" id="errorVorname"></div>
          </div>
          <div class="form-group">
            <label>Nachname *</label>
            <input type="text" id="nachname" name="nachname" placeholder="Mustermann" />
            <div class="error-msg" id="errorNachname"></div>
          </div>
          <div class="form-group">
            <label>E-Mail *</label>
            <input type="email" id="email" name="email" placeholder="max@example.com" />
            <div class="error-msg" id="errorEmail"></div>
          </div>
          <div class="form-group">
            <label>Woher kennen Sie flavr.? *</label>
            <select id="referrer" name="referrer">
              <option value="">-- Bitte wählen --</option>
              <option value="social">Social Media</option>
              <option value="freunde">Freunde &amp; Familie</option>
              <option value="suche">Websuche</option>
            </select>
            <div class="error-msg" id="errorReferrer"></div>
          </div>
          <div class="form-group checkbox-group">
            <input type="checkbox" id="terms" name="terms" value="true" />
            <label>Ich akzeptiere die Teilnahmebedingungen. *</label>
          </div>
          <div class="error-msg" id="errorTerms"></div>
          <button type="submit" class="btn" style="width: 100%; justify-content: center">
            Teilnehmen
          </button>
          <div id="formFeedback"></div>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- Split-Block: Geschichten (rotes Band) -->
<section class="split split-reverse split-rot">
  <div class="split-media">
    <img src="images/erlebnisse/Orientalischer_Basar.webp" alt="Gewürze im Markt" />
  </div>
  <div class="split-inhalt">
    <span class="eyebrow">Entdecke neue Welten</span>
    <h2>Gewürze, die Geschichten erzählen</h2>
    <p>
      Jedes Gewürz steht für seine Herkunft, sein Handwerk und seine Geschichte.
      Entdecke die Welt, Löffel für Löffel.
    </p>
    <a href="erlebnisse.php" class="btn-outline">Mehr entdecken
      <!-- Icon: arrow-right (lucide.dev) -->
      <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
    </a>
  </div>
</section>

<!-- Kundenstimmen -->
<?php
// Stern-Icon einmal als Variable, damit es nicht mehrfach ausgeschrieben werden muss
$stern = '<svg class="icon" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>';
?>
<section class="section">
  <div class="container">
    <div class="section-kopf">
      <span class="eyebrow">Bewertungen</span>
      <h2 class="section-titel">Was Kunden sagen</h2>
      <p class="section-text">Die Qualität spricht für sich selbst.</p>
    </div>
    <div class="testimonial-grid">
      <div class="testimonial-card">
        <div class="sterne"><?php echo str_repeat($stern, 5); ?></div>
        <p class="zitat">„Diese Gewürze haben meine Küche verändert. Der Geschmack ist intensiv und echt."</p>
        <div class="person">
          <span class="person-avatar"></span>
          <div>
            <div class="person-name">Maria Schneider</div>
            <div class="person-rolle">Köchin, Zürich</div>
          </div>
        </div>
      </div>
      <div class="testimonial-card">
        <div class="sterne"><?php echo str_repeat($stern, 5); ?></div>
        <p class="zitat">„Endlich Gewürze, die nicht wie Staub schmecken. Ich bestelle regelmässig."</p>
        <div class="person">
          <span class="person-avatar"></span>
          <div>
            <div class="person-name">Thomas Keller</div>
            <div class="person-rolle">Privatperson, Basel</div>
          </div>
        </div>
      </div>
      <div class="testimonial-card">
        <div class="sterne"><?php echo str_repeat($stern, 5); ?></div>
        <p class="zitat">„Die Blends sind kreativ und funktionieren wirklich. Sehr empfohlen."</p>
        <div class="person">
          <span class="person-avatar"></span>
          <div>
            <div class="person-name">Elena Rossi</div>
            <div class="person-rolle">Restaurantbesitzerin, Bern</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Abschluss-Aufruf -->
<section class="cta-band">
  <h2>Stöbere jetzt im Shop</h2>
  <p>Finde die Gewürze, die deine nächste Mahlzeit unvergesslich machen.</p>
  <div class="cta-buttons">
    <a href="shop.php" class="btn">Entdecken
      <!-- Icon: arrow-right (lucide.dev) -->
      <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
    </a>
    <a href="#newsletterForm" class="btn-outline">Newsletter</a>
  </div>
</section>

<?php require 'footer.php'; ?>
