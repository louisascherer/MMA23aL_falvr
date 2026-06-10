# FLAVR Kräutershop – Projektanleitung für Claude

## Worum geht es?

Schulprojekt: ein kleiner Webshop für einen Kräutershop namens FLAVR, gebaut
mit **PHP** und einer **MariaDB/MySQL-Datenbank** auf einem Schulserver (Plesk).
Das Projekt wird in einer **mündlichen Prüfung** erklärt.

**Wichtigste Konsequenz daraus:** Der Code muss von Schülern **ohne tiefes
PHP/JS-Wissen** verstanden und mündlich erklärt werden können. Einfachheit und
Lesbarkeit sind wichtiger als clevere oder kurze Lösungen.

## Schreibregeln (IMMER einhalten)

Diese Regeln gelten für allen PHP- und JavaScript-Code in diesem Projekt:

1. **Keine Pfeilfunktionen** (`=>`). Immer normale `function`-Syntax.
2. **Kein ternärer Operator** (`? :`). Immer `if`/`else` ausschreiben.
3. **Jeden PHP-Datenbankblock auf Deutsch kommentieren** – kurz erklären, was die
   Abfrage macht.
4. **Lieber 5 einfache Zeilen als 1 clevere Zeile.** Keine verschachtelten
   Tricks, keine selten genutzten Sprachfeatures.
5. **Prepared Statements** für alle Datenbankabfragen mit Benutzereingaben
   (Schutz vor SQL-Injection). Werte werden mit `?` und `execute(array(...))`
   übergeben.
6. **`htmlspecialchars()`** um alle Ausgaben aus der Datenbank im HTML
   (Schutz vor XSS).
7. **Keine HTML5-`required`-Attribute und kein AJAX** – Formularprüfung im
   Browser geschieht mit einfachem JavaScript.
8. **SQL-Views statt JOINs im PHP.** Müssen Daten aus mehreren Tabellen
   kombiniert werden, eine **View in der Datenbank** anlegen und im PHP nur ein
   einfaches `SELECT ... FROM <view>` schreiben. So steht im Seitencode kein
   JOIN, den man in der Prüfung erklären müsste. View-Definitionen als `.sql`
   in `Datenbank referenz/` ablegen (Beispiel: `produkt_details_view.sql`).

## Dateistruktur

| Datei/Ordner | Aufgabe |
|---|---|
| `db_connect.php` | Baut die PDO-Verbindung zur Datenbank auf (`$pdo`). Wird per `require` in jede Seite eingebunden. |
| `index.php` | Startseite: Bestseller-Slider (aus DB), Glücksrad, Gewinnspiel-Formular. |
| `shop.php` | Produktübersicht: Kategorie-Filter, Gruppierung mit Überschriften, Vorschau (3/Kategorie) in der „Alle"-Ansicht, Paginierung (12/Seite) je Kategorie, Warenkorb-Modal. |
| `produkt.php` | Produkt-Detailseite (`?id=`). Nutzt die View `produkt_details`. |
| `erlebnisse.php` | Gewürzerlebnisse aus der DB. |
| `ueber-uns.php` | Statische Info-Seite. |
| `bild.php` | Gibt ein Produktbild (BLOB) aus `produktbilder` aus (`?id=`). |
| `erlebnisbild.php` | Gibt ein Erlebnisbild (BLOB) aus `gewuerzerlebnisbilder` aus (`?id=`). |
| `submit_gewinnspiel.php` | Verarbeitet das Gewinnspiel-Formular (POST), legt Kunde an, leitet zurück. |
| `submit_bestellungen.php` | Verarbeitet die Bestellung (POST), legt Kunde/Bestellung/Positionen an. |
| `script.js` | Warenkorb (localStorage), Slider, Glücksrad, Formular-Validierung im Browser. |
| `style.css` | Alle Styles. |
| `images/` | Statische Bilder (z.B. Logo) als WebP. |
| `Datenbank referenz/` | SQL-Dump der DB und View-Definitionen (`.sql`). |

## Bilder

- **Statische Bilder** (Logo, Deko) liegen im `images/`-Ordner als **WebP**,
  maximal **1920px** breit.
- **Produkt- und Erlebnisbilder** liegen als `mediumblob` in der Datenbank und
  werden über `bild.php?id=` bzw. `erlebnisbild.php?id=` ausgegeben (nicht als
  Datei verlinkt).
- **Neue Bilder** immer zu WebP (max. 1920px Breite, Höhe proportional)
  konvertieren. ffmpeg hat in dieser Umgebung **keinen** WebP-Encoder – deshalb
  mit `sips` skalieren und mit `cwebp` konvertieren.

## Lokale Entwicklung

Für die lokale Entwicklung läuft eine **MariaDB-Kopie** der Produktionsdatenbank
auf dem Mac (installiert via Homebrew). PHP verbindet sich auf `localhost`.

### Lokalen Server starten

```bash
# MariaDB starten (einmalig, läuft danach automatisch beim Login)
brew services start mariadb

# PHP-Entwicklungsserver starten
cd Documents/GitHub/MMA23aL_falvr
php -S localhost:8080
```

Die Seite ist dann unter **http://localhost:8080** erreichbar.

### Datenbankverbindung (`db_connect.php`)

Aktuell zeigt `db_connect.php` auf **localhost** (lokale Entwicklung):

```php
$host = 'localhost';
$db   = 'louisa-scherer_';
$user = 'louisa';
$pass = '!Q*L7n5haDfggl4q';
```

### ⚠️ VOR DER ABGABE: Host auf Schulserver umstellen

Bevor die Seite auf den Plesk-Server hochgeladen wird, muss in `db_connect.php`
der Host von `localhost` auf die bbzwinf.ch-Adresse geändert werden:

```php
$host = 'xyz.Smma23aL.bbzwinf.ch'; // <-- vor Abgabe eintragen
```

### Auf den Server hochladen

Dateien per Plesk-Dateimanager oder FTP hochladen.
Die Seite läuft dann unter `xyz.Smma23aL.bbzwinf.ch`.


## Datenbank

### Zugangsdaten (lokal & Schulserver)

| | Lokal | Schulserver |
|---|---|---|
| Host | `localhost` | `xyz.Smma23aL.bbzwinf.ch` |
| Datenbank | `louisa-scherer_` | `louisa-scherer_` |
| User | `louisa` | `louisa` |
| Passwort | `!Q*L7n5haDfggl4q` | `!Q*L7n5haDfggl4q` |

### Tabellenstruktur

| Tabelle | Wichtigste Spalten | Beschreibung |
|---|---|---|
| `produkte` | `produkt_id`, `name`, `beschreibung`, `preis_chf`, `menge`, `kategorie_id`, `lagerbestand`, `aktiv` | Alle Gewürze/Kräuter im Shop |
| `kategorien` | `kategorie_id`, `name`, `beschreibung` | Produktkategorien (z.B. Pfeffersorten) |
| `produktbilder` | `produktbild_id`, `produkt_id`, `bilddaten` (BLOB), `ist_hauptbild` | Bilder der Produkte |
| `zutaten` | `zutat_id`, `name` | Zutatenliste für Produkte |
| `produkt_zutaten` | `produkt_id`, `zutat_id` | Verknüpfungstabelle Produkt ↔ Zutaten |
| `kunden` | `kunden_id`, `name`, `email`, `adresse`, `postleitzahl`, `passwort`, `newsletter` | Registrierte Kunden |
| `bestellungen` | `bestellung_id`, `kunden_id`, `bestelldatum`, `gesamtpreis_chf`, `zahlungsart`, `status` | Bestellköpfe |
| `bestellpositionen` | `position_id`, `bestellung_id`, `produkt_id`, `anzahl`, `preis_chf` | Einzelne Positionen pro Bestellung |
| `gewuerzerlebnisse` | `gewuerzerlebnis_id`, `titel`, `preis_chf`, `ort`, `max_teilnehmer`, `dauer_minuten`, `verfuegbar` | Kochkurse / Events |
| `buchungen` | `buchung_id`, `kunden_id`, `gewuerzerlebnis_id`, `anzahl_teilnehmer`, `gesamtpreis_chf`, `status` | Buchungen für Erlebnisse |

**Views (nur lesen):** `bestellungen_mit_kunden`, `buchungen_mit_gewuerzerlebnissen`, `produkte_mit_kategorien`, `produkte_mit_zutaten`, `produkt_details`

**Bild-Tabellen (mediumblob):** `produktbilder` (Produktbilder), `gewuerzerlebnisbilder` (Bilder der Erlebnisse).

**Wichtig zu `produkt_details`:** Diese View fasst Produkt + Kategorie + Zutaten
zusammen, damit `produkt.php` ohne JOIN auskommt (nur ein einfaches `SELECT`).
Die View-Definition liegt in `Datenbank referenz/produkt_details_view.sql` und
muss auf der Server-DB einmal ausgeführt werden (sonst fehlt sie dort).

**Direktzugriff lokal:**
```bash
mariadb -u louisa -p'!Q*L7n5haDfggl4q' 'louisa-scherer_'
```

**Direktzugriff Schulserver** (kein SSL):
```bash
MYSQL_PWD='!Q*L7n5haDfggl4q' mysql --skip-ssl \
  -h 'xyz.Smma23aL.bbzwinf.ch' \
  -u 'louisa' 'louisa-scherer_'
```

### Wichtige Tabellen


## Git-Stil (Commits, PRs, Kommentare)

Alle Git-Texte werden **auf Deutsch** geschrieben, im **einfachen Satz-Stil** und
**ohne KI-Hinweis** (kein „Co-Authored-By", kein „Generated with Claude").

- **Commit-Titel:** kurzer deutscher Satz, der die Änderung beschreibt –
  keine Namens-Präfixe. Beispiele:
  - `Ersetzen aller Bilder durch WebP Versionen`
  - `HTML-Seiten durch dynamische PHP-Seiten ersetzt`
  - `hinzufügen SQL Dump für lokales Arbeiten`
- **Commit-Beschreibung (optional):** 1–4 kurze Stichpunkte auf Deutsch, die die
  wichtigsten Änderungen auflisten.
- **Pull-Request-Titel:** kurzer deutscher Satz wie beim Commit-Titel.
- **Pull-Request-Beschreibung:** kurzer Einleitungssatz, dann eine
  Stichpunktliste der Änderungen auf Deutsch.
- **Code-Kommentare:** auf Deutsch, einfach und erklärend (siehe Schreibregeln) –
  jeden PHP-Datenbankblock kurz kommentieren.

## Nach jeder Änderung

Wenn eine Datei fertig ist: in **2 Sätzen auf Deutsch** erklären, was sie macht –
als Vorbereitung für die mündliche Prüfung.
