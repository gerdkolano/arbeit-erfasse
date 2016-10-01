<?php
require_once( "../include/informationsobjekt.php" );
require_once( "../include/anschriftobjekt.php" );
require_once( "../include/betreff_und_textobjekt.php" );

class maske {
  private $gepostet;

  function __construct( $wahl) {
    $this->gepostet = $wahl;
  }

  function html_head( $stylesheet) {
    header('Content-Type: text/html; charset=utf-8');
    $zuletzt_aktualisiert = "Brief zuletzt aktualisiert: Fr 2016-05-20 18:23:55";
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
#   ob_start(); echo "<pre>POST "; print_r( $_POST); echo "</pre>\n"; $erg .= ob_get_clean();
    return $erg;
  }

  function html_fusz( ) {
    $erg = "";
    $erg .= "</body>";
    $erg .= "</html>";
    $erg .= "\n";
    return $erg;
  }

  function knopp() {
    $erg = "";
    $name  = "gesendet-von";
    $value = "brief-schreiben.php";
    $erg .= sprintf( "<button id='knopp' class=\"button-rufe\" type=\"SUBMIT\" name=\"%s\" value=\"%s\">%s </button><br />\n",
        $name,
        $value,
        "Zeig den Brief"
      );
    return $erg;
  }

  function __toString() {
    $ausgabe = "";
    $ausgabe .= $this->knopp();
    $anschriftobjekt    = new anschriftobjekt(    $this->gepostet );
    $informationsobjekt = new informationsobjekt( $this->gepostet );
    $ausgabe .= "<div id='parent'>\n";
    $ausgabe .= "<div id='kind-1'>\n";
    $ausgabe .= $anschriftobjekt    ->erfrage_und_sende_info( $this->gepostet[0]);
    $ausgabe .= "</div>";
    $ausgabe .= "<div id='kind-2'>\n";
    $ausgabe .= $informationsobjekt ->erfrage_und_sende_info( $this->gepostet[1]);
    $ausgabe .= "</div>";
    $ausgabe .= "</div>";
    $ausgabe .= "<div id='brieftexteingabe'>";
    $ausgabe .= (new betreff_und_textobjekt())->erfrage_und_sende_info();
    $ausgabe .= "</div>";

    $actionskript = "brief-din-5008.php";
    $erg = "";
    $erg .= $this->html_head( "css-din-5008.css");
    $erg .= sprintf( "<form id='knopp' method=\"POST\" action=\"%s\">\n%s\n</form>\n",
      $actionskript, $ausgabe);

#   $auswahl .= "<div id='parent'>\n";
#   $auswahl .= "</div>";

    $auswahl  = "";
    $auswahl .= $anschriftobjekt   ->selektiere();
    $auswahl .= $informationsobjekt->selektiere();
#   $auswahl .= $anschriftobjekt   ->wahl();

    $f1 = "<button id='wahl' class=\"button-rufe\" type=\"SUBMIT\" name=\"%s\" value=\"%s\">%s </button><br />\n";
    $auswahl .= sprintf( $f1, "wahl[]", "zeig_mal" , "Zeig mal"   );

    $erg .= sprintf( "<form id='wahl' method=\"POST\" action=\"%s\">\n%s</form>\n\n",
      $_SERVER["PHP_SELF"],                                                      
      $auswahl
    );                                                                           

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

# echo "<pre>brief-schreiben.php "     ; print_r( $_POST)    ; echo "</pre>";

$wahl = array( "a_sabine", "i_leer");
if (isset( $_POST["wahl"])) { $wahl = $_POST["wahl"]; }

echo new maske( $wahl);
