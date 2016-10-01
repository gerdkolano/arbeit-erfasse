<?php
require_once( "konstante.php");
require_once( "connection.php");
require_once( "parser.php");
require_once( "verdiensttabelle.php");

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
  $erg = "<table class=\"update\">\n$erg</table>";
  return sprintf( "<form method=\"POST\" action=\"%s\">\n%s\n</form>\n", (new konstante)->speicher_skript, $erg); // $erg kann "%" enthalten
}

function form_insert( $spalte, $ein_datum) {

  $erg = form_kopf( "INSERT", "Diesen neuen Datensatz speichern");

  foreach ($spalte as $column => $inhalt) {
    $vorhanden = $column == "datum" ? $ein_datum : ""; // zeige das Datum als Vorgabe
    $erg .= table_row( $inhalt, $column, $vorhanden);
  }

  $erg .= row_button( "INSERT", "gesandt-b", "Diesen neuen Datensatz speichern");
  //$erg .= "  <tr><td><button type=\"SUBMIT\" name=\"$submit_name\" value=\"$submit_inhalt\"> $submit_label </button>\n";
  $erg = "<table class=\"insert\">\n$erg</table>";
  return sprintf( "<form method=\"POST\" action=\"%s\">\n%s\n</form>\n", (new konstante)->speicher_skript, $erg); // $erg kann "%" enthalten
}

function verarbeite_id_datum_art( $eine_id, $ein_datum, $tafelart) {
  // if (isset( $ein_datum) and $ein_datum != "") {
  if ($ein_datum) {
    $where =  "WHERE datum = '$ein_datum'";
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
  $konst = new konstante;
  printf( "E060 where=\"%s\", ein_datum=\"%s\", tafelart=\"%s\"<br />\n", $where, $ein_datum, $tafelart);
  $table_name = $konst->verdienst_tafel_name;
  $database_name = $konst->database_name;

  // Ist zu diesem Datum oder dieser Id in "where" ein Datensatz vorhanden ?
  $query = "SELECT id, datum FROM $table_name $where";
  $conn = new conn();
  $erg = $conn->frage( 0, "USE $database_name");
  $schon_da = $conn->hol_array_of_objects( "$query", 0); // todo Fehlerbehandlung
  if ($schon_da) {                                                                                    // UPDATE
    printf("U010 Editiere id=\"%s\", datum=\"%s\"<br />\n", $schon_da[0]["id"], $schon_da[0]["datum"]);
  } else {                                                                                       // INSERT
    printf("U020 Erzeuge mit datum=\"%s\", where=\"%s\" . Noch nicht im Datenbestand.<br />\n", $ein_datum, $where);
  }
  switch ($tafelart) {
  case $konst->art_kurz :
    $tabelle = new tabelle;
    $spalte = $tabelle->kurzfelder;
    $comma_separated = implode(",", $tabelle->kurzwahl);
    $query = "SELECT $comma_separated  FROM $table_name $where";
    break;
  case $konst->art_mini :
    $tabelle = new tabelle;
    $spalte = $tabelle->minifelder;
    $comma_separated = implode(",", $tabelle->miniwahl);
    $query = "SELECT $comma_separated  FROM $table_name $where";
    break;
  case $konst->art_lang :
  default            :
    $spalte = (new tabelle())->felder;
    $query = "SELECT * FROM $table_name $where";
    break;
  }
  if ($schon_da) {                                                                                    // UPDATE
    $erg = $conn->hol_array_of_objects( "$query", 0); // todo Fehlerbehandlung
    printf( "%s", form_update( $spalte, $erg[0]));
  } else {                                                                                       // INSERT
    printf( "%s", form_insert( $spalte, $ein_datum));
  }
  return;

  if ($where == "" or $tafelart == "kurz" or $tafelart == "lang") {                   // INSERT
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
function main() {
  $eine_id    = "";
  $ein_datum  = "";
  $tafelart  = "";
    printf( "<!DOCTYPE html>\n");
    printf( "<html>\n");
    printf( "<head>\n");
    printf( "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n");
    printf( "<link rel=\"stylesheet\" href=\"arbeit-erfasse.css\" type=\"text/css\">\n");
    printf( "</head>\n");
    printf( "<body>\n");
    echo "<pre>"; print_r( $_GET); echo "</pre>";
    foreach ( $_GET as $key=>$arg) {
      switch ($key) {
      case "id"       : $eine_id    = $arg;                   break; // case "id":
      case "datum"    : $ein_datum  = geparstes_datum( $arg); break;
      case "tafelart" : $tafelart   = $arg;                   break;
      default         :                                       break;
      }
    }
    verarbeite_id_datum_art( $eine_id, $ein_datum, $tafelart);
    printf( "</body>\n");
    printf( "</html>\n");
}

main();

?>
