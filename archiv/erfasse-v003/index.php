<?php
$programm = array (
"zeitkonto.php"                                        ,
"zeitkonto.php?start=2014-12"                          ,
"zeitkonto.php?start=2014-12&stop=2016-4"              ,
"zeitkonto.php?start=2014-12&stop=2016-4&gfos=ja"      ,
"mache_geltend.php?datum=2014-11-24&anzahl=77"         ,
"../kalender/anal-to-html.php"                         ,
"README"                                               ,
);
$editiere = array (
"speichere.php"                                        ,
"erfasse.php"                                          ,
);

function fern( $host, array $programm, $adressat) {
  $erg = "";
  $erg .= "<h3> $adressat </h3>\n";
  $erg .= "<ol>\n";
  $erg .= "<!-- >Fern " . $_SERVER['REMOTE_ADDR'] . " -->\n";
  foreach ($programm as $key=>$val) {
    $erg .= sprintf( "<li><a href=\"http://%s/arbeit/erfasse/%s\" target=\"_blank\">%s</a>\n", $host, $val, $val);
  }
  $erg .= "</ol>";
  return $erg;
}

function heim() {
  $erg = "";
  $erg .= "<li>Fern " . $_SERVER['REMOTE_ADDR'];
  return $erg;
}

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php

if (preg_match("/^192\.168\./", $_SERVER['REMOTE_ADDR'])) {
  echo fern( "zoe.xeo", array_merge( $programm, $editiere), "Daheim.");
} else {
  if ($_SERVER['QUERY_STRING'] == "edit") {
    echo fern( "gerd.dyndns.za.net", array_merge( $programm, $editiere), "Für Fred. Wenn's unverständlich ist, bitte fragen. ");
  } else {
    echo fern( "gerd.dyndns.za.net", $programm, "Für Fred. Wenn's unverständlich ist, bitte fragen. ");
  }
}

?>

</body>
</html>

