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
      "Ihr_Zeichen"           => ''                     ,
      "Ihre_Nachricht_vom"    => ''                     ,
      "Unser_Zeichen"         => 'grd'                  ,
      "Unsere_Nachricht_vom"  => ''                     ,
      "Name"                  => 'Sabine Schallehn'     ,
      "Telefon"               => '030 744 09 05'        ,
      "Telefax"               => ''                     ,
      "E_Mail"                => ''                     ,
      "Datum"                 => ''                     ,
    ) ,                       
      "i_fred"                  => array(
      "Ihr_Zeichen"           => 'HBV'                   ,
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
      "Ihr_Zeichen"           => ''                     ,
      "Ihre_Nachricht_vom"    => ''                     ,
      "Unser_Zeichen"         => ''                     ,
      "Unsere_Nachricht_vom"  => ''                     ,
      "Name"                  => 'Sabine Schallehn'     ,
      "Telefon"               => '030 744 09 05'        ,
      "Telefax"               => ''                     ,
      "E_Mail"                => ''                     ,
      "Datum"                 => ''                     ,
    ) ,                       
      "i_an_giebler"               => array(
      "Ihr_Zeichen"           => ''                     ,
      "Ihre_Nachricht_vom"    => ''                     ,
      "Unser_Zeichen"         => ''                     ,
      "Unsere_Nachricht_vom"  => ''                     ,
      "Name"                  => 'Sabine Schallehn'     ,
      "Telefon"               => '030 744 09 05'        ,
      "Telefax"               => ''                     ,
      "E_Mail"                => ''                     ,
      "Datum"                 => ''                     ,
    ) ,                       
      "i_an_dolgener"               => array(
      "Ihr_Zeichen"           => ''                     ,
      "Ihre_Nachricht_vom"    => ''                     ,
      "Unser_Zeichen"         => ''                     ,
      "Unsere_Nachricht_vom"  => ''                     ,
      "Name"                  => 'Sabine Schallehn'     ,
      "Telefon"               => '030 744 09 05'        ,
      "Telefax"               => ''                     ,
      "E_Mail"                => ''                     ,
      "Datum"                 => ''                     ,
    ) ,                       
      "i_an_heyland"               => array(
      "Ihr_Zeichen"           => 'Hey/Zü'               ,
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
      "Ihr_Zeichen"           => ''                     ,
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
  /*
    if (count( $arg) > 0) {
      $ii = 0;
      foreach ($this->adresse as $key=>$val) {
        if ($ii < count($arg)) {
          $this->adresse[$key] = $arg[$ii++];
        } else {
          $this->adresse[$key] = "";
        }
      }
    }

 * */  
  function __toString() {  // Hier wäre auch Einzelbehandlung möglich
    $erg = ""; $ii = 0;
    foreach ( $this->einzelheit as $key=>$val) {
      $erg .= sprintf( "<tr><td class='infolabel'>%s<td class='infoinhalt'>%s</tr>\n",
        $ii < count( $this->info_l) ? $this->info_l[$ii++] : "",
        $val
      );
    }
    return sprintf( "<div class='informationsblock'>\n<table class='ohne-gitter'>\n%s</table>\n</div><!-- informationsblock ende -->\n", $erg);
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
    foreach ( $this->einzelheitn as $key=>$val) {
      $erg .= $this->option( $key , $this->einzelheit, str_replace( "_", " ", $key ));
    }
/*
    $erg .= $this->option( "i_an_aldi" , $this->einzelheit, "Info Aldi"   );
    $erg .= $this->option( "i_sabine"  , $this->einzelheit, "Info Sabine" );
    $erg .= $this->option( "i_fred"    , $this->einzelheit, "Info Fred"   );
    $erg .= $this->option( "i_leer"    , $this->einzelheit, "Info Leer"   );
*/
    return sprintf( "<select id='wahl'  name=\"%s\">\n$erg</select>\n", "wahl[]");
  }

  function erfrage_und_sende_info( $gepostet) {
    $erg = "";
    $zu_senden = $gepostet == "" ? $this->einzelheit : $this->einzelheitn[$gepostet];

    foreach ( $zu_senden as $key=>$val) {
      $erg .= sprintf( "<tr><td>%s<td><input id='knopp' type=\"text\" name=\"%s\" value=\"%s\">\n",
        str_replace( "_", " ", $key ),
        "info[]",
        $val
      );
    }

    $erg = "<table class=\"ohne-gitter\">\n$erg</table>";
    return $erg;
  }

}

?>
