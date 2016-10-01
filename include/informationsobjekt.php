<?php

class informationsobjekt {

  private $einzelheit;
  private $details = array();

  private $info_label = array(
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

  /*
   * @param stringarray $arg
   * @return 
 * */
  public function __construct( $arg = array()) {
    $this->details = array(
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
      "i_an_angelika"               => array(
      "Ihr_Zeichen"           => 'Spi/An'               ,
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
      "Name"                  => ''                     ,
      "Telefon"               => ''                     ,
      "Telefax"               => ''                     ,
      "E_Mail"                => ''                     ,
      "Datum"                 => ''                     ,
    ) ,
    );

    if (count( $arg) > 0) {
      $this->einzelheit = $arg;
    } else {
      $this->einzelheit = $this->details["i_an_giebler"];
    }
  }
  
  function __toString() {  // Hier wäre auch Einzelbehandlung möglich
    $erg = "";
    $ii = 0;
    foreach ( $this->einzelheit as $key=>$val) {
      $erg .= sprintf( "<tr><td class='infolabel'>%s<td class='infoinhalt'>%s</tr>\n",
        $ii < count( $this->info_label) ? $this->info_label[$ii++] : "",
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
    foreach ( $this->details as $key=>$val) {
      $erg .= $this->option( $key , $this->einzelheit, str_replace( "_", " ", $key ));
    }
    return sprintf( "<select id='wahl'  name=\"%s\">\n$erg</select>\n", "wahl[]");
  }

  function erfrage_und_sende_info( $gepostet) {
    $erg = "";
    $zu_senden = $gepostet == "" ? $this->einzelheit : $this->details[$gepostet];

    foreach ( $zu_senden as $key=>$val) {
      $erg .= sprintf( "<tr><td>%s<td><input id='knopp' type=\"text\" name=\"%s\" value=\"%s\">\n",
        str_replace( "_", " ", $key ),
        "info[$key]",
        $val
      );
    }

    $erg = "<table class=\"ohne-gitter\">\n$erg\n</table>\n";
    return $erg;
  }

}

?>
