<?php
require_once( "../include/datum.php"                  );
require_once( "../include/informationsobjekt.php"     );
require_once( "../include/anschriftobjekt.php"        );
require_once( "../include/betreff_und_textobjekt.php" );

class html_seite {

  function html_head( $stylesheet) {
    header('Content-Type: text/html; charset=utf-8');
    $zuletzt_aktualisiert = "Brief zuletzt aktualisiert: Sa 2016-05-28 11:57:36!";
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
$erg .= <<<STIL
<style>

div.body {
    counter-reset: seitennummer 1;
}

div.h1 {
    counter-reset: subsection;
}

div.h1:before {
    counter-increment: seitennummer;
    content: "Seite " counter(seitennummer) ". ";
}

div.h2:before {
    counter-increment: subsection;
    content: counter(seitennummer) "." counter(subsection) " ";
}

</style>

STIL;

    $erg .= "</head>\n";
    $erg .= "<body>\n";
    $erg .= sprintf( "<!-- %s -->\n", $zuletzt_aktualisiert);
    ob_start(); echo "<pre>GET "; print_r( $_GET); echo "</pre>\n"; $erg .= ob_get_clean();
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

  function briefkopf_nicht_wkhtml_obsolet( $arg) {
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

  function nur_eine_seite_obsolet() {
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

  function zwei_seiten_obsolet( $seitenanzahl) {
    $erg ="";
    $erg .= "<div class=\"body\"></div>\n";
              $this->textblock->set_seitenanzahl( $seitenanzahl);;
    $erg_1  = $this->kopf_seite_eins();
    for ( $seitennummer = 0; $seitennummer < $seitenanzahl; $seitennummer ++) {

              $this->textblock->set_seitennummer( $seitennummer);;

    $erg_1 .= "<div class=\"h1\">Seite </div>";
    $erg_1 .= $this->textblock;

    $erg_1 .= "<div class=\"h1\">Seite </div>";

              $this->fuszblock->set_seitennummer( $seitennummer);

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

  }

  function __toString() {
#   $this->textblock->set_kopf_seite_eins( $this->kopf_seite_eins());
#   $this->textblock->set_fuszblock(       $this->fuszblock        );
    return $this->textblock->mit_kopf_und_fusz( $this->kopf_seite_eins(), $this->fuszblock );
  }

}

class fuszobjekt {
  private $iban = "IBAN DE49 10090000 205 306 4003 BIC BEVODDBE";
  function set_seitennummer( $seitennummer) { }
  function __toString() {
    return sprintf( "<div class='fuszblock'>\n%s\n</div>\n", $this->iban);
  }
}

$ein_infoblock      = new informationsobjekt( isset( $_GET["info"     ]) ? $_GET["info"     ] : null );
$ein_anschriftblock = new anschriftobjekt(    isset( $_GET["anschrift"]) ? $_GET["anschrift"] : null );
$ein_textblock      = new betreff_und_textobjekt(
                                              isset( $_GET["brieftext"]) ? $_GET["brieftext"] : null ,
                                              isset( $_GET["allseiten"]) ? $_GET["allseiten"] : null );
$ein_fuszblock      = new fuszobjekt();

$html_seite = new html_seite();

echo $html_seite->html_head( "css-din-5008.css");

echo $ein_brief          = new din_brief( $ein_anschriftblock, $ein_infoblock, $ein_textblock, $ein_fuszblock );
# echo $ein_brief->mehrere_seiten();                                
# echo $ein_brief->zwei_seiten( 2);                                
# echo $ein_brief->zwei_seiten( 1);                                
# echo $ein_brief->zwei_seiten( 3);                                

echo $html_seite->html_fusz();

?>
