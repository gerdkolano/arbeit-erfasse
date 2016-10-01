<?php
function geparstes_datum( $wort) {
  $erg = $wort;
# printf( "S015 %s <br />\n", date("o"));
  preg_match( "/(\d+)[^\d](\d+)[^\d](\d+)(.*)/", $wort, $matches);                                 // 18.3.16   oder 18.3.2016
  if (isset($matches[3]) and $matches[1] <= 31 and $matches[2] <=12) {
    $jahr = $matches[3] < 100 ? 2000 + $matches[3]  : $matches[3] ;
    $erg = sprintf( "%04d-%02d-%02d%s", $jahr, $matches[2], $matches[1], $matches[4]);
    return trim( $erg);
  } else {
    preg_match( "/(\d+)[^\d](\d+)(.*)/", $wort, $matches);                                         //  28.3
    if (isset($matches[1]) and isset($matches[2]) and isset($matches[3]) and $matches[1] <= 31 and $matches[2] <=12) {
      $erg = sprintf( "%04d-%02d-%02d%s", date("o"), $matches[2], $matches[1], $matches[3]);
      return trim( $erg);
    } else {
      preg_match( "/(\d+)-(\d+)-(\d+)/", $wort, $matches);                                         // 2016-03-18
      if (isset($matches[3])) {
        $erg =  $wort;
        return trim( $erg);
      }
    }
  }
  return false;
}

function datum_create( $wort) {
  $formate = array (
    "d",
    "d.",
    "d.m",
    "d.m.",
    "d.m.y",
    "Y-m",
    "Y-m-d",
  );
  foreach ($formate as $eine_form) {
    if ($date = datum_objekt::createFromFormat( $eine_form, $wort)) return $date;
  }
  return new datum_objekt();
}

function geparstes_datum_obsolet( $wort) {
  $erg = $wort;
# printf( "S015 %s <br />\n", date("o"));
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

?>
