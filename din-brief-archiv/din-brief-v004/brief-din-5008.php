<?php
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

  function html_head( $stylesheet) {
    $zuletzt_aktualisiert = "Brief zuletzt aktualisiert: Mo 2016-05-16 10:54:57";
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
    return $erg;
  }
  
  function html_fusz( ) {
    $erg = "";
    $erg .= "</body>";
    $erg .= "</html>";
    $erg .= "\n";
    return $erg;
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

  function anschriftenfeld( $arg) {
    return sprintf( "<div class='anschriftenfeld'>\n%s\n</div>\n", $arg);
  }

  function informationsblock( $arg) {
    return sprintf( "<div class='informationsblock'>\n%s\n</div>\n", $arg);
  }

  function betreff( $arg) {
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
    $erg .= $this->anschriftenfeld( "");
    $erg .= $this->informationsblock( "");
    $erg .= $this->betreff( "");
    $erg .= $this->textfeld( "");
    $erg .= $this->brieffusz( "IBAN DE49 10090000 205 306 4003 BIC BEVODDBE");
    return $erg;
  }

}

$ein_brief = new din_brief();
  echo $ein_brief->html_head( "css-din-5008.css");
  echo $ein_brief->alles();
  echo $ein_brief->html_fusz();

?>
