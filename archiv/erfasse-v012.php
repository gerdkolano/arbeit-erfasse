<?php
require_once( "konstante.php");
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
  $farbe = "#fff0f0";
  $actionskript = "speichere.php";
  $erg = "<table style=\"background-color:$farbe\">\n$erg</table>";
  return sprintf( "<form method=\"POST\" action=\"$actionskript\">\n$erg\n</form>\n", "");
}

function form_insert( $spalte, $ein_datum) {

  $erg = form_kopf( "INSERT", "Diesen neuen Datensatz speichern");

  foreach ($spalte as $column => $inhalt) {
    $vorhanden = $column == "datum" ? $ein_datum : ""; // zeige das Datum als Vorgabe
    $erg .= table_row( $inhalt, $column, $vorhanden);
  }

  $erg .= row_button( "INSERT", "gesandt-b", "Diesen neuen Datensatz speichern");
  //$erg .= "  <tr><td><button type=\"SUBMIT\" name=\"$submit_name\" value=\"$submit_inhalt\"> $submit_label </button>\n";
  $farbe = "#fff0f0";
  $actionskript = "speichere.php";
  $erg = "<table style=\"background-color:$farbe\">\n$erg</table>";
  return sprintf( "<form method=\"POST\" action=\"$actionskript\">\n$erg\n</form>\n", "");
}

function verarbeite_id_datum_art( $eine_id, $ein_datum, $tafelart) {
  if (isset( $ein_datum) and $ein_datum != "") {
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
  $table_name = (new konstante)->table_name;
  $database_name = (new konstante)->database_name;

  // Ist zu diesem Datum oder dieser Id in "where" ein Datensatz vorhanden ?
  $query = "SELECT id, datum_auto FROM $table_name $where";
  $conn = new conn();
  $erg = $conn->frage( 0, "USE $database_name");
  $schon_da = $conn->hol_array_of_objects( "$query", 0); // todo Fehlerbehandlung
  if ($schon_da) {                                                                                    // UPDATE
    printf("U010 id=\"%s\", datum=\"%s\"<br />\n", $schon_da[0]["id"], $schon_da[0]["datum_auto"]);
  } else {                                                                                       // INSERT
    printf("U020 datum=\"%s\", where=\"%s\" . Noch nicht im Datenbestand.<br />\n", $ein_datum, $where);
  }
  if ($tafelart == "1" or $tafelart == "INSERT-kurz" or $tafelart == "UPDATE-kurz") {                // bereite INSERT UPDATE kurz vor
    $tabelle = new tabelle();
    $spalte = $tabelle->kurzfelder;
    $comma_separated = implode(",", $tabelle->kurzwahl);
    $query = "SELECT $comma_separated  FROM $table_name $where";
  } else {                                                                                      // bereite INSERT UPDATE lang vor
    $spalte = (new tabelle())->felder;
    $query = "SELECT * FROM $table_name $where";
  }
  if ($schon_da) {                                                                                    // UPDATE
    $erg = $conn->hol_array_of_objects( "$query", 0); // todo Fehlerbehandlung
    printf( "%s", form_update( $spalte, $erg[0]));
  } else {                                                                                       // INSERT
    printf( "%s", form_insert( $spalte, $ein_datum));
  }
  return;

  if ($where == "" or $tafelart == "INSERT-kurz" or $tafelart == "INSERT-lang") {                   // INSERT
    printf( "%s", form_insert( $spalte, $ein_datum));
  } else {                                                                                      // UPDATE
#   echo "E030 LOS gehts $eine_id !<br />\n";
    $erg = $conn->hol_array_of_objects( "$query", 0); // todo Fehlerbehandlung
    if (!$erg) {
      printf( "%s", form_insert( $spalte, $ein_datum));
      return;
#     printf("E020 Fehler %s<br />\n", $_SERVER["REQUEST_URI"]); //  __DIR__, __FILE__,
#     $url = sprintf("http://%s/%s?tafelart=%s", $_SERVER["SERVER_NAME"], $_SERVER["SCRIPT_NAME"], $tafelart); //  __DIR__, __FILE__,
#     printf("E030 Versuche <a href=\"%s\"> %s </a><br />\n", $url, $url);
    }
#   echo "<pre>"; print_r( $erg); echo "</pre>";
    $erg = form_update( $spalte, $erg[0]);
    printf( "%s", $erg);
  }
}

main();
  # echo "<pre>"; print_r( $spalte); echo "!</pre><br />\n";
?>
