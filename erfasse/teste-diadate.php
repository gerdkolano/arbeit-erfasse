<?php
require_once( "../include/datum.php");

//$u = new diaDate('2012-10-22');
$u = new datum_objekt('2012-10-22');
echo $u->format('r e') . "<br />";
$u->modify('+3 days');
echo $u->format('r e') . "<br />";
$u->reset();
echo $u->format('r e') . "<br />";

$u = new datum_objekt();
echo $u->format('r e') . "<br />";
$u->modify('+3 days');
echo $u->format('r e') . "<br />";
$u->reset();
echo $u->format('r e') . "<br />";

?>
