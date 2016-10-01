<?php
require_once( "../include/datum.php");
require_once( "../include/konst.php");

class anzeige {
  private $verbose;

  function __construct( $verbose) {
    $this->verbose = $verbose;
  }

  function biete_gfos_kontoauszug_an( $name = "") {
    $erg = "";
    $erg .= "<form METHOD=\"POST\" ACTION=\"zeitkonto.php\">\n";
    $erg .= sprintf( "<button class=\"button-rufe\" type=\"SUBMIT\" name=\"%s\" value=\"%s\">%s %s </button>\n",
      $name,
      "",
      "Zeige einen gf*s-Zeitkonto-Auszug",
      "" // $monat->deutsch( "d.MMMM YYYY") // $beschriftung
    );
    $erg .= "</form>\n";
    return $erg;
  }
  
  function biete_verdienstbescheinigung_erfassen_an( $name = "") {
    $erg = "";
    $erg .= "<form METHOD=\"POST\" ACTION=\"../verdienst/verdienstspeichere.php\">\n";
    $erg .= sprintf( "<button class=\"nextbt nextactive\" type=\"SUBMIT\" name=\"%s\" value=\"%s\">%s %s </button>\n",
      $name,
      "",
      "Verdienstabrechnung Erfasse und speichere ",
      "" // $monat->deutsch( "d.MMMM YYYY") // $beschriftung
    );
    $erg .= "</form>\n";
    return $erg;
  }
  
  function biete_arbeitszeiten_erfassen_an( $name = "") {
    $erg = "";
    $erg .= "<form METHOD=\"POST\" ACTION=\"speichere.php\">\n";
    $erg .= sprintf( "<button class=\"button-a\" type=\"SUBMIT\" name=\"%s\" value=\"%s\">%s %s </button>\n",
      $name,
      "",
      "Erfasse und speichere Arbeitszeiten",
      "" // $monat->deutsch( "d.MMMM YYYY") // $beschriftung
    );
    $erg .= "</form>\n";
    return $erg;
  }
  
  function biete_brief_schreiben_an( $name = "debug") {
    $erg = "";
    $erg .= "<form METHOD=\"POST\" ACTION=\"../brief-din-5008/brief-schreiben.php\">\n";
    $erg .= sprintf( "<button class=\"backbt\" type=\"SUBMIT\" name=\"%s\" value=\"%s\">%s %s </button>\n",
      $name,
      "1",
      "Schreibe Briefe",
      "" // $beschriftung
    );
    $erg .= "</form>\n";
    return $erg;
  }
  
  function biete_view_table_an( $name = "debug") {
    $erg = "";
    $erg .= "<form METHOD=\"POST\" ACTION=\"../erfasse/view-table.php\">\n";
    $erg .= sprintf( "<button class=\"backbt\" type=\"SUBMIT\" name=\"%s\" value=\"%s\">%s %s </button>\n",
      $name,
      "1",
      "View Table",
      "" // $beschriftung
    );
    $erg .= "</form>\n";
    return $erg;
  }
  
  function biete_woche_an( $name) {
    $erg = "";
    $erg .= "<form METHOD=\"POST\" ACTION=\"zeitkonto.php\">\n";
    $erg .= sprintf( "<button class=\"button-rufe\" type=\"SUBMIT\" name=\"%s\" value=\"%s\">%s %s </button>\n",
      $name,
      "1woche",
      "Eine Woche",
      "" // $monat->deutsch( "d.MMMM YYYY") // $beschriftung
    );
    $erg .= sprintf( "<input type=\"text\" name=\"wann\" value=\"%s\">\n",
      ""  // $monat->format( "Y-m")
    );
    $erg .= "D.M D.M.Y D.M.YYYY YYYY-MM-DD YYYY-MM";
    $erg .= "</form>\n";
    return $erg;
  }
  
  function html_head( $stylesheet) {
    header('Content-Type: text/html; charset=utf-8');
    $erg = "";
    $erg .= "<!DOCTYPE html>\n";
    $erg .= "<html>\n";
    $erg .= "<head>\n";
#   $erg .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
    $erg .= "<link rel=\"stylesheet\" href=\"$stylesheet\" type=\"text/css\">\n";
    $erg .= "<title>\nRufe</title>\n";
    $erg .= "</head>\n";
    $erg .= "<body>\n";
    return $erg;
  }

  function html_liste( $value, $beschriftung, $name) {  // html_liste( "tage", "Alle Daten täglich menschenlesbar");
    $erg = "";
      $erg .= "<form METHOD=\"POST\" ACTION=\"zeitkonto.php\">\n";
      $erg .= sprintf( "<button class=\"button-rufe\" type=\"SUBMIT\" name=\"%s\" value=\"%s\">%s </button><br />\n",
        $name,
        $value,
        $beschriftung
      );
      $erg .= "</form>\n";
    return $erg;
  }

  function ein_button( $value, $monat, $class, $name) {
    $erg = "";
      $erg .= sprintf( "<button class=\"$class\" type=\"SUBMIT\" name=\"%s\" value=\"%s\">%s </button>\n",
        $name,
        $value,
        $monat->deutsch( "MMMM YYYY") // $beschriftung
      );
    return $erg;
  }

  function html_form( $name) {
    $value        = "5wochen";
    $beschriftung = "Mach Monat April geltend";
    $erg = "";
    for (
      $monat =   new datum_objekt( "2014-11-15");
      $monat <= (new datum_objekt())->modify( "+2 month");
      $monat->modify( "+1 month")
    ) {
      $erg .= "<form METHOD=\"POST\" ACTION=\"zeitkonto.php\">\n";

      $erg .= $this->ein_button( "5wochen", $monat, "button-rufe", $name);
      $erg .= $this->ein_button( "monate" , $monat, "button-e",    $name);
      $erg .= "<br />";

      $erg .= sprintf( "<input type=\"hidden\" name=\"wann\" value=\"%s\">\n", $monat->format( "Y-m") . "-15"); // Mitte von 5wochen

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
    $erg .= sprintf( "<h3> Bearbeite und zeige die Arbeitszeiten</h3>\n");
    # $erg .= "<br />\n";
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
#   $erg .= $this->html_head( "css-formblatt.css"); // "arbeit-erfasse.css"
    $erg .= $this->html_head( "arbeit-erfasse.css"); // "arbeit-erfasse.css"
    $erg .= $this->titel();
    $erg .= "<div id=\"parent\">\n";
    $erg .= "<div>\n";
    $erg .= $this->biete_gfos_kontoauszug_an();
    $erg .= $this->html_liste( "tage" , "Alle Daten täglich menschenlesbar",  $this->verbose);
    $erg .= $this->html_liste( "liste", "Alle Daten täglich maschinenlesbar", $this->verbose);
    $erg .= "</div>\n";
    $erg .= "<div>\n";
    $erg .= $this->biete_arbeitszeiten_erfassen_an();
    $erg .= $this->biete_verdienstbescheinigung_erfassen_an();
    $erg .= $this->biete_woche_an( $this->verbose);
    $erg .= "</div>\n";
    $erg .= "<div>\n";
    $erg .= $this->biete_brief_schreiben_an();
    $erg .= "</div>\n";
    $erg .= "<div>\n";
    $erg .= $this->biete_view_table_an();
    $erg .= "</div>\n";
    $erg .= "</div>\n";
    $erg .= $this->html_form( $this->verbose);
    $erg .= $this->html_foot();
    return $erg;
  }
  
}

echo (new anzeige( konst::$art_verbose))->main();

?>

