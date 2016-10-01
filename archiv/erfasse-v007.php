<?php
require_once( "helfer.php");
require_once( "tabelle.php");

function main () {
  if (php_sapi_name()==="cli") { // von der Kommandozeile gerufen
  #if (false) { // zum Testen
  #  echo count($_SERVER['argv']) . "\n";
    $eine_id = "";
    $stop  = "";
    // foreach geht nicht
    while ( $arg = next( $_SERVER['argv'])) {
      switch ($arg) {
      case "-a": $eine_id = next ($_SERVER['argv']); break;
      case "-e": $stop  = next ($_SERVER['argv']); break;
      default : echo "arg $arg\n"; break;
      }
    }
    arbeite( $eine_id, $stop);
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
    $stop  = "";
    #while ( $arg = next( $_GET)) {
    foreach ( $_GET as $key=>$arg) {
      # echo "<pre>"; print_r( $arg); echo "</pre>";
      switch ($key) {
      case "id": $eine_id = $arg; break;
      case "stop" : $stop  = $arg; break;
      default : echo "arg $arg\n"; break;
      }
    }
    printf( "</pre>\n");
    arbeite( $eine_id, $stop);
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
  $erg .= "<td>" . $column;
  $erg .= "<td>" . $inhalt->muster;
  $erg .= "<td>" . $inhalt->bedeutung . "\n";
  return $erg;
}

function form_kopf( $submit_name) {
  $erg = "";
  $erg .= "<style>  tr td:nth-child(1) {text-align: right;}  </style>\n";

  $fn = pathinfo(__FILE__,PATHINFO_BASENAME);
  $erg .= sprintf( "<input type=\"hidden\" name=\"RUFER\" value=\"$fn\">\n");

  $submit_label = "Vorhandenen Datensatz erneuern"; $submit_inhalt = "gesandt";
  $erg .= "      <td><button type=\"SUBMIT\" name=\"$submit_name\" value=\"$submit_inhalt\"> $submit_label </button>\n";
  return $erg;
}

function form_fusz( $submit_name) {
  $erg = "";
  $submit_label = "Vorhandenen Datensatz erneuern"; $submit_inhalt = "gesandt";
  $erg .= "  <tr><td><button type=\"SUBMIT\" name=\"$submit_name\" value=\"$submit_inhalt\"> $submit_label </button>\n";
  return $erg;
}

function form_update( $arg) {
  $spalte = (new tabelle())->felder;

  $erg = form_kopf( "UPDATE");

  foreach ($arg as $column => $vorhanden) {
    $inhalt = $spalte[$column];
    $erg .= table_row( $inhalt, $column, $vorhanden);
  }
  $erg .= form_fusz( "UPDATE");
  $farbe = "#fff0f0";
  $actionskript = "speichere.php";
  $erg = "<table style=\"background-color:$farbe\">\n$erg</table>";
  return sprintf( "<form method=\"POST\" action=\"$actionskript\">\n$erg\n</form>\n", "");
}

function form_insert( $arg) {
  $spalte = (new tabelle())->felder;

  $erg = "";
  $erg .= "<style>  tr td:nth-child(1) {text-align: right;}  </style>\n";

  $fn = pathinfo(__FILE__,PATHINFO_BASENAME);
  $erg .= sprintf( "<input type=\"hidden\" name=\"RUFER\" value=\"$fn\">\n");

  $submit_name = "INSERT"; $submit_label = "Als neuen Datensatz speichern"; $submit_inhalt = "gesandt";
  $erg .= "  <tr><td><button type=\"SUBMIT\" name=\"$submit_name\" value=\"$submit_inhalt\"> $submit_label </button>\n";

  foreach ($spalte as $column => $inhalt) {
    $vorhanden = "";
    $erg .= table_row( $inhalt, $column, $vorhanden);
  }

  $erg .= "  <tr><td><button type=\"SUBMIT\" name=\"$submit_name\" value=\"$submit_inhalt\"> $submit_label </button>\n";
  $actionskript = "speichere.php";
  $farbe = "#fff0f0";
  $erg = "<table style=\"background-color:$farbe\">\n$erg</table>";
  return sprintf( "<form method=\"POST\" action=\"$actionskript\">\n$erg\n</form>\n", "");
}

function arbeite( $eine_id = "", $stop = "") {
# echo "E060 arbeite LOS $eine_id !<br />\n";
  if (isset( $eine_id) and is_numeric( $eine_id) and $eine_id != "") {
#   echo "E030 LOS gehts $eine_id !<br />\n";
    $conn = new conn();

#   $erg = $conn->frage( 0, "SELECT * FROM $table");
#   echo "<pre>"; print_r( $felder); echo "!</pre><br />\n";

    $database_name = "arbeit";
    $erg = $conn->frage( 0, "USE $database_name");

    $table = "zeiten";
    $query = "SELECT * FROM $table WHERE id = $eine_id";
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
