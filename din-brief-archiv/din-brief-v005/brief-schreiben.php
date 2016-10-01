<?php
require_once( "../include/informationsobjekt.php" );
require_once( "../include/anschriftobjekt.php" );

class maske {

  function html_head( $stylesheet) {
    header('Content-Type: text/html; charset=utf-8');
    $zuletzt_aktualisiert = "Brief zuletzt aktualisiert: Mi 2016-05-18 12:44:20";
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
    $erf  = (new informationsobjekt())->erfrage_und_sende_info();
    $erf .= (new anschriftobjekt())->erfrage_und_sende_info();
    $actionskript = "brief-din-5008.php";

    $erg = "";
    $erg .= $this->html_head( "css-din-5008.css");
    $erg .= sprintf( "<form method=\"POST\" action=\"%s\">\n%s\n</form>\n", $actionskript, $erf);
    $erg .= $this->html_fusz();
    return $erg;
  }

  function sende_info() {
    $erg = "";
    $informationsobjekt = new informationsobjekt();
    return $informationsobjekt->sende_info();
  }

}

echo new maske();
