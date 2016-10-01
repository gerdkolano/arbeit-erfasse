<?php
require_once( "helfer.php");

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

$gepostet = new gepostet();
$gepostet->zeig();

$conn = new conn();
$erg = $conn->frage( 0, "CREATE DATABASE arbeit");
$erg = $conn->frage( 0, "USE arbeit");
$erg = $conn->frage( 0, "CREATE TABLE MyGuests (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
firstname VARCHAR(30) NOT NULL,
lastname VARCHAR(30) NOT NULL,
email VARCHAR(50),
reg_date TIMESTAMP
) ");

$erg = $conn->hol_array_of_objects( "SELECT 22 as zahl");
echo "<pre> \$erg"; print_r( $erg); echo "</pre>";
echo "<pre> \$erg[0]"; print_r( $erg[0]); echo "</pre>";
echo "<pre> \$erg[0][\"zahl\"]"; print_r( $erg[0]["zahl"]); echo "</pre>";
echo "<pre> current( \$erg)"; print_r( current( $erg)); echo "</pre>";

while ($zahl = current( $erg)) {
  echo "<pre> \$zahl"; print_r( $zahl); echo "</pre>";
  printf( "%s <br />\n", $zahl["zahl"]);
  next( $erg);
}

$erg = $conn->hol_array_of_objects( "SELECT 333");
foreach ($erg as $key=>$value) {
  foreach ($value as $shlüssel=>$wert) {
    printf( "%s %s<br />\n", $shlüssel, $wert);
  }
}

$erg = $conn->hol_array_of_objects( "SELECT vorname, name from joo336.st_stamm WHERE selbst < 13");
foreach ($erg as $key=>$value) {
  printf( "key %s<br />\n", $key);
  foreach ($value as $shlüssel=>$wert) {
    printf( "%s %s %s<br />\n", $key, $shlüssel, $wert);
  }
}
  foreach ($erg[0] as $shlüssel=>$wert) {
    printf( "%s %s<br />\n", $shlüssel, $wert);
  }

?>
