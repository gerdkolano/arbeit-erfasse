<?php
require_once( "../include/datum.php");

/*
$za_liste = new za_liste( new inhalt());
echo $za_liste->male( "Sabine Schallehn");

for ($i=0; $i<=10;$i++) { echo "<br />"; } echo "\n";
echo "<div class=\"page-break\"></div>\n";

$za_liste = new za_liste( new inhalt( false));
echo $za_liste->male( "Sabine Schallehn");

echo "<div class=\"page-break\"></div>\n";
 */

class inhalt {
  private $leer;
  function __construct( $leer = true) {
    $this->leer = $leer;
  }
  function zelle( $zeile, $spalte) {
    return $this->leer ? "" : sprintf( "%s %s", $zeile, $spalte);
  }

  function datum( $zeile) {
    return $this->leer ? "" : sprintf( "%s", $zeile);
  }

}

class za_liste_einer_woche {
  private $zeile;
  private $spalte;
  private $spalto;
  private $spaltu;
  private $inhalt;
  function __construct() {
    $this->zeile = array(
      "<td class='tagname'>Mo",
      "<td class='tagname'>Di",
      "<td class='tagname'>Mi",
      "<td class='tagname'>Do",
      "<td class='tagname'>Fr",
      "<td class='tagname'>Sa",
      "<td class='tagname'>Summe"
    );
    $this->spalto = array(
#     "<th rowspan=2>Datum",
      "<th colspan=3 class='geplant'>geplant",
    # "<th>bis",
    # "<th>geplant Std",
      "<th rowspan=2>erscheine im Laden",
      "<th class='geplant'>komme",
      "<th class='geplant'>gehe",
      "<th class='geplant'>komme",
      "<th class='geplant'>gehe",
      "<th class='geplant'>komme",
      "<th class='geplant'>gehe",
      "<th rowspan=2>verlasse den Laden", 
      "<th rowspan=2>reine Arbeitszeit",
      "<th rowspan=2>20% Spät- zuschlag",
      "<th rowspan=2>50% Nacht- zuschlag",
      "<th rowspan=2>Verfallszeit" 
    );
    $this->spaltu = array(
    # "<th>Datum",
      "<th>von",
      "<th>bis",
      "<th>Stunden",
    # "<th>er- scheine",
      "<th>Arbeit anfangen",
      "<th>in die 1. Pause",
      "<th>aus der 1. Pause",
      "<th>in die 2. Pause",
      "<th>aus der 2. Pause",
      "<th>Arbeit beenden",
    # "<th>verlasse", 
    # "<th>reine Arbeits- zeit",
    # "<th>20%",
    # "<th>50%",
    # "<th>Verfalls- zeit" 
    );
    $this->spalte = array(
#     "<th>Datum",
      "<th>geplant von",
      "<th>bis",
      "<th>geplant Std",
      "<th>er- scheine",
      "<th>komme zur Arbeit",
      "<th>gehe in die 1. Pause",
      "<th>komme aus der 1. Pause",
      "<th>gehe in die 2. Pause",
      "<th>komme aus der 2. Pause",
      "<th>gehe mich um- ziehen",
      "<th>verlasse", 
      "<th>reine Arbeits- zeit",
      "<th>20%",
      "<th>50%",
      "<th>Verfalls- zeit" 
    );
  }

  function kopf_einer_woche( $anspruchsteller) {
    $h1 = sprintf( "%s - Detaillierte Auflistung der Zeitausgleichsliste für Monat … … … … …", $anspruchsteller);
    $kopf = sprintf( "<h4>%s</h4>", $h1);

    $erg = "";
    $erg .= "<thead>\n";
    $erg .= "<tr><th rowspan=2> Datum";
    foreach ( $this->spalto as $skey=>$sval) {
      $erg .= "$sval\n";
    }
    $erg .= "</tr>\n";
    $erg .= "<tr>";
    foreach ( $this->spaltu as $skey=>$sval) {
      $erg .= "$sval\n";
    }
    $erg .= "</tr>\n";
    $erg .= "</thead>\n";
    return $erg;
    return sprintf( "%s\n<table>\n%s</table>\n%s\n", $kopf, $erg, $fusz);
  }
}
    
class za_liste {
  private $zeile;
  private $spalte;
  private $spalto;
  private $spaltu;
  private $inhalt;
  function __construct( inhalt $inhalt) {
    $this->inhalt = $inhalt;
    $this->zeile = array(
      "<td class='tagname'>Mo",
      "<td class='tagname'>Di",
      "<td class='tagname'>Mi",
      "<td class='tagname'>Do",
      "<td class='tagname'>Fr",
      "<td class='tagname'>Sa",
      "<td class='tagname'>Summe"
    );
    $this->spalto = array(
#     "<th rowspan=2>Datum",
      "<th colspan=3 class='geplant'>geplant",
    # "<th>bis",
    # "<th>geplant Std",
      "<th rowspan=2>erscheine im Laden",
      "<th class='geplant'>komme",
      "<th class='geplant'>gehe",
      "<th class='geplant'>komme",
      "<th class='geplant'>gehe",
      "<th class='geplant'>komme",
      "<th class='geplant'>gehe",
      "<th rowspan=2>verlasse den Laden", 
      "<th rowspan=2>reine Arbeitszeit",
      "<th rowspan=2>20% Spät- zuschlag",
      "<th rowspan=2>50% Nacht- zuschlag",
      "<th rowspan=2>Verfallszeit" 
    );
    $this->spaltu = array(
    # "<th>Datum",
      "<th>von",
      "<th>bis",
      "<th>Stunden",
    # "<th>er- scheine",
      "<th>Arbeit anfangen",
      "<th>in die 1. Pause",
      "<th>aus der 1. Pause",
      "<th>in die 2. Pause",
      "<th>aus der 2. Pause",
      "<th>Arbeit beenden",
    # "<th>verlasse", 
    # "<th>reine Arbeits- zeit",
    # "<th>20%",
    # "<th>50%",
    # "<th>Verfalls- zeit" 
    );
    $this->spalte = array(
#     "<th>Datum",
      "<th>geplant von",
      "<th>bis",
      "<th>geplant Std",
      "<th>er- scheine",
      "<th>komme zur Arbeit",
      "<th>gehe in die 1. Pause",
      "<th>komme aus der 1. Pause",
      "<th>gehe in die 2. Pause",
      "<th>komme aus der 2. Pause",
      "<th>gehe mich um- ziehen",
      "<th>verlasse", 
      "<th>reine Arbeits- zeit",
      "<th>20%",
      "<th>50%",
      "<th>Verfalls- zeit" 
    );
  }

  function kopf_einer_woche( $anspruchsteller) {
    $h1 = sprintf( "%s - Detaillierte Auflistung der Zeitausgleichsliste für Monat … … … … …", $anspruchsteller);
    $kopf = sprintf( "<h4>%s</h4>", $h1);

    $erg = "";
    $erg .= "<thead>\n";
    $erg .= "<tr><th rowspan=2> Datum";
    foreach ( $this->spalto as $skey=>$sval) {
      $erg .= "$sval\n";
    }
    $erg .= "</tr>\n";
    $erg .= "<tr>";
    foreach ( $this->spaltu as $skey=>$sval) {
      $erg .= "$sval\n";
    }
    $erg .= "</tr>\n";
    $erg .= "</thead>\n";
    return $erg;
    return sprintf( "%s\n<table>\n%s</table>\n%s\n", $kopf, $erg, $fusz);
  }
    

  function male( $anspruchsteller) {
    $h1 = sprintf( "%s - Detaillierte Auflistung der Zeitausgleichsliste für Monat … … … … …", $anspruchsteller);
    $kopf = sprintf( "<h4>%s</h4>", $h1);

    $h3 = sprintf( "Beschäftigungsumfang %s Stunden, %s von %s Stunden wöchentlich", "33,30", "90%", "37");
    $fusz = sprintf( "<div>%s</div>", $h3);

    $erg = "";
    $erg .= "<thead>\n";
    $erg .= "<tr><th rowspan=2> Datum";
    foreach ( $this->spalto as $skey=>$sval) {
      $erg .= "$sval\n";
    }
    $erg .= "</tr>\n";
    $erg .= "<tr>";
    foreach ( $this->spaltu as $skey=>$sval) {
      $erg .= "$sval\n";
    }
    $erg .= "</tr>\n";
    $erg .= "</thead>\n";

    $erg .= "<tbody>\n";
    foreach ( $this->zeile as $zkey=>$zval) {
      $erg .= "<tr>$zval " . $this->inhalt->datum( $zkey);
      foreach ( $this->spalte as $skey=>$sval) {
        $erg .= "<td>" . $this->inhalt->zelle( $zkey, $skey);
      }
      $erg .= "</tr>\n";
    }
    $erg .= "</tbody>\n";
    return sprintf( "%s\n<table>\n%s</table>\n%s\n", $kopf, $erg, $fusz);
  }
    
}

?>
