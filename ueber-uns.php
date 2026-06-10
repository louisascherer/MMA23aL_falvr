<?php
// Über uns ist eine statische Info-Seite (keine Datenbank nötig).
$seitentitel = 'flavr. Über uns';
require 'header.php';
?>

<!-- Split-Block: Vorstellung -->
<section class="split">
  <div class="split-media">
    <img src="images/Schokolade_und_Gewürze.webp" alt="Gewürze und Zutaten" />
  </div>
  <div class="split-inhalt">
    <span class="eyebrow">Warum flavr.</span>
    <h2>Gewürze, die wirklich schmecken</h2>
    <p>
      Wir arbeiten nur mit Lieferanten zusammen, die unsere Standards teilen.
      Keine Kompromisse, keine Füllstoffe, nur ehrlicher Geschmack.
    </p>
    <a href="shop.php" class="btn-outline">Probieren
      <!-- Icon: arrow-right (lucide.dev) -->
      <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
    </a>
  </div>
</section>

<!-- Was uns unterscheidet (dunkle Feature-Karten) -->
<section class="section">
  <div class="container">
    <div class="section-kopf">
      <span class="eyebrow">Qualität</span>
      <h2 class="section-titel">Was uns unterscheidet</h2>
      <p class="section-text">Qualität steht an erster Stelle in allem, was wir tun.</p>
    </div>

    <div class="features-dark">
      <div class="feature-dark">
        <!-- Icon: shield-check (lucide.dev) -->
        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
        <h3>Sorgfältig ausgewählte Gewürze</h3>
        <p>Jedes Gewürz wird nach strengen Qualitätsstandards ausgewählt und getestet.</p>
        <a href="shop.php">Erfahren
          <!-- Icon: arrow-right (lucide.dev) -->
          <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </a>
      </div>
      <div class="feature-dark">
        <!-- Icon: sparkles (lucide.dev) -->
        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .962 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.962 0z"/><path d="M20 3v4"/><path d="M22 5h-4"/><path d="M4 17v2"/><path d="M5 18H3"/></svg>
        <h3>Kreative Blends für neue Geschmäcker</h3>
        <p>Unsere Blends verbinden traditionelle Rezepte mit modernen Kombinationen.</p>
        <a href="shop.php">Erkunden
          <!-- Icon: arrow-right (lucide.dev) -->
          <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </a>
      </div>
      <div class="feature-dark">
        <!-- Icon: truck (lucide.dev) -->
        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/></svg>
        <h3>Schnelle und zuverlässige Lieferung</h3>
        <p>Deine Bestellung kommt sicher und schnell zu dir nach Hause.</p>
        <a href="shop.php">Details
          <!-- Icon: arrow-right (lucide.dev) -->
          <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Abschluss-Aufruf -->
<section class="cta-band">
  <h2>Dein Geschmack verdient mehr</h2>
  <p>Starte jetzt dein kulinarisches Abenteuer mit reinen &amp; ehrlichen Gewürzen.</p>
  <div class="cta-buttons">
    <button class="btn" id="entdeckenBtn">Entdecken
      <!-- Icon: arrow-right (lucide.dev) -->
      <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
    </button>
  </div>
</section>

<?php require 'footer.php'; ?>
