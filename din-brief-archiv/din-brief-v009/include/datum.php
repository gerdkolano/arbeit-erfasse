<?php
// $date = DateTime::createFromFormat("d.m.y", "18.3.15");echo $date->format("Y-m-d\n");
//

class datum_objekt extends DateTime {
  private $u;

  public function __construct($time='now', $timezone='Europe/Berlin') {
      parent::__construct($time, new DateTimeZone($timezone));
      $this->u = $this->format('U');  
  }

  public function __toString() {
      return $this->format('Y-m-d H:i:s');
  }

  public function reset() {
      $this->setTimestamp($this->u);  
  }

  function donnerstag() {
    $donnerstag = clone $this;
#   $donnerstag->sub( new DateInterval( 'P3D'));
#   $donnerstag->modify( "thursday this week");
    $donnerstag->modify( "wednesday this week");
    return $donnerstag->format( "n");
  }

  function deutsch( $format) {
    $fmt_tagesname = new IntlDateFormatter( 
        'de-DE',
        IntlDateFormatter::FULL,
        IntlDateFormatter::FULL,
        'Europe/Berlin',
        IntlDateFormatter::GREGORIAN,
        $format   // http://www.unicode.org/reports/tr35/tr35-dates.html#Date_Field_Symbol_Table
                  // http://www.icu-project.org/apiref/icu4c/classSimpleDateFormat.html#details
      );
    return rtrim(
      $fmt_tagesname->format( $this)
      , ".");
  }

  function tagesname() {
    $fmt_tagesname = new IntlDateFormatter( 
        'de-DE',
        IntlDateFormatter::FULL,
        IntlDateFormatter::FULL,
        'Europe/Berlin',
        IntlDateFormatter::GREGORIAN,
        "EEE"   // http://www.unicode.org/reports/tr35/tr35-dates.html#Date_Field_Symbol_Table
      );
    return rtrim(
      $fmt_tagesname->format( $this)
      , ".");
  }

  function erster_tag_des_monats() {
    $date = clone $this;
    return $date->modify( "first day of this month");
  }

  function erster_werktag_der_woche() {
    $date = clone $this;
    return $date->modify( sprintf( "%+d day", 1 - $date->format("N"))); // 1 2 3 4 5 6 7 wird über 0 -1 -2 -3 -4 -5 -6 zu 1 1 1 1 1 1 1
  }

  function letzter_werktag_der_woche() {
    $date = clone $this;
    return $date->modify( sprintf( "%+d day", 6 - $date->format("N")));
  }

  function donnerstag_der_woche() {
    $date = clone $this;
    return $date->modify( sprintf( "%+d day", 4 - $date->format("N"))); // 1 2 3 4 5 6 7 über 3 2 1 0 -1 -2 -3 zu 4 4 4 4 4 4 4
  }

  function sonntag_der_woche() {
    $date = clone $this;
    return $date->modify( sprintf( "%+d day", 7 - $date->format("N")));
  }

  function monatsnummer_der_woche() {   // function monat_der_woche() {
    $date = $this->donnerstag_der_woche();
    return $date->format( "m");
  }

  function mach_7_wochentage() {                               // liefert array( "2015-05-09", "2015-05-08", "2015-05-07", …)
    $sieben_tage = array();
    $erster = clone $this;
    $erster->modify(sprintf( "%+d day", 1 - $erster->format("N")));                             // N Wochentag mo=1 so=7
    $letzter = clone $this;
    $letzter->modify(sprintf( "%+d day", 7 - $letzter->format("N")));
    while ( $erster <= $letzter) { 
      $sieben_tage[] = $erster->format('Y-m-d');
      $erster->add( new DateInterval( 'P1D'));
    } 
    return $sieben_tage;
  }

  function montage_einer_abrechnungsperiode() {         // liefert array( "2015-05-09", "2015-05-08", "2015-05-07", …)
    $sieben_tage = array();
    $erster  = $this-> erster_der_abrechnungsperiode();
    $letzter = $this->letzter_der_abrechnungssamstag();
    while ( $erster <= $letzter) {
      if ($erster->format("N") != "1") printf( "%s sollte ein Montag sein!<br />\n", $erster->format( "D Y-m-d "));    
      $sieben_tage[] = clone $erster;
      $erster->add( new DateInterval( 'P1W'));
    }
    return $sieben_tage;
  }

  function montage_der_abrechnungsperiode() {         // liefert array( "2015-05-09", "2015-05-08", "2015-05-07", …)
    $sieben_tage = array();
    $erster  = $this-> erster_der_abrechnungsmontag1();
    $letzter = $this->letzter_der_abrechnungssamstag();
    while ( $erster <= $letzter) {
      if ($erster->format("N") != "1") printf( "%s sollte ein Montag sein!<br />\n", $erster->format( "D Y-m-d "));    
      $sieben_tage[] = clone $erster;
      $erster->add( new DateInterval( 'P1W'));
    }
    return $sieben_tage;
  }

  function mach_tage_einer_abrechnungsperiode() {         // liefert array( "2015-05-09", "2015-05-08", "2015-05-07", …)
    $sieben_tage = array();
    $erster  = $this-> erster_der_abrechnungsperiode();
    $letzter = $this->letzter_der_abrechnungssamstag();
    while ( $erster <= $letzter) {
      $sieben_tage[] = $erster->format('Y-m-d');
      $erster->add( new DateInterval( 'P1D'));
    }
    return $sieben_tage;
  }

  function mach_tage_einer_abrechnungsperiode_obsplet() {         // liefert array( "2015-05-09", "2015-05-08", "2015-05-07", …)
    $sieben_tage = array();
    $erster  = $this-> erster_der_abrechnungsperiode();
    $letzter = $this->letzter_der_abrechnungssamstag();
    while ( $erster <= $letzter) {
      $sieben_tage[] = $erster->format('Y-m-d');
      $erster->add( new DateInterval( 'P1D'));
    }
    return $sieben_tage;
  }

  function mach_tage_eines_monats() {                           // liefert array( "2015-05-09", "2015-05-08", "2015-05-07", …)
    $sieben_tage = array();
    $erster = clone $this;
    $erster->modify('first day of this month');
    $letzter = clone $this;
    $letzter->modify('last day of this month');
    while ( $erster <= $letzter) {
      $sieben_tage[] = $erster->format('Y-m-d');
      $erster->add( new DateInterval( 'P1D'));
    }
    return $sieben_tage;
  }

  function erster_der_nachperiode() {                                    // N Wochentag mo=1 so=7
    $ultimo = $this->letzter_der_abrechnungssamstag();
    $ultimo->modify('+2 week');
    return $ultimo; // ->erster_der_abrechnungsmontag1();
  }
  
  function letzter_der_vorsonntag() {                                    // N Wochentag mo=1 so=7
    $ultimo = $this->erster_der_abrechnungsperiode();
    $ultimo->modify('-1 week');
    return $ultimo->letzter_der_abrechnungssonntag();
  }
  
  function letzter_der_abrechnungssonntag() {                            // N Wochentag mo=1 so=7
    $ultimo = clone $this;
    $ultimo->modify('last day of this month');
    $abtag = clone $ultimo;
    if (($ultimo->format('N')) < 4) {                                    // mo di mi    zurück   1 2 3   wird über -1 -2 -3    zu  0  0  0 
      $abtag->modify(sprintf( "%+d day",    - $abtag->format("N")));
    } else {                                                             // do fr sa so    vor   4 5 6 7 wird über +3 +2 +1  0 zu  7  7  7  7
      $abtag->modify(sprintf( "%+d day",  7 - $abtag->format("N")));
    };
    return $abtag;
  }
  
  function letzter_der_vorsamstag() {                                    // N Wochentag mo=1 so=7
    $ultimo = $this->erster_der_abrechnungsperiode();
    $ultimo->modify('-1 week');
    return $ultimo->letzter_der_abrechnungssamstag();
  }
  
  function letzter_der_abrechnungssamstag() {                            // N Wochentag mo=1 so=7
    $ultimo = clone $this;
    $ultimo->modify('last day of this month');
    $abtag = clone $ultimo;
    if (($ultimo->format('N')) < 4) {                                    // mo di mi    zurück   1 2 3   wird über -2 -3 -4    zu -1 -1 -1 
      $abtag->modify(sprintf( "%+d day", -1 - $abtag->format("N")));
    } else {                                                             // do fr sa so    vor   4 5 6 7 wird über +2 +1  0 -1 zu  6  6  6  6
      $abtag->modify(sprintf( "%+d day",  6 - $abtag->format("N")));
    };
    return $abtag;
  }
  
  function erster_der_abrechnungsperiode() {                             // N Wochentag mo=1 so=7
    $ultimo = clone $this;
    $ultimo->modify('first day of this month');
    $abtag = clone $ultimo;
    if (($ultimo->format('N')) < 4) {                                    // mo di mi    zurück   1 2 3   wird über +0 -1 -2    zu 1 1 1
      $abtag->modify(sprintf( "%+d day", 1 - $abtag->format("N")));
    } else {                                                             // do fr sa so    vor   4 5 6 7 wird über +4 +3 +2 +1 zu 8 8 8
      $abtag->modify(sprintf( "%+d day", 8 - $abtag->format("N")));
    };
    return $abtag;
  }
  
  function erster_der_abrechnungsmontag1() {                             // N Wochentag mo=1 so=7
    $ultimo = clone $this;
    $ultimo->modify('first day of this month');
    $abtag = clone $ultimo;
    if (($ultimo->format('N')) <=4) {                                    // mo di mi do zurück   1 2 3 4 wird über +0 -1 -2 -3 zu 1 1 1 1
      $abtag->modify(sprintf( "%+d day", 1 - $abtag->format("N")));
    } else {                                                             //    fr sa so    vor     5 6 7 wird über    +3 +2 +1 zu 8 8 8
      $abtag->modify(sprintf( "%+d day", 8 - $abtag->format("N")));
    };
    return $abtag;
  }
  
  function innerer_der_abrechnungsperiode() {                             // N Wochentag mo=1 so=7
    $ultimo = $this->letzter_der_abrechnungssamstag();
    return $ultimo->modify('-2 weeks');
  }
  
  function innerer_der_nachperiode() {                             // N Wochentag mo=1 so=7
    $ultimo = $this->letzter_der_abrechnungssamstag();
    return $ultimo->modify('+2 weeks');
  }
  
}

class datum_objekt_version_00 extends DateTime {
  private $d_objekt;
  function __construct ( $datum) {
    DateTime::__construct();
    $this->d_objekt = $this->datumsobjekt( $datum);
  }

  function format( $arg) {
    return $this->d_objekt->format( $arg);
  }

  function tagesnummer() {
    return $this->d_objekt->format( 'd');
  }

  function monatsnummer() {
    return $this->d_objekt->format( 'n');
  }

  function Ymd() {
    return $this->d_objekt->format( 'Y-m-d');
  }

  function tagesname() {
    $fmt_tagesname = new IntlDateFormatter( 
        'de-DE',
        IntlDateFormatter::FULL,
        IntlDateFormatter::FULL,
        'Europe/Berlin',
        IntlDateFormatter::GREGORIAN,
        "EEE"   // http://www.unicode.org/reports/tr35/tr35-dates.html#Date_Field_Symbol_Table
      );
    return rtrim( $fmt_tagesname->format( $this->d_objekt), ".");
  }

  function datumsobjekt( $datum) {
      try {
      $DateTimeZone = new DateTimeZone( "Europe/Berlin");
      $date = new DateTime( $datum);
      $date->setTimezone( $DateTimeZone);
    } catch (Exception $e) {
      echo $e->getMessage();
      exit(1);
    }
    //echo $date->format('Y-m-d  H:i:s N W');
    return $date;
  }

}

function datumsobjekt( $datum) {

  try {
    $DateTimeZone = new DateTimeZone( "Europe/Berlin");
    $date = new DateTime( $datum);
    $date->setTimezone( $DateTimeZone);
  } catch (Exception $e) {
    echo $e->getMessage();
    exit(1);
  }
  //echo $date->format('Y-m-d  H:i:s N W');
  return $date;
}

class ein_datum {
# echo "<pre>"; print_r( $_POST); echo "</pre>";
# echo "<pre>"; print_r( $_GET); echo "</pre>";

  private $monat_obj;
  private $monat_orig;
  private $monat_woche;

  function __construct( $monat_orig, $fmt_string = "MMMM yyyy w.'Woche ab' dd.MM.") {
    // $fmt_string = "MMMM yyyy w.'Woche ab' dd.MM.";   // http://www.unicode.org/reports/tr35/tr35-dates.html#Date_Field_Symbol_Table
    $fmt_monat = new IntlDateFormatter(
      'de-DE',
      IntlDateFormatter::FULL,
      IntlDateFormatter::FULL,
      'Europe/Berlin',
      IntlDateFormatter::GREGORIAN,
      $fmt_string   
    );

    $monat_obj = datumsobjekt( $monat_orig);

    $monat_woche = $fmt_monat->format( $monat_obj);

    $this->monat_obj = $monat_obj;
    $this->monat_orig = $monat_orig;
    $this->monat_woche = $monat_woche;
  }

  function get_monat_orig() {
    return $this->monat_orig;
  }

  function add_einen_monat() {
    $einen_monat = new DateInterval( 'P1M'); // Period 1 Month
    $this->monat_obj->add( $einen_monat);
  }

  function get_monat_woche() {
    return $this->monat_woche;
  }

  function format( $fmt_string) { // $ein_datum->get_was( "MMMM yyyy w.'Woche ab' dd.MM.");
    $fmt = new IntlDateFormatter(
      'de-DE',
      IntlDateFormatter::FULL,
      IntlDateFormatter::FULL,
      'Europe/Berlin',
      IntlDateFormatter::GREGORIAN,
      $fmt_string
    );
    return $fmt->format( $this->monat_obj); 
  }
  
  /*
   *   $ein_datum = new ein_datum( $_GET["m"]);
   *   $monat_woche = $ein_datum->get_monat_woche();
   *   printf( "Monat = %s<br />\n", $ein_datum->get_monat_orig()); 
   *   printf( "Monat = %s<br />\n", $monat_woche); 
   *   printf( "<h3 style=\"text-align: center\">%s </h3><br />\n", $monat_woche);
   *   
   *   $ein_datum = new ein_datum( "2016-08-16");
   *   $monat_woche = $ein_datum->get_monat_woche();
   *   printf( "Monat = %s<br />\n", $ein_datum->get_monat_orig()); 
   *   printf( "Monat = %s<br />\n", $monat_woche); 
   *   printf( "<h3 style=\"text-align: center\">%s </h3><br />\n", $monat_woche);
   *   
   */
}

?>
