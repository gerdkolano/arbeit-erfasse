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
    printf( "<link rel=\"stylesheet\" href=\"css-speichere.css\" type=\"text/css\">\n"); 
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
  foreach ($felder as $column => $inhalt) {  // Bei UPDATE dürfen die leeren Felder nicht weggelassen werden, sonst bleiben alte Werte in der Datenbank stehen
#   if ($inhalt != "") {
      $erg .= "$column = '$inhalt', ";
#   }
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

function wie_weiter_form( $zuletzt_bearbeitete_id) {
  $erfasse_skript = (new konstante)->erfasse_skript;
  $erg = "";
  $erg .= "";
  $fn = pathinfo(__FILE__,PATHINFO_BASENAME);
  $erg .= sprintf( "<input type=\"hidden\" name=\"RUFER\" value=\"$fn\">\n");

  $erg .= "<button class=\"button-e\" type=\"SUBMIT\" name=\"tafelart\" value=\"leer-kurz\"> Erzeuge neuen Datensatz mit kurzem Formular SU010 </button><br />\n";
  $erg .= "<button class=\"button-a\" type=\"SUBMIT\" name=\"tafelart\" value=\"leer-lang\"> Erzeuge neuen Datensatz mit langem Formular SU020 </button><br />\n";

  $erg .= "<input type=\"text\" name=\"datum\" value=\"\"                   >Datum<br />\n";
  $erg .= "<input type=\"text\" name=\"id\"    value=\"$zuletzt_bearbeitete_id\">Id   <br />\n";

  $erg .= "<button class=\"button-b\" type=\"SUBMIT\" name=\"tafelart\" value=\"voll-kurz\"> Editiere vorhandenen Datensatz mit kurzem Formular SU030 </button><br />\n";
  $erg .= "<button class=\"button-c\" type=\"SUBMIT\" name=\"tafelart\" value=\"voll-lang\"> Editiere vorhandenen Datensatz mit langem Formular SU040 </button><br />\n";

  $erg = sprintf( "<form method=\"GET\" action=\"%s\">\n%s\n</form>\n", $erfasse_skript, $erg);
  $erg_source = str_replace( "<", "&lt;"        , $erg);
  $erg_source = str_replace( ">", "&gt;<br />\n", $erg_source);
  return $erg . $erg_source;
}

function wie_weiter_anker( $ziel, $zieltext) {
  printf( "<a href=\"%s\"> %s </a> %s <br />\n", $ziel, $zieltext, $ziel);
}

function datensatz_schon_vorhanden( $wahl, $wert, $conn) {
  $erg = $conn->hol_einen_wert( "SELECT datum_auto as xxx FROM zeiten WHERE $wahl in ('$wert')", "xxx");
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
  $erfasse_skript = (new konstante)->erfasse_skript;
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
  $erfasse_skript = (new konstante)->erfasse_skript;
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
    angebot( "S048");
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

  /* Ändere einige Daten  gepostete__felder
  if ($gepostete__felder["erscheine"] == "Urlaub") {
    $gepostete__felder["arbeit_kommt"] = "00:00";
    $gepostete__felder["arbeit_geht" ] = "05:33";
  }
*/
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

  if ($geposteter_auftrag == "INSERT") {
    $zuletzt_bearbeitete_id = $conn->hol_last_inserted(); // INSERT // liefert 0 bei UPDATE // $zuletzt_bearbeitete_id = $conn->get_mysqli()->insert_id;
  } else {                                            // UPDATE
    $zuletzt_bearbeitete_id = $gepostet->id();
  }
  echo "S030 zuletzt_bearbeitete_id = \"$zuletzt_bearbeitete_id\" <br />\n";
  printf( "%s", "W050 " . wie_weiter_form( $zuletzt_bearbeitete_id));

}

function öffne_connection_zur_database( $database_name, $table_name, $conn) {
  $erg = $conn->frage( 0, "CREATE DATABASE IF NOT EXISTS $database_name");
  $erg = $conn->frage( 0, "USE $database_name");
  if (false) {
    $create = create( $table_name);
#   echo "S010 " . $create . "<br />\n";
    $erg = $conn->frage( 0, $create);
  }
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
