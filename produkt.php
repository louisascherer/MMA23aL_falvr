<?php
// Zentrale Datenbankverbindung einbinden
require 'db_connect.php';

// Produkt-Nummer aus der Adresse holen und in eine Zahl umwandeln.
// "?? 0" heisst: fehlt die Nummer in der Adresse, wird 0 genommen.
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

// Titel setzen und den gemeinsamen Kopf laden
if ($produkt) {
    $seitentitel = 'flavr. ' . $produkt['produkt_name'];
} else {
    $seitentitel = 'flavr. Produkt';
}
require 'header.php';
?>

<div class="container">
  <?php if (!$produkt): ?>
  <!-- Kein Produkt mit dieser Nummer gefunden -->
  <h1 style="margin-top: 3rem">Produkt nicht gefunden</h1>
  <p style="margin-bottom: 1.5rem">Dieses Produkt gibt es nicht (mehr).</p>
  <a href="shop.php" class="btn-outline">Zurück zum Shop</a>
  <?php else: ?>

  <!-- Zurück-Link oberhalb der Detailkarte -->
  <a href="shop.php" class="zurueck-link">
    <!-- Icon: chevron-left (lucide.dev) -->
    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
    Zurück zum Shop
  </a>

  <div class="product-detail">
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

      <button class="btn">
        <!-- Icon: shopping-cart (lucide.dev) -->
        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
        In den Warenkorb
      </button>
    </div>
  </div>
  <?php endif; ?>
</div>

<?php require 'footer.php'; ?>
