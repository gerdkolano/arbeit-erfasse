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
    $zuletzt_aktualisiert = "Brief zuletzt aktualisiert: So 2016-05-29 19:25:04!";
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

  function knopp( $label) {
    $erg = "";
    $name  = "gesendet-von";
    $value = "brief-schreiben.php";
    $wert = $this->debug();

    $erg .= "<input id='knopp' type=\"hidden\" name=\"debug\" value=\"$wert\">\n";
    $erg .= sprintf( "<button id='knopp' class=\"button-rufe\" type=\"SUBMIT\" name=\"%s\" value=\"%s\">%s </button><br />\n",
        $name,
        $value,
        $label
      );
    return $erg;
  }

  function debug() {
    $wert = isset( $_GET["debug"] ) ? $_GET["debug"]  : 1;
    if ($wert < 1) {
      $wert = isset( $_POST["debug"]) ? $_POST["debug"] : 1;
    }
    return $wert;
  }

  function __toString() {
    $ausgabe = "";
    $ausgabe .= $this->knopp( "Zeig den Brief");
    $anschriftobjekt        = new anschriftobjekt(        $this->gepostet );
    $informationsobjekt     = new informationsobjekt(     $this->gepostet );
    $betreff_und_textobjekt = new betreff_und_textobjekt( $this->gepostet );
    $ausgabe .= "<div id='parent'>\n";
    $ausgabe .= "<div id='kind-1'>\n";
    $ausgabe .= $anschriftobjekt       ->erfrage_und_sende_info( $this->gepostet[0]);
    $ausgabe .= "</div>";
    $ausgabe .= "<div id='kind-2'>\n";
    $ausgabe .= $informationsobjekt    ->erfrage_und_sende_info( $this->gepostet[1]);
    $ausgabe .= "</div>\n";
    $ausgabe .= "</div>\n";
    $ausgabe .= "<div id='brieftexteingabe'>" . "<!-- brieftexteingabe -->" ."\n";
    $ausgabe .= $betreff_und_textobjekt->erfrage_und_sende_info( $this->gepostet[2]);
    $ausgabe .= "</div>" . "<!-- brieftexteingabe -->" ."\n";
/*  Weitere Seite
    $ausgabe .= "<div id='brieftexteingabe'>";
    $ausgabe .= (new betreff_und_textobjekt())->erfrage_und_sende_info();
    $ausgabe .= "</div>";
*/
                                                                               // Gib alles aus
    $actionskript = "brief-din-5008.php";
#   $actionscript .= isset( $_GET["debug"]) ? ("?debug=" . $_GET["debug"]) : "";

    $erg = "";
    $erg .= $this->html_head( "css-din-5008.css");
    $erg .= sprintf( "<form id='knopp' method=\"GET\" action=\"%s\">\n%s\n</form>\n",
      $actionskript, $ausgabe);

#   $auswahl .= "<div id='parent'>\n";
#   $auswahl .= "</div>";

                                                                               // Selektion von Anschrift und Betreff
    $auswahl  = "";
    $auswahl .= $anschriftobjekt       ->selektiere();
    $auswahl .= $informationsobjekt    ->selektiere();
    $auswahl .= $betreff_und_textobjekt->selektiere();
#   $auswahl .= $anschriftobjekt   ->wahl();

    $wert = $this->debug();

    $auswahl .= "<input id='knopp' type=\"hidden\" name=\"debug\" value=\"$wert\">\n";
    $f1 = "<button id='wahl' class=\"button-rufe\" type=\"SUBMIT\" name=\"%s\" value=\"%s\">%s </button><br />\n";
    $auswahl .= sprintf( $f1, "wahl[]", "zeig_mal" , "Zeig mal"   );

    $actionscript = $_SERVER["PHP_SELF"];

    $erg .= sprintf( "<form id='wahl' method=\"POST\" action=\"%s\">\n%s</form>\n\n",
      $actionscript,
      $auswahl
    );                                                                           

    $erg .= $this->html_fusz();
    return $erg;
  }

}

# $wahl = array( "a_sabine", "i_leer");
$wahl = array( "a_an_giebler", "i_an_giebler", "b_an_heyland");
if (isset( $_POST["wahl"])) { $wahl = $_POST["wahl"]; }

echo new maske( $wahl);

if ((isset( $_GET["debug"]) and $_GET["debug"] > 0 ) or (isset( $_POST["debug"]) and $_POST["debug"] > 0 )) { 
  $erg="";
  ob_start(); echo "<pre>GET  "; print_r( $_GET ); echo "</pre>\n"; $erg .= ob_get_clean();
  ob_start(); echo "<pre>POST "; print_r( $_POST); echo "</pre>\n"; $erg .= ob_get_clean();
  echo $erg;
}

?>
