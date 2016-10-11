<?php
require_once( "../include/konst.php");
require_once( "../include/datum.php");
require_once( "helfer.php");
require_once( "tabelle.php");
require_once( "parser.php");

function main() {
  $eine_id    = "";
  $ein_datum  = "";
  $tafelart  = "";
  if (php_sapi_name()==="cli") { // von der Kommandozeile gerufen
  #if (false) { // zum Testen
  #  echo count($_SERVER['argv']) . "\n";
    // foreach geht nicht
    while ( $arg = next( $_SERVER['argv'])) { // next überspringt das erste Arrayelement
      switch ($arg) {
      case "-a": $eine_id    = next ($_SERVER['argv']); break;
      case "-e": $ein_datum  = next ($_SERVER['argv']); break;
      case "-k": $tafelart  = next ($_SERVER['argv']); break;
      default : echo "M020 arg $arg\n"; break;
      }
    }
    verarbeite_id_datum_art( $eine_id, $ein_datum, $tafelart);
  } else {
    printf( "<!DOCTYPE html>\n");
    printf( "<html>\n");
    printf( "<head>\n");
    printf( "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n");
    printf( "<link rel=\"stylesheet\" href=\"arbeit-erfasse.css\" type=\"text/css\">\n");
    printf( "</head>\n");
    printf( "<body>\n");
    #php_sapi_name()==="cli";
    #printf( "%s\n", php_sapi_name());
    # echo count($_GET) . "<br />\n";
    echo "<pre>"; print_r( $_GET); echo "</pre>";
    #while ( $arg = next( $_GET)) {
    foreach ( $_GET as $key=>$arg) {
      $GET_parameter = new GET_parameter();
      switch ($key) {
      case $GET_parameter->get_name_fuer_id        : $eine_id    = $arg;                   break; // case "id":
      case $GET_parameter->get_name_fuer_datum     : $ein_datum  = geparstes_datum( $arg); break;
      case $GET_parameter->get_name_fuer_kurztafel : $tafelart   = $arg;                   break;
      case "tafelart"                              : $tafelart   = $arg;                   break;
      default      :                                                                       break;
      //default      : echo "M030 key $key arg $arg\n"; break;
      }
    }
    verarbeite_id_datum_art( $eine_id, $ein_datum, $tafelart);
    printf( "</body>\n");
    printf( "</html>\n");
  }
}

function table_row( $inhalt, $column, $vorhanden) {
  $mit_column = false;
  $mit_column = true;
  $erg = "";
  $readonly = $inhalt->rw == "r" ? " readonly=\"readonly\"" : "" ;
  $erg .= "  <tr><td>" . $inhalt->label;
  $erg .= "<td><input type=\"TEXT\" name=\"$column\" size=\"17\" value='$vorhanden' $readonly>";
  if ($mit_column) $erg .= "<td>" . $column;
  $erg .= "<td>" . $inhalt->muster;
  $erg .= "<td>" . $inhalt->bedeutung . "\n";
  return $erg;
}

function form_kopf( $submit_name, $submit_label) {
  $erg = "";
  $erg .= "<style>  tr td:nth-child(1) {text-align: right;}  </style>\n";

  $erg .= row_button( $submit_name, "gesandt-a", $submit_label);
  
  $fn = pathinfo(__FILE__,PATHINFO_BASENAME);
  $erg .= sprintf( "<input type=\"HIDDEN\" name=\"RUFER\" value=\"%s%s%s\">\n", "http://", $_SERVER['SERVER_NAME'], $_SERVER['REQUEST_URI'] );

  return $erg;
}

function row_button( $submit_name, $submit_inhalt, $submit_label) {
  $erg = "";
  //$submit_inhalt = "gesandt";
  $erg .= "  <tr><td><button type=\"SUBMIT\" name=\"$submit_name\" value=\"$submit_inhalt\"> $submit_label </button>\n";
  return $erg;
}

function form_update( $spalte, $arg) {

  $erg = form_kopf( "UPDATE", "Diesen Datensatz " . $arg["id"] . " speichern");

  foreach ($arg as $column => $vorhanden) {
    $inhalt = $spalte[$column];
    $erg .= table_row( $inhalt, $column, $vorhanden);
  }
  $erg .= row_button( "UPDATE", "id-id" . $arg["id"], "Diesen Datensatz " . $arg["id"] . "  speichern");
  $actionskript = konst::$speicher_skript;
  $erg = "<table class=\"update\">\n$erg</table>";
  return sprintf( "<form method=\"POST\" action=\"%s\">\n%s\n</form>\n", $actionskript, $erg);
}

function form_insert( $spalte, $ein_datum, $tafelart) {

  $erg = form_kopf( "INSERT", "Diesen neuen Datensatz speichern");

  $tarifliche_arbeitszeit = 37.0;
  $beschäftigungsumfang   = $tarifliche_arbeitszeit * 90.0 / 100.0;
  $tägliche_arbeitszeit   = $beschäftigungsumfang / 6 ;

  switch ($tafelart) {
  default                   : $erscheine = ""         ; $bezahlte_zeit = ""                    ; break;
  case konst::$art_planung  : $erscheine = "plan"     ; $bezahlte_zeit = ""                    ; break;
  case konst::$art_feiertag : $erscheine = "Feiertag" ; $bezahlte_zeit = $tägliche_arbeitszeit ; break;
  case konst::$art_urlaub   : $erscheine = "Urlaub"   ; $bezahlte_zeit = $tägliche_arbeitszeit ; break;
  case konst::$art_frei     : $erscheine = "frei"     ; $bezahlte_zeit = ""                    ; break;
  }
  foreach ($spalte as $column => $inhalt) {
    switch ($column) {
    default                   : $vorhanden = ""             ; break;
    case "erscheine"          : $vorhanden = $erscheine     ; break;
    case "arbzeit_plan_dauer" : $vorhanden = $bezahlte_zeit ; break;
    case "datum"              : $vorhanden = $ein_datum     ; break;
    case "i_saldo_datum"      : $vorhanden = $ein_datum     ; break;
    }
#   $vorhanden = ($column == "datum" or $column == "i_saldo_datum") ? $ein_datum : ""; // zeige das Datum als Vorgabe
    $erg .= table_row( $inhalt, $column, $vorhanden);
  }

  $erg .= row_button( "INSERT", "gesandt-b", "Diesen neuen Datensatz speichern");
  //$erg .= "  <tr><td><button type=\"SUBMIT\" name=\"$submit_name\" value=\"$submit_inhalt\"> $submit_label </button>\n";
  $actionskript = konst::$speicher_skript;
  $erg = "<table class=\"insert\">\n$erg</table>";
  return sprintf( "<form method=\"POST\" action=\"%s\">\n%s\n</form>\n", $actionskript, $erg);
}

function verarbeite_id_datum_art( $eine_id, $ein_datum, $tafelart) {
  // if (isset( $ein_datum) and $ein_datum != "") {
  if ($ein_datum) {
    $where =  "WHERE datum_auto = '$ein_datum'";
  } else {
    if (isset( $eine_id) and is_numeric( $eine_id) and $eine_id != "") {
      $where =  "WHERE id = '$eine_id'";
    } else {
      $where =  "";
    }
  }
  erzeuge_ein_formular( $where, $ein_datum, $tafelart);
}

function erzeuge_ein_formular( $where, $ein_datum, $tafelart) {
  printf( "E060 where=\"%s\", ein_datum=\"%s\", tafelart=\"%s\"<br />\n", $where, $ein_datum, $tafelart);
  $table_name = konst::$table_name;
  $database_name = konst::$database_name;

  // Ist zu diesem Datum oder dieser Id in "where" ein Datensatz vorhanden ?
  $query = "SELECT id, datum_auto FROM $table_name $where";
  $conn = new conn();
  $erg = $conn->frage( 0, "USE $database_name");
  $schon_da = $conn->hol_array_of_objects( "$query", 0); // todo Fehlerbehandlung
  if ($schon_da) {                                                                                    // UPDATE
    $deutsch = (new datum_objekt( $schon_da[0]["datum_auto"]))->deutsch( "EEEE, d. MMMM YYYY");
    printf("U010 Editiere id=\"%s\", datum=\"%s\"<br />\n", $schon_da[0]["id"], $schon_da[0]["datum_auto"]);
  } else {                                                                                       // INSERT
    $deutsch = $ein_datum == "" ? "" : (new datum_objekt( $ein_datum))->deutsch( "EEEE, d. MMMM YYYY");
    printf("U020 Erzeuge mit datum=\"%s\", where=\"%s\" . Noch nicht im Datenbestand.<br />\n", $ein_datum, $where);
  }
  printf( "<strong>%s</strong><br />\n", $deutsch);
  switch ($tafelart) {
  case konst::$art_planung :
    $tabelle = new tabelle;
    $spalte = $tabelle->planungsfelder;
    $comma_separated = implode(",", $tabelle->planungswahl);
    $query = "SELECT $comma_separated  FROM $table_name $where";
    break;
  case konst::$art_feiertag :
    $tabelle = new tabelle;
    $spalte = $tabelle->feiertagsfelder;
    $comma_separated = implode(",", $tabelle->feiertagswahl);
    $query = "SELECT $comma_separated  FROM $table_name $where";
    break;
  case konst::$art_urlaub :
    $tabelle = new tabelle;
    $spalte = $tabelle->urlaubsfelder;
    $comma_separated = implode(",", $tabelle->urlaubswahl);
    $query = "SELECT $comma_separated  FROM $table_name $where";
    break;
  case konst::$art_frei :
    $tabelle = new tabelle;
    $spalte = $tabelle->freifelder;
    $comma_separated = implode(",", $tabelle->freiwahl);
    $query = "SELECT $comma_separated  FROM $table_name $where";
    break;
  case konst::$art_kurz :
    $tabelle = new tabelle;
    $spalte = $tabelle->kurzfelder;
    $comma_separated = implode(",", $tabelle->kurzwahl);
    $query = "SELECT $comma_separated  FROM $table_name $where";
    break;
  case konst::$art_mini :
    $tabelle = new tabelle;
    $spalte = $tabelle->minifelder;
    $comma_separated = implode(",", $tabelle->miniwahl);
    $query = "SELECT $comma_separated  FROM $table_name $where";
    break;
  case konst::$art_lang :
  default            :
    $spalte = (new tabelle())->felder; # Was ist der Unterschied zwischen "new tabelle" und "new tabelle()" ?
    $query = "SELECT * FROM $table_name $where";
    break;
  }
  if ($schon_da) {                                                                                    // UPDATE
    $erg = $conn->hol_array_of_objects( "$query", 0); // todo Fehlerbehandlung
    printf( "%s", form_update( $spalte, $erg[0]));
  } else {                                                                                       // INSERT
    printf( "%s", form_insert( $spalte, $ein_datum, $tafelart));
  }
  return;
}

main();
  # echo "<pre>"; print_r( $spalte); echo "!</pre><br />\n";
?>
