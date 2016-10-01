<?php
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
  } else {
    printf( "<!DOCTYPE html>\n");
    printf( "<html>\n");
    printf( "<head>\n");
    printf( "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n");
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
  }
  arbeite( $start, $stop);
}

class ein_tag {
  function __construct () {
  }
}

class zeile {
  public $label;
  public $muster;
  public $bedeutung;
  function __construct ( $label, $muster, $bedeutung) {
    $this->label     = $label;
    $this->muster    = $muster;
    $this->bedeutung = $bedeutung;
    #printf( "M010 m%s l%s<br />\n", $this->$muster, $this->$label);
  }
}

function arbeite( $start="", $stop="") {

  $felder = array (
    "datum"                    => (new zeile( "Datum"                                 , "04.03"                               , "Tag der Arbeit")),
    "erscheine"                => (new zeile( "Erscheine"                             , "12:12"                               , "Eintreffe Vst")),
    "datum"                    => (new zeile( "Datum"                                 , "04.03"                               , "Bedeutung" )),
    "erscheine"                => (new zeile( "erscheine"                             , "12:12"                               , "Bedeutung" )),
    "sitzt"                    => (new zeile( "sitzt"                                 , "Hönow"                               , "Bedeutung" )),
    "nehme"                    => (new zeile( "nehme"                                 , "12.45"                               , "Bedeutung" )),
    "ss"                       => (new zeile( "ss"                                    , "3"                                   , "Bedeutung" )),
    "erhalte"                  => (new zeile( "erhalte"                               , "286.00 13:06"                        , "Bedeutung" )),
    "rollen"                   => (new zeile( "davon Rollen"                          , "186.00"                              , "Bedeutung" )),
    "wechselgeld"              => (new zeile( "Wechselgeld"                           , "186.00 14:03"                        , "Bedeutung" )),
    "schein100"                => (new zeile( "100"                                   , "1"                                   , "Bedeutung" )),
    "schein200"                => (new zeile( "200"                                   , "0"                                   , "Bedeutung" )),
    "schein500"                => (new zeile( "500"                                   , "0"                                   , "Bedeutung" )),
    "bonstorno"                => (new zeile( "Bonstorno"                             , "0"                                   , "Bedeutung" )),
    "retouren"                 => (new zeile( "Retouren"                              , "1"                                   , "Bedeutung" )),
    "nullbon"                  => (new zeile( "Nullbon"                               , "2"                                   , "Bedeutung" )),
    "kunden"                   => (new zeile( "Anzahl Kunden"                         , "250"                                 , "Bedeutung" )),
    "abrechnungsbon"           => (new zeile( "Abrechnungsbon"                        , "21:05"                               , "Bedeutung" )),
    "einkaufdurchschnitt"      => (new zeile( "Durchschnittlicher Einkauf D/"         , "16.80 €"                             , "Bedeutung" )),
    "ec_karte"                 => (new zeile( "EC"                                    , "2089.98 €"                           , "Bedeutung" )),
    "ec_kunden"                => (new zeile( "EC-Kunden"                             , "58"                                  , "Bedeutung" )),
    "pos_je_stunde"            => (new zeile( "Pos/h"                                 , "2907"                                , "Bedeutung" )),
    "kasse_wartezeit"          => (new zeile( "Kasse/Wartezeit"                       , "22/8"                                , "Bedeutung" )),
    "Manko"                    => (new zeile( "Manko"                                 , "+0.00"                               , "Bedeutung" )),
    "leergut_auszahlung"       => (new zeile( "Leergut Auszahlung"                    , "138.75 €"                            , "Bedeutung" )),
    "leergut_sack"             => (new zeile( "Leergut Sack oder Einzelflasche s.o.e" , ""                                    , "Bedeutung" )),
    "sack1"                    => (new zeile( "1. Sack"                               , ""                                    , "Bedeutung" )),
    "sack2"                    => (new zeile( "2. Sack"                               , ""                                    , "Bedeutung" )),
    "leergut_anzahl"           => (new zeile( "Leergut Anzahl Flaschen und Dosen"     , "555"                                 , "Bedeutung" )),
    "pfand"                    => (new zeile( "Pfand"                                 , "148.25 €"                            , "Bedeutung" )),
    "pause1_eigene_uhr"        => (new zeile( "1.Pause Eigene Uhr"                    , "16:17 - 16.32"                       , "Bedeutung" )),
    "pause2_eigene_uhr"        => (new zeile( "2.Pause Eigene Uhr"                    , "19:01 - 19:16"                       , "Bedeutung" )),
    "arbeitszeit_geplant"      => (new zeile( "Geplante Arbeitszeit"                  , "8.00 h 12:45 - 21:15"                , "Bedeutung" )),
    "arbeitszeit_tatsächlich"  => (new zeile( "Tatsächliche Arbeitszeit"              ,"12:45 - 21:10"                        , "Bedeutung" )),
    "zeitgutschrift_20"        => (new zeile( "ZEG Zeitgutschrift 20%%"               , "0.30 h"                              , "Bedeutung" )),
    "nachtzuschlag_50"         => (new zeile( "Nachtzuschlag 50%%"                    , "0.59 h"                              , "Bedeutung" )),
    "zusammen"                 => (new zeile( "Zusammen"                              , "0.89 h"                              , "Bedeutung" )),
    "mehrarbeit"               => (new zeile( "Mehrarbeit"                            , "0.00 h"                              , "Bedeutung" )),
    "mehrarbeit+zeg+nacht"     => (new zeile( "Mehrarbeit+ZEG+Nachtzuschlag"          , "0.89 h"                              , "Bedeutung" )),
    "abschöpfung_bar"          => (new zeile( "Abschöpfung Bar"                       ,"0.00 € 14:00"                         , "Bedeutung" )),
    "abschöpfung_safebag"      => (new zeile( "Abschöpfung Safebag"                   ,"0.00 € 15:00"                         , "Bedeutung" )),
    "abschöpfung_piep"         => (new zeile( "Abschöpfung Piep"                      ,"0 Piep-aus 19:42"                     , "Bedeutung" )),
    "kassensturz"              => (new zeile( "Kassensturz"                           , "0.00 €   15:00 HK oder nicht HK"     , "Bedeutung" )),
    "arbeitszeit"              => (new zeile( "Arbeitszeit"                           , "8.81 h"                              , "Bedeutung" )),
    "gehe"                     => (new zeile( "gehe"                                  , "21:20"                               , "Bedeutung" )),
    "computerausdruck"         => (new zeile( "Computerausdruck"                      , "21:04:51"                            , "Bedeutung" )),
    "arbeitszeit_stunden"      => (new zeile( "Arbeitszeit in h"                      , "8.00 h 03.03.16"                     , "Bedeutung" )),
    "salden"                   => (new zeile( "Salden"                                , "29.16 h 04.03.16"                    , "Bedeutung" )),
    "arbeit_anfang"            => (new zeile( "Anfang"                                , "50 12:12"                            , "Bedeutung" )),
    "arbeit_ende"              => (new zeile( "Ende"                                  , "50 21:10"                            , "Bedeutung" )),
    "pause1_zeiterfassung"     => (new zeile( "1.Pause"                               , "50"                                  , "Bedeutung" )),
    "pause2_zeiterfassung"     => (new zeile( "2.Pause"                               , "50 21.14"                            , "Bedeutung" )),
    "tagesendebon"             => (new zeile( "Tagesendebon"                          , "21:07"                               , "Bedeutung" )),
    "händlerbons_hinten"       => (new zeile( "Händlerbons hintere Kasse"             ,"24,91 € Bonnummer 8692 hintere Kasse" , "Bedeutung" )),
    "händlerbons_vorn"         => (new zeile( "Händlerbons vordere Kasse"             ,"83.51 € Bonnummer 2026 vordere Kasse" , "Bedeutung" )),
    "händlerbons_mitte"        => (new zeile( "Händlerbons mittlere Kasse"            ,"00.00 € Bonnummer   0 mittlere Kasse" , "Bedeutung" )),
    "bar_ist"                  => (new zeile( "Bar Istwert"                           , "2069.63 €"                           , "Bedeutung" )),
    "umsatz_ist"               => (new zeile( "Umsatz Istwert"                        , "4210.61 €"                           , "Bedeutung" ))
  );
# echo "<pre>"; print_r( $felder); echo "!</pre><br />\n";

printf( "M010 m%s l%s<br />\n", $felder["datum"]->muster, $felder["datum"]->label);

  $muster = array (
    "datum"                    => "04.03",                                                #
    "erscheine"                => "12:12",                                                #
    "sitzt"                    => "Hönow",                                                #
    "nehme"                    => "12.45",                                                #
    "ss"                       => "3",                                                    #
    "erhalte"                  => "286.00 13:06",                                         #
    "rollen"                   => "186.00",                                               #
    "wechselgeld"              => "186.00 14:03",                                         #
    "schein100"                => "1",                                                    #
    "schein200"                => "0",                                                    #
    "schein500"                => "0",                                                    #
    "bonstorno"                => "0",                                                    #
    "retouren"                 => "1",                                                    #
    "nullbon"                  => "2",                                                    #
    "kunden"                   => "250",                                                  #
    "abrechnungsbon"           => "21:05",                                                #
    "einkaufdurchschnitt"      => "16.80 €",                                              #
    "ec_karte"                 => "2089.98 €",                                            #
    "ec_kunden"                => "58",                                                   #
    "pos_je_stunde"            => "2907",                                                 #
    "kasse_wartezeit"          => "22/8",                                                 #
    "Manko"                    => "+0.00",                                                #
    "leergut_auszahlung"       => "138.75 €",                                             #
    "leergut_sack"             => "",                                                     #
    "sack1"                    => "",                                                     #
    "sack2"                    => "",                                                     #
    "leergut_anzahl"           => "555",                                                  #
    "pfand"                    => "148.25 €",                                             #
    "pause1_eigene_uhr"        => "16:17 - 16.32",                                        #
    "pause2_eigene_uhr"        => "19:01 - 19:16",                                        #
    "arbeitszeit_geplant"      => "8.00 h 12:45 - 21:15",                                 #
    "arbeitszeit_tatsächlich"  => "12:45 - 21:10",                                        #
    "zeitgutschrift_20"        => "0.30 h",                                               #
    "nachtzuschlag_50"         => "0.59 h",                                               #
    "zusammen"                 => "0.89 h",                                               #
    "mehrarbeit"               => "0.00 h",                                               #
    "mehrarbeit+zeg+nacht"     => "0.89 h",                                               #
    "abschöpfung_bar"          => "0.00 € 14:00",                                         #
    "abschöpfung_safebag"      => "0.00 € 15:00",                                         #
    "abschöpfung_piep"         => "0 Piep-aus 19:42",                                     #
    "kassensturz"              => "0.00 €   15:00 HK oder nicht HK",                      #
    "arbeitszeit"              => "8.81 h",                                               #
    "gehe"                     => "21:20",                                                #
    "computerausdruck"         => "21:04:51",                                             #
    "arbeitszeit_stunden"      => "8.00 h 03.03.16", # letzvorangegangener Arbeitstag     #
    "salden"                   => "29.16 h 04.03.16",                                     #
    "arbeit_anfang"            => "50 12:12",                                             #
    "arbeit_ende"              => "50 21:10",                                             #
    "pause1_zeiterfassung"     => "50",                                                   #
    "pause2_zeiterfassung"     => "50 21.14",                                             #
    "tagesendebon"             => "21:07",                                                #
    "händlerbons_hinten"       => "24,91 € Bonnummer 8692 hintere Kasse",                 #
    "händlerbons_vorn"         => "83.51 € Bonnummer 2026 vordere Kasse",                 #
    "händlerbons_mitte"        => "00.00 € Bonnummer   0 mittlere Kasse",                 #
    "bar_ist"                  => "2069.63 €",                                            #
    "umsatz_ist"               => "4210.61 €",                                            #
  );
  $felder_kurz = array (
    "datum"                                 => "Datum"                                 ,
    "erscheine"                             => "erscheine"                             ,
    "sitzt"                                 => "sitzt"                                 ,
    "nehme"                                 => "nehme"                                 ,
    "ss"                                    => "ss"                                    ,
    "erhalte"                               => "erhalte"                               ,
    "rollen"                                => "davon Rollen"                          ,
    "wechselgeld"                           => "Wechselgeld"                           ,
    "schein100"                             => "100"                                   ,
    "schein200"                             => "200"                                   ,
    "schein500"                             => "500"                                   ,
    "bonstorno"                             => "Bonstorno"                             ,
    "retouren"                              => "Retouren"                              ,
    "nullbon"                               => "Nullbon"                               ,
    "kunden"                                => "Anzahl Kunden"                         ,
    "abrechnungsbon"                        => "Abrechnungsbon"                        ,
    "einkaufdurchschnitt"                   => "Durchschnittlicher Einkauf D/"         ,
    "ec_karte"                              => "EC"                                    ,
    "ec_kunden"                             => "EC-Kunden"                             ,
    "pos_je_stunde"                         => "Pos/h"                                 ,
    "kasse_wartezeit"                       => "Kasse/Wartezeit"                       ,
    "Manko"                                 => "Manko"                                 ,
    "leergut_auszahlung"                    => "Leergut Auszahlung"                    ,
    "leergut_sack"                          => "Leergut Sack oder Einzelflasche s.o.e" ,
    "sack1"                                 => "1. Sack"                               ,
    "sack2"                                 => "2. Sack"                               ,
    "leergut_anzahl"                        => "Leergut Anzahl Flaschen und Dosen"     ,
    "pfand"                                 => "Pfand"                                 ,
    "pause1_eigene_uhr"                     => "1.Pause Eigene Uhr"                    ,
    "pause2_eigene_uhr"                     => "2.Pause Eigene Uhr"                    ,
    "arbeitszeit_geplant"                   => "Geplante Arbeitszeit"                  ,
    "arbeitszeit_tatsächlich"               => "Tatsächliche Arbeitszeit"              ,
    "zeitgutschrift_20"                     => "ZEG Zeitgutschrift 20%%"               ,
    "nachtzuschlag_50"                      => "Nachtzuschlag 50%%"                    ,
    "zusammen"                              => "Zusammen"                              ,
    "mehrarbeit"                            => "Mehrarbeit"                            ,
    "mehrarbeit+zeg+nacht"                  => "Mehrarbeit+ZEG+Nachtzuschlag"          ,
    "abschöpfung_bar"                       => "Abschöpfung Bar"                       ,
    "abschöpfung_safebag"                   => "Abschöpfung Safebag"                   ,
    "abschöpfung_piep"                      => "Abschöpfung Piep"                      ,
    "kassensturz"                           => "Kassensturz"                           ,
    "arbeitszeit"                           => "Arbeitszeit"                           ,
    "gehe"                                  => "gehe"                                  ,
    "computerausdruck"                      => "Computerausdruck"                      ,
    "arbeitszeit_stunden"                   => "Arbeitszeit in h"                      ,
    "salden"                                => "Salden"                                ,
    "arbeit_anfang"                         => "Anfang"                                ,
    "arbeit_ende"                           => "Ende"                                  ,
    "pause1_zeiterfassung"                  => "1.Pause"                               ,
    "pause2_zeiterfassung"                  => "2.Pause"                               ,
    "tagesendebon"                          => "Tagesendebon"                          ,
    "händlerbons_hinten"                    => "Händlerbons hintere Kasse"             ,
    "händlerbons_vorn"                      => "Händlerbons vordere Kasse"             ,
    "händlerbons_mitte"                     => "Händlerbons mittlere Kasse"            ,
    "bar_ist"                               => "Bar Istwert"                           ,
    "umsatz_ist"                            => "Umsatz Istwert"                        ,
  );
  $erg = "";
  $erg .= "<style>  tr td:nth-child(1) {text-align: right;}  </style>\n";

  $speichern = "speichern"; $label = "Speichern"; $inhalt = "gesandt";
  $erg .= "    <tr><td><button type=\"SUBMIT\" name=\"$speichern\"  value=\"$inhalt\"   > $label </button>\n";
  foreach ($felder_kurz as $name => $inhalt) {
    $postname = "post_$name";
    $erg .= "    <tr><td>$inhalt<td><input type=\"TEXT\"   name=\"$postname\" size=\"33\" value='$name' >\n";
    $erg .= "        <td>" . $muster[$name];
    //printf( "%s %s<br />\n", $name, $inhalt);
  }
  $erg .= "    <tr><td><button type=\"SUBMIT\" name=\"$speichern\"  value=\"$inhalt\"   > $label </button>\n";
  $actionskript = "xx.php";
  $farbe = "#fff0f0";
  $erg = "<table style=\"background-color:$farbe\">\n$erg</table>";
  printf( "<form method=\"POST\" action=\"$actionskript\">\n$erg\n</form>\n", "");
}

main();
?>
