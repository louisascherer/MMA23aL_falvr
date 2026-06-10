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

## Dateistruktur

| Datei/Ordner | Aufgabe |
|---|---|
| `db_connect.php` | Baut die PDO-Verbindung zur Datenbank auf (`$pdo`). Wird per `require` in jede Seite eingebunden. |

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

**Views (nur lesen):** `bestellungen_mit_kunden`, `buchungen_mit_gewuerzerlebnissen`, `produkte_mit_kategorien`, `produkte_mit_zutaten`

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


## Nach jeder Änderung

Wenn eine Datei fertig ist: in **2 Sätzen auf Deutsch** erklären, was sie macht –
als Vorbereitung für die mündliche Prüfung.
