<?php

class anschriftobjekt {

  private $einzelheit = array(
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

  private $einzelheitn = array();

  /*
   * @param stringarray $arg
   * @return 
 * */
  public function __construct( $arg = array()) {
    $this->einzelheitn = array(
      "a_sabine"    => array(
      "vermerk_1"   => ''                     ,
      "vermerk_2"   => ''                     ,
      "vermerk_3"   => ''                     ,
      "adresse_1"   => 'Sabine Schallehn'     ,
      "adresse_2"   => 'Löwenbrucher Weg 24c' ,
      "adresse_3"   => '12307 Berlin'         ,
      "adresse_4"   => ''                     ,
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
      "adresse_1"   => 'Aldi GmbH & Co. KG'   ,
      "adresse_2"   => 'Frau Giebler'         ,
      "adresse_3"   => 'Osdorfer Ring 21'     ,
      "adresse_4"   => '14979 Großbeeren'     ,
      "adresse_5"   => 'Deutschland'          ,
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
    }
  }
  
  function __toString() {  // Hier wäre auch Einzelbehandlung möglich
    $erg = "";
    foreach ( $this->einzelheit as $key=>$val) {
      $erg .= sprintf( "<span class='infoinhalt'>%s</span><br />\n", $val);
    }
    return sprintf( "<div class='anschriftenfeld'>\n%s\n</div>\n", $erg);

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
    $erg .= $this->option( "a_an_aldi" , $this->einzelheit, "An Aldi"   );
    $erg .= $this->option( "a_sabine"  , $this->einzelheit, "An Sabine" );
    $erg .= $this->option( "a_fred"    , $this->einzelheit, "An Fred"   );
    $erg .= $this->option( "a_leer"    , $this->einzelheit, "An Leer"   );
    return sprintf( "<select id='wahl'  name=\"%s\">\n$erg</select>\n", "wahl[]");
  }

  function wahl_obsolet() {
    $f1 = "<button id='wahl' class=\"button-rufe\" type=\"SUBMIT\" name=\"%s\" value=\"%s\">%s </button><br />\n";
    $erg = "";
    $erg .= sprintf( $f1, "wahl[]", "a_an_aldi" , "An Aldi"   );
    $erg .= sprintf( $f1, "wahl[]", "a_sabine"  , "An Sabine" );
    $erg .= sprintf( $f1, "wahl[]", "a_fred"    , "An Fred"   );
    $erg .= sprintf( $f1, "wahl[]", "a_leer"    , "An Leer"   );
    return $erg;
  }

  function erfrage_und_sende_info( $gepostet) {
    $erg = "";
    $zu_senden = $gepostet == "" ? $this->einzelheit : $this->einzelheitn[$gepostet];

    foreach ( $zu_senden as $key=>$val) {
      $erg .= sprintf( "<tr><td>%s<td><input id='knopp' type=\"text\" name=\"%s\" value=\"%s\">\n",
        $key,
        "anschrift[]",
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
