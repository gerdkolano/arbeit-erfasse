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
      "id"     => (new zeile("r", $id_eigsch, "Id"                                            , ""        , "Automatisch vergeben ID")),
      "datum"  => (new zeile("w", "date"    , "Monat"                                         , "1.1.15"  , "Monat der Verdienstabrechnung")),
      "la001"  => (new zeile("w", "INT(11)" , "Basisstunden"                                  , "14400"   , "Beschäftigungsumfang monatlich")),
      "la300"  => (new zeile("w", "INT(11)" , "Gehalt (JLL)"                                  , "217440"  , ""  )),
      "zt305"  => (new zeile("w", "INT(11)" , "Mehrarbeit (JLL)"                              , "823"     , "Stunden"  )),
      "sa305"  => (new zeile("w", "INT(11)" , "Mehrarbeit (JLL)"                              , "1513"    , "€/h Stundensatz"  )),
      "la305"  => (new zeile("w", "INT(11)" , "Mehrarbeit (JLL)"                              , "12452"   , "€ Betrag"  )),
      "zt307"  => (new zeile("w", "INT(11)" , "25% Mehrarbeit f. Angest. (JLL)"               , "232"     , "Stunden"  )),
      "sa307"  => (new zeile("w", "INT(11)" , "25% Mehrarbeit f. Angest. (JLL)"               , "388"     , "€/h Stundensatz"  )),
      "la307"  => (new zeile("w", "INT(11)" , "25% Mehrarbeit f. Angest. (JLL)"               , "900"     , "€ Betrag"  )),
      "zt357"  => (new zeile("w", "INT(11)" , "20% Zuschlag Spätöffnung (JLL)"                , "5470"    , "Stunden"  )),
      "sa357"  => (new zeile("w", "INT(11)" , "20% Zuschlag Spätöffnung (JLL)"                , "1551"    , "€/h 104% des Stundenlohns"  )),
      "la357"  => (new zeile("w", "INT(11)" , "20% Zuschlag Spätöffnung (JLL)"                , "16968"   , "€ Betrag"  )),
      "la422"  => (new zeile("w", "INT(11)" , "Verkaufsstellenprämie (JLL)"                   , "22203"   , ""  )),
      "la444"  => (new zeile("w", "INT(11)" , "Zulage 4% (JLL)"                               , "8590"    , ""  )),
      "la531"  => (new zeile("w", "INT(11)" , "Urlaubsgeld (JEE)"                             , "0"       , ""  )),
      "la541"  => (new zeile("w", "INT(11)" , "Weihnachtsgeld manuell Betrag (JEE)"           , "222089"  , ""  )),
      "la549"  => (new zeile("w", "INT(11)" , "Sachbezug st/svpfl. EBEZ (NEE)"                , "14800"   , ""  )),
      "la550"  => (new zeile("w", "INT(11)" , "Sachbezug Netto-Brutto"                        , "13386"   , ""  )),
      "la570"  => (new zeile("w", "INT(11)" , "Efektivbeschäftigung manuell (JEE)"            , "900"     , ""  )),
      "la613"  => (new zeile("w", "INT(11)" , "Sachbezug Verpflegung (JLL)"                   , "217440"  , ""  )),
      "la639"  => (new zeile("w", "INT(11)" , "Tarifliche Altersvorsorge"                     , "11422"   , ""  )),
      "la671"  => (new zeile("w", "INT(11)" , "VWL - AGA (JLL)"                               , "3589"    , "Vermögenswirksame Leistungen Arbeitgeberanteil")),
      "la677"  => (new zeile("w", "INT(11)" , "Fahrgeld pauschal (JPF)"                       , "-548"    , ""  )),
      "la692"  => (new zeile("w", "INT(11)" , "Zuschlagspflichtige Monatsstd."                , "000"     , ""  )),
      "la700"  => (new zeile("w", "INT(11)" , "Pauschaler Meharbeitsausgleich (JLL)"          , "6942"    , ""  )),
      "la707"  => (new zeile("w", "INT(11)" , "Pauschaler Meharbeitsausgleich 25% (JLL)"      , "000"     , ""  )),
      "la753"  => (new zeile("w", "INT(11)" , "Urlaubsausgl. (JLL)"                           , "000"     , ""  )),
      "la756"  => (new zeile("w", "INT(11)" , "Krankheitsausgl. (12 Monate)(JLL)"             , "262"     , ""  )),
      "la760"  => (new zeile("w", "INT(11)" , "Kontoführungsgebühr (JLL)"                     , "102"     , ""  )),
      "zt770"  => (new zeile("w", "INT(11)" , "Nachtzuschlag 50%pfl.25%fr. Gehalt (JLL)"      , "2238"    , "Stunden"  )),
      "sa770"  => (new zeile("w", "INT(11)" , "Nachtzuschlag 50%pfl.25%fr. Gehalt (JLL)"      , "1491"    , "€/h Stundenlohn")),
      "la770"  => (new zeile("w", "INT(11)" , "Nachtzuschlag 50%pfl.25%fr. Gehalt (JLL)"      , "16685"   , "€ Betrag"  )),
      "BRG"    => (new zeile("w", "INT(11)" , "Gesamtbrutto"                                  , " "       , ""  )),
      "BSL"    => (new zeile("w", "INT(11)" , "Steuerbrutto, laufende Bezüge"                 , " "       , ""  )),
      "SZFz"   => (new zeile("w", "INT(11)" , "Steuerfreie Sonn-/Feiertag und Nacht-zuschläge", "1699"    , ""  )),
      "SZF"    => (new zeile("w", "INT(11)" , "Steuerfreie Sonn-/Feiertag und Nacht-zuschläge", "1949"    , ""  )),
      "BSE"    => (new zeile("w", "INT(11)" , "Steuerbrutto, sonstige Bezüge"                 , " "       , ""  )),
      "LSE"    => (new zeile("w", "INT(11)" , "Lohnsteuer aus sonstigen Bezügen"              , " "       , ""  )),
      "LST"    => (new zeile("w", "INT(11)" , "Lohnsteuer aus monatlichen Bezügen"            , "-"       , ""  )),
      "LAG"    => (new zeile("w", "INT(11)" , "Lohnsteuerausgleich"                           , "-"       , ""  )),
      "SOZ"    => (new zeile("w", "INT(11)" , "Solidaritätszuschlag"                          , "-"       , ""  )),
      "SAG"    => (new zeile("w", "INT(11)" , "Solidaritätszuschlag, Jahresausgleich"         , "-"       , ""  )),
      "BRK"    => (new zeile("w", "INT(11)" , "Krankenversicherungsbrutto"                    , " "       , ""  )),
      "BEK"    => (new zeile("w", "INT(11)" , "Krankenversicherungsbrutto Einmalbezüge"       , " "       , ""  )),
      "KAN"    => (new zeile("w", "INT(11)" , "KV AN [allgemeiner Beitrag]"                   , "-"       , "Krankenversicherung Arbeitnehmeranteil")),
      "KZA"    => (new zeile("w", "INT(11)" , "KV Zusatzbeitrag AN [allgemeiner Beitrag]"     , "-"       , "Krankenversicherung Arbeitnehmeranteil")),
      "KEN"    => (new zeile("w", "INT(11)" , "KV AN Einmalbezug [allgemeiner Beitrag]"       , " "       , "Krankenversicherung Arbeitnehmeranteil")),
      "KZE"    => (new zeile("w", "INT(11)" , "KV Zusatzbeitrag AN EMZ [allgemeiner Beitrag]" , " "       , "Krankenversicherung Arbeitnehmeranteil")),
      "BRR"    => (new zeile("w", "INT(11)" , "Rentenversicherungsbrutto"                     , " "       , ""  )),
      "BER"    => (new zeile("w", "INT(11)" , "Rentenversicherungsbrutto Einmalbezüge"        , " "       , ""  )),
      "RAN"    => (new zeile("w", "INT(11)" , "RV AN [voller Beitrag]"                        , "-"       , "Rentenversicherung Arbeitnehmeranteil")),
      "PAN"    => (new zeile("w", "INT(11)" , "PV AN [voller Beitrag]"                        , "-"       , "Pflegeversicherung Arbeitnehmeranteil")),
      "PEN"    => (new zeile("w", "INT(11)" , "PV AN Einmalbezug [voller Beitrag]"            , " "       , "Pflegeversicherung Arbeitnehmeranteil")),
      "AAN"    => (new zeile("w", "INT(11)" , "AV AN [voller Beitrag]"                        , "-"       , "Arbeitlosenversicherung Arbeitnehmeranteil")),
      "REN"    => (new zeile("w", "INT(11)" , "RV AN Einmalbezug [voller Beitrag]"            , " "       , "Rentenversicherung Arbeitnehmeranteil")),
      "AEN"    => (new zeile("w", "INT(11)" , "AV AN Einmalbezug [voller Beitrag]"            , " "       , "Arbeitlosenversicherung Arbeitnehmeranteil")),
      "ZVU"    => (new zeile("w", "INT(11)" , "Zusatzversorgung - Umlage (AG-Beitrag)"        , " "       , ""  )),
      "GSN"    => (new zeile("w", "INT(11)" , "Gesetzliches Netto"                            , " "       , ""  )),
      "la990"  => (new zeile("w", "INT(11)" , "Abschlagsverrechnung"                          , "-163747" , ""  )),
      "VLA"    => (new zeile("w", "INT(11)" , "VL Allgemein"                                  , "-4000"   , ""  )),
      "GWS"    => (new zeile("w", "INT(11)" , "Summe Abzug Sachbezug"                         , "-13377"  , ""  )),
      "UVM"    => (new zeile("w", "INT(11)" , "Überzahlung aus dem Vormomat"                  , "-"       , ""  )),
      "UEZ"    => (new zeile("w", "INT(11)" , "Überzahlung wird im nächsten Monat abgezogen"  , "324"     , ""  )),
      "AZB"    => (new zeile("w", "INT(11)" , "Auszahlungsbetrag"                             , " "       , ""  )),
      "aktualisiert" => (new zeile("r", $aktuell  , "aktualisiert"                            , " "       , "Aktualisierungzeitpunkt automatisch eingetragen")),
    );
    $this->miniwahl = array (
      "id"                   ,
      "datum"                ,
      "BRG"                   
    );
    $this->kurzwahl = array (
      "id"                   ,
      "datum"                ,
      "la001"                ,
      "la300"                ,
      "zt307"                ,
      "sa307"                ,
      "la307"                ,
      "zt357"                ,
      "sa357"                ,
      "la357"                ,
      "la422"                ,
      "la444"                ,
      "la531"                ,
      "la613"                ,
      "la613"                ,
      "la639"                ,
      "la671"                ,
      "la677"                ,
      "la753"                ,
      "la756"                ,
      "zt770"                ,
      "sa770"                ,
      "la770"                ,
      "la541"                ,
    );
    foreach ($this->miniwahl as $wahl) {
      $this->minifelder[$wahl] = $this->felder[$wahl];
    }
    foreach ($this->kurzwahl as $wahl) {
      $this->kurzfelder[$wahl] = $this->felder[$wahl];
    }
  }
}
/*
  Prüfe die Plausibilität
  Solidaritätszuschlag 5.5 % der Lohnsteuer
  Krankenversicherung       Arbeitnehmeranteil 7.30 %
  Rentenversicherung        Arbeitnehmeranteil 9.35 %
  Pflegeversicherung        Arbeitnehmeranteil 1.175 %
  Arbeitslosenversicherung  Arbeitnehmeranteil 1.175 %
select id,datum, lst, soz, (lst) * 0.055 - soz as nix from verdienst ;     -- nix < 1
select id,datum, lst, soz, (lst+lse) * 0.055 - soz as nix from verdienst ; -- nix < 1
select id,datum, kan, brk, kan/brk as prozentsatz73 from verdienst ;       -- prozentsatz73 = 0.073
select id,datum, kan, brk, kan/brk as prozentsatz73  ,ken, bek, round(bek*0.073 ) + ken as nix, ken/bek from verdienst ; -- nix = 0 und prozentsatz73  = 0.073
select id,datum, ran, brr, ran/brr as prozentsatz935 ,ren, ber, round(ber*0.0935) + ren as nix, ren/ber from verdienst ; -- nix = 0 und prozentsatz935 = 0.0935
select id,datum, ran, brr, round(brr*0.01175) + pan ,pen, ber, round(ber*0.01175) + pen, pen/ber from verdienst ;
select id,datum, ran, brr, round(brr*0.01175) + pan ,pen, ber, round(ber*0.01175) + pen, pen/ber from verdienst ;
select id,datum, aan, brr, round(brr*0.015  ) + aan ,aen, ber, round(ber*0.015  ) + aen, aen/ber from verdienst ;

*/
/*
ALTER TABLE verdienst ADD LAG INT(11) AFTER LST;
ALTER TABLE verdienst ADD SAG INT(11) AFTER SOZ;
ALTER TABLE verdienst CHANGE la541 la541 INT(11);
ALTER TABLE verdienst MODIFY COLUMN la541 VARCHAR(50) AFTER la531;
ALTER TABLE verdienst ADD SZF   INT(11) AFTER BSL;
ALTER TABLE verdienst ADD SZFz  INT(11) AFTER BSL;
ALTER TABLE verdienst ADD la990 INT(11) AFTER GSN;
ALTER TABLE verdienst ADD UEZ INT(11) AFTER UVM;
ALTER TABLE verdienst ADD UVM INT(11) AFTER VLA;
ALTER TABLE verdienst ADD GVS INT(11) AFTER VLA;
 */
?>
