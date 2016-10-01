<?php
require_once( "../include/datum.php"                  );
require_once( "../include/informationsobjekt.php"     );
require_once( "../include/anschriftobjekt.php"        );
require_once( "../include/betreff_und_textobjekt.php" );

class html_seite {

  function html_head( $stylesheet) {
    header('Content-Type: text/html; charset=utf-8');
    $zuletzt_aktualisiert = "Brief zuletzt aktualisiert: Fr 2016-05-20 18:26:12";
    $erg = "";
    $erg .= "<!DOCTYPE html>\n";
    $erg .= "<html>\n";
    $erg .= "<head>\n";
    $erg .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
    $erg .= "<link rel=\"stylesheet\" href=\"$stylesheet\" type=\"text/css\">\n";
    $erg .= "<title>Din-5008-Brief</title>\n";
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

}

class kasten {
  public $erg;

  function __construct( $f, $tief) {
    $this->erg = "";
    $this->kiste( $f, $tief);
  }
  
  function zeichnung() { return $this->erg; }
  
  function kiste( $f, $tief) {
    $d = 8; $e = $f - $d;
    $d = 4; $e = $f - $d;
    $d = 2; $e = $f - $d; $k = 0;
    $this->erg .= sprintf( "<div style='border:solid black %dpx; width:%dpx; height:%dpx; padding:%dpx 0px 0px %dpx; margin:0px 0px 0px 0px'>\n",
      $d,                              // 1 
    ( $tief * $f - $d) * 2 + $k,        // 2 5.5
    ( $tief * $f - $d) * 2 + $k,        // 4 4
      $e,
      $e 
    );
    if ($tief>1) {
      $this->kiste( $f, $tief-1);
    } else {
    }
    $this->erg .= "</div>\n";
  }

}

class din_brief {

  private $anschrift;
  private $infoblock;
  private $textblock;

  function __construct( anschriftobjekt $anschrift, informationsobjekt $infoblock, betreff_und_textobjekt $textblock) {
    $this->anschrift = $anschrift;
    $this->infoblock = $infoblock;
    $this->textblock = $textblock;
  }

  function briefkopf( $arg) {
    $kasten = (new kasten( 6,  5))->zeichnung();
    $abstand = ""; for ($i=0;$i<50;$i++) $abstand .= "&nbsp;"; $abstand .= "\n";
    $arg = sprintf( "<div style='display: inline-block;'>%s</div>", $arg);    
    $erg = sprintf( "<div style='display: inline-block;'>\n%s</div>", $kasten);    
    return sprintf( "<div class='briefkopf'>\n%s%s %s\n</div>\n",
      $arg,
      $abstand,
      $erg);
  }

  function absenderzeile( $arg) {
    return sprintf( "<div class='absenderzeile'>\n%s\n</div>\n", $arg);
  }

  function bezugszeile( $arg) {
    return sprintf( "<div class='bezugszeile'>\n%s\n</div>\n", $arg);
  }

  function betreffzeile( $arg) {
    return sprintf( "<div class='betreff'>\n%s\n</div>\n", $arg);
  }

  function textfeld( $arg) {
    return sprintf( "<div class='textfeld'>\n%s\n</div>\n", $arg);
  }

  function brieffusz( $arg) {
    return sprintf( "<div class='brieffusz'>\n%s\n</div>\n", $arg);
  }

  function alles() {
    $erg ="";
    $erg .= $this->briefkopf( "Sabine Schallehn<br />\nBerlin-Lichtenrade");
    $erg .= $this->absenderzeile( "Sabine Schallehn Löwenbrucher Weg 24c D-12307 Berlin");
    $erg .= $this->anschrift;
    if (true) {
      $erg .= $this->infoblock;
    } else {
      $erg .= $this->bezugszeile( "Ihr Schreiben vom ");
    }
    if (false) {
      $erg .= $this->betreffzeile( "Verdienstabrechnungen und \"gfos 4.7plus\"-Zeitkontoauszüge");
    } 

    $erg .= $this->textblock;
    $erg .= $this->brieffusz( "IBAN DE49 10090000 205 306 4003 BIC BEVODDBE");
    $erg .= "<div class=\"page-break\"></div>\n";
    return $erg;
  }

}

if (isset( $_POST["info"])) {
  $ein_infoblock = new informationsobjekt( $_POST["info"]);
} else {
  $ein_infoblock = new informationsobjekt();
}

if (isset( $_POST["anschrift"])) {
  $eine_anschrift = new anschriftobjekt( $_POST["anschrift"]);
} else {
  $eine_anschrift = new anschriftobjekt();
}

if (isset( $_POST["brieftext"])) {
  $ein_textblock = new betreff_und_textobjekt( $_POST["brieftext"]);
} else {
  $ein_textblock = new betreff_und_textobjekt();
}

$eine_seite = new html_seite();
echo $eine_seite->html_head( "css-din-5008.css");

# echo "<pre>POST"     ; print_r( $_POST)    ; echo "</pre>";

$ein_brief = new din_brief( $eine_anschrift, $ein_infoblock, $ein_textblock);
  echo $ein_brief->alles();                                
                                                           
$ein_brief = new din_brief( $eine_anschrift, $ein_infoblock, $ein_textblock);
  echo $ein_brief->alles();                                
                                                           
$ein_brief = new din_brief( $eine_anschrift, $ein_infoblock, $ein_textblock);
  echo $ein_brief->alles();

echo $eine_seite->html_fusz();

?>
