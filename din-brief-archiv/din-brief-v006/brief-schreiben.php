<?php
require_once( "../include/informationsobjekt.php" );
require_once( "../include/anschriftobjekt.php" );
require_once( "../include/betreff_und_textobjekt.php" );

class maske {
  private $ausgewählt;

  function __construct( $wahl) {
    $this->ausgewählt = $wahl;
  }

  function html_head( $stylesheet) {
    header('Content-Type: text/html; charset=utf-8');
    $zuletzt_aktualisiert = "Brief zuletzt aktualisiert: Do 2016-05-19 08:47:49";
    $erg = "";
    $erg .= "<!DOCTYPE html>\n";
    $erg .= "<html>\n";
    $erg .= "<head>\n";
    $erg .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
    $erg .= "<link rel=\"stylesheet\" href=\"$stylesheet\" type=\"text/css\">\n";
    $erg .= "<title>Schreibe Din-5008-Brief</title>\n";
    $erg .= "</head>\n";
    $erg .= "<body>\n";
    $erg .= sprintf( "<!-- %s -->\n", $zuletzt_aktualisiert);
    return $erg;
  }

  function html_fusz( ) {
    $erg = "";
    $erg .= "</body>";
    $erg .= "</html>";
    $erg .= "\n";
    return $erg;
  }


  function __toString() {
    $ausgabe = "";
    $ausgabe .= (new betreff_und_textobjekt())->erfrage_und_sende_info();
    $ausgabe .= (new informationsobjekt()    )->erfrage_und_sende_info();
    $anschriftobjekt = new anschriftobjekt();
    $ausgabe .= $anschriftobjekt->erfrage_und_sende_info( $this->ausgewählt);

    $actionskript = "brief-din-5008.php";
    $erg = "";
    $erg .= $this->html_head( "css-din-5008.css");
    $erg .= sprintf( "<form method=\"POST\" action=\"%s\">\n%s\n</form>\n", $actionskript, $ausgabe);
    $erg .= $anschriftobjekt->wahl();
    $erg .= $this->html_fusz();
    return $erg;
  }

  /*
  function sende_info() {
    $erg = "";
    $informationsobjekt = new informationsobjekt();
    return $informationsobjekt->sende_info();
  }
   */

}

echo "<pre>brief-schreiben.php "     ; print_r( $_POST)    ; echo "</pre>";

$wahl = "";
if (isset( $_POST["wahl"])) { $wahl = $_POST["wahl"]; }

echo new maske( $wahl);
