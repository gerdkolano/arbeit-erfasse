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

  function erster_werktag_der_woche() {
    $date = clone $this;
    return $date->modify( sprintf( "%+d day", 1 - $date->format("N"))); // N : Wochentag 1 bis 7 Mo bis So
  }

  function letzter_werktag_der_woche() {
    $date = clone $this;
    return $date->modify( sprintf( "%+d day", 6 - $date->format("N")));
  }

  function donnerstag_der_woche() {
    $date = clone $this;
    return $date->modify( sprintf( "%+d day", 4 - $date->format("N")));
  }

  function sonntag_der_woche() {
    $date = clone $this;
    return $date->modify( sprintf( "%+d day", 7 - $date->format("N")));
  }

  function monat_der_woche() {
    $date = $this->donnerstag_der_woche();
    return $date->format( "m");
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
    $date = new DateTime( $datum);
  } catch (Exception $e) {
    echo $e->getMessage();
    exit(1);
  }
  //echo $date->format('Y-m-d  H:i:s N W');
  return $date;
}
?>
