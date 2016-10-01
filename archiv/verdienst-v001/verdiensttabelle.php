<?php
class zeile {
  public $rw;
  public $mysql_typ;
  public $label;
  public $muster;
  public $bedeutung;
  function __construct ( $rw, $mysql_typ, $label, $muster, $bedeutung) {
    $this->rw        = $rw; // r = read only, w = read and write
    $this->mysql_typ = $mysql_typ;
    $this->label     = $label;
    $this->muster    = $muster;
    $this->bedeutung = $bedeutung;
    #printf( "M010 m%s l%s<br />\n", $this->typ, $this->label, $this->muster, $this->bedeutung);
  }
}

class tabelle {
  function __construct() {
    $id_eigsch = "INT(11) AUTO_INCREMENT PRIMARY KEY";
    $aktuell = "TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
    $decimal = "decimal(5,2) DEFAULT NULL";

    $this->felder = array (
      "id"     => (new zeile("r", $id_eigsch    , "Id"                                            , ""        , "Automatisch vergeben ID")),
      "datum"  => (new zeile("w", "date"        , "Monat"                                         , ""        , "Auto Monat der Verdienstabrechnung")),
      "la001"  => (new zeile("w", "INT(11)"     , "Basisstunden"                                  , "144,00"  , "Beschäftigungsumfang monatlich")),
      "la300"  => (new zeile("w", "INT(11)"     , "Gehalt (JLL)"                                  , "2174.40" , ""  )),
      "la422"  => (new zeile("w", "INT(11)"     , "Verkaufsstellenprämie (JLL)"                   , "222.03"  , ""  )),
      "la444"  => (new zeile("w", "INT(11)"     , "Zulage 4% (JLL)"                               , "85.90"   , ""  )),
      "la531"  => (new zeile("w", "INT(11)"     , "Urlaubsgeld (JEE)"                             , ""   , ""  )),
      "la613"  => (new zeile("w", "INT(11)"     , "Sachbezug Verpflegung (JLL)"                   , "2174.40" , ""  )),
      "la613"  => (new zeile("w", "INT(11)"     , "Sachbezug Verpflegung (JLL)"                   , "2174.40" , ""  )),
      "la639"  => (new zeile("w", "INT(11)"     , "Tarifliche Altersvorsorge"                     , "114.22"  , ""  )),
      "la671"  => (new zeile("w", "INT(11)"     , "VWL - AGA (JLL)"                               , ""        , "Vermögenswirksame Leistungen Arbeitgeberanteil"  )),
      "la677"  => (new zeile("w", "INT(11)"     , "Fahrgeld pauschal(JPF)"                        , "-5.48"   , ""  )),
      "la753"  => (new zeile("w", "INT(11)"     , "Urlaubsausgl (JLL)"                            , "2174.40" , ""  )),
      "la756"  => (new zeile("w", "INT(11)"     , "Krankheitsausgl (12 Monate)(JLL)"              , "2174.40" , ""  )),
      "la756"  => (new zeile("w", "INT(11)"     , "Krankheitsausgl (12 Monate)(JLL)"              , "2174.40" , ""  )),
      "la753"  => (new zeile("w", "INT(11)"     , "Urlaubsgeld (12 Monate)(JLL)"                  , "35.49"   , ""  )),
      "la541"  => (new zeile("w", "INT(11)"     , "Weihnachtsgeld manuell Betrag (JEE)"           , "4.40"    , ""  )),
      "BRG"    => (new zeile("w", "INT(11)"     , "Gesamtbrutto"                                  , ""     , ""  )),
      "BSL"    => (new zeile("w", "INT(11)"     , "Steuerbrutto, laufende Bezüge"                 , ""     , ""  )),
      "BSE"    => (new zeile("w", "INT(11)"     , "Steuerbrutto, sonstige Bezüge"                 , ""     , ""  )),
      "LSE"    => (new zeile("w", "INT(11)"     , "Lohnsteuer aus sonstigen Bezügen"              , ""     , ""  )),
      "LST"    => (new zeile("w", "INT(11)"     , "Lohnsteuer aus monatlichen Bezügen"            , ""     , ""  )),
      "SOZ"    => (new zeile("w", "INT(11)"     , "Solidaritätszuschlag"                          , ""     , ""  )),
      "BRK"    => (new zeile("w", "INT(11)"     , "Krankenversicherungsbrutto"                    , ""     , ""  )),
      "BEK"    => (new zeile("w", "INT(11)"     , "Krankenversicherungsbrutto Einmalbezüge"       , ""     , ""  )),
      "KAN"    => (new zeile("w", "INT(11)"     , "KV AN [allgemeiner Beitrag]"                   , ""     , "Krankenversicherung Arbeitnehmeranteil")),
      "KZA"    => (new zeile("w", "INT(11)"     , "KV Zusatzbeitrag AN [allgemeiner Beitrag]"     , ""     , "Krankenversicherung Arbeitnehmeranteil")),
      "KEN"    => (new zeile("w", "INT(11)"     , "KV AN Einmalbezug [allgemeiner Beitrag]"       , ""     , "Krankenversicherung Arbeitnehmeranteil")),
      "KZE"    => (new zeile("w", "INT(11)"     , "KV Zusatzbeitrag AN EMZ [allgemeiner Beitrag]" , ""     , "Krankenversicherung Arbeitnehmeranteil")),
      "BRR"    => (new zeile("w", "INT(11)"     , "Rentenversicherungsbrutto"                     , ""     , ""  )),
      "BER"    => (new zeile("w", "INT(11)"     , "Rentenversicherungsbrutto Einmalbezüge"        , ""     , ""  )),
      "RAN"    => (new zeile("w", "INT(11)"     , "RV AN [voller Beitrag]"                        , ""     , "Rentenversicherung Arbeitnehmeranteil")),
      "PAN"    => (new zeile("w", "INT(11)"     , "PV AN [voller Beitrag]"                        , ""     , "Pflegeversicherung Arbeitnehmeranteil")),
      "PEN"    => (new zeile("w", "INT(11)"     , "PV AN Einmalbezug [voller Beitrag]"            , ""     , "Pflegeversicherung Arbeitnehmeranteil")),
      "AAN"    => (new zeile("w", "INT(11)"     , "AV AN [voller Beitrag]"                        , ""     , "Arbeitlosenversicherung Arbeitnehmeranteil")),
      "REN"    => (new zeile("w", "INT(11)"     , "RV AN Einmalbezug [voller Beitrag]"            , ""     , "Rentenversicherung Arbeitnehmeranteil")),
      "AEN"    => (new zeile("w", "INT(11)"     , "AV AN Einmalbezug [voller Beitrag]"            , ""     , "Arbeitlosenversicherung Arbeitnehmeranteil")),
      "ZVU"    => (new zeile("w", "INT(11)"     , "Zusatzversorgung - Umlage (AG-Beitrag)"        , ""     , ""  )),
      "GSN"    => (new zeile("w", "INT(11)"     , "Gesetzliches Netto"                            , ""     , ""  )),
      "VLA"    => (new zeile("w", "INT(11)"     , "VL Allgemein"                                  , ""     , ""  )),
      "AZB"    => (new zeile("w", "INT(11)"     , "Auszahlungsbetrag"                             , ""     , ""  )),
      "aktualisiert" => (new zeile("r", $aktuell  , "aktualisiert"                                , ""     , "Aktualisierungzeitpunkt automatisch eingetragen" )),
    );
    $this->miniwahl = array (
      "id"                   ,
      "datum"                ,
      "BRG"                   
    );
    $this->kurzwahl = array (
      "id"                   ,
      "datum"                ,
      "BRG"                   
    );
    foreach ($this->miniwahl as $wahl) {
      $this->minifelder[$wahl] = $this->felder[$wahl];
    }
    foreach ($this->kurzwahl as $wahl) {
      $this->kurzfelder[$wahl] = $this->felder[$wahl];
    }
  }
}
?>
