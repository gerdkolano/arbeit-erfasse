<?php

class informationsobjekt {

  private $einzelheit;
  private $info_l = array(
    "Ihr Zeichen"           ,
    "Ihre Nachricht von"    ,
    "Unser Zeichen"         ,
    "Unsere Nachricht von"  ,
    "Name"                  ,
    "Telefon"               ,
    "Telefax"               ,
    "E_Mail"                ,
    "Datum"                 ,
  );

  private $einzelheitn = array();

  /*
   * @param stringarray $arg
   * @return 
 * */
  public function __construct( $arg = array()) {
    $this->einzelheitn = array(
      "i_sabine"                => array(
      "Ihr_Zeichen"           => 'sa'                   ,
      "Ihre_Nachricht_vom"    => ''                     ,
      "Unser_Zeichen"         => ''                     ,
      "Unsere_Nachricht_vom"  => ''                     ,
      "Name"                  => 'Sabine Schallehn'     ,
      "Telefon"               => '030 744 09 05'        ,
      "Telefax"               => ''                     ,
      "E_Mail"                => ''                     ,
      "Datum"                 => ''                     ,
    ) ,                       
      "i_fred"                  => array(
      "Ihr_Zeichen"           => 'ffr'                   ,
      "Ihre_Nachricht_vom"    => ''                     ,
      "Unser_Zeichen"         => ''                     ,
      "Unsere_Nachricht_vom"  => ''                     ,
      "Name"                  => 'Sabine Schallehn'     ,
      "Telefon"               => '030 744 09 05'        ,
      "Telefax"               => ''                     ,
      "E_Mail"                => ''                     ,
      "Datum"                 => ''                     ,
    ) ,                       
      "i_an_aldi"               => array(
      "Ihr_Zeichen"           => 'al'                   ,
      "Ihre_Nachricht_vom"    => ''                     ,
      "Unser_Zeichen"         => ''                     ,
      "Unsere_Nachricht_vom"  => ''                     ,
      "Name"                  => 'Sabine Schallehn'     ,
      "Telefon"               => '030 744 09 05'        ,
      "Telefax"               => ''                     ,
      "E_Mail"                => ''                     ,
      "Datum"                 => ''                     ,
    ) ,                       
      "i_leer"                  =>  array(
      "Ihr_Zeichen"           => 'le'                   ,
      "Ihre_Nachricht_vom"    => ''                     ,
      "Unser_Zeichen"         => ''                     ,
      "Unsere_Nachricht_vom"  => ''                     ,
      "Name"                  => 'Sabine Schallehn'     ,
      "Telefon"               => '030 744 09 05'        ,
      "Telefax"               => ''                     ,
      "E_Mail"                => ''                     ,
      "Datum"                 => ''                     ,
    ) ,
    );

    if (count( $arg) > 0) {
      $this->einzelheit = $arg;
    }
  }
  
  function __toString() {  // Hier wäre auch Einzelbehandlung möglich
    $erg = ""; $ii = 0;
    foreach ( $this->einzelheit as $key=>$val) {
      $erg .= sprintf( "<tr><td class='infolabel'>%s<td class='infoinhalt'>%s</tr>\n", $this->info_l[$ii++], $val);
    }
    return sprintf( "<div class='informationsblock'>\n<table class='ohne-gitter'>\n%s</table></div>\n", $erg);
  }

  function option( $option_value, $gepostet, $label) {
    $selected = "";
    foreach ($gepostet as $posted_val) {
      if ($option_value == $posted_val) {
        $selected = " selected";
        break;
      }
    }
    return sprintf( "<option value=\"%s\"%s>%s </option>\n", $option_value , $selected, $label);
  }

  function selektiere() {
    $erg = "";
    $erg .= $this->option( "i_an_aldi" , $this->einzelheit, "Info Aldi"   );
    $erg .= $this->option( "i_sabine"  , $this->einzelheit, "Info Sabine" );
    $erg .= $this->option( "i_fred"    , $this->einzelheit, "Info Fred"   );
    $erg .= $this->option( "i_leer"    , $this->einzelheit, "Info Leer"   );
    return sprintf( "<select id='wahl'  name=\"%s\">\n$erg</select>\n", "wahl[]");
  }

  function wahl_obsolet() {
    $f1 = "<button id='wahl' class=\"button-rufe\" type=\"SUBMIT\" name=\"%s\" value=\"%s\">%s </button><br />\n";
    $erg = "";
    $erg .= sprintf( $f1, "wahl[]", "i_an_aldi" , "Info Aldi"   );
    $erg .= sprintf( $f1, "wahl[]", "i_sabine"  , "Info Sabine" );
    $erg .= sprintf( $f1, "wahl[]", "i_fred"    , "Info Fred"   );
    $erg .= sprintf( $f1, "wahl[]", "i_leer"    , "Info Leer"   );
    return $erg;
    return sprintf( "<form id='wahl' method=\"POST\" action=\"$actionskript\">\n$erg\n</form>\n", "");
  }

  function erfrage_und_sende_info( $gepostet) {
    $erg = "";
    $zu_senden = $gepostet == "" ? $this->einzelheit : $this->einzelheitn[$gepostet];

    foreach ( $zu_senden as $key=>$val) {
      $erg .= sprintf( "<tr><td>%s<td><input id='knopp' type=\"text\" name=\"%s\" value=\"%s\">\n",
        $key,
        "info[]",
        $val);
    }

/*  
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
