<?php
require_once( "konstante.php");
require_once( "helfer.php");
require_once( "tabelle.php");

$database_name = "arbeit";
$table_name = "zeiten";

function parke() {
  echo "<pre>"; print_r( $_POST); echo "</pre>";
  echo "<pre>"; print_r( $erg); echo "</pre>";
}

function head() {
    printf( "<!DOCTYPE html>\n");
    printf( "<html>\n");
    printf( "<head>\n");
    printf( "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n");
    printf( "</head>\n");
    printf( "<body>\n");
}

function create( $table_name) {
  $felder = (new tabelle())->felder;
  $erg = "CREATE TABLE IF NOT EXISTS $table_name ( ";
  foreach ($felder as $column => $inhalt) {
    $erg .= "$column " . $inhalt->typ . ", ";
  }
  // $erg = substr( $erg, 0, -2);
  $erg = rtrim( $erg, " ,");
  $erg .= ")";
  return $erg;
}

function insert_query( $table_name, $felder) {
  // $felder  = $gepostet->get_datenfeld();

  $erg = "";
  foreach ($felder as $column => $inhalt) {
    $erg .= "$column" . ", ";
  }
  $erg = rtrim( $erg, " ,");
  $columns = $erg;

  $erg = "";
  foreach ($felder as $column => $inhalt) {
    $erg .= "'$inhalt', ";
  }
  $erg = rtrim( $erg, " ,");
  $werte = $erg;

  $erg  = "INSERT INTO $table_name ( ";
  $erg .= $columns;
  $erg .= ") VALUES ( ";
  $erg .= $werte;
  $erg .= ")";
  return $erg;
}

function update_query( $table_name, $felder, $id) {
  // $felder  = $gepostet->get_datenfeld();
  // $id = $gepostet->id();

  $erg  = "UPDATE $table_name SET ";
  foreach ($felder as $column => $inhalt) {
    $erg .= "$column = '$inhalt', ";
  }
  $erg = rtrim( $erg, " ,");
  $erg .= " WHERE id = $id"; 

  return $erg;
}

function geparstes_datum( $wort) {
  $erg = $wort;
# printf( "S017 %s <br />\n", date("o"));
  preg_match( "/(\d+)[^\d](\d+)[^\d](\d+)(.*)/", $wort, $matches);                                 // 18.3.16   oder 18.3.2016
  if (isset($matches[3]) and $matches[1] <= 31 and $matches[2] <=12) {
    $jahr = $matches[3] < 100 ? 2000 + $matches[3]  : $matches[3] ;
    $erg = sprintf( "%04d-%02d-%02d%s", $jahr, $matches[2], $matches[1], $matches[4]);
  } else {
    preg_match( "/(\d+)[^\d](\d+)(.*)/", $wort, $matches);                                         //  28.3
    if ($matches[1] <= 31 and $matches[2] <=12) {
      $erg = sprintf( "%04d-%02d-%02d%s", date("o"), $matches[2], $matches[1], $matches[3]);
    } else {
      preg_match( "/(\d+)-(\d+)-(\d+)/", $wort, $matches);                                         // 2016-03-18
      if (isset($matches[3])) {
        $erg =  $wort;
      }
    }
  }
  return trim( $erg);
}

function wie_weiter_form( ) {
  $zu_rufendes_skript = "erfasse.php";
  $erg = "";
  $erg .= "";
  $fn = pathinfo(__FILE__,PATHINFO_BASENAME);
  $erg .= sprintf( "<input type=\"hidden\" name=\"RUFER\" value=\"$fn\">\n");

  $submit_name = "datum"; $submit_inhalt = ""; $submit_label = "Editiere Datensatz mit dem Datum oder der Id";
  $erg .= "<button type=\"SUBMIT\" name=\"$submit_name\" value=\"$submit_inhalt\"> $submit_label </button><br />\n";

  $erg .= "<input type=\"text\" name=\"$submit_name\" value=\"$submit_inhalt\">Datum<br />\n";

  $submit_name = "id"; $submit_inhalt = ""; $submit_label = "Editiere Datensatz mit dem Datum";
  $erg .= "<input type=\"text\" name=\"$submit_name\" value=\"$submit_inhalt\">Id<br />\n";

  $erg = sprintf( "<form method=\"GET\" action=\"%s\">\n%s\n</form>\n", $zu_rufendes_skript, $erg);
  return $erg;
}

function wie_weiter_anker( $ziel, $zieltext) {
  printf( "<a href=\"%s\"> %s </a><br />\n", $ziel, $zieltext);
}

function datensatz_schon_vorhanden( $wahl, $wert, $conn) {
  $erg = $conn->hol_einen_wert( "SELECT datum_auto as xxx FROM zeiten WHERE $wahl in ('$wert')", "xxx");
  printf( "S041 %s <br />\n", $erg);
  return $erg == "" ? false : $erg;
}

function nichts_gepostet( $conn) {
  $id    = isset($_GET["id"   ]) ? $_GET["id"   ] : false;
  $datum = isset($_GET["datum"]) ? $_GET["datum"] : false;
  $wahl  = isset($_GET["id"   ]) ?       "id"     : false;
  $wahl  = isset($_GET["datum"]) ?       "datum"  : false;

  printf( "S040 <br />\n");

  switch ($wahl) {
  case "id"    :
    $erg = datensatz_schon_vorhanden( $wahl, $_GET[$wahl], $conn)
      ? printf( "S042 %s %s<br />\n", $erg, "erfasse.php?id=$wahl")
      : printf( "S043 <br />\n")
      ;

    printf( "S044 %s %s<br />\n", $wahl, $_GET[$wahl]);
    break;
  case "datum" :
    $erg = datensatz_schon_vorhanden( $wahl, $_GET[$wahl], $conn)
      ? printf( "S045 %s %s<br />\n", $erg, "erfasse.php?$wahl=".$_GET[$wahl])
      : printf( "S046 %s?%s=%s <br />\n", "erfasse.php", "wunschdatum", $_GET[$wahl])
      ;

    printf( "S047 %s %s<br />\n", $wahl, $_GET[$wahl]);
    break;
  default      :
    printf( "S048 %s <br />\n", "erfasse.php");
    break;
  }
}

function tu_was( $table_name, $gepostet, $conn) {
  $geposteter_auftrag = $gepostet->geposteter_auftrag();
  echo "S017 geposteter_auftrag = :$geposteter_auftrag: <br />\n"; 
  if ( ! ($geposteter_auftrag == "UPDATE" or $geposteter_auftrag == "INSERT") ) {
    nichts_gepostet( $conn); return;
  } 

  $gepostete__felder  = $gepostet->get_datenfeld();

  // Ändere einige Daten  gepostete__felder
  $gepostete__felder["datum_auto"] =  geparstes_datum( $gepostete__felder["datum"]);
  printf( "S018 \"%s\" wird zu \"%s\" <br />\n", $gepostete__felder["datum"], $gepostete__felder["datum_auto"]); 
  $gepostete__felder["datum"]      =  geparstes_datum( $gepostete__felder["datum"]);
  
  switch ($geposteter_auftrag) {
  case "UPDATE": $query = update_query( $table_name, $gepostete__felder, $gepostet->id()); break; 
  case "INSERT": $query = insert_query( $table_name, $gepostete__felder); break; 
  default      : nichts_gepostet( $conn);    return; break;
  } # echo "S020 query = \"$query\" <br />\n";

  // ------------------------------------------------------------------ Tus wirklich : INSERT oder UPDATE
  $erg = $conn->frage( 0, $query);

  $last_inserted = $conn->hol_last_inserted(); // liefert 0 bei UPDATE // $last_inserted = $conn->get_mysqli()->insert_id;
  echo "S030 last_inserted = \"$last_inserted\" <br />\n";

  $rufer = $gepostet->rufer();
  echo "S032 rufer = \"$rufer\" <br />\n";
  echo "S034 id    = \"" . $gepostet->id() . "\" <br />\n";

  $query_string = strtok ( $rufer, "?");
  $query_string = strtok ( "?");
  printf( "S036 query_string = \"%s\"<br />\n", $query_string);
  parse_str ( $query_string, $params);
  foreach ($params as $key => $val) {
    printf( "S038 \"%s\" = \"%s\"<br />\n", $key, $val);
  }

  wie_weiter_anker( strtok ( $rufer, "?"),                     "Erfasse weitere Daten.");
  printf( " oder <br />\n");
  wie_weiter_anker( strtok ( $rufer, "?") . "?k=1",                     "Erfasse weitere Daten Kurzformular.");
  printf( " oder <br />\n");
  wie_weiter_anker( sprintf( "%s&%s=%s", $rufer, (new GET_parameter)->get_name_fuer_id, $last_inserted), "Ändere die soeben erfassten Daten. ID Nummer " . $gepostet->id());
  printf( " oder <br />\n");
  wie_weiter_anker( sprintf( "%s?%s=%s", $rufer, (new GET_parameter)->get_name_fuer_id, $gepostet->id()), "Ändere die soeben erfassten Daten. ID Nummer " . $gepostet->id());
  printf( " oder <br />\n");
  wie_weiter_anker( sprintf( "%s?k=1&%s=%s", strtok ( $rufer, "?"), (new GET_parameter)->get_name_fuer_id, $gepostet->id()), "Ändere Kurzformular. ID Nummer " . $gepostet->id());
  printf( " oder <br />\n");
 
  printf( "%s", wie_weiter_form());

//$ziel = $rufer;                  $zieltext = "Erfasse weitere Daten.";                                      printf( "<a href=\"%s\"> %s </a>", $ziel, $zieltext);
//                                                                                                              printf( " oder ");
//$ziel = "$rufer?id=$last_inserted"; $zieltext = "Ändere die soeben erfassten Daten. ID Nummer $last_inserted"; printf( "<a href=\"%s\"> %s </a>", $ziel, $zieltext);
}

function öffne_connection_zur_database( $database_name, $table_name, $conn) {
  $erg = $conn->frage( 0, "CREATE DATABASE IF NOT EXISTS $database_name");
  $erg = $conn->frage( 0, "USE $database_name");
  $create = create( $table_name);
# echo "S010 " . $create . "<br />\n";
  $erg = $conn->frage( 0, $create);
}

head();

$gepostet = new gepostet();
# echo "S088 <br />\n" . $gepostet->zeig();
echo "POST <pre>"; print_r( $_POST); echo "</pre>\n";
echo "GET  <pre>"; print_r( $_GET); echo "</pre>\n";


$conn = new conn();

öffne_connection_zur_database( $database_name, $table_name, $conn);
tu_was( $table_name, $gepostet, $conn);

?>
