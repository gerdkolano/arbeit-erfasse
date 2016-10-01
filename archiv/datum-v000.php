<?php
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
    $eine_woche = new DateInterval( 'P1M'); // Period 1 Month
    $this->monat_obj->add( $eine_woche);
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
