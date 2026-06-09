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

Die Datenbank läuft **direkt auf dem Schulserver**. Das PHP-File verbindet sich
über die Hostadresse direkt mit dem Datenbankserver – es braucht **keine lokale
MariaDB-Kopie** mehr. So wird gearbeitet:

### Lokalen Server starten

```bash
# PHP-Entwicklungsserver starten
cd C:\Users\Alexej\Documents\GitHub\MMA23aL_falvr
php -S localhost:8080
```

Die Seite ist dann unter **http://localhost:8080** erreichbar. Die Daten kommen
dabei direkt vom Schul-Datenbankserver.

### Datenbankverbindung (`config/db.php`)

Diese Werte sind in `db_connect.php` gesetzt (Direktverbindung zum Schulserver):

```php
$host = 'url wird noch geliefert';
$db   = 'louisa-scherer_';
$user = 'louisa';
$pass = '!Q*L7n5haDfggl4q';
```

Diese Werte gelten sowohl für die lokale Entwicklung als auch nach dem Hochladen
auf den Server – es muss **nichts mehr umgestellt** werden.

### Auf den Server hochladen

Dateien per Plesk-Dateimanager oder FTP auf den Server hochladen.
Die Seite läuft dann unter `url wird noch geliefert`.


## Datenbank

Die Verbindung steht in `db_connect.php`. Die Datenbank läuft auf dem Schulserver
und ist über die Hostadresse `url wird noch geliefert`


**WICHTIG – immer die Live-DB benutzen:** Wenn du die Datenbankstruktur oder
Daten brauchst (Spalten, Tabellen, Werte prüfen, Abfragen testen), verbinde dich
**immer direkt mit dem Plesk-/Schulserver** über die Zugangsdaten aus
`db_connect.php` (Befehl unten). 

**Direktzugriff per Kommandozeile (z.B. für Migrationen):** Der Schulserver
bietet **kein TLS/SSL** an, der `mysql`-Client verlangt es aber standardmässig.
Deshalb beim direkten Verbinden `--skip-ssl` mitgeben, sonst kommt der Fehler
„SSL is required, but the server does not support it":

```bash
MYSQL_PWD='!Q*L7n5haDfggl4q' mysql --skip-ssl \
  -h 'url wird noch geliefert' \
  -u 'louisa' 'louisa-scherer_'
```

### Wichtige Tabellen


## Nach jeder Änderung

Wenn eine Datei fertig ist: in **2 Sätzen auf Deutsch** erklären, was sie macht –
als Vorbereitung für die mündliche Prüfung.
