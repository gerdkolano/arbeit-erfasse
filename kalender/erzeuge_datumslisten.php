<?php
require_once("../include/datum.php");

$startzeit = "2015-12";
$stopzeit  = "2017-2";

$nun = new datum_objekt( "2015-12");
$nun->mach_tage_eines_monats();
$erg = $nun->mach_tage_einer_abrechnungsperiode();
echo "<pre>" ; print_r( $erg) ; "</pre><br />\n";

erzeuge_tage_eines_monats( new datum_objekt( $startzeit), new datum_objekt( $stopzeit));

function mach_tage_eines_monats( datum_objekt $laufobjekt) {         // liefert array( "2015-05-09", "2015-05-08", "2015-05-07")
  $sieben_tage = array();
  $erster = clone $laufobjekt;
  $erster->modify('first day of this month');
  $letzter = clone $laufobjekt;
  $letzter->modify('last day of this month');
  printf( "Tag %s MonatsErster %s MonatsLetzter %s ", $laufobjekt->format('D N Y-m-d'), $erster->format('Y-m-d D N '), $letzter->format('Y-m-d D N'));
  $interval = $erster->diff($letzter);
  printf( "Diff %s <br />\n", $interval->format('%R%a Tage'));
  while ( $erster <= $letzter) {
    $sieben_tage[] = $erster->format('Y-m-d');
    printf( "%s <br />\n", $erster);
    $erster->add( new DateInterval( 'P1D'));
  }
  return $sieben_tage;
}

function erzeuge_tage_eines_monats( datum_objekt $laufobjekt, datum_objekt $stopobjekt) {  // Testet mach_tage_eines_monats
  $llaauuff = clone ( $laufobjekt);
  while ( $laufobjekt < $stopobjekt) {
    $laufobjekt->mach_tage_eines_monats();                                              // Testet mach_7_wochentage aus datum.php
    $laufobjekt->add( new DateInterval( 'P1M'));
  }
  $laufobjekt = $llaauuff;
  while ( $laufobjekt < $stopobjekt) {
    mach_tage_eines_monats( $laufobjekt);                                              // Testet mach_7_wochentage von hier
    $laufobjekt->add( new DateInterval( 'P1M'));
  }
}

echo "<br />\n";

$startzeit = "2016-04-17";
$stopzeit  = "2016-05-11";

erzeuge_7_wochentage( new datum_objekt( $startzeit), new datum_objekt( $stopzeit));

function mach_7_wochentage( datum_objekt $laufobjekt) {         // liefert array( "2015-05-09", "2015-05-08", "2015-05-07")
  $sieben_tage = array();
  $erster = clone $laufobjekt;
  $erster->modify(sprintf( "%+d day", 1 - $erster->format("N")));                             // N Wochentag mo=1 so=7
  $letzter = clone $laufobjekt;
  $letzter->modify(sprintf( "%+d day", 7 - $letzter->format("N")));
  printf( "Tag %s WochenErster %s WochenLetzter %s ", $laufobjekt->format('D N Y-m-d'), $erster->format('Y-m-d D N '), $letzter->format('Y-m-d D N'));
  $interval = $erster->diff($letzter);
  printf( "Diff %s <br />\n", $interval->format('%R%a Tage'));
  while ( $erster <= $letzter) {
    $sieben_tage[] = $erster->format('Y-m-d');
    printf( "%s <br />\n", $erster);
    $erster->add( new DateInterval( 'P1D'));
  }
  return $sieben_tage;
}

function erzeuge_7_wochentage( datum_objekt $laufobjekt, datum_objekt $stopobjekt) {
  $llaauuff = clone ( $laufobjekt);
  while ( $laufobjekt < $stopobjekt) {
    $laufobjekt->mach_7_wochentage();                                              // Testet mach_7_wochentage aus datum.php
    $laufobjekt->add( new DateInterval( 'P1D'));
  }
  $laufobjekt = $llaauuff;
  while ( $laufobjekt < $stopobjekt) {
    mach_7_wochentage( $laufobjekt);                                              // Testet mach_7_wochentage von hier
    $laufobjekt->add( new DateInterval( 'P1D'));
  }
}

echo "<br />\n";

$startzeit = "2015-12";
$stopzeit  = "2017-2";

erzeuge_abrechnungstag( new datum_objekt( $startzeit), new datum_objekt( $stopzeit));

function erzeuge_abrechnungstag( datum_objekt $laufobjekt, datum_objekt $stopobjekt) {
  $voriger_abtag = clone $laufobjekt;
  while ( $laufobjekt < $stopobjekt) {
    $anfang = clone $laufobjekt;
    $ende   = clone $laufobjekt;
    printf( "Von %s bis %s %s",
      $erster  = $anfang->erster_der_abrechnungsperiode(),
      $letzter = $ende ->letzter_der_abrechnungsperiode(),
      $erster->diff($letzter)->format('%R%a Tage ')
    );
    $ultimo = clone $laufobjekt;
    $ultimo->modify('last day of this month');
    $abtag = clone $ultimo;
    if (($ultimo->format('N')) < 4) {                                    // mo di mi    zurück
      $abtag->modify(sprintf( "%+d day",   - $abtag->format("N")));                             // N Wochentag mo=1 so=7
    } else {                                                             // do fr sa so    vor
      $abtag->modify(sprintf( "%+d day", 7 - $abtag->format("N")));
    };
    printf( "Monat %s Ultimo %s Abrechnungstag %s ", $laufobjekt->format('Y-m'), $ultimo->format('Y-m-d N'), $abtag->format('Y-m-d D'));
    $interval = $voriger_abtag->diff($abtag);
    $voriger_abtag = clone $abtag;
    printf( "Diff %s <br />\n", $interval->format('%R%a Tage'));
    $laufobjekt->add( new DateInterval( 'P1M'));
  }
}

echo "<br />\n";

erzeuge_monate( new datum_objekt( $startzeit), new datum_objekt( $stopzeit));

function erzeuge_monate( datum_objekt $laufobjekt, datum_objekt $stopobjekt) {
  while ( $laufobjekt < $stopobjekt) {
    printf( "%s %s<br />\n", $laufobjekt->format('Y-m-d W D'), $laufobjekt->donnerstag_der_woche()->sonntag_der_woche()->format('Y-m-d W D'));
    $laufobjekt->add( new DateInterval( 'P1M'));
  }
}

echo "<br />\n";

erzeuge_sonntage( new datum_objekt( "2015-1-4"), new datum_objekt(  "2016-12-31"));

function erzeuge_sonntage( datum_objekt $laufobjekt, datum_objekt $stopobjekt) {
  while ( $laufobjekt < $stopobjekt) {
    printf( "INSERT zeiten (datum_auto) values%s<br />\n", $laufobjekt->format('(\'Y-m-d\'); -- D W'));
    $laufobjekt->add( new DateInterval( 'P1W'));
  }
}
?>

