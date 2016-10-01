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
  public $typ;
  public $label;
  public $muster;
  public $bedeutung;
  function __construct ( $typ, $label, $muster, $bedeutung) {
    $this->typ       = $typ;
    $this->label     = $label;
    $this->muster    = $muster;
    $this->bedeutung = $bedeutung;
    #printf( "M010 m%s l%s<br />\n", $this->typ, $this->label, $this->muster, $this->bedeutung);
  }
}

class tabelle {
  public $felder;
  function __construct() {
    $id_eigsch = "INT(11) AUTO_INCREMENT PRIMARY KEY";
    $aktuell = "TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
  $this->felder = array (
    "id"                   => (new zeile( $id_eigsch, "Id"                         , ""                   , "Automatisch vergeben ID")),
    "datum"                => (new zeile( "text    ", "Datum"                      , "04.03"              , "Tag der Arbeitsleistung")),
    "erscheine"            => (new zeile( "text    ", "Erscheine"                  , "12:12"              , "ich erscheine in der Vst")),
    "sitzt_oeffnet"        => (new zeile( "text    ", "sitzt oder öffnet"          , "Hönow"              , "wer sitzt in einer Kasse oder öffnet den Laden" )),
    "tageseroeffnung"      => (new zeile( "text    ", "Tageseröffnung"             , "06:30"              , "Kassen hochgefahren, jede Kasse druckt Bon" )),
    "nehme_arbeit_auf"     => (new zeile( "text    ", "nehme"                      , "12.45"              , "ZEG nehme die Arbeit auf" )),
    "starterset"           => (new zeile( "text    ", "SS Starterset"              , "3"                  , "Anzahl Alditalk-Startersets zum Verkauf" )),
    "erhalte"              => (new zeile( "text    ", "erhalte"                    , "286.00 13:06"       , "€ Zeit in der Kassenlade Zeit" )),
    "rollen"               => (new zeile( "text    ", "davon Rollen"               , "186.00"             , "€ Rollen in der Kassenlade" )),
    "wechselgeld"          => (new zeile( "text    ", "Wechselgeld"                , "186.00 14:03"       , "€ Zeit im Lauf des Tages erhalten" )),
    "schein100"            => (new zeile( "text    ", "100"                        , "1"                  , "Anzahl der eingenommenen 100-€-Scheine" )),
    "schein200"            => (new zeile( "text    ", "200"                        , "0"                  , "Anzahl der eingenommenen 200-€-Scheine" )),
    "schein500"            => (new zeile( "text    ", "500"                        , "0"                  , "Anzahl der eingenommenen 500-€-Scheine" )),
    "bonstorno"            => (new zeile( "text    ", "Bonstorno"                  , "0"                  , "Anzahl der Bonstornos" )),
    "retouren"             => (new zeile( "text    ", "Retouren"                   , "1"                  , "Anzahl der Retouren" )),
    "nullbon"              => (new zeile( "text    ", "Nullbon"                    , "2"                  , "Anzahl der Nullbons" )),
    "kunden"               => (new zeile( "text    ", "Kunden"                     , "250"                , "Anzahl der Kunden" )),
    "abrechnungsbon"       => (new zeile( "text    ", "Abrechnungsbon"             , "21:05"              , "Kassiererin meldet sich an der Kasse ab: Bon" )),
    "einkaufdurchschnitt"  => (new zeile( "text    ", "DE Durchschnitt"            , "16.80"              , "€ Durchschnittlicher Einkauf" )),
    "ec_karte"             => (new zeile( "text    ", "EC"                         , "2089.98"            , "€ EC-Karte" )),
    "ec_kunden"            => (new zeile( "text    ", "EC-Kunden"                  , "58"                 , "Anzahl der EC-Zahlungen" )),
    "pos_je_stunde"        => (new zeile( "text    ", "Pos je Stunde"              , "2907"               , "Positionen je Stunde, Kassenleistung" )),
    "kasse_wartezeit"      => (new zeile( "text    ", "Kasse/Wartezeit"            , "22/8"               , "bedeutet??" )),
    "Manko"                => (new zeile( "text    ", "Manko"                      , "+0.00"              , "€ Zu viel + oder zu wenig - in der Kasse" )),
    "leergut_auszahlung"   => (new zeile( "text    ", "Leergut Auszahlung"         , "138.75"             , "€ An Kunden ausgezahles Pfand" )),
    "leergut_sack"         => (new zeile( "text    ", "Leergut SoE"                , "5"                  , "Leergut Sack oder Einzelflasche" )),
    "sack1"                => (new zeile( "text    ", "1. Sack"                    , "1"                  , "Sackanzahl und Flaschenanzahl Einzelflaschen" )),
    "sack2"                => (new zeile( "text    ", "2. Sack"                    , "0"                  , "Sackanzahl und Flaschenanzahl Einzelflaschen" )),
    "leergut_anzahl"       => (new zeile( "text    ", "Leergut Anzahl"             , "555"                , "Anzahl der Leergut-Flaschen und -Dosen" )),
    "pfand"                => (new zeile( "text    ", "Pfand"                      , "148.25"             , "€ bedeutet??" )),
    "pause1_geht"          => (new zeile( "text    ", "1.Pause Geht"               , "16:17"              , "Pause beginnt" )),
    "pause1_kommt"         => (new zeile( "text    ", "1.Pause Kommt"              , "16.32"              , "Pause endet" )),
    "pause2_geht"          => (new zeile( "text    ", "2.Pause Geht"               , "19:01"              , "Pause beginnt" )),
    "pause2_kommt"         => (new zeile( "text    ", "2.Pause Kommt"              , "19:16"              , "Pause endet" )),
    "arbzeit_plan_dauer"   => (new zeile( "text    ", "Geplante Dauer"             , "8.00"               , "Geplante Arbeitszeit Dauer in h dezimal" )),
    "arbzeit_plan_anfang"  => (new zeile( "text    ", "Geplanter Anfang"           , "12:45"              , "Geplante Arbeitszeit Anfangszeitpunkt" )),
    "arbzeit_plan_ende"    => (new zeile( "text    ", "Geplantes Ende"             , "21:15"              , "Geplante Arbeitszeit Endzeitpunkt" )),
    "arbeit_kommt"         => (new zeile( "text    ", "Tatsächlich Anfang"         , "12:45"              , "ZEG Anfang des Einsatzes tatsächlich" )),
    "arbeit_geht"          => (new zeile( "text    ", "Tatsächlich Ende"           , "21:10"              , "ZEG Ende des Einsatzes tatsächlich" )),
    "zeitgutschrift_20"    => (new zeile( "text    ", "ZEG 20%%"                   , "0.30"               , "h 20%% Zeitgutschrift von 18.30 bis 20.00 Uhr" )),
    "nachtzuschlag_50"     => (new zeile( "text    ", "ZEG 50%%"                   , "0.59"               , "h 50%% Zeitgutschrift ab 20.00 Uhr" )),
    "zuschlag_summe"       => (new zeile( "text    ", "Mehr"                       , "0.89"               , "h Summe der Zeitgutschriften ab 18.30 und 20.00" )),
    "mehrarbeit"           => (new zeile( "text    ", "Gesamt / Mehr"              , "0.00"               , "h bedeutet?? Mehrarbeit + ZEG + Nachtzuschlag" )),
    "abschöpfung_bar"      => (new zeile( "text    ", "Abschöpfung Bar"            , "0.00 14:00"         , "€ Zeitpunkt Abschöpfung Bar" )),
    "abschöpfung_safebag"  => (new zeile( "text    ", "Abschöpfung Safebag"        , "0.00 15:00"         , "€ Zeitpunkt Abschöpfung Safebag" )),
    "abschöpfung_piep"     => (new zeile( "text    ", "Abschöpfung Piep"           , "0.00 19:42"         , "€ Zeitpunkt Abschöpfung Piep" )),
    "kassensturz"          => (new zeile( "text    ", "Kassensturz"                , "0.00 15:00"         , "€ Zeitpunkt Betrag in der Kasse [hk]" )),
    "arbeitszeit"          => (new zeile( "text    ", "Arbeitszeit"                , "8.81"               , "Arbeitszeit in Stunden dezimal" )),
    "gehe"                 => (new zeile( "text    ", "gehe"                       , "21:20"              , "bedeutet??" )),
    "computerausdruck"     => (new zeile( "text    ", "Computerausdruck"           , "21:04:51"           , "entsteht beim Abrechnen" )),
    "i_arbzeit_dauer"      => (new zeile( "text    ", "Arbeitszeit Dauer Info "     , "8.00"               , "Drücke Infotaste" )),
    "i_arbzeit_datum"      => (new zeile( "text    ", "                 Datum"     , "03.03.16"           , "ZEG zeigt Dauer und Datum des letzen Einsatzes" )),
    "saldo_dauer"          => (new zeile( "text    ", "     Saldo Dauer"           , "29.16"              , "ZEG zeigt Saldo und Datum" )),
    "saldo_datum"          => (new zeile( "text    ", "     Saldo Datum"           , "04.03.16"           , "ZEG zeigt Saldo und Datum" )),
    "arbeit_anfang"        => (new zeile( "text    ", "Stech Anfang"               , "51"                 , "ZEG Sekunde des Minutensprungs Kommen" )),
    "arbeit_ende"          => (new zeile( "text    ", "      Ende"                 , "51"                 , "ZEG Sekunde des Minutensprungs Gehen" )),
    "pause1_zeiterfassung" => (new zeile( "text    ", "      1.Pause"              , "51"                 , "ZEG Sekunde des Minutensprungs 1.Pause" )),
    "pause2_zeiterfassung" => (new zeile( "text    ", "      2.Pause"              , "51"                 , "ZEG Sekunde des Minutensprungs 2.Pause" )),
    "tagesendebon"         => (new zeile( "text    ", "Tagesendebon"               , "21:07"              , "Abrechnungsbon 50 Code Kassenwechsel oder Abmeldung" )),
    "händlerbons_hinten"   => (new zeile( "text    ", "Händlerbons hintere Kasse"  , "24,91 8692"         , "€ Bonnummer Kredikarte mit Unterschrift" )),
    "händlerbons_vorn"     => (new zeile( "text    ", "Händlerbons vordere Kasse"  , "83.51 2026"         , "€ Bonnummer Kredikarte mit Unterschrift" )),
    "händlerbons_mitte"    => (new zeile( "text    ", "Händlerbons mittlere Kasse" , "00.00   0 "         , "€ Bonnummer Kredikarte mit Unterschrift" )),
    "bar_ist"              => (new zeile( "text    ", "Bar Ist"                    , "2069.63"            , "€ Inhalt der Kasse" )),
    "umsatz_ist"           => (new zeile( "text    ", "Umsatz Ist"                 , "4210.61"            , "€ bedeutet??" )),
    "bemerkung"            => (new zeile( "text    ", "Bemerkung"                  , "bemerkung"          , "Bemerkung" )),
    "aktualisiert"         => (new zeile( "$aktuell", "aktualisiert"               , "aktualisiert"       , "Aktualisierungzeitpunkt automatisch eingetragen" )),
    );
  }
}

?>
<?php
/* alter table zeiten change arbzeit_ist_anfang arbeit_kommt text;
 * alter table zeiten change arbzeit_ist_ende arbeit_geht text;
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
