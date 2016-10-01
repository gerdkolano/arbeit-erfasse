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

function hhmm( $wort) { // string ziffern nichtziffern ziffern
  preg_match( "/(\d+)[^\d](\d+)/", $wort, $matches);
  if (!isset($matches[1])) {
    printf( "Z022 Kein Match %s", $wort);
    return "HH.MM ";
  }
  $erg = sprintf( "%02d.%02d", $matches[1], $matches[2]);
  return $erg;
}

function dauer( $anfang, $ende) { // string ziffern nichtziffern ziffern
  $differenz = minuten( $ende) - minuten( $anfang);
# printf( "MIN020 anfang=%s ende=%s differenz=%d<br />\n", $anfang, $ende, $differenz);
  return $differenz / 60.0;
}

function dauer_mit_pause( $anfang, $ende) { // string ziffern nichtziffern ziffern
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
    printf( "Z023 Kein Match %s", $wort);
    return "-1";
  }
  $erg = sprintf( "%4d", $matches[1] * 60 + $matches[2]);
  $erg = $matches[1] * 60 + $matches[2];
# printf( "MIN010 %s:%s=%dmin<br />\n", $matches[1], $matches[2], $erg);
  return $erg;
}

function geplant( $arbzeit_plan_anfang, $arbzeit_plan_ende) {
  return sprintf( "%.2f Stunden von %s bis %s",
    dauer_mit_pause( $arbzeit_plan_anfang, $arbzeit_plan_ende),
    hhmm( $arbzeit_plan_anfang),
    hhmm( $arbzeit_plan_ende)
  );
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
  public $zwanzig, $fünfzig, $gutschrift_dezimal, $arbeitszeit_in_minuten;
  function __construct() {
  }
  function toString() {
    return sprintf( "Gutschrift %s %.2fh %s %.2fh %s %.2fh %s %.2fh <br />\n",
      "20%",         $this->zwanzig,
      "50%",         $this->fünfzig,
      "Gut",         $this->gutschrift_dezimal,
      "Arbeitszeit", $this->arbeitszeit_in_minuten / 60.0
    );
  }
}

function berechnete_daten( $value) {
  $ergebnis = new ergebnis();
  $kommt_geht = array();
  if ($value["arbeit_kommt"] != "") $kommt_geht[] = hhmm( $value["arbeit_kommt"]);
  if ($value["pause1_geht" ] != "") $kommt_geht[] = hhmm( $value["pause1_geht" ]);
  if ($value["pause1_kommt"] != "") $kommt_geht[] = hhmm( $value["pause1_kommt"]);
  if ($value["pause2_geht" ] != "") $kommt_geht[] = hhmm( $value["pause2_geht" ]);
  if ($value["pause2_kommt"] != "") $kommt_geht[] = hhmm( $value["pause2_kommt"]);
  if ($value["arbeit_geht" ] != "") $kommt_geht[] = hhmm( $value["arbeit_geht" ]);
  sort ($kommt_geht);
  $anzahl = count ($kommt_geht);
# printf( "Z012 %d<br />\n", $anzahl);
  if ($anzahl % 2 == 1) {printf( "Z013 %d Arbeitzeiten. Eine Arbeitszeit fehlt.<br />\n", $anzahl);}
  $arbeitszeit_in_minuten = 0; 
  $arbeitszeit_dezimal = 0.0; 
  for ($i = 0; $i+1 < $anzahl; $i += 2) {
#   printf( "Z014 %s %s<br />\n", $kommt_geht[$i], $kommt_geht[$i+1]);
    $in_minuten = minuten( $kommt_geht[$i+1]) - minuten( $kommt_geht[$i], $kommt_geht[$i+1]);
    $dezimal = $in_minuten / 60.0;
    $arbeitszeit_in_minuten += $in_minuten;
    $arbeitszeit_dezimal += $dezimal;
  }
  $ergebnis->arbeitszeit_in_minuten = $arbeitszeit_in_minuten;

  $arbeit_geht = hhmm($value["arbeit_geht"]);
  if (minuten( $arbeit_geht) > minuten("18.30")) {
#   printf( "%s  > %s und %s  > %s ", $arbeit_geht, "18.30", minuten( $arbeit_geht), minuten( "18.30"));
    $kommt_geht[] = hhmm( "18.30");
    $kommt_geht[] = hhmm( "18.30");
  } else {
#   printf( "%s <= %s und %s <= %s ", $arbeit_geht, "18.30", minuten( $arbeit_geht), minuten( "18.30"));
  }
  if (minuten( $arbeit_geht) > minuten("20.00")) {
#   printf( "%s  > %s und %s  > %s ", $arbeit_geht, "20.00", minuten( $arbeit_geht), minuten( "20.00"));
    $kommt_geht[] = hhmm( "20.00");
    $kommt_geht[] = hhmm( "20.00");
  } else {
#   printf( "%s <= %s und %s <= %s ", $arbeit_geht, "20.00", minuten( $arbeit_geht), minuten( "20.00"));
  }
  sort ($kommt_geht);
  $anzahl = count ($kommt_geht);
  $gutschrift_dezimal = 0.0;
  for ($i = 0; $i+1 < $anzahl; $i += 2) {
#   printf( "Z016 %s %s<br />\n", $kommt_geht[$i], $kommt_geht[$i+1]);
    if (minuten( $kommt_geht[$i]) >= minuten( "18.30") and minuten( $kommt_geht[$i]) < minuten( "20.00")) {
      $in_minuten = minuten( $kommt_geht[$i+1]) - minuten( $kommt_geht[$i], $kommt_geht[$i+1]);
      $ergebnis->zwanzig = $in_minuten / 60.0 * 0.20;
#     printf( "Z060 %s %.2f<br />\n", "20% =", $ergebnis->zwanzig);
      $gutschrift_dezimal += $ergebnis->zwanzig;
    } else
      if (minuten( $kommt_geht[$i]) >= minuten( "20.00")) {
        $in_minuten = minuten( $kommt_geht[$i+1]) - minuten( $kommt_geht[$i], $kommt_geht[$i+1]);
        $ergebnis->fünfzig = $in_minuten / 60.0 * 0.50;
#       printf( "Z070 %s %.2f<br />\n", "50% =", $ergebnis->fünfzig);
        $gutschrift_dezimal += $ergebnis->fünfzig;
      }
  }
    $ergebnis->gutschrift_dezimal = $gutschrift_dezimal;
# printf( "Z080 %s %.2f<br />\n", "Gutschrift =", $gutschrift_dezimal);
  return $ergebnis;
}

function lies( $table_name, $conn) {
  $query = "SELECT id,
    datum, 
    erscheine, 
    pause1_geht,
    pause1_kommt,
    pause2_geht,
    pause2_kommt,
    arbzeit_plan_anfang,
    arbzeit_plan_ende,
    arbeit_kommt,
    arbeit_geht
    FROM $table_name WHERE id < 13";
# echo "################# $query<br />\n";
  $erg = $conn->hol_array_of_objects( "$query");
  foreach ($erg as $key=>$value) {
#   printf( "key %s<br />\n", $key);
    foreach ($value as $schlüssel=>$wert) {
#       printf( "%s %s %s<br />\n", $key, $schlüssel, $wert);
#       printf( "Z008 %s %s %s <br />\n", $schlüssel, hhmm( $wert), minuten( $wert));
    }
    printf( "id %d ", $value["id"]);
    printf( "datum %s ", hhmm( $value["datum"]));
    printf( "erscheine %s ", hhmm( $value["erscheine"]));
#   printf( "Z081 geplant %.2f <br />\n",  dauer( $value["arbzeit_plan_anfang"], $value["arbzeit_plan_ende"]));
    printf( "geplant %s<br />\n", geplant( $value["arbzeit_plan_anfang"], $value["arbzeit_plan_ende"]));
    $berechnete = berechnete_daten( $value);
#   printf( "Z083 berechnete %d %.2f <br />\n", $berechnete, $berechnete / 60.0 );
    printf( "%s <br />\n", $berechnete->toString());
  }
}

head();

# echo "<pre>"; print_r( $_POST); echo "</pre>";
# echo "<pre>"; print_r( $erg); echo "</pre>";

$database_name = "arbeit";
$table_name = "zeiten";

$gepostet = new gepostet();
$gepostet->zeig();

$conn = new conn();
$erg = $conn->frage( 0, "USE $database_name");

lies( $table_name,  $conn);
?>

