<?php
require_once( "../include/datum.php"                  );
require_once( "../include/informationsobjekt.php"     );
require_once( "../include/anschriftobjekt.php"        );
require_once( "../include/betreff_und_textobjekt.php" );

class html_seite {

  function html_head( $stylesheet) {
    header('Content-Type: text/html; charset=utf-8');
    $zuletzt_aktualisiert = "Brief zuletzt aktualisiert: Mi 2016-06-01 08:48:28!";
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

    $erg .= "<title>Sende Brief Din-5008</title>\n";
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

  function __toString() {
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

class schreibe {
  private $fh;
  public function __construct( $text) {
    $myFile = "include-generator.php";
    $myFile = "/var/www/generator/include-generator.php";
    $this->fh = fopen( $myFile, 'w')
      or printf( "Kann %s/%s nicht öffnen.<br />\n"
                . "Als root: <br />\n"
                . "f=%s/%s; touch \$f; chown www-data: \$f<br />\n"
                . "Server addr %s Server name %s Http host %s <br />\n",
        __DIR__,
        $myFile,
        __DIR__,
        $myFile,
        $_SERVER["SERVER_ADDR"],
        $_SERVER['SERVER_NAME'],
        $_SERVER['HTTP_HOST']
      );
    if ( $this->fh) {
    # fwrite( $this->fh, sprintf ( "%s\n", date( "Y-m-d H:i:s")));
      fwrite( $this->fh, sprintf ( "<?php \$parameter=\"%s\";?%s\n", $text, ">"));
#     fwrite( $this->fh, "\n");
    }

  }

  private function schreibe( $arg) { fwrite( $this->fh, $arg);}
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

if (isset( $_GET["debug"]) and $_GET["debug"] > 0 ) {
  $erg="";
  ob_start(); echo "<pre>GET  "; print_r( $_GET ); echo "</pre>\n"; $erg .= ob_get_clean();
  ob_start(); echo "<pre>POST "; print_r( $_POST); echo "</pre>\n"; $erg .= ob_get_clean();
  $erg .= http_build_query( $_GET);
  new schreibe( http_build_query( $_GET));
  echo $erg;
}

echo $html_seite->html_fusz();

?>
