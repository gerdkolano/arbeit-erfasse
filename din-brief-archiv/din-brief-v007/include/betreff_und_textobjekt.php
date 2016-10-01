<?php
require_once( "LoremIpsum.class.php");

class betreff_und_textobjekt {

  private $adresse = array(
    "betreff"       => 'Betrifft'                 ,
    "anrede"        => 'Sehr geehrte'             ,
    "inhalt"        => 'Inhalt'                   ,
    "grusz"         => 'Mit freundlichen Grüßen'  ,
    "unterschrift"  => 'Sabine Schallehn'         ,
  );

  public function __construct( $arg = array()) {
    $this->adresse["inhalt"      ] = (new LoremIpsumGenerator)->getContent( 430);

    if (count( $arg) > 0) {
      $ii = 0;
      foreach ($this->adresse as $key=>$val) {
        if ($ii >= count($arg)) break;
        $this->adresse[$key] = $arg[$ii++];
      }
    }
  }
  
  function __toString() {  // Hier wäre auch Einzelbehandlung möglich
#   echo "<pre>__toString "     ; print_r( $this->adresse)    ; echo "</pre>";
    $erg = "";
    $erg .= sprintf( "<div class='betrifft'     >%s</div>                  \n", $this->adresse["betreff"     ]);
    $erg .= sprintf( "<div class='anrede'       >%s</div>                  \n", $this->adresse["anrede"      ]);
    $erg .= sprintf( "<div class='inhalt'       >%s</div>                  \n", $this->adresse["inhalt"      ]);
    $erg .= sprintf( "<div class='grusz'        >%s</div>                  \n", $this->adresse["grusz"       ]);
    $erg .= sprintf( "<div class='unterschrift' >%s</div>                  \n", $this->adresse["unterschrift"]);
#   foreach ( $this->adresse as $key=>$val) {
#     $erg .= sprintf( "<span class='infoinhalt'>%s</span><br />\n", $key.$val);
#   }
    return sprintf( "<div class='textfeld'>\n%s\n</div>\n", $erg);

  }

  function erfrage_und_sende_info() {
    $erg = "";
    $f1 = "<tr><td>%s<td><input id='knopp' type=\"text\" name=\"%s\" value=\"%s\">\n";
    $f2 = "<tr><td>%s<td><textarea id='knopp' type=\"text\" name=\"%s\" value=\"%s\" cols='100' rows='50'>%s</textarea>\n";

    $erg .= sprintf( $f1, "betreff"     , "brieftext[]",     $this->adresse["betreff"     ]);
    $erg .= sprintf( $f1, "anrede"      , "brieftext[]",     $this->adresse["anrede"      ]);
    $erg .= sprintf( $f2, "inhalt"      , "brieftext[]", "", $this->adresse["inhalt"      ]);
    $erg .= sprintf( $f1, "grusz"       , "brieftext[]",     $this->adresse["grusz"       ]);
    $erg .= sprintf( $f1, "unterschrift", "brieftext[]",     $this->adresse["unterschrift"]);
/*
    foreach ( $this->adresse as $key=>$val) {
      $erg .= sprintf( "<tr><td>%s<td><input id='knopp' type=\"text\" name=\"%s\" value=\"%s\">\n",
        $key,
        "brieftext[]",
        $val);
    }

    $name  = "gesendet-von";
    $value = "brief-schreiben.php";
    $erg .= sprintf( "<button id='knopp' class=\"button-rufe\" type=\"SUBMIT\" name=\"%s\" value=\"%s\">%s </button><br />\n",
        $name,
        $value,
        "knopp"
      );
*/  
    $erg = "<table class=\"ohne-gitter\">\n$erg</table>";
    return $erg;
  }

}

?>
