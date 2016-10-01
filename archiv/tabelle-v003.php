<?php
$todo = '
<pre>
T010
<strong>tabelle.php verbessern </strong>
ZEG bedeutet Zeiterfassungsgerät
ZEG zeigt "Datum, Zeit und Wochentag" 
Drücke Infotaste und halte Ausweis vors ZEG
ZEG zeigt "Arbeitszeit in Stunden" "letzter Arbeitstag" "Saldo" "Datum"
 
Abrechnungsbon sind  2 bis 3 Bons Code 50
Abrechnungsbon = Abmeldung

</pre>
';
class zeile {
  public $rw;
  public $typ;
  public $label;
  public $muster;
  public $bedeutung;
  function __construct ( $rw, $typ, $label, $muster, $bedeutung) {
    $this->rw        = $rw; // r = read only, w = read and write
    $this->typ       = $typ;
    $this->label     = $label;
    $this->muster    = $muster;
    $this->bedeutung = $bedeutung;
    #printf( "M010 m%s l%s<br />\n", $this->typ, $this->label, $this->muster, $this->bedeutung);
  }
}

class tabelle {
  public $felder, $kurzfelder, $kurzwahl;
  public function get_muster( $column_name) {
  }
  function __construct() {
    $id_eigsch = "INT(11) AUTO_INCREMENT PRIMARY KEY";
    $aktuell = "TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
    $dec = "decimal(5,2) DEFAULT NULL";
  $this->felder = array (
    "id"                   => (new zeile("r", $id_eigsch    , "Id"                         , ""               , "Automatisch vergeben ID")),
    "datum_auto"           => (new zeile("r", "date"        , "date"                       , ""               , "Auto Tag der Arbeitsleistung")),
    "datum"                => (new zeile("w", "text"        , "Datum"                      , "04.03"          , "Tag der Arbeitsleistung")),
    "erscheine"            => (new zeile("w", "text"        , "Erscheine"                  , "12:12"          , "Zeit oder 'frei'/'Urlaub'/'BA'/'BR'")),
    "sitzt_oeffnet"        => (new zeile("w", "text"        , "sitzt oder öffnet"          , "Hönow"          , "wer sitzt in einer Kasse oder öffnet den Laden" )),
    "tageseroeffnung"      => (new zeile("w", "text"        , "Tageseröffnung"             , "06:30"          , "Kassen hochgefahren, jede Kasse druckt Bon" )),
    "nehme_arbeit_auf"     => (new zeile("w", "text"        , "nehme"                      , "12.45"          , "ZEG nehme die Arbeit auf" )),
    "starterset"           => (new zeile("w", "text"        , "SS Starterset"              , "3"              , "Anzahl Alditalk-Startersets zum Verkauf" )),
    "erhalte"              => (new zeile("w", "text"        , "erhalte"                    , "286.00 13:06"   , "€ Zeit in der Kassenlade Zeit" )),
    "rollen"               => (new zeile("w", "text"        , "davon Rollen"               , "186.00"         , "€ Rollen in der Kassenlade" )),
    "wechselgeld"          => (new zeile("w", "text"        , "Wechselgeld"                , "186.00 14:03"   , "€ Zeit im Lauf des Tages erhalten" )),
    "schein100"            => (new zeile("w", "text"        , "100"                        , "1"              , "Anzahl der eingenommenen 100-€-Scheine" )),
    "schein200"            => (new zeile("w", "text"        , "200"                        , "0"              , "Anzahl der eingenommenen 200-€-Scheine" )),
    "schein500"            => (new zeile("w", "text"        , "500"                        , "0"              , "Anzahl der eingenommenen 500-€-Scheine" )),
    "bonstorno"            => (new zeile("w", "text"        , "Bonstorno"                  , "0"              , "Anzahl der Bonstornos" )),
    "retouren"             => (new zeile("w", "text"        , "Retouren"                   , "1"              , "Anzahl der Retouren" )),
    "nullbon"              => (new zeile("w", "text"        , "Nullbon"                    , "2"              , "Anzahl der Nullbons" )),
    "kunden"               => (new zeile("w", "text"        , "Kunden"                     , "250"            , "Anzahl der Kunden" )),
    "abrechnungsbon"       => (new zeile("w", "text"        , "Abrechnungsbon"             , "21:05"          , "Kassiererin meldet sich an der Kasse ab: Bon" )),
    "einkaufdurchschnitt"  => (new zeile("w", "text"        , "DE Durchschnitt"            , "16.80"          , "€ Durchschnittlicher Einkauf" )),
    "ec_karte"             => (new zeile("w", "text"        , "EC"                         , "2089.98"        , "€ EC-Karte" )),
    "ec_kunden"            => (new zeile("w", "text"        , "EC-Kunden"                  , "58"             , "Anzahl der EC-Zahlungen" )),
    "pos_je_stunde"        => (new zeile("w", "text"        , "Pos je Stunde"              , "2907"           , "Positionen je Stunde, Kassenleistung" )),
    "kasse_wartezeit"      => (new zeile("w", "text"        , "Kasse/Wartezeit"            , "22/8"           , "bedeutet??" )),
    "Manko"                => (new zeile("w", "text"        , "Manko"                      , "+0.00"          , "€ Zu viel + oder zu wenig - in der Kasse" )),
    "leergut_auszahlung"   => (new zeile("w", "text"        , "Leergut Auszahlung"         , "138.75"         , "€ An Kunden ausgezahles Pfand" )),
    "leergut_soe"          => (new zeile("w", "text"        , "Leergut SoE"                , "5"              , "Leergut Sack oder Einzelflasche" )),
    "leergut_sack1"        => (new zeile("w", "text"        , "Leergut 1. Sack"            , "1"              , "Sackanzahl und Flaschenanzahl Einzelflaschen" )),
    "leergut_sack2"        => (new zeile("w", "text"        , "Leergut 2. Sack"            , "0"              , "Sackanzahl und Flaschenanzahl Einzelflaschen" )),
    "leergut_anzahl"       => (new zeile("w", "text"        , "Leergut Anzahl"             , "555"            , "Anzahl der Leergut-Flaschen und -Dosen" )),
    "pfand"                => (new zeile("w", "text"        , "Pfand"                      , "148.25"         , "€ bedeutet??" )),
    "pause1_geht"          => (new zeile("w", "text"        , "1.Pause Geht"               , "16:17"          , "Pause beginnt" )),
    "pause1_kommt"         => (new zeile("w", "text"        , "1.Pause Kommt"              , "16.32"          , "Pause endet" )),
    "pause2_geht"          => (new zeile("w", "text"        , "2.Pause Geht"               , "19:01"          , "Pause beginnt" )),
    "pause2_kommt"         => (new zeile("w", "text"        , "2.Pause Kommt"              , "19:16"          , "Pause endet" )),
    "arbzeit_plan_dauer"   => (new zeile("w", "text"        , "Geplante Dauer"             , "8.00"           , "Geplante Arbeitszeit Dauer in h dezimal" )),
    "arbzeit_plan_anfang"  => (new zeile("w", "text"        , "Geplanter Anfang"           , "12:45"          , "Geplante Arbeitszeit Anfangszeitpunkt" )),
    "arbzeit_plan_ende"    => (new zeile("w", "text"        , "Geplantes Ende"             , "21:15"          , "Geplante Arbeitszeit Endzeitpunkt" )),
    "arbeit_kommt"         => (new zeile("w", "text"        , "Tatsächlich Anfang"         , "12:45"          , "ZEG Anfang des Einsatzes tatsächlich" )),
    "arbeit_geht"          => (new zeile("w", "text"        , "Tatsächlich Ende"           , "21:10"          , "ZEG Ende des Einsatzes tatsächlich" )),
    "zeitgutschrift_20"    => (new zeile("w", "text"        , "ZEG 20%%"                   , "0.30"           , "h 20%% Zeitgutschrift von 18.30 bis 20.00 Uhr" )),
    "nachtzuschlag_50"     => (new zeile("w", "text"        , "50%%"                       , "0.59"           , "h 50%% Nachtzuschlag ab 20.00 Uhr" )),
    "zuschlag_summe"       => (new zeile("w", "text"        , "Mehr"                       , "0.89"           , "h Summe der Zeitgutschriften ab 18.30 und 20.00" )),
    "mehrarbeit"           => (new zeile("w", "text"        , "Gesamt / Mehr"              , "0.00"           , "h bedeutet?? Mehrarbeit + ZEG + Nachtzuschlag" )),
    "abschöpfung_bar"      => (new zeile("w", "text"        , "Abschöpfung Bar"            , "0.00 14:00"     , "€ Zeitpunkt Abschöpfung Bar" )),
    "abschöpfung_safebag"  => (new zeile("w", "text"        , "Abschöpfung Safebag"        , "0.00 15:00"     , "€ Zeitpunkt Abschöpfung Safebag" )),
    "abschöpfung_piep"     => (new zeile("w", "text"        , "Abschöpfung Piep"           , "0.00 19:42"     , "€ Zeitpunkt Abschöpfung Piep" )),
    "kassensturz"          => (new zeile("w", "text"        , "Kassensturz"                , "0.00 15:00"     , "€ Zeitpunkt Betrag in der Kasse [hk]" )),
    "arbeitszeit"          => (new zeile("w", "text"        , "Arbeitszeit"                , "8.81"           , "Arbeitszeit in Stunden dezimal" )),
    "verlasse"             => (new zeile("w", "text"        , "verlasse"                   , "21:20"          , "Verlasse tatsächlich den Arbeitsplatz" )),
    "computerausdruck"     => (new zeile("w", "text"        , "Computerausdruck"           , "21:04:51"       , "entsteht beim Abrechnen" )),
    "i_arbzeit_dauer"      => (new zeile("w", $dec          , "Abz. / h"                   , "8.00"           , "ZEG zeigt Arbeitszeit (Infotaste wurde gedückt" )),
    "i_arbzeit_datum"      => (new zeile("w", "text"        , "                 Datum"     , "03.03.16"       , "ZEG zeigt Datum des letzen Einsatzes" )),
    "i_saldo_dauer"        => (new zeile("w", $dec          , "     Saldo Dauer"           , "29.16"          , "ZEG zeigt Saldo" )),
    "i_saldo_datum"        => (new zeile("w", "text"        , "     Saldo Datum"           , "04.03.16"       , "ZEG zeigt Datum" )),
    "abweichung_kommen"    => (new zeile("w", "text"        , "Stech Anfang"               , "51"             , "ZEG Sekunde des Minutensprungs Kommen" )),
    "abweichung_gehen"     => (new zeile("w", "text"        , "      Ende"                 , "51"             , "ZEG Sekunde des Minutensprungs Gehen" )),
    "abweichung_pause1"    => (new zeile("w", "text"        , "      1.Pause"              , "51"             , "ZEG Sekunde des Minutensprungs 1.Pause" )),
    "abweichung_pause2"    => (new zeile("w", "text"        , "      2.Pause"              , "51"             , "ZEG Sekunde des Minutensprungs 2.Pause" )),
    "tagesendebon"         => (new zeile("w", "text"        , "Tagesendebon"               , "21:07"          , "Abrechnungsbon 50 Code Kassenwechsel oder Abmeldung" )),
    "händlerbons_hinten"   => (new zeile("w", "text"        , "Händlerbons hintere Kasse"  , "24,91 8692"     , "€ Bonnummer Kredikarte mit Unterschrift" )),
    "händlerbons_vorn"     => (new zeile("w", "text"        , "Händlerbons vordere Kasse"  , "83.51 2026"     , "€ Bonnummer Kredikarte mit Unterschrift" )),
    "händlerbons_mitte"    => (new zeile("w", "text"        , "Händlerbons mittlere Kasse" , "00.00   0 "     , "€ Bonnummer Kredikarte mit Unterschrift" )),
    "bar_ist"              => (new zeile("w", "text"        , "Bar Ist"                    , "2069.63"        , "€ Inhalt der Kasse" )),
    "umsatz_ist"           => (new zeile("w", "text"        , "Umsatz Ist"                 , "4210.61"        , "€ bedeutet??" )),
    "bemerkung"            => (new zeile("w", "text"        , "Bemerkung"                  , "text"           , "Bemerkung" )),
    "aktualisiert"         => (new zeile("r", "$aktuell"    , "aktualisiert"               , ""               , "Aktualisierungzeitpunkt automatisch eingetragen" )),
  );
    $this->gfos_zeitkonto = array (
    "id"                   ,
    "datum_auto"           ,
    "arbzeit_plan_anfang"  ,
    "arbeit_kommt"         ,
    "pause1_geht"          ,
    "pause1_kommt"         ,
    "pause2_geht"          ,
    "pause2_kommt"         ,
    "arbeit_geht"          ,
    "arbzeit_plan_ende"    ,
    "i_arbzeit_dauer"      ,
    "i_arbzeit_datum"      ,
    "i_saldo_dauer"        ,
    "i_saldo_datum"         
  );
    $this->kurzwahl = array (
    "id"                   ,
    "datum_auto"           ,
    "datum"                ,
    "arbzeit_plan_anfang"  ,
    "arbzeit_plan_ende"    ,
    "erscheine"            ,
    "arbeit_kommt"         ,
    "pause1_geht"          ,
    "pause1_kommt"         ,
    "pause2_geht"          ,
    "pause2_kommt"         ,
    "arbeit_geht"          ,
    "verlasse"             ,
    "i_saldo_dauer"        ,
    "i_saldo_datum"        ,
    "i_arbzeit_dauer"      ,
    "i_arbzeit_datum"      ,
    "bemerkung"            ,
    "aktualisiert"          
  );
    foreach ($this->kurzwahl as $wahl) {
      $this->kurzfelder[$wahl] = $this->felder[$wahl];
    }
  }
}

?>
<?php
/* 
  $erg = $conn->frage( 0, "CREATE TABLE IF NOT EXISTS zu_loeschen (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  firstname VARCHAR(30) NOT NULL,
  lastname VARCHAR(30) NOT NULL,
  email VARCHAR(50),
  reg_date TIMESTAMP
  ) ");
select id, datum, datum_auto, erscheine, count(erscheine) from zeiten where not erscheine like '%.%'   group by erscheine order by erscheine;
+-----+-------------+------------+-----------+------------------+
| id  | datum       | datum_auto | erscheine | count(erscheine) |
+-----+-------------+------------+-----------+------------------+
|  10 | 2016-02-09  | 2016-02-09 | BA        |                4 |
|  23 | 2016-02-23  | 2016-02-23 | BR        |                4 |
|  24 | 2016-02-24  | 2016-02-24 | BV        |                2 |
|  66 | 2016-01-01  | 2016-01-01 | Feiertag  |                4 |
|  18 | 2016-02-17  | 2016-02-17 | frei      |               25 |
| 130 | 2015-11-23  | 2015-11-23 | krank     |                1 |
|  37 | 2016-01-25  | 2016-01-25 | Urlaub    |               18 |
+-----+-------------+------------+-----------+------------------+
SELECT id,datum_auto,arbeit_kommt,arbeit_geht  FROM zeiten WHERE datum_auto >= '2016-01-25' and datum_auto < '2016-01-31' ORDER BY datum_auto;+----+------------+--------------+-------------+
| id | datum_auto | arbeit_kommt | arbeit_geht |
+----+------------+--------------+-------------+
| 37 | 2016-01-25 |              |             |
| 38 | 2016-01-26 |              |             |
| 39 | 2016-01-27 |              |             |
| 40 | 2016-01-28 |              |             |
| 41 | 2016-01-29 |              |             |
| 42 | 2016-01-30 |              |             |
+----+------------+--------------+-------------+
update zeiten set arbeit_kommt = '0.00', arbeit_geht = '5.55'  WHERE datum_auto >= '2016-01-25' and datum_auto < '2016-01-31';
update zeiten set arbeit_kommt = '0.00', arbeit_geht = '5.33' , arbzeit_plan_anfang = '0.00', arbzeit_plan_ende = '5.48' WHERE datum_auto >= '2016-01-25' and datum_auto < '2016-01-31';

update zeiten set i_saldo_dauer = null WHERE i_saldo_dauer = 0.0;
update zeiten set i_arbzeit_dauer = null WHERE i_arbzeit_dauer = 0.0;

alter table zeiten change i_saldo_dauer i_saldo_dauer decimal(5,2) default NULL;
alter table zeiten change i_arbzeit_dauer i_arbzeit_dauer decimal(5,2) default NULL;

alter table zeiten change gehe verlasse text;
alter table zeiten change datum_auto datum_auto date;
ALTER TABLE zeiten ADD datum_auto datetime AFTER id;

CREATE TABLE IF NOT EXISTS zeiten_bakup LIKE zeiten;
INSERT zeiten_bakup SELECT * FROM zeiten;
CREATE TABLE IF NOT EXISTS new_table LIKE $table_name;
INSERT new_table SELECT * FROM $table_name;
DELETE FROM $tafel WHERE selbst >= 5324;
ALTER TABLE $tafel AUTO_INCREMENT = 5324;
                            colname DATETIME DEFAULT CURRENT_DATETIME  ON UPDATE CURRENT_DATETIME  geht ab 5.6
ALTER TABLE zeiten ADD aktualsiert TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER bemerkung;
DELETE FROM $tafel WHERE selbst >= 5324;
ALTER TABLE $tafel AUTO_INCREMENT = 5324;

alter table zeiten change leergut_sack leergut_soe text;
alter table zeiten change sack1 leergut_sack1 text;
alter table zeiten change sack2 leergut_sack2 text;

alter table zeiten change arbeit_anfang abweichung_kommen text;
alter table zeiten change arbeit_ende   abweichung_gehen text;
alter table zeiten change pause1_zeiterfassung   abweichung_pause1 text;
alter table zeiten change pause2_zeiterfassung   abweichung_pause2 text;

alter table zeiten change saldo_dauer i_saldo_dauer text;
alter table zeiten change saldo_datum i_saldo_datum text;
alter table zeiten change arbzeit_ist_anfang arbeit_kommt text;
alter table zeiten change arbzeit_ist_ende arbeit_geht text;
drop table zeiten_backup;
CREATE TABLE IF NOT EXISTS zeiten_backup LIKE zeiten;
INSERT zeiten_backup SELECT * FROM zeiten;
 */
?>
<?php
/*
"datetime"  datum = 9.3                        
"datetime"  erscheine = 5.55
"text    "  sitzt = slopianka
"datetime"  nehme = 6.45
"int(11) "  ss = 1
"text    "  erhalte = 599.86 6.48
"int(11) "  rollen = 111
"text    "  wechselgeld = 111 12.44
"int(11) "  schein100 = 4
"int(11) "  schein200 =
"int(11) "  schein500 =
"int(11) "  bonstorno =
"int(11) "  retouren = 7
"int(11) "  nullbon = 2
"int(11) "  kunden = 403
"datetime"  abrechnungsbon = 15.6
"datetime"  einkaufdurchschnitt = 14.85
"datetime"  ec_karte = 1842.79
"int(11) "  ec_kunden = 71
"int(11) "  pos_je_stunde = 2754
"text    "  kasse_wartezeit = 24/8
"int(11) "  Manko = -1.06
"int(11) "  leergut_auszahlung = 137.25
"int(11) "  leergut_sack =
"int(11) "  sack1 =
"int(11) "  sack2 =
"int(11) "  leergut_anzahl = 549
"int(11) "  pfand = 176.75
"text    "  pause1_eigene_uhr = 10.9 10.24
"text    "  pause2_eigene_uhr = 13.25 13.40
"text    "  arbeitszeit_plan = 6.45 15.15 8
"text    "  arbeitszeit_ist = 6.45 15.15 8
"int(11) "  zeitgutschrift_20 =
"int(11) "  nachtzuschlag_50 =
"int(11) "  zuschlag_summe =
"int(11) "  mehrarbeit =
"text    "  abschöpfung_bar =
"text    "  abschöpfung_safebag =
"text    "  abschöpfung_piep = 801 12.7
"text    "  kassensturz =
"int(11) "  arbeitszeit = 8
"datetime"  gehe = 15.20
"datetime"  computerausdruck = 15.11.44
"text    "  arbeitszeit_stunden = 6 5.3.16
"text    "  salden = 5.36 9.3.16
"text    "  arbeit_anfang = 0.49
"text    "  arbeit_ende = 0.46
"text    "  pause1_zeiterfassung = 10.8 0.49
"text    "  pause2_zeiterfassung = 13.28 0.46
"datetime"  tagesendebon =
"text    "  händlerbons_hinten = 4.48 2130
"text    "  händlerbons_vorn =
"text    "  händlerbons_mitte =
"int(11) "  bar_ist = 4891.26
"int(11) "  umsatz_ist = 6023.19             
"text    "  bemerkung
 */
?>
