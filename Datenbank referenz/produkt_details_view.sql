-- View "produkt_details": fasst Produkt, Kategorie und Zutaten zusammen.
-- So muss im PHP-Seitencode (produkt.php) KEIN JOIN geschrieben werden –
-- die Seite macht nur ein einfaches "SELECT ... FROM produkt_details WHERE produkt_id = ?".
--
-- Diese View muss auf jeder Datenbank vorhanden sein (lokal UND auf dem Schulserver).
-- Zum Anlegen einfach dieses Skript einmal ausführen.

CREATE OR REPLACE VIEW produkt_details AS
SELECT
  p.produkt_id,
  p.name AS produkt_name,
  p.beschreibung,
  p.preis_chf,
  p.herkunft,
  p.menge,
  p.lagerbestand,
  p.aktiv,
  k.name AS kategorie_name,
  (SELECT GROUP_CONCAT(z.name ORDER BY z.name SEPARATOR ', ')
     FROM produkt_zutaten pz
     JOIN zutaten z ON z.zutat_id = pz.zutat_id
     WHERE pz.produkt_id = p.produkt_id) AS zutaten
FROM produkte p
LEFT JOIN kategorien k ON k.kategorie_id = p.kategorie_id;
