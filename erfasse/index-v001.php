<?php
$programm = array (
  "Leeres Formular und Mach eine Woche geltend"                  => "zeitkonto.php?start=2016-03&verbose=woche"            ,
  "Leeres Formular und Mach einen Monat geltend"                 => "zeitkonto.php?start=2016-03&verbose=monat"            ,
  "Leeres Formular : Mach eine Woche geltend. Daten veraltet"  => "formular-woche.php"                                   ,
  "Leeres Formular : Mach einen Monat geltend. Daten veraltet" => "formular-monat.php"                                   ,
  "Zeitkonto Vor- und jetziger Monat"                          => "zeitkonto.php"                                        ,
  "Zeitkonto Vor- und jetziger Monat. Zeige normalisierte"     => "zeitkonto.php?verbose=norm"                           ,
  "Mach Monate ab März 2016 geltend"                           => "zeitkonto.php?start=2016-03&verbose=monat"            ,
  "Mach Monate ab Dezember 2014 geltend"                       => "zeitkonto.php?start=2014-12&verbose=monat"            ,
  "Liste aller Tage Rohdaten"                                  => "zeitkonto.php?start=2014-12&verbose=liste"            ,
  "Liste aller Tage menschenlesbar"                            => "zeitkonto.php?start=2014-12&verbose=tage"             ,
  "gf*s 4.7plus Zeitkonto ab Dezember 2014"                    => "zeitkonto.php?start=2014-12"                          ,
  "gf*s 4.7plus Zeitkonto ab Dezember 2014 bis März 2016"      => "zeitkonto.php?start=2014-12&stop=2016-tage"           ,
  "gf*s 4.7plus Zeitkonto mit Saldo-Inkonsistenzen"            => "zeitkonto.php?start=2014-12&stop=2016-4&gfos=ja"      ,
  "Mach geltend - Erste Version"                               => "mache_geltend.php?datum=2014-11-24&anzahl=77"         ,
  "Saldo, gezeigt von der Infotaste"                           => "../kalender/anal-to-html.php"                         ,
  "Lies Mich"                                                  => "README"                                               ,
  "Zusammenhänge zwischen Verdienst und gf*s"                  => "../verdienst/abgegolten.txt"                          ,
  "Nichts"                                                     => "?edit"                                                ,
);                                                             
$editiere = array (                                            
  "&nbsp;"                                                     => ""                                                     ,
  "Tägliche Daten erfassen : Hier bitte einsteigen"            => "speichere.php"                                        ,
  "keym"                                                       => "erfasse.php"                                          ,
  "an-giebler"                                                 => "an-giebler.html"                                      ,
  "Verdienstabrechnung erfassen : Hier bitte einsteigen"       => "../verdienst/verdienstspeichere.php"                  ,
  "Verdienstabrechnung erfassen"                               => "../verdienst/verdiensterfasse.php?id=1"               ,
  "abgegolten"                                                 => "../simpel/vimpel.php"                                 ,
);

function fern( $host, array $programm, $adressat) {
  $erg = "";
  $erg .= "<h3> $adressat </h3>\n";
  $erg .= "<table border>\n";
  $erg .= "<!-- >Fern " . $_SERVER['REMOTE_ADDR'] . " -->\n";
  foreach ($programm as $key=>$val) {
    $erg .= sprintf( "<tr><td><a href=\"http://%s/arbeit/erfasse/%s\" target=\"_blank\">%s </a><td>%s\n", $host, $val, $val, $key);
  }
  $erg .= "</table>";
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
  echo fern( "zoe.xeo", array_merge( $programm, $editiere,
      liste_dateinamen_mit_suffix( ".", ".pdf"),
      liste_dateinamen_mit_suffix( "../pdf", ".pdf")),
    "Daheim.");
} else {
  if ($_SERVER['QUERY_STRING'] == "edit") {
    echo fern( "gerd.dyndns.za.net", array_merge( $programm, $editiere,
        liste_dateinamen_mit_suffix( ".", ".pdf"),
        liste_dateinamen_mit_suffix( "../pdf", ".pdf")),
      "Für Kenner. ");
  } else {
    echo fern( "gerd.dyndns.za.net", $programm,
      "Für Fred. Wenn's unverständlich ist, bitte fragen. ");
  }
}

?>
<?php

#finde_alle();

function liste_dateinamen_mit_suffix( $wo, $str) {
  $liste = array();
  if ($handle = opendir( $wo)) {
    /* Das ist der korrekte Weg, ein Verzeichnis zu durchlaufen. */
    while ($filename = readdir($handle)) {
      if (strpos($filename, $str) == strlen($filename) - strlen($str)) $liste[] = $filename; 
    }
    closedir( $handle);
  
    #sort( $liste);
  }
  return $liste;
}
  
function zeige_dateinamen_mit_suffix( $str) {
  $erg = "";
  if ($handle = opendir('.')) {
    /* Das ist der korrekte Weg, ein Verzeichnis zu durchlaufen. */
    while ($filename = readdir($handle)) {
      $liste[] = $filename; 
    }
    closedir( $handle);
  
    sort( $liste);
  
    foreach ($liste as $key => $filename) {
      if (strpos($filename, $str) == strlen($filename) - strlen($str)) {
        $erg .= sprintf( "<td>%s<td>%s\n<br />", $filename, $filename);
      }
    }
  }
  return $erg;
}
  
function finde_alle() {
  if ($handle = opendir('.')) {
    /* Das ist der korrekte Weg, ein Verzeichnis zu durchlaufen. */
    while ($filename = readdir($handle)) {
      $liste[] = $filename; 
    }
    closedir( $handle);
  
    $str = ".pdf";
    foreach ($liste as $key => $filename) {
      if (strpos($filename, $str) == strlen($filename) - strlen($str)) {
        echo "$filename\n<br />";
      }
    }
  
    echo "ende\n<br />";
  
    sort( $liste);
  
    foreach ($liste as $key => $filename) {
    echo "$filename\n<br />";
    }
  }
}
  
?>

</body>
</html>

