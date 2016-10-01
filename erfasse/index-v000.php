<?php
$programm = array (
  "Leeres Formular : Mach eine Woche geltend"  => "formular-woche.php"                                   ,
  "Leeres Formular : Mach einen Monat geltend" => "formular-monat.php"                                   ,
  "Zeitkonto Vormonat und jetziger Monat"      => "zeitkonto.php"                                        ,
  "Mach Monate ab März 2016 geltend"           => "zeitkonto.php?start=2016-03&verbose=2"                ,
  "gf*s 4.7plus Zeitkonto ab Dezember 2014"    => "zeitkonto.php?start=2014-12"                          ,
  "keyf" => "zeitkonto.php?start=2014-12&stop=2016-4"              ,
  "keyg" => "zeitkonto.php?start=2014-12&stop=2016-4&gfos=ja"      ,
  "keyh" => "mache_geltend.php?datum=2014-11-24&anzahl=77"         ,
  "keyi" => "../kalender/anal-to-html.php"                         ,
  "keyj" => "README"                                               ,
  "keyk" => "edit"                                                 ,
);
$editiere = array (
  "keyl" => "speichere.php"                                        ,
  "keym" => "erfasse.php"                                          ,
  "keyn" => "../verdienst/verdiensterfasse.php?id=1"               ,
);

function fern( $host, array $programm, $adressat) {
  $erg = "";
  $erg .= "<h3> $adressat </h3>\n";
  $erg .= "<ol>\n";
  $erg .= "<!-- >Fern " . $_SERVER['REMOTE_ADDR'] . " -->\n";
  foreach ($programm as $key=>$val) {
    $erg .= sprintf( "<li><a href=\"http://%s/arbeit/erfasse/%s\" target=\"_blank\">%s </a>%s\n", $host, $val, $val, $key);
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
  echo fern( "zoe.xeo", array_merge( $programm, $editiere),
    "Daheim.");
} else {
  if ($_SERVER['QUERY_STRING'] == "edit") {
    echo fern( "gerd.dyndns.za.net", array_merge( $programm, $editiere),
      "Für Fred. Wenn's unverständlich ist, bitte fragen. ");
  } else {
    echo fern( "gerd.dyndns.za.net", $programm,
      "Für Fred. Wenn's unverständlich ist, bitte fragen. ");
  }
}

?>

</body>
</html>

