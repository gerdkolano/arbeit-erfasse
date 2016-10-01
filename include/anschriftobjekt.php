<?php

class anschriftobjekt {

  private $einzelheit = array();
  private $details = array();

  private $einzelheit_obsolet = array(
    "vermerk_1"   => 'Einschreiben'         ,
    "vermerk_2"   => 'Wenn verzogen,'       ,
    "vermerk_3"   => 'Zurück an Absender'   ,
    "adresse_1"   => 'Ein Firmenname'       ,
    "adresse_2"   => 'Personenname'         ,
    "adresse_3"   => 'Und die Straße'       ,
    "adresse_4"   => 'bärlin'               ,
    "adresse_5"   => 'Und das Land'         ,
    "adresse_6"   => 'Postfach'             ,
  );

  /*
   * @param stringarray $arg
   * @return 
 * */
  public function __construct( $arg = array()) {
    $this->details = array(
      "a_sabine"    => array(
      "vermerk_1"   => ''                     ,
      "vermerk_2"   => ''                     ,
      "vermerk_3"   => ''                     ,
      "adresse_1"   => 'Sabine Schallehn'     ,
      "adresse_2"   => 'Löwenbrucher Weg 24c' ,
      "adresse_3"   => '12307 Berlin'         ,
      "adresse_4"   => 'Deutschland'          ,
      "adresse_5"   => ''                     ,
      "adresse_6"   => ''                     ,
    ) ,
      "a_fred"      => array(
      "vermerk_1"   => ''                     ,
      "vermerk_2"   => ''                     ,
      "vermerk_3"   => ''                     ,
      "adresse_1"   => 'Manfred Birkhahn'     ,
      "adresse_2"   => 'Ostpreußendamm 24b'   ,
      "adresse_3"   => '12207 Berlin'         ,
      "adresse_4"   => ''                     ,
      "adresse_5"   => ''                     ,
      "adresse_6"   => ''                     ,
    ) ,
      "a_an_aldi"   => array(
      "vermerk_1"   => ''                     ,
      "vermerk_2"   => ''                     ,
      "vermerk_3"   => ''                     ,
      "adresse_1"   => 'Aldi GmbH &amp; Co. KG'   ,
      "adresse_2"   => 'Osdorfer Ring 21'     ,
      "adresse_3"   => '14979 Großbeeren'     ,
      "adresse_4"   => ''                     ,
      "adresse_5"   => ''                     ,
      "adresse_6"   => ''                     ,
    ) ,
      "a_an_giebler"   => array(
      "vermerk_1"   => ''                     ,
      "vermerk_2"   => ''                     ,
      "vermerk_3"   => ''                     ,
      "adresse_1"   => 'Aldi GmbH & Co. KG'   ,
      "adresse_2"   => 'Frau Giebler'         ,
      "adresse_3"   => 'Osdorfer Ring 21'     ,
      "adresse_4"   => '14979 Großbeeren'     ,
      "adresse_5"   => ''                     ,
      "adresse_6"   => ''                     ,
    ) ,
      "a_an_dolgener"   => array(
      "vermerk_1"   => ''                     ,
      "vermerk_2"   => ''                     ,
      "vermerk_3"   => ''                     ,
      "adresse_1"   => 'Aldi GmbH & Co. KG'   ,
      "adresse_2"   => 'Herr Dolgener'        ,
      "adresse_3"   => 'Osdorfer Ring 21'     ,
      "adresse_4"   => '14979 Großbeeren'     ,
      "adresse_5"   => ''                     ,
      "adresse_6"   => ''                     ,
    ) ,
      "a_an_heyland"   => array(
      "vermerk_1"   => ''                     ,
      "vermerk_2"   => ''                     ,
      "vermerk_3"   => ''                     ,
      "adresse_1"   => 'Aldi GmbH & Co. KG'   ,
      "adresse_2"   => 'Herr Heyland'         ,
      "adresse_3"   => 'Osdorfer Ring 21'     ,
      "adresse_4"   => '14979 Großbeeren'     ,
      "adresse_5"   => ''                     ,
      "adresse_6"   => ''                     ,
    ) ,
      "a_an_angelika"      =>  array(
      "vermerk_1"   => ''                     ,
      "vermerk_2"   => ''                     ,
      "vermerk_3"   => ''                     ,
      "adresse_1"   => 'Angelika Spillner'    ,
      "adresse_2"   => 'Barbiser Straße 46'   ,
      "adresse_3"   => '37431 Barbis'         ,
      "adresse_4"   => ''                     ,
      "adresse_5"   => ''                     ,
      "adresse_6"   => ''                     ,
    ) ,
      "a_leer"      =>  array(
      "vermerk_1"   => ''                     ,
      "vermerk_2"   => ''                     ,
      "vermerk_3"   => ''                     ,
      "adresse_1"   => ''                     ,
      "adresse_2"   => ''                     ,
      "adresse_3"   => ''                     ,
      "adresse_4"   => ''                     ,
      "adresse_5"   => ''                     ,
      "adresse_6"   => ''                     ,
    ) ,
    );

    if (count( $arg) > 0) {
      $this->einzelheit = $arg;
    } else {
      $this->einzelheit = $this->details["a_an_giebler"];
    }
  }
  
  function __toString() {  // Hier wäre auch Einzelbehandlung möglich
    $erg = "";
    foreach ( $this->einzelheit as $key=>$val) {
      $erg .= sprintf( "<span class='infoinhalt'>%s</span><br />\n", $val);
    }
    return sprintf( "<div class='anschriftblock'>\n%s</div><!-- anschriftblock ende -->\n", $erg);
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
        "anschrift[$key]",
        $val
      );
    }

    $erg = "<table class=\"ohne-gitter\">\n$erg\n</table>\n";
    return $erg;
  }

}

?>
