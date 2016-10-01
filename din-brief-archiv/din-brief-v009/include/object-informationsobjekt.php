<?php

class informationsobjekt {
    public $Ihr_Zeichen            ;
    public $Ihre_Nachricht_vom     ;
    public $Unser_Zeichen          ;
    public $Unsere_Nachricht_vom   ;
    public $Name                   ;
    public $Telefon                ;
    public $Telefax                ;
    public $E_Mail                 ;
    public $Datum                  ;

  function __construct( $arg = array()) {
    $ii = 0;
    if (count( $arg) <= $ii) {
      foreach ( get_object_vars( $this) as $key=>$val) {
        $this->$key = $key;
      }
    } else {
      foreach ( get_object_vars( $this) as $key=>$val) {
        if ($ii >= count( $arg)) break;
        $this->$key = $arg[$ii++];
      }
    }
  }

  function __toString() {  // Hier wäre auch Einzelbehandlung möglich
    $erg = "";
    foreach ( get_object_vars( $this) as $key=>$val) {
      $erg .= sprintf( "<tr><td class='infolabel'>%s<td class='infoinhalt'>%s</tr>\n",
        $key, $val);
    }
    return sprintf( "<div class='informationsblock'>\n<table class='ohne-gitter'>\n%s</table></div>\n", $erg);
  }

  function erfrage_und_sende_info() {
    $erg = "";

    foreach ( get_object_vars( $this) as $key=>$val) {
      $erg .= sprintf( "<tr><td>%s<td><input id='knopp' type=\"text\" name=\"%s\" value=\"%s\">\n",
        $key,
        "info[]",
        $val);
    }

    $name  = "gesendet-von";
    $value = "brief-schreiben.php";
    $erg .= sprintf( "<button id='knopp' class=\"button-rufe\" type=\"SUBMIT\" name=\"%s\" value=\"%s\">%s </button><br />\n",
        $name,
        $value,
        "knopp"
      );
  
    $erg = "<table class=\"ohne-gitter\">\n$erg</table>";
#   $actionskript = "brief-din-5008.php";
#   $erg = sprintf( "<form method=\"POST\" action=\"%s\">\n%s\n</form>\n", $actionskript, $erg);
    return $erg;
  }

}
?>
