<?php
# require_once("abgegolten.php");
require_once("../erfasse/abgeltung.php");

$wann = "2016-03-01";
echo "$wann ";
$host = "zoe.xeo";
echo " $host ";
echo new abgegolten( new datum_objekt( $wann), $host); echo " fertig <br />\n";
?>
