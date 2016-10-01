<?php
require_once( "../include/datum.php"                  );
require_once( "../include/informationsobjekt.php"     );
require_once( "../include/anschriftobjekt.php"        );
require_once( "../include/betreff_und_textobjekt.php" );

class html_seite {

  function html_head( $stylesheet) {
    header('Content-Type: text/html; charset=utf-8');
    $zuletzt_aktualisiert = "Brief zuletzt aktualisiert: Di 2016-05-24 16:53:02!";
    $erg = "";
    $erg .= "<!DOCTYPE html>\n";
    $erg .= "<html>\n";
    $erg .= "<head>\n";
    $erg .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";

    $erg .= "<link href='css-din-5008.css'      rel='stylesheet'           type='text/css' title='Default Style'>\n";
    $erg .= "<link href='css-din-5008-bunt.css' rel='alternate stylesheet' type='text/css' title='bunt'>\n";
    $erg .= "<link href='insane.css'            rel='alternate stylesheet' type='text/css' title='Insane'>\n";
#           wenn insane.css nicht existiert
#           QNetworkReplyImplPrivate::error: Internal problem, this method must only be called once.
    $erg .= "<link href='$stylesheet'      rel='alternate stylesheet' type='text/css' title='eins'>\n";
    $erg .= "<link href='$stylesheet'      rel='alternate stylesheet' type='text/css' title='zwei'>\n";

    $erg .= "<title>Din-5008-Brief</title>\n";
    $erg .= "</head>\n";
    $erg .= "<body>\n";
    $erg .= sprintf( "<!-- %s -->\n", $zuletzt_aktualisiert);
#   ob_start(); echo "<pre>GET "; print_r( $_GET); echo "</pre>\n"; $erg .= ob_get_clean();
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
    $this->erg .= sprintf( "<div style='"
      . " border  : solid black %dpt;"
      . " width   : %dpt;"
      . " height  : %dpt;"
      . " padding : %dpt 0pt 0pt %dpt;"
      . " margin  :  0pt 0pt 0pt  0pt;"
      . "'>\n",
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

  private $anschriftblock;
  private $infoblock;
  private $textblock;

  function __construct(
    anschriftobjekt         $anschriftblock,
    informationsobjekt      $infoblock,
    betreff_und_textobjekt  $textblock,
    fuszobjekt              $fuszblock
  ) {
    $this->anschriftblock = $anschriftblock;
    $this->infoblock      = $infoblock;
    $this->textblock      = $textblock;
    $this->fuszblock      = $fuszblock;
  }

  function briefkopf( $arg) {
    $ausgabe = "";
    $ausgabe .= "<div style='height:30mm; width:205mm; '>\n";
    $ausgabe .= "<span style='float:left;'>\n";
    $ausgabe .= sprintf( "<span class='kopf-links'>%s</span>", $arg);
    $ausgabe .= "</span>\n";
    $ausgabe .= "<span style='float:right; '>\n";
    $ausgabe .= (new kasten( 6,  5))->zeichnung();
    $ausgabe .= "</span>\n";
    $ausgabe .= "</div>\n";
    $ausgabe .= "<span style='float:stop; '>\n";
    $ausgabe .= "</span>\n";
    return $ausgabe;
  }

  function briefkopf_nicht_wkhtml( $arg) {
    $kasten = (new kasten( 6,  5))->zeichnung();
    $ausgabe = "";
    $ausgabe .= "<div id='parent'>\n";
    $ausgabe .= "<div id='kegel-1'>\n";
    $ausgabe .= "";
    $ausgabe .= sprintf( "<span class='kopf-links'>%s</span>", $arg);
#   $ausgabe .= sprintf( "x", $arg);
    $ausgabe .= "</div>\n";
    $ausgabe .= "<div id='kegel-2'>\n";
    $ausgabe .= sprintf( "<div class='kopf-rechts'>%s</div>", $kasten);
#   $ausgabe .= sprintf( "", $kasten);
    $ausgabe .= "</div>\n";
    $ausgabe .= "</div>\n";
    return sprintf( "<div class='briefkopf'>\n%s\n</div>\n", $ausgabe);
  }

  function absenderblock( $arg) {
    return sprintf( "<div class='absenderblock'>\n%s\n</div>\n", $arg);
  }

  function bezugsblock( $arg) {
    return sprintf( "<div class='bezugsblock'>\n%s\n</div>\n", $arg);
  }

  function betreffblock( $arg) {
    return sprintf( "<div class='betreffblock'>\n%s\n</div>\n", $arg);
  }

  function kopf_seite_eins() {
    $erg ="";
    $erg .= $this->briefkopf( "Sabine Schallehn<br />\nBerlin-Lichtenrade");
    $erg .= $this->absenderblock( "Sabine Schallehn Löwenbrucher Weg 24c D-12307 Berlin");
    $erg .= $this->anschriftblock;
    if (true) {
      $erg .= $this->infoblock;
    } else {
      $erg .= $this->bezugsblock( "Ihr Schreiben vom ");
    }
    if (false) {
      $erg .= $this->betreffblock( "Verdienstabrechnungen und \"gfos 4.7plus\"-Zeitkontoauszüge");
    } 

    return $erg;
  }

  function nur_eine_seite() {
    $erg ="";
    $erg .= $this->kopf_seite_eins();
            $this->textblock->set_seitenanzahl(1);;
    $erg .= $this->textblock;
    $erg .= $this->fuszblock;
    $erg = sprintf( "<div class='dinavier'>%s\n%s\n</div>%s\n",
      "<!-- dinavier a anfang -->",
      $erg,
      "<!-- dinavier a ende -->"
    );
    $erg .= "<div class=\"page-break\"></div>\n";
    return $erg;
  }

  function zwei_seiten( $seitenanzahl) {
    $erg ="";
              $this->textblock->set_seitenanzahl( $seitenanzahl);;
    $erg_1  = $this->kopf_seite_eins();
    for ( $seitennummer = 0; $seitennummer < $seitenanzahl; $seitennummer ++) {

              $this->textblock->set_seitennummer( $seitennummer);;

    $erg_1 .= $this->textblock;

    $erg_1 .= $this->fuszblock;
    $erg   .= sprintf( "<div class='dinavier'>%s\n%s\n</div>%s\n",
      "<!-- dinavier a anfang -->",
      $erg_1,
      "<!-- dinavier a ende -->"
    );
    $erg   .= "<div class=\"page-break\"></div>\n";
    $erg_1 ="";
    }

    return $erg;

    $seitennummer = 0;
    $erg ="";
              $this->textblock->set_seitenanzahl( $seitenanzahl);;

    $erg_1  = $this->kopf_seite_eins();

              $this->textblock->set_seitennummer( $seitennummer++);;

    $erg_1 .= $this->textblock;
    $erg_1 .= $this->fuszblock;

    $erg   .= sprintf( "<div class='dinavier'>%s\n%s\n</div>%s\n",
      "<!-- dinavier a anfang -->",
      $erg_1,
      "<!-- dinavier a ende -->"
    );
    $erg   .= "<div class=\"page-break\"></div>\n";

    if ($seitenanzahl <= $seitennummer) { return $erg;}
    $erg_1 ="";

              $this->textblock->set_seitennummer( $seitennummer++);;

    $erg_1 .= $this->textblock;

    $erg_1 .= $this->fuszblock;
    $erg   .= sprintf( "<div class='dinavier'>%s\n%s\n</div>%s\n",
      "<!-- dinavier a anfang -->",
      $erg_1,
      "<!-- dinavier a ende -->"
    );
    $erg   .= "<div class=\"page-break\"></div>\n";

    if ($seitenanzahl <= $seitennummer) { return $erg;}
    $erg_1 ="";

              $this->textblock->set_seitennummer( $seitennummer++);;

    $erg_1 .= $this->textblock;

    $erg_1 .= $this->fuszblock;
    $erg   .= sprintf( "<div class='dinavier'>%s\n%s\n</div>%s\n",
      "<!-- dinavier a anfang -->",
      $erg_1,
      "<!-- dinavier a ende -->"
    );
    $erg   .= "<div class=\"page-break\"></div>\n";

    if ($seitenanzahl <= $seitennummer) { return $erg;}
    return $erg;
  }

}

class fuszobjekt {
  private $iban = "IBAN DE49 10090000 205 306 4003 BIC BEVODDBE";
  function __toString() {
    return sprintf( "<div class='fuszblock'>\n%s\n</div>\n", $this->iban);
  }
}

$ein_infoblock      = new informationsobjekt( isset( $_GET["info"     ]) ? $_GET["info"     ] : null );
$ein_anschriftblock = new anschriftobjekt(    isset( $_GET["anschrift"]) ? $_GET["anschrift"] : null );
$ein_textblock      = new betreff_und_textobjekt( );
$ein_fuszblock      = new fuszobjekt();
$ein_brief          = new din_brief( $ein_anschriftblock, $ein_infoblock, $ein_textblock, $ein_fuszblock );

$html_seite = new html_seite();

echo $html_seite->html_head( "css-din-5008.css");

#echo $ein_brief->nur_eine_seite();                                
echo $ein_brief->zwei_seiten( 2);                                
echo $ein_brief->zwei_seiten( 1);                                
echo $ein_brief->zwei_seiten( 3);                                

echo $html_seite->html_fusz();

?>
