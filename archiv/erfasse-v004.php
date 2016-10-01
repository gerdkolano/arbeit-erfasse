<?php
require_once( "helfer.php");

function main () {
  if (php_sapi_name()==="cli") { // von der Kommandozeile gerufen
  #if (false) { // zum Testen
    // echo count($_SERVER['argv']) . "\n";
    $start = "";
    $stop  = "";
    // foreach geht nicht
    while ( $arg = next( $_SERVER['argv'])) {
      switch ($arg) {
      case "-a": $start = next ($_SERVER['argv']); break;
      case "-e": $stop  = next ($_SERVER['argv']); break;
      default : echo "arg $arg\n"; break;
      }
    }
    arbeite( $start, $stop);
  } else {
    printf( "<!DOCTYPE html>\n");
    printf( "<html>\n");
    printf( "<head>\n");
    printf( "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n");
    printf( "</head>\n");
    printf( "<body>\n");
    printf( "<pre>\n");
    #php_sapi_name()==="cli";
    #printf( "%s\n", php_sapi_name());
    # echo count($_GET) . "<br />\n";
    # echo "<pre>"; print_r( $_GET); echo "</pre>";
    $start = "";
    $stop  = "";
    #while ( $arg = next( $_GET)) {
    foreach ( $_GET as $key=>$arg) {
      # echo "<pre>"; print_r( $arg); echo "</pre>";
      switch ($key) {
      case "start": $start = $arg; break;
      case "stop" : $stop  = $arg; break;
      default : echo "arg $arg\n"; break;
      }
    }
    printf( "</pre>\n");
    arbeite( $start, $stop);
    printf( "</body>\n");
    printf( "</html>\n");
  }
}

class ein_tag {
  function __construct () {
  }
}

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

function arbeite( $start="", $stop="") {
  $felder = array (
    "datum"                => (new zeile( "datetime", "Datum"                      , "04.03"              , "Tag der Arbeitsleistung")),
    "erscheine"            => (new zeile( "datetime", "Erscheine"                  , "12:12"              , "ich erscheine in der Vst")),
    "sitzt"                => (new zeile( "text    ", "sitzt"                      , "Hönow"              , "wer sitzt in einer Kasse" )),
    "nehme"                => (new zeile( "datetime", "nehme"                      , "12.45"              , "bedeutet??" )),
    "ss"                   => (new zeile( "int(11) ", "ss"                         , "3"                  , "bedeutet??" )),
    "erhalte"              => (new zeile( "text    ", "erhalte"                    , "286.00 13:06"       , "€ in der Kassenlade Zeit" )),
    "rollen"               => (new zeile( "int(11) ", "davon Rollen"               , "186.00"             , "€ Rollen in der Kassenlade" )),
    "wechselgeld"          => (new zeile( "text    ", "Wechselgeld"                , "186.00 14:03"       , "€ ?? Zeit" )),
    "schein100"            => (new zeile( "int(11) ", "100"                        , "1"                  , "Anzahl der 100-€-Scheine" )),
    "schein200"            => (new zeile( "int(11) ", "200"                        , "0"                  , "Anzahl der 200-€-Scheine" )),
    "schein500"            => (new zeile( "int(11) ", "500"                        , "0"                  , "Anzahl der 500-€-Scheine" )),
    "bonstorno"            => (new zeile( "int(11) ", "Bonstorno"                  , "0"                  , "Anzahl der Bonstornos" )),
    "retouren"             => (new zeile( "int(11) ", "Retouren"                   , "1"                  , "Anzahl der Retouren" )),
    "nullbon"              => (new zeile( "int(11) ", "Nullbon"                    , "2"                  , "Anzahl der Nullbons" )),
    "kunden"               => (new zeile( "int(11) ", "Anzahl Kunden"              , "250"                , "Anzahl der Kunden" )),
    "abrechnungsbon"       => (new zeile( "datetime", "Abrechnungsbon"             , "21:05"              , "bedeutet??" )),
    "einkaufdurchschnitt"  => (new zeile( "datetime", "DE Durchschnitt"            , "16.80"              , "€ Durchschnittlicher Einkauf" )),
    "ec_karte"             => (new zeile( "datetime", "EC"                         , "2089.98"            , "€ EC-Karte" )),
    "ec_kunden"            => (new zeile( "int(11) ", "EC-Kunden"                  , "58"                 , "Anzahl der EC-Zahlungen" )),
    "pos_je_stunde"        => (new zeile( "int(11) ", "Pos/h"                      , "2907"               , "Positionen je Stunde, Kassenleistung" )),
    "kasse_wartezeit"      => (new zeile( "text    ", "Kasse/Wartezeit"            , "22/8"               , "bedeutet??" )),
    "Manko"                => (new zeile( "int(11) ", "Manko"                      , "+0.00"              , "€ Zu viel + oder zu wenig - in der Kasse" )),
    "leergut_auszahlung"   => (new zeile( "int(11) ", "Leergut Auszahlung"         , "138.75"             , "€ An Kunden ausgezahles Pfand" )),
    "leergut_sack"         => (new zeile( "int(11) ", "Leergut SoE"                , ""                   , "Leergut Sack oder Einzelflasche" )),
    "sack1"                => (new zeile( "int(11) ", "1. Sack"                    , ""                   , "bedeutet??" )),
    "sack2"                => (new zeile( "int(11) ", "2. Sack"                    , ""                   , "bedeutet??" )),
    "leergut_anzahl"       => (new zeile( "int(11) ", "Leergut Anzahl"             , "555"                , "Anzahl der Leergut-Flaschen und -Dosen" )),
    "pfand"                => (new zeile( "int(11) ", "Pfand"                      , "148.25"             , "€ bedeutet??" )),
    "pause1_eigene_uhr"    => (new zeile( "text    ", "1.Pause Eigene Uhr"         , "16:17 - 16.32"      , "bedeutet??" )),
    "pause2_eigene_uhr"    => (new zeile( "text    ", "2.Pause Eigene Uhr"         , "19:01 - 19:16"      , "bedeutet??" )),
    "arbeitszeit_plan"     => (new zeile( "text    ", "Geplante Arbeitszeit"       , "8.00 12:45 - 21:15" , "Arbeitszeit in Stunden dezimal Anfang Ende" )),
    "arbeitszeit_ist"      => (new zeile( "text    ", "Tatsächliche Arbeitszeit"   , "12:45 - 21:10"      , "von Zeitpunkt - bis Zeitpunkt" )),
    "zeitgutschrift_20"    => (new zeile( "int(11) ", "ZEG 20%%"                   , "0.30"               , "h 20%% Zeitgutschrift von 18.30 bis 20.00 Uhr" )),
    "nachtzuschlag_50"     => (new zeile( "int(11) ", "ZEG 50%%"                   , "0.59"               , "h 50%% Zeitgutschrift ab 20.00 Uhr" )),
    "zuschlag_summe"       => (new zeile( "int(11) ", "Mehr"                       , "0.89"               , "h Summe der Zeitgutschriften von 18.30 bis Arbeitsende" )),
    "mehrarbeit"           => (new zeile( "int(11) ", "Gesamt / Mehr"              , "0.00"               , "h bedeutet?? Mehrarbeit + ZEG + Nachtzuschlag" )),
    "abschöpfung_bar"      => (new zeile( "text    ", "Abschöpfung Bar"            , "0.00 14:00"         , "€ Zeitpunkt Abschöpfung Bar" )),
    "abschöpfung_safebag"  => (new zeile( "text    ", "Abschöpfung Safebag"        , "0.00 15:00"         , "€ Zeitpunkt Abschöpfung Safebag" )),
    "abschöpfung_piep"     => (new zeile( "text    ", "Abschöpfung Piep"           , "0.00 19:42"         , "€ Zeitpunkt Abschöpfung Piep" )),
    "kassensturz"          => (new zeile( "text    ", "Kassensturz"                , "0.00 15:00"         , "€ Zeitpunkt Betrag in der Kasse [hk]" )),
    "arbeitszeit"          => (new zeile( "int(11) ", "Arbeitszeit"                , "8.81"               , "Arbeitszeit in Stunden dezimal" )),
    "gehe"                 => (new zeile( "datetime", "gehe"                       , "21:20"              , "bedeutet??" )),
    "computerausdruck"     => (new zeile( "datetime", "Computerausdruck"           , "21:04:51"           , "bedeutet??" )),
    "arbeitszeit_stunden"  => (new zeile( "text    ", "Arbeitszeit in h"           , "8.00 h 03.03.16"    , "bedeutet??" )),
    "salden"               => (new zeile( "text    ", "Salden"                     , "29.16 h 04.03.16"   , "bedeutet??" )),
    "arbeit_anfang"        => (new zeile( "text    ", "Anfang"                     , "50 12:12"           , "Sekunde des Minutensprungs ZEG Kommen" )),
    "arbeit_ende"          => (new zeile( "text    ", "Ende"                       , "50 21:10"           , "Sekunde des Minutensprungs ZEG Gehen" )),
    "pause1_zeiterfassung" => (new zeile( "text    ", "1.Pause"                    , "50"                 , "Sekunde des Minutensprungs 1.Pause" )),
    "pause2_zeiterfassung" => (new zeile( "text    ", "2.Pause"                    , "50 21.14"           , "Sekunde des Minutensprungs 2.Pause" )),
    "tagesendebon"         => (new zeile( "datetime", "Tagesendebon"               , "21:07"              , "bedeutet??" )),
    "händlerbons_hinten"   => (new zeile( "text    ", "Händlerbons hintere Kasse"  , "24,91 8692"         , "€ Bonnummer hintere, vordere, mittlere Kasse" )),
    "händlerbons_vorn"     => (new zeile( "text    ", "Händlerbons vordere Kasse"  , "83.51 2026"         , "bedeutet??" )),
    "händlerbons_mitte"    => (new zeile( "text    ", "Händlerbons mittlere Kasse" , "00.00   0 "         , "bedeutet??" )),
    "bar_ist"              => (new zeile( "int(11) ", "Bar Istwert"                , "2069.63"            , "€ bedeutet??" )),
    "umsatz_ist"           => (new zeile( "int(11) ", "Umsatz Istwert"             , "4210.61"            , "€ bedeutet??" )),
    "bemerkung"            => (new zeile( "text    ", "Bemerkung"                  , "bemerkung"          , "Bemerkung" )),
  );
# echo "<pre>"; print_r( $felder); echo "!</pre><br />\n";

  $erg = "";
  $erg .= "<style>  tr td:nth-child(1) {text-align: right;}  </style>\n";

  $submit_speichern = "speichern"; $submit_label = "Speichern"; $submit_inhalt = "gesandt";
  $erg .= "  <tr><td><button type=\"SUBMIT\" name=\"$submit_speichern\" value=\"$submit_inhalt\"> $submit_label </button>\n";
  // printf( "M010 label %s muster %s bedeutung %s<br />\n", $felder["datum"]->label, $felder["datum"]->muster, $felder["datum"]->bedeutung);

  foreach ($felder as $column => $inhalt) {
    $postname = "$column";
    $erg .= "  <tr><td>$inhalt->label<td><input type=\"TEXT\" name=\"$postname\" size=\"17\" value='' >";
    $erg .= "<td>" . $inhalt->muster;
    $erg .= "<td>" . $inhalt->bedeutung . "\n"; 
    //printf( "%s %s<br />\n", $column, $inhalt);
  }

  $erg .= "  <tr><td><button type=\"SUBMIT\" name=\"$submit_speichern\" value=\"$submit_inhalt\"> $submit_label </button>\n";
  $actionskript = "speichere.php";
  $farbe = "#fff0f0";
  $erg = "<table style=\"background-color:$farbe\">\n$erg</table>";
  printf( "<form method=\"POST\" action=\"$actionskript\">\n$erg\n</form>\n", "");
}

main();
?>
