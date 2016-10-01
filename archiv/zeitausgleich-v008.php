<?php
require_once( "helfer.php");
require_once( "tabelle.php");

function head() {
    printf( "<!DOCTYPE html>\n");
    printf( "<html>\n");
    printf( "<head>\n");
    printf( "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n");
    printf( "</head>\n");
    printf( "<body>\n");
}

function kurz( $wort) { // string ziffern nichtziffern ziffern
  preg_match( "/(\d+)[^\d](\d+)[^\d](\d+)/", $wort, $matches);
  if (!isset($matches[3])) {
#   printf( "Z022 Kein Match \"%s\"", $wort);
    return "+++ ";
  }
  $erg = sprintf( "%d.%d.%d", $matches[3], $matches[2], $matches[1]%100);
  return $erg;
}

function hhmm( $wort) { // string ziffern nichtziffern ziffern
  preg_match( "/(\d+)[^\d](\d+)/", $wort, $matches);
  if (!isset($matches[1])) {
#   printf( "Z022 Kein Match \"%s\"", $wort);
    return "……… ";
  }
  $erg = sprintf( "%02d.%02d", $matches[1], $matches[2]);
  return $erg;
}

function stdm( $value, $warg) { // string ziffern nichtziffern ziffern
  $wort = $value[$warg];
  preg_match( "/(\d+)[^\d](\d+)/", $wort, $matches);
  if (!isset($matches[1])) {
    printf( "Z019 Kein Match \"%s\" id=%s %s!<br />\n", $wort, $value["id"], $warg);
    return ".... ";
  }
  $erg = sprintf( "%02d.%02d", $matches[1], $matches[2]);
  return $erg;
}

function diff_in_min( $anfang, $ende) { // string ziffern nichtziffern ziffern
  $differenz = minuten( $ende) - minuten( $anfang);
# printf( "MIN020 anfang=%s ende=%s differenz=%d<br />\n", $anfang, $ende, $differenz);
  return $differenz;
}

function diff_in_std( $anfang, $ende) { // string ziffern nichtziffern ziffern
  $differenz = minuten( $ende) - minuten( $anfang);
# printf( "MIN020 anfang=%s ende=%s differenz=%d<br />\n", $anfang, $ende, $differenz);
  return $differenz / 60.0;
}

function dauer_ohne_pause( $anfang, $ende) { // string ziffern nichtziffern ziffern
  $differenz = minuten( $ende) - minuten( $anfang);
  if ($differenz < 390) { $pause = 15; } else { $pause = 30; } 
# printf( "MIN020 anfang=%s ende=%s differenz=%d<br />\n", $anfang, $ende, $differenz);
  $differenz -= $pause;
# printf( "MIN030 anfang=%s ende=%s differenz=%d<br />\n", $anfang, $ende, $differenz);
  return $differenz / 60.0;
}

function minuten( $wort) { // string ziffern nichtziffern ziffern
  preg_match( "/(\d+)[^\d](\d+)/", $wort, $matches);
  if (!isset($matches[1])) {
    printf( "Z023 Kein Match \"%s\"<br />\n", $wort);
    return "-1";
  }
  $erg = sprintf( "%4d", $matches[1] * 60 + $matches[2]);
  $erg = $matches[1] * 60 + $matches[2];
# printf( "MIN010 %s:%s=%dmin<br />\n", $matches[1], $matches[2], $erg);
  return $erg;
}

/* alter table zeiten change arbzeit_ist_anfang arbeit_kommt text;
 * alter table zeiten change arbzeit_ist_ende arbeit_geht text;
 */

function minuten_oder_null ( $anfang, $ende) {
  return ( $anfang != "" and  $ende != "")
    ? minuten( $ende) - minuten( $anfang)
    : 0
    ;
}
/*
 * kommt geht
 * kommt geht kommt geht
 * kommt geht kommt geht kommt geht
 * starte mit arbeit_kommt
 * weiter mit pause1_geht wenn pause1_geht nicht leer
 *
 * */
class ergebnis {
  public $zwanzig;
  public $fünfzig;
  public $gutschrift_dezimal;
  public $arbeitszeit_in_minuten;
  public $plan_dauer_mit_pause;
  public $plan_dauer_ohne_pause;
  public $plan_anfang;
  public $plan_ende;
  public $id;
  public $datum;
  public $gekommen;
  public $gehe;
  public $arbeit_kommt;
  public $pause1_geht;
  public $pause1_kommt;
  public $pause2_geht;
  public $pause2_kommt;
  public $arbeit_geht;
  function __construct() {
  }
  
  function toString() {
    return sprintf( "%s %s %s %s %s %s Gutschrift %s %.2fh %s %.2fh %s %.2fh %s %.2fh %s %.2fh (%s %.2f) %s %s %s %s<br />\n",
      "id",                       $this->id,
      "Datum",                    $this->datum,
      "kommen",                   $this->gekommen,
      "20%",                      $this->zwanzig,
      "50%",                      $this->fünfzig,
      "Gut",                      $this->gutschrift_dezimal,
      "Arbeitszeit",              $this->arbeitszeit_in_minuten / 60.0,
      "geplante Arbeitszeit",     $this->dauer_ohne_pause,
      "mit Pause",                $this->plan_dauer_mit_pause,
      "Arbeitsanfang",            $this->plan_anfang,
      "Arbeitsende",              $this->plan_ende                     
    );
  }
  
  function leer( $real) {
    return $real <= 0.001
      ? ""
      : sprintf( "%.2f", $real);
      ;
  }
  
  function toHTML() {
    $erg = "";
    $erg .= sprintf( "<tr><td> %s",                $this->id                            );
    $erg .= sprintf( "    <td> %s",                $this->datum                         );
    $erg .= sprintf( "    <td> %s",                $this->plan_anfang                   ); 
    $erg .= sprintf( "    <td> %s",                $this->plan_ende                     ); 
    $erg .= sprintf( "    <td> %.2f",              $this->dauer_ohne_pause              ); 
    $erg .= sprintf( "    <td> %s",                $this->gekommen                      ); 
    $erg .= sprintf( "    <td> %s",                $this->arbeit_kommt                  );
    $erg .= sprintf( "    <td> %s",                $this->pause1_geht                   );
    $erg .= sprintf( "    <td> %s",                $this->pause1_kommt                  );
    $erg .= sprintf( "    <td> %s",                $this->pause2_geht                   );
    $erg .= sprintf( "    <td> %s",                $this->pause2_kommt                  );
    $erg .= sprintf( "    <td> %s",                $this->arbeit_geht                   );
    $erg .= sprintf( "    <td> %s",                $this->gehe                          );
    $erg .= sprintf( "    <td> %s",   $this->leer( $this->zwanzig                      ));
    $erg .= sprintf( "    <td> %s",   $this->leer( $this->fünfzig                      ));
    $erg .= sprintf( "    <td> %s",   $this->leer( $this->gutschrift_dezimal           ));
    $erg .= sprintf( "    <td> %.2f",              $this->arbeitszeit_in_minuten / 60.0 );
    $erg .= sprintf( "    <td> %.2f",              $this->plan_dauer_mit_pause          );
    $erg .= "\n";
    return $erg;
  }
  
  function table_header() {
    $erg = "";
    $erg .= sprintf( "<tr><th> %s",                          "id         ");
    $erg .= sprintf( "    <th> %s",                          "Datum      ");
    $erg .= sprintf( "    <th colspan=3 border=0> %s",       "geplant"    );
   #$erg .= sprintf( "    <th> %s",                          "plan_ende  ");
   #$erg .= sprintf( "    <th> %s",                          "plan_arb   ");
    $erg .= sprintf( "    <th> %s",                          "kom-"       );
    $erg .= sprintf( "    <th> %s",                          "An-"        );
    $erg .= sprintf( "    <th colspan=2 border=0> %s",       "Pause 1"    );
   #$erg .= sprintf( "    <th> %s",                          "4"          );
    $erg .= sprintf( "    <th colspan=2 border=0> %s",       "Pause 2"    );
   #$erg .= sprintf( "    <th> %s",                          "6"          );
    $erg .= sprintf( "    <th> %s",                          "En-"        );
    $erg .= sprintf( "    <th> %s",                          ""           );
    $erg .= sprintf( "    <th colspan=3 border=0> %s",       "Gutschrift" );
   #$erg .= sprintf( "    <th> %s",                          "50%        ");
   #$erg .= sprintf( "    <th> %s",                          "gut        ");
    $erg .= sprintf( "    <th> %s",                          "ges        ");
    $erg .= sprintf( "    <th> %s",                          "mit"        );
    $erg .= "\n";

    $erg .= sprintf( "<tr><th> %s",       "id         ");
    $erg .= sprintf( "    <th> %s",       "Datum      ");
    $erg .= sprintf( "    <th> %s",       "von"        );
    $erg .= sprintf( "    <th> %s",       "bis"        );
    $erg .= sprintf( "    <th> %s",       "Std"        );
    $erg .= sprintf( "    <th> %s",       "men"        );
    $erg .= sprintf( "    <th> %s",       "fang"       );
    $erg .= sprintf( "    <th> %s",       "geh"        );
    $erg .= sprintf( "    <th> %s",       "kom"        );
    $erg .= sprintf( "    <th> %s",       "geh"        );
    $erg .= sprintf( "    <th> %s",       "kom"        );
    $erg .= sprintf( "    <th> %s",       "de"         );
    $erg .= sprintf( "    <th> %s",       "gehe"       );
    $erg .= sprintf( "    <th> %s",       "20%        ");
    $erg .= sprintf( "    <th> %s",       "50%        ");
    $erg .= sprintf( "    <th> %s",       "zus."       );
    $erg .= sprintf( "    <th> %s",       "amt"        );
    $erg .= sprintf( "    <th> %s",       "Pause"      );
    $erg .= "\n";
    return $erg;
  }

}

function zeig( $koge) {
  foreach ( $koge as $key=>$val) {
    printf( "KOGE1 %s %s<br />\n", $key, $val);
  }
}

function berechnete_daten( $value, $ergebnis) {
  $kommt_oder_geht = array();
  if ($value["arbeit_kommt"] != "") $kommt_oder_geht[] = hhmm( $value["arbeit_kommt"]);
  if ($value["pause1_geht" ] != "") $kommt_oder_geht[] = hhmm( $value["pause1_geht" ]);
  if ($value["pause1_kommt"] != "") $kommt_oder_geht[] = hhmm( $value["pause1_kommt"]);
  if ($value["pause2_geht" ] != "") $kommt_oder_geht[] = hhmm( $value["pause2_geht" ]);
  if ($value["pause2_kommt"] != "") $kommt_oder_geht[] = hhmm( $value["pause2_kommt"]);
  if ($value["arbeit_geht" ] != "") $kommt_oder_geht[] = hhmm( $value["arbeit_geht" ]);
  sort ($kommt_oder_geht);
  $anzahl = count ($kommt_oder_geht);
# printf( "Z012 %d<br />\n", $anzahl);
  if ($anzahl % 2 == 1) {
    printf( "Z013 %d Arbeitzeiten. Eine Arbeitszeit fehlt. id=%d <br />\n", $anzahl, $value["id"]);
    printf( "Z014 <a href=\"id=%d\"> </a> <br />\n", $anzahl, $value["id"]);
  }
  $arbeitszeit_in_minuten = 0; 
  $arbeitszeit_dezimal = 0.0; 
  for ($i = 0; $i+1 < $anzahl; $i += 2) {
#   printf( "Z014 %s %s<br />\n", $kommt_oder_geht[$i], $kommt_oder_geht[$i+1]);
    //$in_minuten = minuten( $kommt_oder_geht[$i+1]) - minuten( $kommt_oder_geht[$i]);
    $in_minuten = diff_in_min( $kommt_oder_geht[$i], $kommt_oder_geht[$i+1]);
    $dezimal = $in_minuten / 60.0;
    $arbeitszeit_in_minuten += $in_minuten;
    $arbeitszeit_dezimal += $dezimal;
  }
  $ergebnis->arbeitszeit_in_minuten = $arbeitszeit_in_minuten;

  $arbeit_geht = hhmm($value["arbeit_geht"]);
  if (minuten( $arbeit_geht) > minuten("18.30")) {
#   printf( "%s  > %s und %s  > %s ", $arbeit_geht, "18.30", minuten( $arbeit_geht), minuten( "18.30"));
    $kommt_oder_geht[] = hhmm( "18.30");
    $kommt_oder_geht[] = hhmm( "18.30");
  } else {
#   printf( "%s <= %s und %s <= %s ", $arbeit_geht, "18.30", minuten( $arbeit_geht), minuten( "18.30"));
  }
  if (minuten( $arbeit_geht) > minuten("20.00")) {
#   printf( "%s  > %s und %s  > %s ", $arbeit_geht, "20.00", minuten( $arbeit_geht), minuten( "20.00"));
    $kommt_oder_geht[] = hhmm( "20.00");
    $kommt_oder_geht[] = hhmm( "20.00");
  } else {
#   printf( "%s <= %s und %s <= %s ", $arbeit_geht, "20.00", minuten( $arbeit_geht), minuten( "20.00"));
  }
  sort ($kommt_oder_geht);
# zeig ($kommt_oder_geht);
  $anzahl = count ($kommt_oder_geht);
  $g20_dezimal = 0.0;
  $g50_dezimal = 0.0;
  for ($i = 0; $i+1 < $anzahl; $i += 2) {
#   printf( "Z016 %s %s<br />\n", $kommt_oder_geht[$i], $kommt_oder_geht[$i+1]);
    if (minuten( $kommt_oder_geht[$i]) >= minuten( "18.30") and minuten( $kommt_oder_geht[$i]) < minuten( "20.00")) {
      //$in_minuten = minuten( $kommt_oder_geht[$i+1]) - minuten( $kommt_oder_geht[$i]);
      $in_minuten = diff_in_min( $kommt_oder_geht[$i], $kommt_oder_geht[$i+1]);
      $g20_dezimal += $in_minuten / 60.0 * 0.20;
#     printf( "Z060 %s %.2f<br />\n", "20% =", $g20_dezimal);
    } else
      if (minuten( $kommt_oder_geht[$i]) >= minuten( "20.00")) {
        //$in_minuten = minuten( $kommt_oder_geht[$i+1]) - minuten( $kommt_oder_geht[$i]);
        $in_minuten = diff_in_min( $kommt_oder_geht[$i], $kommt_oder_geht[$i+1]);
        $g50_dezimal += $in_minuten / 60.0 * 0.50;
#       printf( "Z070 %s %.2f<br />\n", "50% =", $g50_dezimal);
      }
  }
  $ergebnis->zwanzig = $g20_dezimal;
  $ergebnis->fünfzig = $g50_dezimal;
  $ergebnis->gutschrift_dezimal = $g20_dezimal + $g50_dezimal;
# printf( "Z080 %s %.2f<br />\n", "Gutschrift =", $gutschrift_dezimal);
# printf( "Z084 geplant %.2f <br />\n", $ergebnis->plan_dauer_mit_pause);
  return $ergebnis;
}

function lies( $woche, $table_name, $conn) {
  $query = "SELECT id,
    datum, 
    erscheine, 
    gehe, 
    pause1_geht,
    pause1_kommt,
    pause2_geht,
    pause2_kommt,
    arbzeit_plan_anfang,
    arbzeit_plan_ende,
    arbeit_kommt,
    arbeit_geht
    FROM $table_name WHERE weekofyear(datum_auto) = $woche ORDER BY datum_auto";
//  FROM $table_name WHERE id < 13";
# echo "################# $query<br />\n";
  $erg = $conn->hol_array_of_objects( "$query");
  $HTML = "";
  $text = "";
  foreach ($erg as $key=>$value) {
#   printf( "key %s<br />\n", $key);
    foreach ($value as $schlüssel=>$wert) {
#       printf( "%s %s %s<br />\n", $key, $schlüssel, $wert);
#       printf( "Z008 %s %s %s <br />\n", $schlüssel, hhmm( $wert), minuten( $wert));
    }
  $ergebnis = new ergebnis();
#   printf( "id %d ", $value["id"]);
#   printf( "datum %s ", hhmm( $value["datum"]));
#   printf( "erscheine %s ", hhmm( $value["erscheine"]));

/*    
    $ergebnis->datum              =       $value[ "datum"      ] ;
    $ergebnis->gekommen           = stdm( $value, "erscheine"   );
    $ergebnis->arbeit_kommt       = stdm( $value, "arbeit_kommt");
    $ergebnis->pause1_geht        = stdm( $value, "pause1_geht" );
    $ergebnis->pause1_kommt       = stdm( $value, "pause1_kommt");
    $ergebnis->pause2_geht        = stdm( $value, "pause2_geht" );
    $ergebnis->pause2_kommt       = stdm( $value, "pause2_kommt");
    $ergebnis->arbeit_geht        = stdm( $value, "arbeit_geht" );
    $ergebnis->gehe               = stdm( $value, "gehe"        );
*/    

    $ergebnis->id                 = $value["id"];
    $ergebnis->datum              = kurz( $value["datum"       ]);
    $ergebnis->gekommen           = hhmm( $value["erscheine"   ]);
    $ergebnis->arbeit_kommt       = hhmm( $value["arbeit_kommt"]);
    $ergebnis->pause1_geht        = hhmm( $value["pause1_geht" ]);
    $ergebnis->pause1_kommt       = hhmm( $value["pause1_kommt"]);
    $ergebnis->pause2_geht        = hhmm( $value["pause2_geht" ]);
    $ergebnis->pause2_kommt       = hhmm( $value["pause2_kommt"]);
    $ergebnis->arbeit_geht        = hhmm( $value["arbeit_geht" ]);
    $ergebnis->gehe               = hhmm( $value["gehe"        ]);
    $ergebnis->plan_dauer_mit_pause = diff_in_std( $value["arbzeit_plan_anfang"], $value["arbzeit_plan_ende"]);
    $ergebnis->dauer_ohne_pause = dauer_ohne_pause( $value["arbzeit_plan_anfang"], $value["arbzeit_plan_ende"]);
    $ergebnis->plan_anfang     = hhmm( $value["arbzeit_plan_anfang"]);
    $ergebnis->plan_ende       = hhmm( $value["arbzeit_plan_ende"]);
    $berechnete = berechnete_daten( $value, $ergebnis);
    $HTML .= $berechnete->toHTML();
    $text .= $berechnete->toString();
#   printf( "Z081 geplant %.2f <br />\n",  diff_in_std( $value["arbzeit_plan_anfang"], $value["arbzeit_plan_ende"]));
#   printf( "Z082 geplant %.2f <br />\n", $ergebnis->plan_dauer_mit_pause);
#   printf( "geplante Arbeitszeit %s<br />\n", geplant( $value["arbzeit_plan_anfang"], $value["arbzeit_plan_ende"]));
  }

  printf( "<style> table, td, th { border: 1px solid gray } </style>\n");
  printf( "<style>  tr td:nth-child(2) {text-align: right;}  </style>\n");

  printf( "<table>\n %s \n %s</table>\n", $berechnete->table_header(), $HTML);
# printf( "%s <br />\n", $text);
}

head();

# echo "<pre>"; print_r( $_POST); echo "</pre>";
# echo "<pre>"; print_r( $erg); echo "</pre>";

$database_name = "arbeit";
$table_name = "zeiten";

$gepostet = new gepostet();
echo $gepostet->toString();

$conn = new conn();
$erg = $conn->frage( 0, "USE $database_name");

$woche = 10;
if (isset( $_GET["woche"])) $woche = $_GET["woche"];
lies( $woche, $table_name,  $conn);
?>

