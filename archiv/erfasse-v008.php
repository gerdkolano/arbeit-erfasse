<?php
require_once( "helfer.php");
require_once( "tabelle.php");

function main() {
  if (php_sapi_name()==="cli") { // von der Kommandozeile gerufen
  #if (false) { // zum Testen
  #  echo count($_SERVER['argv']) . "\n";
    $eine_id = "";
    $ein_datum  = "";
    // foreach geht nicht
    while ( $arg = next( $_SERVER['argv'])) {
      switch ($arg) {
      case "-a": $eine_id = next ($_SERVER['argv']); break;
      case "-e": $ein_datum  = next ($_SERVER['argv']); break;
      default : echo "M020 arg $arg\n"; break;
      }
    }
    arbeite( $eine_id, $ein_datum);
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
    $eine_id = "";
    $ein_datum  = "";
    #while ( $arg = next( $_GET)) {
    foreach ( $_GET as $key=>$arg) {
      # echo "<pre>"; print_r( $arg); echo "</pre>";
      switch ($key) {
      case "id": $eine_id = $arg; break;
      case "datum" : $ein_datum  = $arg; break;
      default : echo "M030 key $key arg $arg\n"; break;
      }
    }
    printf( "</pre>\n");
    arbeite( $eine_id, $ein_datum);
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

  $erg .= row_button( $submit_name, $submit_label);
  //$submit_inhalt = "gesandt";
  //$erg .= "  <tr><td><button type=\"SUBMIT\" name=\"$submit_name\" value=\"$submit_inhalt\"> $submit_label </button>\n";
  
  $fn = pathinfo(__FILE__,PATHINFO_BASENAME);
  $erg .= sprintf( "<input type=\"hidden\" name=\"RUFER\" value=\"$fn\">\n");

  return $erg;
}

function row_button( $submit_name, $submit_label) {
  $erg = "";
  $submit_inhalt = "gesandt";
  $erg .= "  <tr><td><button type=\"SUBMIT\" name=\"$submit_name\" value=\"$submit_inhalt\"> $submit_label </button>\n";
  return $erg;
}

function form_update( $arg) {
  $spalte = (new tabelle())->felder;

  $erg = form_kopf( "UPDATE", "Diesen geänderten Datensatz erneuern");

  foreach ($arg as $column => $vorhanden) {
    $inhalt = $spalte[$column];
    $erg .= table_row( $inhalt, $column, $vorhanden);
  }
  $erg .= row_button( "UPDATE", "Diesen geänderten Datensatz erneuern");
  $farbe = "#fff0f0";
  $actionskript = "speichere.php";
  $erg = "<table style=\"background-color:$farbe\">\n$erg</table>";
  return sprintf( "<form method=\"POST\" action=\"$actionskript\">\n$erg\n</form>\n", "");
}

function form_insert( $arg) {
  $spalte = (new tabelle())->felder;

  $erg = form_kopf( "INSERT", "Diesen neuen Datensatz speichern");

  foreach ($spalte as $column => $inhalt) {
    $vorhanden = "";
    $erg .= table_row( $inhalt, $column, $vorhanden);
  }

  $erg .= row_button( "INSERT", "Diesen neuen Datensatz speichern");
  //$erg .= "  <tr><td><button type=\"SUBMIT\" name=\"$submit_name\" value=\"$submit_inhalt\"> $submit_label </button>\n";
  $farbe = "#fff0f0";
  $actionskript = "speichere.php";
  $erg = "<table style=\"background-color:$farbe\">\n$erg</table>";
  return sprintf( "<form method=\"POST\" action=\"$actionskript\">\n$erg\n</form>\n", "");
}

function arbeite( $eine_id = "", $ein_datum = "") {
  if (isset( $ein_datum) and $ein_datum != "") {
    $where =  "WHERE datum_auto = '$ein_datum'";
  } else {
    if (isset( $eine_id) and is_numeric( $eine_id) and $eine_id != "") {
      $where =  "WHERE id = '$eine_id'";
    } else {
      $where =  "";
    }
  }
  arbeite_0( $where);
}

function arbeite_0( $where) {
# echo "E060 arbeite LOS $eine_id !<br />\n";
  if ($where != "") {
#   echo "E030 LOS gehts $eine_id !<br />\n";
    $conn = new conn();

#   $erg = $conn->frage( 0, "SELECT * FROM $table");
#   echo "<pre>"; print_r( $felder); echo "!</pre><br />\n";

    $database_name = "arbeit";
    $erg = $conn->frage( 0, "USE $database_name");

    $table = "zeiten";
    $query = "SELECT * FROM $table $where";
    $erg = $conn->hol_array_of_objects( "$query"); // todo Fehlerbehandlung
    if (!$erg) {
      printf("E020 Fehler %s<br />\n", $_SERVER["REQUEST_URI"]); //  __DIR__, __FILE__,
      $url = sprintf("http://%s/%s", $_SERVER["SERVER_NAME"], $_SERVER["SCRIPT_NAME"]); //  __DIR__, __FILE__,
      printf("E030 Versuche <a href=\"%s\"> %s </a><br />\n", $url, $url);
    }
    # echo "<pre>"; print_r( $erg); echo "</pre>";
    $erg = form_update( $erg[0]);
    printf( "%s", $erg);
    return;
  }

  printf( "%s", form_insert( ""));
}

main();
  # echo "<pre>"; print_r( $spalte); echo "!</pre><br />\n";
?>
