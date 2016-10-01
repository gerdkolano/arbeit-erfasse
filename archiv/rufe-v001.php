<?php
require_once( "datum.php");

class anzeige {

  function html_head( $stylesheet) {
    $erg = "";
    $erg .= "<!DOCTYPE html>\n";
    $erg .= "<html>\n";
    $erg .= "<head>\n";
    $erg .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
    $erg .= "<link rel=\"stylesheet\" href=\"$stylesheet\" type=\"text/css\">\n";
    $erg .= "</head>\n";
    $erg .= "<body>\n";
    return $erg;
  }

  function html_form() {
    $name         = "verbose";
    $value        = "5wochen";
    $beschriftung = "Mach Monat April geltend";
    $erg = "";
    for (
      $monat =  new datum_objekt( "2014-11-15");
      $monat <= new datum_objekt( "2016-05-15");
      $monat->modify( "+1 month")
    ) {
      $erg .= "<form METHOD=\"POST\" ACTION=\"zeitkonto.php\">\n";
      $erg .= sprintf( "<button class=\"button-b\" type=\"SUBMIT\" name=\"%s\" value=\"%s\">%s </button><br />\n",
        $name,
        $value,
        $monat->deutsch( "MMMM YYYY") // $beschriftung
      );
      $erg .= sprintf( "<input type=\"hidden\" name=\"woche\" value=\"%s\">\n", $monat->format( "Y-m"));
      $erg .= "</form>\n";
    }
    return $erg;
  }

  function gepostet() {
    $erg  = "";
    foreach($_POST as $key=>$val) {
      $erg .= sprintf( "key %s val %s<br />\n", $key, $val);
    }
    $erg .= "<br />\n";
    return $erg;
  }

  function titel() {
    $erg  = "";
    $erg .= sprintf( "<h3> Mach' einen Monat geltend</h3>\n");
    $erg .= "<br />\n";
    return $erg;
  }

  function html_foot() {
    $erg = "";
    $erg .= "</body>\n";
    $erg .= "</html>\n";
    return $erg;
  }

  function main() {
    $erg = "";
    $erg .= $this->html_head( "css-formblatt.css"); // "arbeit-erfasse.css"
    $erg .= $this->titel();
    $erg .= $this->html_form();
    $erg .= $this->html_foot();
    return $erg;
  }
  
}
  
echo (new anzeige())->main();

?>

