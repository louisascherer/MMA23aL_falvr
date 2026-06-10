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

// Titel setzen und den gemeinsamen Kopf laden
$seitentitel = 'flavr. Shop';
require 'header.php';
?>

<div class="container">
  <div class="section-kopf" style="text-align: left; max-width: none; margin-top: 3rem">
    <span class="eyebrow">Sortiment</span>
    <h1 class="section-titel">Unsere Gewürze</h1>
    <p class="section-text">Handverlesene Qualität, direkt aus der Mühle.</p>
  </div>

  <!-- Filter-Leiste: nach Kategorie filtern (Seite lädt jeweils neu) -->
  <div class="filter-bar">
    <a href="shop.php" class="<?php if ($aktuelleKategorie == 0) { echo 'active'; } ?>">Alle</a>
    <?php foreach ($kategorien as $kat): ?>
    <a href="shop.php?kategorie=<?php echo $kat['kategorie_id']; ?>" class="<?php if ($aktuelleKategorie == $kat['kategorie_id']) { echo 'active'; } ?>"><?php echo htmlspecialchars($kat['name']); ?></a>
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
      <!-- Bild und Name führen zur Detailseite -->
      <a class="produkt-link" href="produkt.php?id=<?php echo $produkt['produkt_id']; ?>">
        <div class="product-image"><img src="<?php echo $bildPfad; ?>" alt="<?php echo htmlspecialchars($produkt['name']); ?>" loading="lazy"></div>
        <div class="product-title"><?php echo htmlspecialchars($produkt['name']); ?></div>
      </a>
      <div class="product-foot">
        <span class="product-price"><?php echo number_format($produkt['preis_chf'], 2); ?> CHF</span>
        <button class="add-btn" aria-label="In den Warenkorb">+</button>
      </div>
    </div>
    <?php endforeach; ?>
    <?php if ($aktuelleKategorie == 0): ?>
    <!-- Letzte Kachel in der Übersicht: führt zur ganzen Kategorie -->
    <a class="product-card show-all-card" href="shop.php?kategorie=<?php echo $kat['kategorie_id']; ?>">
      Alle anzeigen
      <!-- Icon: arrow-right (lucide.dev) -->
      <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
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
</div>

<?php require 'footer.php'; ?>
