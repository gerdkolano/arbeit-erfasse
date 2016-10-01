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

function arbeite ( $start="", $stop="") {
  $felder = array ( 
   "Datum"                                 => "04.03",
   "erscheine"                             => "12:12",
   "sitzt"                                 => "Hönow",
   "nehme"                                 => "12.45",
   "ss"                                    => "3",
   "erhalte"                               => "286.00 13:06",
   "davon Rollen"                          => "186.00",
   "Wechselgeld"                           => "186.00 14:03",
   "100"                                   => "1",
   "200"                                   => "0",
   "500"                                   => "0",
   "Bonstorno"                             => "0",
   "Retouren"                              => "1",
   "Nullbon"                               => "2",
   "Kunden"                                => "250",
   "Abrechnungsbon"                        => "21:05",
   "Durchschnittlicher Einkauf D/"         => "16.80 €",
   "EC"                                    => "2089.98 €",
   "EC-Kunden"                             => "58",
   "Pos/h"                                 => "2907",
   "Kass/Wartezeit"                        => "22/8",
   "Manko"                                 => "+0.00",
   "Leergut Auszahlung"                    => "138.75 €",
   "Leergut Sack oder Einzelflasche s.o.e" => "",
   "1. Sack"                               => "",
   "2. Sack"                               => "",
   "Leergut Anzahl Gebinde"                => "555",
   "Pfand"                                 => "148.25 €",
   "1.Pause"                               => "16:17 - 16.32",
   "2.Pause"                               => "19:01 - 19:16",
   "Geplante Arbeitszeit"                  => "8.00 h 12:45 - 21:15",
   "Tatsächliche Arbeitszeit"              => "12:45 - 21:10",
   "ZEG Zeitgutschrift 20%%"               => "0.30 h",
   "Nachtzuschlag 50%%"                    => "0.59 h",
   "Zusammen"                              => "0.89 h",
   "Mehrarbeit"                            => "0.00 h",
   "Mehrarbeit+ZEG+nacht"                  => "0.89 h",
   "Abschöpfung Bar"                       => "0.00 € 14:00",
   "Abschöpfung Safebag"                   => "0.00 € 15:00",
   "Abschöpfung Piep verboten"             => "0 Piep-aus 19:42",
   "Kassensturz"                           => "0.00 €   15:00 HK oder nicht HK",
   "Arbeitszeit"                           => "8.81 h",
   "gehe"                                  => "21:20",
   "Computerausdruck"                      => "21:04:51",
   # Zeiterfassungsgrät
   "Arbeitszeit in h"                      => "8.00 h 03.03.16", # letzvorangegangener Arbeitstag
   "Salden"                                => "29.16 h 04.03.16",
   "Anfang"                                => "50 12:12",
   "Ende"                                  => "50 21:10",
   "1.Pause"                               => "50",
   "2.Pause"                               => "50 21.14",
   "Tagesendebon"                          => "21:07",
   "Händlerbons"                           => "24,91 € Bonnummer 8692 hintere Kasse",
   "Händlerbons"                           => "83.51 € Bonnummer 2026 vordere Kasse",
   "Bar Ist"                               => "2069.63 €",
   "Umsatz Ist"                            => "4210.61 €",
   ""                                      => "4210.61 €"
  );
  $erg = "";
  $erg .= "<style>  tr td:nth-child(1) {text-align: right;}  </style>\n";

  $name = "submit"; $inhalt = "gesandt";
    $erg .= "    <tr><td><button type=\"SUBMIT\" name=\"$name\"  value=\"$inhalt\"   > $name   </button>\n";
  foreach ($felder as $name => $inhalt) {
    $postname = "post_$name";
    $erg .= "    <tr><td>$name<td><input type=\"TEXT\"   name=\"$postname\" size=\"7\" value='$value' >\n";
    //printf( "%s %s<br />\n", $name, $inhalt);
  }
  $actionskript = "xx.php";
  $farbe = "#fff0f0";
  $erg = "<table style=\"background-color:$farbe\">\n$erg</table>";
  printf( "<form method=\"POST\" action=\"$actionskript\">\n$erg\n</form>\n", "");
}

main();
?>
