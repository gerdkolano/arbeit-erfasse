<?php
require_once( "helfer.php");
require_once( "tabelle.php");

$database_name = "arbeit";
$table_name = "zeiten";

function head() {
    printf( "<!DOCTYPE html>\n");
    printf( "<html>\n");
    printf( "<head>\n");
    printf( "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n");
    printf( "</head>\n");
    printf( "<body>\n");
}

head();

# echo "<pre>"; print_r( $_POST); echo "</pre>";
# echo "<pre>"; print_r( $erg); echo "</pre>";

$gepostet = new gepostet();
$gepostet->zeig();

$conn = new conn();
$erg = $conn->frage( 0, "CREATE DATABASE IF NOT EXISTS $database_name");
$erg = $conn->frage( 0, "USE $database_name");

$erg = $conn->frage( 0, "CREATE TABLE IF NOT EXISTS zu_loeschen (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
firstname VARCHAR(30) NOT NULL,
lastname VARCHAR(30) NOT NULL,
email VARCHAR(50),
reg_date TIMESTAMP
) ");

# $erg = $conn->frage( 0, "CREATE TABLE IF NOT EXISTS new_table LIKE $table_name");
# $erg = $conn->frage( 0, "INSERT new_table SELECT * FROM $table_name");
# ALTER TABLE new_table ADD tageseroeffnung text AFTER sitzt
# $conn->frage( 0, "DELETE FROM $tafel WHERE selbst >= 5324");
# $conn->frage( 0, "ALTER TABLE $tafel AUTO_INCREMENT = 5324;");
# dt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
# ALTER TABLE zeiten ADD aktualsiert TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER bemerkung;
# $conn->frage( 0, "DELETE FROM $tafel WHERE selbst >= 5324");
# $conn->frage( 0, "ALTER TABLE $tafel AUTO_INCREMENT = 5324;");


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

function insert( $table_name, $gepostet) {
  $felder  = $gepostet->get_datenfeld();

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

function update( $table_name, $gepostet) {
  $felder  = $gepostet->get_datenfeld();
  $id = $gepostet->id();

  $erg  = "UPDATE $table_name SET ";
  foreach ($felder as $column => $inhalt) {
    $erg .= "$column = '$inhalt', ";
  }
  $erg = rtrim( $erg, " ,");
  $erg .= " WHERE id = $id"; 

  return $erg;
}

function tu_was( $table_name, $gepostet, $conn) {
  $felder  = $gepostet->get_datenfeld();
  $auftrag = $gepostet->auftrag();
  echo "S018 auftrag = \"$auftrag\" <br />\n"; 
  switch ($auftrag) {
  case "UPDATE": $query = update( $table_name, $gepostet); break; 
  case "INSERT": $query = insert( $table_name, $gepostet); break; 
  default:        break;
  }
  echo "S020 query = \"$query\" <br />\n";
  $erg = $conn->frage( 0, $query);
}

function bereite_vor( $table_name, $conn) {
  $create = create( $table_name);
  echo "S010 " . $create . "<br />\n";
  $erg = $conn->frage( 0, $create);
}

bereite_vor( $table_name, $conn);
tu_was( $table_name, $gepostet, $conn);

?>

