<?php
// Zentrale Datenbankverbindung einbinden
require 'db_connect.php';

// Datenbankabfrage: alle verfügbaren Gewürzerlebnisse laden
$stmt = $pdo->query("SELECT gewuerzerlebnis_id, titel, preis_chf, ort, dauer_minuten FROM gewuerzerlebnisse WHERE verfuegbar = 1 ORDER BY gewuerzerlebnis_id");
$erlebnisse = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Titel setzen und den gemeinsamen Kopf laden
$seitentitel = 'flavr. Erlebnisse';
require 'header.php';
?>

<div class="container">
  <div class="section-kopf" style="text-align: left; max-width: none; margin-top: 3rem">
    <span class="eyebrow">Erlebnisse</span>
    <h1 class="section-titel">Kochkurse &amp; Tastings</h1>
    <p class="section-text">Entdecke die Welt der Gewürze, live und interaktiv.</p>
  </div>

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
        <div>
          <!-- Icon: map-pin (lucide.dev) -->
          <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
          <?php echo htmlspecialchars($erlebnis['ort']); ?>
        </div>
        <div>
          <!-- Icon: clock (lucide.dev) -->
          <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          <?php echo intval($erlebnis['dauer_minuten']); ?> Minuten
        </div>
      </div>
      <button class="btn-outline">Buchen</button>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<?php require 'footer.php'; ?>
