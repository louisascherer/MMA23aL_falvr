# M291 – Offene Restpunkte (Code/Repo)

> Abgeglichen mit `m291-checkliste.md`. Nur Punkte, die im **Code/Repo** noch
> fehlen oder abweichen. Das Word-Abgabedokument (Abschnitt 1/2/3) ist Schritt 2.

## ✅ Erledigt (Code)

- [x] **Footer-Disclaimer im exakten Wortlaut** „Das ist ein Schulprojekt und
      keine reale Website" (`footer.php`).
- [x] **Namen aller Gruppenmitglieder im Footer** (Leonie Walker, Louisa
      Scherer, Olivia Vieli) – neue `.footer-team`-Zeile.
- [x] **README.md** mit allen Teilnehmernamen angelegt.

## ⚠️ Prüfen / klären

- [ ] **Lehrer als GitHub-Collaborator** hinzugefügt? (`rajethan.ranjan@sluz.ch`)
      – im Repo nicht sichtbar, auf GitHub kontrollieren.
- [ ] **`common.css`**: Die Checkliste nennt die gemeinsame Datei `common.css`,
      bei uns heißt sie `style.css`. Inhaltlich erfüllt (gemeinsame Styles
      ausgelagert). Entweder so lassen und in der Doku erwähnen, oder umbenennen.
- [ ] **Grafiken max. 0.5 MB, JPG/PNG**: Wir nutzen WebP + DB-BLOBs. In der
      mündlichen Prüfung/Doku begründen (WebP = kleiner). Kein echtes Problem,
      nur erklären können.

## ✅ Bereits erfüllt (zur Kontrolle)

- [x] 3+ Web Pages: `index.php`, `shop.php`, `erlebnisse.php`, `ueber-uns.php`
      (+ `produkt.php`)
- [x] Responsive, einheitlicher Header/Footer (`header.php` / `footer.php`)
- [x] Navigation: Logo, aktiver Link (JS), Hover, Hamburger-Menü, responsiv
- [x] CSS-Pflichteffekte: Hover, `box-shadow`, Gradient, `@keyframes`-Animation
- [x] Formular: 5 Felder, E-Mail-Pflichtfeld, 3+ Pflichtfelder
- [x] JS-Validierung aller Felder inkl. `@`-Prüfung, **kein** HTML5 (`novalidate`)
- [x] Inline-Fehlermeldungen (nicht nur `alert()`)
- [x] Speicherung in DB (`kunden`) per Prepared Statement
      (`submit_gewinnspiel.php`)
- [x] 5 interaktive Elemente: Navigation, Formular+DB, Glücksrad (großes
      Element, startet erst per Klick), Image-Slider, Newsletter
- [x] Mind. 15 JS-Zeilen / 30 CSS-Zeilen (CSS ~1360, JS ~412)
