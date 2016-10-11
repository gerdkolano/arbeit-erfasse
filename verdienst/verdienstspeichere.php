<?php
require_once( "gepostet.php");
require_once( "../include/konst.php");
require_once( "connection.php");
require_once( "verdiensttabelle.php");
require_once( "parser.php");
require_once( "../include/datum.php");

function head() {
  $zuletzt_aktualisiert = "Zuletzt aktualisiert: So 2016-05-29 21:16:08";
  printf( "<!DOCTYPE html>\n");
  printf( "<html>\n");
  printf( "<head>\n");
  printf( "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n");
  printf( "<link rel=\"stylesheet\" href=\"arbeit-erfasse.css\" type=\"text/css\">\n"); 
  printf( "</head>\n");
  printf( "<body>\n");
  printf( "%s <br />\n", $zuletzt_aktualisiert);
}

function create( $table_name) {
  $felder = (new tabelle())->felder;
  $erg = "CREATE TABLE IF NOT EXISTS $table_name ( ";
  foreach ($felder as $column => $inhalt) {
    $erg .= "$column " . $inhalt->mysql_typ . ", ";
  }
  // $erg = substr( $erg, 0, -2);
  $erg = rtrim( $erg, " ,");
  $erg .= ")";
  return $erg;
}

function insert_query( $table_name, $felder) {
  // $felder  = $gepostet->get_datenfeld();

  $columns = "";
  $werte = "";

  foreach ($felder as $column => $inhalt) {
    if ($inhalt != "") {                     // Bei INSERT dürfen die leeren Felder weggelassen werden, mysql füllt sie mit Defaults
      $columns .= "$column" . ", ";
      $werte .= "'$inhalt', ";
    }
  }

  $columns = rtrim( $columns, " ,");
  $werte = rtrim( $werte, " ,");

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
                 // Bei UPDATE dürfen die leeren Felder nicht weggelassen werden, sonst bleiben alte Werte in der Datenbank stehen
                 //   if ($inhalt != "") {
                 //     $erg .= "$column = '$inhalt', ";
                 //   }
                 // Bei UPDATE müssen die leeren Felder mit NULL gefüllt werden, bei decimals wird sonst aus '' ein 0.00 
    if ($inhalt == "") {
      $erg .= "$column = NULL, "; // NULL ohne Quotes ergibt mysql-NULL
    } else {
      $erg .= "$column = '$inhalt', ";
    }
  }
  $erg = rtrim( $erg, " ,");
  $erg .= " WHERE id = $id"; 

  return $erg;
}

function wie_weiter_form( $zuletzt_bearbeitete_id) {
  $erfasse_skript = konst::$verdienst_erfasse_skript;
  $erg = "";
  $erg .= "";
  $fn = pathinfo(__FILE__,PATHINFO_BASENAME);
  $erg .= sprintf( "<input type=\"hidden\" name=\"RUFER\" value=\"$fn\">\n");

  $erg .= "<button class=\"button-e\" type=\"SUBMIT\" name=\"tafelart\" value=\"mini\"> Arbeite mit Mini-Formular SU010 </button><br />\n";
# $erg .= "<button class=\"button-a\" type=\"SUBMIT\" name=\"tafelart\" value=\"lang\"> Erzeuge neuen Datensatz mit langem Formular SU020 </button><br />\n";

  $erg .= "<button class=\"button-b\" type=\"SUBMIT\" name=\"tafelart\" value=\"kurz\"> Arbeite mit kurzem Formular SU030 </button><br />\n";
  $erg .= "<button class=\"button-c\" type=\"SUBMIT\" name=\"tafelart\" value=\"lang\"> Arbeite mit langem Formular SU040 </button><br />\n";

  $erg .= "<input type=\"text\" name=\"datum\" value=\"\"                   >Datum hat Vorrang vor Id<br />\n";
  $erg .= "<input type=\"text\" name=\"id\"    value=\"$zuletzt_bearbeitete_id\">Id   <br />\n";

  $erg = sprintf( "<form method=\"GET\" action=\"%s\">\n%s\n</form>\n", $erfasse_skript, $erg);
  return $erg;
  // Zeige den HTML-Code dieser Form.
  $erg_source = str_replace( "<", "&lt;"        , $erg);
  $erg_source = str_replace( ">", "&gt;<br />\n", $erg_source);
  return $erg . $erg_source;
}

function wie_weiter_anker( $ziel, $zieltext) {
  printf( "<a href=\"%s\"> %s </a> %s <br />\n", $ziel, $zieltext, $ziel);
}

function datensatz_schon_vorhanden( $wahl, $wert, $conn) {
  $erg = $conn->hol_einen_wert( "SELECT datum as xxx FROM zeiten WHERE $wahl in ('$wert')", "xxx");
# printf( "S041 %s <br />\n", $erg);
  return $erg == "" ? false : $erg;
}

function url_origin( $s, $use_forwarded_host = false ) {
    $ssl      = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
    $sp       = strtolower( $s['SERVER_PROTOCOL'] );
    $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
    $port     = $s['SERVER_PORT'];
    $port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
    $host     = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) )
      ? $s['HTTP_X_FORWARDED_HOST']
      : ( isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null )
      ;
    $host     = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;
    return $protocol . '://' . $host;
}

function full_url( $s, $use_forwarded_host = false ) {
    return url_origin( $s, $use_forwarded_host ) . $s['REQUEST_URI'];
}

function curPageName_() {
   return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/") + 1);
}

function curPageName( $arg) {
   return substr( $arg, 0, strrpos( $arg, "/") + 1);
   return substr( $arg, strrpos( $arg, "/") + 1);
}

function angebot( $meldung) {
# printf( "S040 %s <br />\n", $meldung);
  $erfasse_skript = konst::$verdienst_erfasse_skript;
  $parameter = sprintf( "?%s=%s",
    "id"   , "1"
  );
  $url = sprintf( "%s%s%s", curPageName( full_url( $_SERVER )), $erfasse_skript, $parameter);
  printf("E047 Versuche <a href=\"%s\"> %s </a><br />\n", $url, $url);
  $parameter = sprintf( "?%s=%s",
    "datum", "2015-1-1"
  );
  $url = sprintf( "%s%s%s", curPageName( full_url( $_SERVER )), $erfasse_skript, $parameter);
  printf("E048 Versuche <a href=\"%s\"> %s </a><br />\n", $url, $url);

}

function nichts_gepostet( $conn) {
  $erfasse_skript = konst::$verdienst_erfasse_skript;
  //$erfasse_skript = "erfasse.php";
  $id    = isset($_GET["id"   ]) ? $_GET["id"   ] : false;
  $datum = isset($_GET["datum"]) ? $_GET["datum"] : false;
  $wahl  = isset($_GET["id"   ]) ?       "id"     : false;
  $wahl  = isset($_GET["datum"]) ?       "datum"  : false;

# printf( "S040 <br />\n");

  switch ($wahl) {
  case "id"    :
    $erg = datensatz_schon_vorhanden( $wahl, $_GET[$wahl], $conn)
      ? printf( "S042 %s?%s=%s <br />\n", $erfasse_skript, $wahl, $_GET[$wahl])
      : angebot( "S043")
      ;

    printf( "S044 %s %s<br />\n", $wahl, $_GET[$wahl]);
    break;
  case "datum" :
    $erg = datensatz_schon_vorhanden( $wahl, $_GET[$wahl], $conn)
      ? printf( "S045 %s?%s=%s <br />\n", $erfasse_skript, $wahl, $_GET[$wahl])
      : angebot( "S046")
      ;

#   printf( "S047 %s %s<br />\n", $wahl, $_GET[$wahl]);
    break;
  default      :
    printf( "%s\n%s", "<!-- W050 -->", wie_weiter_form( 0));
    break;
  }
}

function tu_was( $table_name, $gepostet, $conn) {
# echo "<pre>"; print_r( $_POST); echo "</pre>";
  $geposteter_auftrag = $gepostet->geposteter_auftrag();
  if ( ! ($geposteter_auftrag == "UPDATE" or $geposteter_auftrag == "INSERT") ) {
    nichts_gepostet( $conn); return;
  } 

  $gepostete__felder  = $gepostet->get_datenfeld();

  /* Ändere einige Daten  gepostete__felder
  if ($gepostete__felder["erscheine"] == "Urlaub") {
    $gepostete__felder["arbeit_kommt"] = "00:00";
    $gepostete__felder["arbeit_geht" ] = "05:33";
  }
*/
  // Ändere einige Daten  gepostete__felder
  $geparstes_datum =  geparstes_datum( $gepostete__felder["datum"]);
  if ($geparstes_datum) {
#   printf( "S018 \"%s\" wird zu \"%s\" <br />\n", $gepostete__felder["datum"], $geparstes_datum); 
    $gepostete__felder["datum"     ] = $geparstes_datum;
  }
  
  switch ($geposteter_auftrag) {
  case "UPDATE": $query = update_query( $table_name, $gepostete__felder, $gepostet->id()); break; 
  case "INSERT": $query = insert_query( $table_name, $gepostete__felder); break; 
  default      : nichts_gepostet( $conn);    return; break;
  } # echo "S020 query = \"$query\" <br />\n";

  // ------------------------------------------------------------------ Tus wirklich : INSERT oder UPDATE
  $erg = $conn->frage( 0, $query);

  if ($geposteter_auftrag == "INSERT") {
    $zuletzt_bearbeitete_id = $conn->hol_last_inserted(); // INSERT // liefert 0 bei UPDATE // $zuletzt_bearbeitete_id = $conn->get_mysqli()->insert_id;
  } else {                                            // UPDATE
    $zuletzt_bearbeitete_id = $gepostet->id();
  }
  $datum = $gepostete__felder["datum"];

# printf( "Erledigt: %s Datum=\"%s\" id=%s<br />\n", $geposteter_auftrag, (new DateTime( $datum))->format('l, Y-m-d'), $zuletzt_bearbeitete_id); 
  printf( "Erledigt: %s Datum=\"%s\" id=%s<br />\n", $geposteter_auftrag, (new datum_objekt( $datum))->deutsch('EEEE, d. MMMM YYYY'), $zuletzt_bearbeitete_id);
# echo "S030 zuletzt_bearbeitete_id = \"$zuletzt_bearbeitete_id\" <br />\n";
  printf( "%s\n%s", "<!-- W050 -->", wie_weiter_form( $zuletzt_bearbeitete_id));

}

function öffne_connection_zur_database( $database_name, $table_name, $conn) {
  $erg = $conn->frage( 0, "CREATE DATABASE IF NOT EXISTS $database_name");
  $erg = $conn->frage( 0, "USE $database_name");
  if (true) {
    $create = create( $table_name);
#   echo "S010 " . $create . "<br />\n";
    $erg = $conn->frage( 0, $create);
  }
}

head();

$gepostet = new gepostet();
# echo "S088 <br />\n" . $gepostet->zeig();
# echo "POST <pre>"; print_r( $_POST); echo "</pre>\n";
# echo "GET  <pre>"; print_r( $_GET); echo "</pre>\n";


$conn = new conn();

$database_name = konst::$database_name;
$table_name    = konst::$verdienst_tafel_name   ;

öffne_connection_zur_database( $database_name, $table_name, $conn);
tu_was( $table_name, $gepostet, $conn);

?>
