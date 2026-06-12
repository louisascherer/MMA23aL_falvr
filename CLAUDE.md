# CLAUDE.md — FLAVR Gewürz-Webshop

Schulprojekt **M291**, Klasse MMA23aL. Kleiner Gewürz-Webshop in **PHP + MariaDB**,
gehostet auf **Plesk**. Wird in einer **mündlichen Prüfung** erklärt → der Code muss
von Schüler:innen **ohne tiefes PHP/JS-Wissen** verstanden und erklärt werden können.
**Einfach und lesbar schlägt clever und kurz.**
Team: Leonie Walker, Louisa Scherer (Plesk-Verantwortliche), Olivia Vieli.

## Absolute Regeln (PHP und JS)

1. **Keine Pfeilfunktionen** – immer normales `function`.
2. **Kein ternärer Operator `? :`** – immer `if`/`else`. (`??` ist erlaubt, aber kurz kommentieren.)
3. **Lieber 5 einfache Zeilen als 1 clevere Zeile.** Keine seltenen Sprachfeatures, keine Tricks.

## Absolute Regeln (PHP)

4. **Jeden Datenbank-Block deutsch kommentieren** (was macht die Abfrage).
5. **Prepared Statements** für jede Abfrage mit Benutzereingabe: `?` als Platzhalter + `execute(array(...))`.
6. **`htmlspecialchars()`** um jede Text-Ausgabe aus der DB im HTML. Zahlen über `intval()` / `number_format()`.
7. **Views statt JOINs.** Daten aus mehreren Tabellen → eine View in der DB anlegen, im PHP nur `SELECT ... FROM <view>`. Neue View zusätzlich als `.sql` sichern, damit sie auf dem Server neu angelegt werden kann.

## Absolute Regeln (Formulare)

8. **Kein HTML5-`required`, kein AJAX.** Ablauf immer: Browser-Prüfung mit einfachem JS (`novalidate`) → Server prüft nochmal → `header('Location: …')`-Redirect mit Status (`?xy=ok` / `?xy=fehler`), Seite zeigt die Meldung an.

## Workflow

- **Nach jeder fertigen Datei:** in **2 Sätzen auf Deutsch** erklären, was sie macht (Prüfungs-Vorbereitung).
- **Git & alle Texte (Commits, PRs, Kommentare):** auf **Deutsch**, einfacher Satzstil, **kein KI-Hinweis** (kein „Co-Authored-By", kein „Generated with…"). Commit-Titel = kurzer Satz; optionale Beschreibung = 1–4 Stichpunkte.

## Lokal starten (Windows / XAMPP)

```powershell
# PHP-Server (Seite dann auf http://localhost:8080)
& "C:\xampp\php\php.exe" -S localhost:8080

# Direkter DB-Zugriff (Passwort über Umgebungsvariable)
$env:MYSQL_PWD = '!Q*L7n5haDfggl4q'
& "C:\xampp\mysql\bin\mysql.exe" --skip-ssl -h "flavr.Smma23aL.bbzwinf.ch" -u "louisa" "louisa-scherer_"
```

`db_connect.php` zeigt **bereits auf den Schulserver** (auch lokal wird damit gearbeitet, es gibt keine separate lokale DB). Hochladen auf Plesk per Dateimanager/FTP.

## Datenbank

Host `flavr.Smma23aL.bbzwinf.ch` · Port `3306` · DB `louisa-scherer_` · User `louisa` · Passwort `!Q*L7n5haDfggl4q`

**Tabellen (wichtigste Spalten):**
- `produkte` (produkt_id, name, beschreibung, preis_chf, menge, kategorie_id, lagerbestand, aktiv)
- `kategorien` (kategorie_id, name, beschreibung)
- `produktbilder` (produktbild_id, produkt_id, bilddaten = BLOB, ist_hauptbild)
- `zutaten` + `produkt_zutaten` (Verknüpfung Produkt ↔ Zutat)
- `kunden` (kunden_id, name, email = UNIQUE, telefon, adresse, postleitzahl, passwort, newsletter, registrierungsdatum)
- `bestellungen` + `bestellpositionen`
- `gewuerzerlebnisse` (…, ort, dauer_minuten, verfuegbar) + `gewuerzerlebnisbilder` (BLOB) + `buchungen`

**Views (nur lesen):** `produkt_details`, `produkte_mit_kategorien`, `produkte_mit_zutaten`, `bestellungen_mit_kunden`, `buchungen_mit_gewuerzerlebnissen`. `produkt.php` nutzt `produkt_details` (Produkt + Kategorie + Zutaten ohne JOIN).

**Bilder:** Produkt-/Erlebnisbilder liegen als BLOB in der DB, ausgegeben über `bild.php?id=` bzw. `erlebnisbild.php?id=`.

## Dateien

| Datei | Aufgabe |
|---|---|
| `db_connect.php` | PDO-Verbindung (`$pdo`), per `require` in jede Seite eingebunden |
| `header.php` / `footer.php` | Gemeinsamer Kopf/Fuss (Nav, Logo, Footer mit Newsletter + Team-Namen + Disclaimer) |
| `index.php` | Startseite: Hero, Bestseller-Slider (DB), Glücksrad + Gewinnspiel-Formular |
| `shop.php` | Produktübersicht: Kategorie-Filter, Gruppierung, Vorschau (3/Kat.), Paginierung (12/Seite) |
| `produkt.php` | Detailseite (`?id=`), nutzt View `produkt_details` |
| `erlebnisse.php` | Gewürzerlebnisse aus der DB |
| `ueber-uns.php` | Statische Info-Seite |
| `bild.php` / `erlebnisbild.php` | Geben ein Bild (BLOB) aus der DB aus (`?id=`) |
| `submit_gewinnspiel.php` | Gewinnspiel-Formular → Kunde anlegen (`newsletter = 1`) |
| `submit_newsletter.php` | Footer-Newsletter → Kunde anlegen/aktualisieren (`newsletter = 1`) |
| `script.js` | Navigation/Hamburger, Slider, Glücksrad, Formular-Validierung im Browser |
| `style.css` | Alle Styles |
| `images/` | Statische Bilder als WebP (max. 1920px), Logos (SVG) · `font/` = Schriftart |

## Bilder hinzufügen

Neue statische Bilder als **WebP**, max. **1920px** breit, in `images/`. Produkt-/Erlebnisbilder kommen als BLOB in die DB (nicht als Datei verlinken).
