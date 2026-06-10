<?php
// header.php – gemeinsamer Kopf für alle Seiten.
// Jede Seite setzt vorher $seitentitel und ruft dann: require 'header.php';

// Falls eine Seite keinen Titel gesetzt hat, einen Standardtitel verwenden.
if (!isset($seitentitel)) {
    $seitentitel = 'flavr. Gewürze mit Charakter';
}
?>
<!doctype html>
<html lang="de">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($seitentitel); ?></title>
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <!-- Rote Ankündigungsleiste ganz oben -->
    <div class="ankuendigung">
      Kostenloser Versand ab 49 CHF
      <!-- Icon: arrow-right (lucide.dev) -->
      <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
    </div>

    <header>
      <div class="top-bar">
        <div class="logo">
          <a href="index.php"
            ><img
              src="images/Logo_flavr_new%20weiss.svg"
              alt="flavr."
              class="logo-img"
          /></a>
        </div>

        <nav class="nav-links">
          <a href="index.php">Home</a>
          <a href="shop.php">Shop</a>
          <a href="erlebnisse.php">Erlebnisse</a>
          <a href="ueber-uns.php">Über uns</a>
        </nav>

        <div class="header-aktionen">
          <a href="shop.php" class="icon-link" aria-label="Warenkorb">
            <!-- Icon: shopping-cart (lucide.dev) -->
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
          </a>
          <a href="shop.php" class="btn btn-klein">Bestellen</a>
          <div class="hamburger"><span></span><span></span><span></span></div>
        </div>
      </div>
    </header>

    <main>
