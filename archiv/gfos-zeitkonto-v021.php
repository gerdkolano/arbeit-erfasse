<?php
require_once( "datum.php");
require_once( "helfer.php");
require_once( "tabelle.php");

function head() {
    printf( "<!DOCTYPE html>\n");
    printf( "<html>\n");
    printf( "<head>\n");
    printf( "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n");
    printf( "</head>\n");
    printf( "<body>\n");
}

head();

$gepostet = new gepostet();
$gepostet->toString();

class pause {
  function get_pausenzeit_in_minuten( $geplant_in_minuten) {
    if ( $geplant_in_minuten < 30) return 0;
    else
      if ( $geplant_in_minuten < 360) return 15;
    else
        if ( $geplant_in_minuten < 540) return 30;
    else
      return 45;
  // Nutzung: $pausenzeit = (new pause)->get_pausenzeit_in_minuten( $arbeitszeit);
  }

  function get_pausenzeit_in_stunden( $geplant_in_minuten) {
      return $this->get_pausenzeit_in_minuten( $geplant_in_minuten) / 60.0;
  // Nutzung: $pausenzeit = (new pause)->get_pausenzeit_in_minuten( $arbeitszeit);
  }
}

class gfos_ausgabe_element {
  public $wert         ;
  public $kurzname     ;
  public $head_format  ;
  public $row_format   ;

  public function __construct( $kurzname, $langname, $head_format, $row_format) {
    $this->wert         = ""           ;
    $this->kurzname     = $kurzname    ;
    $this->langname     = $langname    ;
    $this->head_format  = $head_format ;
    $this->row_format   = $row_format  ;
  }

  public function set_wert( $wert) {
    $this->wert = $wert;
  }

  public function header() {
    return sprintf( $this->head_format, $this->kurzname);
  }

  public function row() {
    return sprintf( $this->row_format , $this->wert);
  }

}

class salden  {
  public $echt      ; // Nichts verfällt
  public $kum       ;  // Nicht autorisierte Überziehungszeiten vefallen
  public $summe_ist ;
  public $summe_soll;
  public $soll_summe;
  public function set_summen( $summe_ist, $summe_soll) {
    $this->summe_ist   = $summe_ist;
    $this->summe_soll  = $summe_soll;
  }
  public function inc_summen( $summe_ist, $summe_soll) {
    $this->summe_ist  += $summe_ist;
    $this->summe_soll += $summe_soll;
  }
  public function set_salden( $kum, $echt) {
    $this->kum   = $kum;
    $this->echt  = $echt;
  }
  public function inc_salden( $kum, $echt) {
    $this->kum  += $kum;
    $this->echt += $echt;
  }
}

class gfos_zeile {
  public $ausfelder;
  private $fmt_montag;
  private $fmt_nr;
  private $fmt_name;
  private $value;

  function runde_diff( $minuend, $subtrahend) {
      $nk = 4;
      $f2 = "%05.2f";
    $diff = round( $minuend /60.0, $nk) - round( $subtrahend /60.0, $nk);
    return sprintf( $f2, $diff);
  }

  public function zeige_salden( $salden) {
    $fzwei = "%05.2f";
    $this->ausfelder["saldo_kum" ]->wert = sprintf( $fzwei, $salden->kum );
    $this->ausfelder["saldo_echt"]->wert = sprintf( $fzwei, $salden->echt);
  }
  
  public function set_ist_gfos() {
    $this->ausfelder["ist_gfos" ]->wert =
      $this->value["arbzeit_plan_dauer"] == ""
        ? $this->ausfelder["ist_gfos" ]->wert = $this->runde_diff( $this->value["arbeit_geht"], $this->value["arbeit_kommt"])
        : $this->ausfelder["ist_gfos" ]->wert  = $this->value["arbzeit_plan_dauer"]
        ;
  }

  public function erkläre_abkürzungen() {
    foreach ($this->ausfelder as $key => $val) {
      printf( "%s=%s ", $val->kurzname, $val->langname);
    }
  }

  public function toTH() {
    $table_zeile = "<tr>\n";
    foreach ($this->ausfelder as $key => $val) {
      $table_zeile .= $val->header();
    } 
    echo $table_zeile;
  }
      
  public function toTR__( $ist_gfos = "", $ist_echt = "") {
    // if  (0 !== strcmp( $ist_gfos, $ist_echt)) {
    if  ($ist_gfos != $ist_echt) {
      $rette_row_format_gfos = $this->ausfelder["ist_gfos"]->row_format;
      $rette_row_format_echt = $this->ausfelder["ist_echt"]->row_format;
      $this->ausfelder["ist_gfos"]->row_format = "<td style=\"background-color:#ffdddd\"> %s";
      $this->ausfelder["ist_echt"]->row_format = "<td style=\"background-color:#ddddff\"> %s";
    }
    $table_zeile = "<tr>\n";
    foreach ($this->ausfelder as $key => $val) {
      $table_zeile .= $val->row();
    } 
    echo $table_zeile;
    if  ($ist_gfos != $ist_echt) {
      $this->ausfelder["ist_gfos"]->row_format = $rette_row_format_gfos;
      $this->ausfelder["ist_echt"]->row_format = $rette_row_format_echt;
    }
    //printf("<td>ist_gfos=%s ist_echt=%s %s", $ist_gfos, $ist_echt, ($ist_gfos != $ist_echt));
  }
      
  public function es_ist_montag() { // Jeden Montag noch ne Zwischenzeile
    $datumsobjekt = datumsobjekt( $this->value["datum_auto"]);
    return 1 == $this->fmt_montag->format( $datumsobjekt);    // Jeden Montag noch ne Zwischenzeile
  }
      
  function zeige_vormonatssummen( $salden, $kennung) {
    $this->ausfelder["tagnr"         ]->wert = $kennung;
    $this->ausfelder["tagnr"         ]->row_format = "<td  colspan='9'> %s";
    $this->ausfelder["tagnname"      ]->row_format = "";
    $this->ausfelder["änd_kz"        ]->row_format = "";
    $this->ausfelder["kommt"         ]->row_format = "";
    $this->ausfelder["geht"          ]->row_format = "";
    $this->ausfelder["pause"         ]->row_format = "";
    $this->ausfelder["pause_ges"     ]->row_format = "";
    $this->ausfelder["ist_gfos"      ]->row_format = "";
    $this->ausfelder["modulo"        ]->row_format = "";
    $this->ausfelder["saldo_kum"     ]->row_format = "<td style=\"background-color:#ddffdd\"> %s";
    $this->ausfelder["saldo_kum"     ]->wert = $salden->kum ;
    $this->ausfelder["saldo_echt"    ]->wert = $salden->echt;
  }
      
  function set_datum() {
    $datumsobjekt = datumsobjekt( $this->value["datum_auto"]);
    $this->ausfelder["tagnr"      ]->wert =        $this->fmt_nr  ->format( $datumsobjekt);
    $this->ausfelder["tagnname"   ]->wert = rtrim( $this->fmt_name->format( $datumsobjekt), ".");
  }

  function __construct( $value, $salden) {
    $this->salden = $salden;
    $this->value = $value;
    $this->ausfelder = array (
      "tagnr"         => new  gfos_ausgabe_element("tag"       , ""                   , "<th colspan='2'> %s ", "<td> %s "),       
      "tagnname"      => new  gfos_ausgabe_element("tag"       , ""                   , "        ", "<td> %s "),          
      "änd_kz"        => new  gfos_ausgabe_element("ä"         , "Änderungskz."       , "<th> %s ", "<td> %s "),       
      "kommt"         => new  gfos_ausgabe_element("komt"      , "Kommt"              , "<th> %s ", "<td> %s "),       
      "geht"          => new  gfos_ausgabe_element("geht"      , "Geht"               , "<th> %s ", "<td> %s "),      
      "pause"         => new  gfos_ausgabe_element("paus"      , "Pause"              , "<th> %s ", "<td> %s "),       
      "pause_ges"     => new  gfos_ausgabe_element("pges"      , "Pause ges."         , "<th> %s ", "<td> %s "),           
      "ist_gfos"      => new  gfos_ausgabe_element(" ist"      , "Ist"                , "<th> %s ", "<td> %s "),
      "modulo"        => new  gfos_ausgabe_element(" mod"      , "Modulo"             , "<th> %s ", "<td> %s "),
      "soll"          => new  gfos_ausgabe_element("soll"      , "Soll"               , "<th> %s ", "<td> %s "),      
      "saldo_kum"     => new  gfos_ausgabe_element("skum"      , "Saldo kum"          , "<th> %s ", "<td> %s "),           
      "fehlzeit_zeit" => new  gfos_ausgabe_element("fehz"      , "Fehlzeit (Zeit)"    , "<th> %s ", "<td> %s "),               
      "fehlzeit_text" => new  gfos_ausgabe_element("fehl"      , "Fehlzeit"           , "<th> %s ", "<td> %s "),               
      "vst"           => new  gfos_ausgabe_element(" vst"      , "VST"                , "<th> %s ", "<td> %s "),     
      "bemerkung"     => new  gfos_ausgabe_element("bemerkung" , "Bemerkung"          , "<th> %s ", "<td> %s "),           
      "autorisiert"   => new  gfos_ausgabe_element("auto"      , "Autorisiert"        , "<th> %s ", "<td> %s "),           
      "ist_echt"      => new  gfos_ausgabe_element("eist"      , "Ist echt"           , "<th> %s ", "<td> %s "),     
      "saldo_echt"    => new  gfos_ausgabe_element("ekum"      , "Saldo echt"         , "<th> %s ", "<td> %s "),
      "saldo_zeg"     => new  gfos_ausgabe_element("szeg"      , "Zeiterfassungsgerät", "<th> %s ", "<td> %s "),           
    );
 // Nicht autorisierte Überziehungen verfallen 5.10.15
    $this->fmt_nr = new IntlDateFormatter(
        'de-DE',
        IntlDateFormatter::FULL,
        IntlDateFormatter::FULL,
        'Europe/Berlin',
        IntlDateFormatter::GREGORIAN,
        "dd"   // http://www.unicode.org/reports/tr35/tr35-dates.html#Date_Field_Symbol_Table
    );
      
    $this->fmt_name = new IntlDateFormatter(
        'de-DE',
        IntlDateFormatter::FULL,
        IntlDateFormatter::FULL,
        'Europe/Berlin',
        IntlDateFormatter::GREGORIAN,
        "EEE"   // http://www.unicode.org/reports/tr35/tr35-dates.html#Date_Field_Symbol_Table
    );
      
    $this->fmt_montag = new IntlDateFormatter(
        'de-DE',
        IntlDateFormatter::FULL,
        IntlDateFormatter::FULL,
        'Europe/Berlin',
        IntlDateFormatter::GREGORIAN,
        "e"   // http://www.unicode.org/reports/tr35/tr35-dates.html#Date_Field_Symbol_Table
    );
  }
}

class gfos_tag {
  public $gfoszeilen;
  function __construct() {
    $this->gfoszeilen = array();
  }  
}

class rechne {
  public function minToHHMM( $wert) {
    return sprintf( "%02d.%02d", $wert/60, $wert%60);
  }
}

class ein_monat {
  private $ein_tag;
  private $tabelle;
  private $daten_2D;
  private $normal_2D;
  private $conn;
  private $rechne;
  private $start_datum;
  function __construct( $welcher_monat) { //  Noch zu berücksichtigen i_saldo_datum
    //                                                  und           i_arbzeit_dauer i_arbzeit_datum

    $this->start_datum = new ein_datum( $welcher_monat == "" ? "first day of this month" : $welcher_monat); 
 // $this->start_datum = $welcher_monat == "" ? new ein_datum( "first day of this month") : new ein_datum( $welcher_monat); 
 // $this->start_datum = new ein_datum( $welcher_monat); // Zwinge auf den 1. des Monats // new DateTime("first day of 2012-02")
    $stop_datum = new ein_datum( $welcher_monat);  $stop_datum->add_einen_monat();

    $anfangstag = $this->start_datum->format( "yyyy-MM-dd");
    $schlusstag = $stop_datum->format( "yyyy-MM-dd");
    $tabelle = new tabelle();
    $this->tabelle = $tabelle;
    $this->rechne = new rechne;
    $spalte = $tabelle->kurzfelder;
    $comma_separated = implode(",", $tabelle->gfos_zeitkonto); // Welche Daten werden geholt
    $table = "zeiten";
    $where = "WHERE datum_auto >= '$anfangstag' and datum_auto < '$schlusstag' ORDER BY datum_auto";
    $query = "SELECT $comma_separated  FROM $table $where";
    # echo "<pre>"; print_r( $this->tabelle->felder); echo "!</pre><br />\n";
    $conn = new conn();
    $conn->frage( 0, "USE arbeit");

    // Hole die nicht normaliserten Daten aus der Datenbank
    $this->daten_2D = $conn->hol_array_of_objects( "$query");
    $normal_2D = array();
  }

  function head( ) {
    $erg = "";
    $erg .= "<html>";
    $erg .= "<body>";
    return $erg;
  }

  function foot( ) {
    $erg = "";
    $erg .= "</body>";
    $erg .= "</html>";
    return $erg;
  }
  
  function runde_dixx( $minuend, $subtrahend, $datum) {
      $nk = 4;
      $f2 =  "%05.2f";
      $fp = "%05.2f";
      $fm = "%05.2f";
      $md = $minuend - $subtrahend;
    if ($md == 140                           ) { $diff = round( 0.005+$md /60.0, $nk);return sprintf( $fp, $diff);}
    if ($md == 155                           ) { $diff = round( 0.005+$md /60.0, $nk);return sprintf( $fp, $diff);}
    if ($md == 170 and $datum != "2015-08-20") { $diff = round( 0.005+$md /60.0, $nk);return sprintf( $fp, $diff);} // am 26.9
    if ($md == 173 and $datum != "2015-08-14") { $diff = round( 0.005+$md /60.0, $nk);return sprintf( $fp, $diff);}
    if ($md == 194                           ) { $diff = round( 0.005+$md /60.0, $nk);return sprintf( $fp, $diff);}
    if ($md == 197                           ) { $diff = round( 0.005+$md /60.0, $nk);return sprintf( $fm, $diff);} 
    if ($md == 200                           ) { $diff = round( 0.005+$md /60.0, $nk);return sprintf( $fm, $diff);} 
    if ($md == 206                           ) { $diff = round( 0.005+$md /60.0, $nk);return sprintf( $fm, $diff);} 
    if ($md == 212                           ) { $diff = round( 0.005+$md /60.0, $nk);return sprintf( $fm, $diff);} 
    if ($md == 218                           ) { $diff = round( 0.005+$md /60.0, $nk);return sprintf( $fp, $diff);}
    if ($md ==  58                           ) { $diff = round(-0.005+$md /60.0, $nk);return sprintf( $fm, $diff);} 
    if ($md ==  61                           ) { $diff = round(-0.005+$md /60.0, $nk);return sprintf( $fm, $diff);} 
    if ($md ==  97                           ) { $diff = round(-0.005+$md /60.0, $nk);return sprintf( $fm, $diff);} 
    if ($md == 106                           ) { $diff = round(-0.005+$md /60.0, $nk);return sprintf( $fm, $diff);} 
    if ($md == 118                           ) { $diff = round(-0.005+$md /60.0, $nk);return sprintf( $fm, $diff);} 
#   if ($md == 133 and $datum != "2015-08-21") { $diff = round(-0.005+$md /60.0, $nk);return sprintf( $fm, $diff);} 
    if ($md == 133 and $subtrahend == 932    ) { $diff = round(-0.005+$md /60.0, $nk);return sprintf( $fm, $diff);} 
    if ($md == 145                           ) { $diff = round(-0.005+$md /60.0, $nk);return sprintf( $fm, $diff);} 
    if ($md == 178                           ) { $diff = round(-0.005+$md /60.0, $nk);return sprintf( $fm, $diff);} 
    if ($md == 220                           ) { $diff = round(-0.005+$md /60.0, $nk);return sprintf( $fm, $diff);} 
   #if ($md == 161) { $diff = round(-0.005+$md /60.0, $nk);return sprintf( $fm, $diff);} 

      $diff = round( $md /60.0, $nk);
      return sprintf( $f2, $diff);

/* 
    if ($minuend == 845 and $subtrahend == 705) { $diff = round( 0.005+$md /60.0, $nk);return sprintf( $fp, $diff, $minuend-$subtrahend);}
    if ($minuend == 755 and $subtrahend == 600) { $diff = round( 0.005+$md /60.0, $nk);return sprintf( $fp, $diff, $minuend-$subtrahend);}
    if ($minuend == 794 and $subtrahend == 600) { $diff = round( 0.005+$md /60.0, $nk);return sprintf( $fp, $diff, $minuend-$subtrahend);}
    if ($minuend == 608 and $subtrahend == 390) { $diff = round( 0.005+$md /60.0, $nk);return sprintf( $fp, $diff, $minuend-$subtrahend);}
    if ($minuend == 741 and $subtrahend == 623) { $diff = round(-0.005+$md /60.0, $nk);return sprintf( $fm, $diff, $minuend-$subtrahend);} 
    if ($minuend ==1110 and $subtrahend ==1004) { $diff = round(-0.005+$md /60.0, $nk);return sprintf( $fm, $diff, $minuend-$subtrahend);} 
    if ($minuend ==1110 and $subtrahend == 890) { $diff = round(-0.005+$md /60.0, $nk);return sprintf( $fm, $diff, $minuend-$subtrahend);} 
    if ($minuend ==1021 and $subtrahend == 860) { $diff = round(-0.005+$md /60.0, $nk);return sprintf( $fm, $diff, $minuend-$subtrahend);} 
    if ($minuend == 900 and $subtrahend == 839) { $diff = round(-0.005+$md /60.0, $nk);return sprintf( $fm, $diff, $minuend-$subtrahend);} */
  }
  
  function runde_diff( $minuend, $subtrahend) {
      $nk = 4;
      $f2 = "%05.2f";
    $diff = round( $minuend /60.0, $nk) - round( $subtrahend /60.0, $nk);
    return sprintf( $f2, $diff);
  }
  
  function gearbeitete_zeit( $salden) {

    $beschäftigungsumfang = 33.30;

    $gfos_titel = "gfos 4.7plus Zeitkonto";
    printf( "<h3 style=\"text-align: center\">%s — %s </h3><br />\n", $gfos_titel, $this->start_datum->format( "MMMM yyyy"));

    printf( "<table  cellspacing=\"0\" cellpadding=\"2\" border=\"1\"> \n");
    $gfos_zeile = new gfos_zeile( "", $salden);
    $gfos_zeile->erkläre_abkürzungen();
    $gfos_zeile->zeige_vormonatssummen( $salden, "Vormonatssummen");
    $gfos_zeile->toTR__();
    $gfos_zeile->toTH();
    
    for ($zeilennummer=0; $zeilennummer<count( $this->normal_2D); $zeilennummer++) {
    //foreach ($this->normal_2D as $zeilennummer => $value) {
      $value = $this->normal_2D[$zeilennummer];
      //$arbeitszeit = $value["arbende_autorisiert"] - $value["arbzeit_plan_anfang"];
      $arbeitszeit = $value["arbzeit_plan_ende"  ] - $value["arbzeit_plan_anfang"];

      $pause_ges = (new pause)->get_pausenzeit_in_stunden( $arbeitszeit);
      $pause     = $pause_ges;

      $kommt_geht = array();
      if ($value["arbeit_kommt"] >= 0) { $kommt_geht[] = $value["arbeit_kommt"];}
      if ($value["pause1_geht" ] >= 0) { $kommt_geht[] = $value["pause1_geht" ];}
      if ($value["pause1_kommt"] >= 0) { $kommt_geht[] = $value["pause1_kommt"];}
      if ($value["pause2_geht" ] >= 0) { $kommt_geht[] = $value["pause2_geht" ];}
      if ($value["pause2_kommt"] >= 0) { $kommt_geht[] = $value["pause2_kommt"];}
      if ($value["arbeit_geht" ] >= 0) { $kommt_geht[] = $value["arbeit_geht" ];}
      if (count( $kommt_geht) % 2 == 1) {
        printf( "Z099 Eine \"Kommt-\" oder \"Geht\"-Zeit fehlt oder ist überzählig <br />\n");
        continue; // mit dem nächsten Tag
      }
      $fzwei = "%05.2f";
      $gfos_zeile = new gfos_zeile( $value, $salden);
      $af = $gfos_zeile->ausfelder;
      $gfos_zeile->set_datum();

      switch ( $value["erscheine"]) {
      case "BAx"       : $gfos_zeile->ausfelder["bemerkung"]->wert = "Betriebsausschuss"    ; $gfos_zeile->ausfelder["fehlzeit_zeit"]->wert = "br" ;
            $af["ist_gfos" ]->wert =
              $value["arbzeit_plan_dauer"] == ""
                ? $af["ist_gfos" ]->wert = $this->runde_diff( $value["arbeit_geht"], $value["arbeit_kommt"])
                : $af["ist_gfos" ]->wert  = $value["arbzeit_plan_dauer"]
                ;
            $gfos_zeile->toTR__();
      break;
      case "BA"       : $gfos_zeile->ausfelder["bemerkung"]->wert = "Betriebsausschuss"    ; $gfos_zeile->ausfelder["fehlzeit_zeit"]->wert = "br" ;
            $gfos_zeile->set_ist_gfos();
            $gfos_zeile->ausfelder["ist_echt" ]->wert = $gfos_zeile->ausfelder["ist_gfos" ]->wert;
            $salden->inc_salden(        $gfos_zeile->ausfelder["ist_gfos"]->wert, 
                                        $gfos_zeile->ausfelder["ist_echt"]->wert);
            $gfos_zeile->zeige_salden( $salden);
            $gfos_zeile->toTR__();
      break;
      case "BR"       : $af["bemerkung"]->wert = "Betriebsrat"          ; $af["fehlzeit_zeit"]->wert = "br" ;
            $af["ist_gfos" ]->wert =
              $value["arbzeit_plan_dauer"] == ""
                ? $af["ist_gfos" ]->wert = $this->runde_diff( $value["arbeit_geht"], $value["arbeit_kommt"])
                : $af["ist_gfos" ]->wert  = $value["arbzeit_plan_dauer"]
                ;
            $af["ist_echt" ]->wert = $af["ist_gfos" ]->wert;
            $salden->kum             += $af["ist_gfos"]->wert; 
            $salden->echt            += $af["ist_echt"]->wert; 
            $af["saldo_kum" ]->wert = sprintf( $fzwei, $salden->kum );
            $af["saldo_echt"]->wert = sprintf( $fzwei, $salden->echt);
            $gfos_zeile->toTR__();
      break;
      case "BV"       : $af["bemerkung"]->wert = "Betriebsversammlung"  ; $af["fehlzeit_zeit"]->wert = "br" ;
            $af["ist_gfos" ]->wert = $this->runde_diff( $value["arbeit_geht"], $value["arbeit_kommt"]);
            $af["ist_echt" ]->wert = $af["ist_gfos" ]->wert;
            $salden->kum             += $af["ist_gfos"]->wert; 
            $salden->echt            += $af["ist_echt"]->wert; 
            $af["saldo_kum" ]->wert = sprintf( $fzwei, $salden->kum );
            $af["saldo_echt"]->wert = sprintf( $fzwei, $salden->echt);
            $gfos_zeile->toTR__();
      break;
      case "Seminar"  : $af["bemerkung"]->wert = "Seminar"              ; $af["fehlzeit_zeit"]->wert = "br" ;
            $af["ist_gfos" ]->wert = $this->runde_diff( $value["arbeit_geht"], $value["arbeit_kommt"]);
            $af["ist_echt" ]->wert = $af["ist_gfos" ]->wert;
            $salden->kum             += $af["ist_gfos"]->wert; 
            $salden->echt            += $af["ist_echt"]->wert; 
            $af["saldo_kum" ]->wert = sprintf( $fzwei, $salden->kum );
            $af["saldo_echt"]->wert = sprintf( $fzwei, $salden->echt);
            $gfos_zeile->toTR__();
      break;
      case "Feiertag" : $af["bemerkung"]->wert = "Feiertag"             ; $af["fehlzeit_zeit"]->wert = "fei";
            $af["ist_gfos" ]->wert  = $value["arbzeit_plan_dauer"];
            $af["ist_echt" ]->wert  = $af["ist_gfos" ]->wert;
            $salden->kum             += $af["ist_gfos"]->wert; 
            $salden->echt            += $af["ist_echt"]->wert; 
            $af["saldo_kum" ]->wert = sprintf( $fzwei, $salden->kum );
            $af["saldo_echt"]->wert = sprintf( $fzwei, $salden->echt);
            $gfos_zeile->toTR__();
      break;
      case "frei"     : $af["bemerkung"]->wert = "frei"                 ; $af["fehlzeit_zeit"]->wert = "f"  ;
            $gfos_zeile->toTR__();
      break;
      case "krank"    : $af["bemerkung"]->wert = "krank"                ; $af["fehlzeit_zeit"]->wert = ""   ;
            $gfos_zeile->toTR__();
      break;
      case "Urlaub"   : $af["bemerkung"]->wert = "Urlaub"               ; $af["fehlzeit_zeit"]->wert = "u"  ;
            $af["ist_gfos" ]->wert  = $value["arbzeit_plan_dauer"];
            $af["ist_echt" ]->wert  = $af["ist_gfos" ]->wert;
            $salden->kum             += $af["ist_gfos"]->wert; 
            $salden->echt            += $af["ist_echt"]->wert; 
            $af["saldo_kum" ]->wert = sprintf( $fzwei, $salden->kum );
            $af["saldo_echt"]->wert = sprintf( $fzwei, $salden->echt);
            $gfos_zeile->toTR__();
      break;
      default         :
      $af["pause_ges"  ]->wert = sprintf( "%.2f", $pause_ges);
      $af["autorisiert"]->wert = $this->toText( $value["arbende_autorisiert"]);
      for ($ii = 0; $ii < count( $kommt_geht); $ii++) {
        $af["kommt"    ]->wert = $this->rechne->minToHHMM( $kommt_geht[$ii  ]);
        $af["geht"     ]->wert = $this->rechne->minToHHMM( $kommt_geht[$ii+1]);
        $af["pause"    ]->wert = $pause > 0.0 ? sprintf( "%.2f", $pause) : "";
        $pause = $pause > 0.25 ? $pause - 0.25 : 0.0;
        $af["ist_echt" ]->wert = $this->runde_diff( $kommt_geht[$ii+1], $kommt_geht[$ii]);
        $plan_ende = max( $value["arbzeit_plan_ende"], $value["arbende_autorisiert"]); // $value["arbende_autorisiert"] kann -1 sein
        $endzeit = min( $kommt_geht[$ii+1], $plan_ende);
        $af["ist_gfos" ]->wert = $this->runde_dixx( $endzeit, $kommt_geht[$ii], $value["datum_auto"])    ;
        $af["modulo"   ]->wert =                  (($endzeit- $kommt_geht[$ii]) % 60) . " §";
        $af["modulo"   ]->wert =                  (($endzeit- $kommt_geht[$ii])     ) . " § " . $kommt_geht[$ii];
        $salden->kum            += $af["ist_gfos"]->wert; 
        $salden->echt           += $af["ist_echt"]->wert; 
        $af["saldo_kum" ]->wert = sprintf( $fzwei, $salden->kum );
        $af["saldo_echt"]->wert = sprintf( $fzwei, $salden->echt);
        $af["saldo_zeg" ]->wert = $value["i_saldo_dauer"];

        $gfos_zeile->toTR__( $af["ist_gfos" ]->wert, $af["ist_echt" ]->wert);
        //$gfos_zeile->toTR__( $af["ist_gfos" ]->wert != $af["ist_echt" ]->wert);
        
#       printf( "%s bis %s", $this->minToHHMM( $kommt_geht[$i  ]), $this->minToHHMM( $kommt_geht[$i+1]));
#       printf( "<br />\n");
        $af["tagnr"      ]->wert = "";
        $af["tagnname"   ]->wert = "";
        $af["pause_ges"  ]->wert = "";
        $af["autorisiert"]->wert = "";
        $ii++;
      }
        break;
      }

      if ($gfos_zeile->es_ist_montag()) {  // Jeden Montag noch ne Zwischenzeile
        $gfos_zeile = new gfos_zeile( $value, $salden);
        $af = $gfos_zeile->ausfelder;
        $salden->kum                              -= $beschäftigungsumfang;
        $salden->echt                             -= $beschäftigungsumfang;
        $af["saldo_kum" ]->wert = sprintf( $fzwei, $salden->kum);
        $af["saldo_echt"]->wert = sprintf( $fzwei, $salden->echt);
        $af["ist_echt"  ]->wert = sprintf( $fzwei, $beschäftigungsumfang);
        $af["soll"      ]->wert = sprintf( $fzwei, $beschäftigungsumfang);
        $af["kommt"     ]->wert = "";
        $af["geht"      ]->wert = "";
        $af["bemerkung" ]->wert = "Sollzeit";
        $gfos_zeile->toTR__();
      }

    }
    $gfos_zeile = new gfos_zeile( "", $salden);
    $gfos_zeile->zeige_vormonatssummen( $salden, "Übertrag für den nächsten Monat");
    $gfos_zeile->toTR__();
    printf( "</table> \n");

    return $salden;
  }
  
  function toText( $wert) {
    return sprintf( "%s ", $wert < 0 ? "" : $this->rechne->minToHHMM( $wert));
  }
  
  function toMin( $wort) { // string ziffern nichtziffern ziffern oder 3 bis 4 Ziffern
    preg_match( "/(\d+)[^\d](\d+)/", $wort, $matches);
    if (!isset($matches[2])) {
      preg_match( "/(\d{1,2})(\d{2})/", $wort, $matches); // 3 oder 4 Ziffern
      if (isset($matches[2])) {
        $stunden = $matches[1]; $minuten = $matches[2];
      } else {
        return -1;
      }
      return -1;
    } else {
      $stunden = $matches[1]; $minuten = $matches[2];
    }
    if ($stunden >=24 or $minuten >= 60) {
      return -1;
    } 
    return $stunden * 60 + $minuten;
  }
  
  function zeige_die_verwendeten_normalisierten_daten( ) {
    $erg = "";
    $erg .= sprintf( "<h3 style=\"text-align: center\"> Verwendete Daten —  %s </h3><br />\n",
      (new ein_datum( $this->normal_2D[0]["datum_auto"]))->format( "MMMM yyyy"));
    // Kolumnennamen als header
    if (true) foreach ($this->normal_2D[0] as $kolumne=>$wert) {
      $erg .= sprintf( "%s \n", $kolumne);
    }
    $erg .= sprintf( "<tr>");
    $erg .= sprintf( "<th>Datum");
    $erg .= sprintf( "<th>ersch");
    $erg .= sprintf( "<th>daur" );
    $erg .= sprintf( "<th>panf" );
    $erg .= sprintf( "<th>kom"  );
    $erg .= sprintf( "<th>geh"  );
    $erg .= sprintf( "<th>kom"  );
    $erg .= sprintf( "<th>geh"  );
    $erg .= sprintf( "<th>kom"  );
    $erg .= sprintf( "<th>geh"  );
    $erg .= sprintf( "<th>plen" );
    $erg .= sprintf( "<th>auto" );
    $erg .= sprintf( "<th>arbz" );
    $erg .= sprintf( "<th>a dat");
    $erg .= sprintf( "<th>saldo");
    $erg .= sprintf( "<th>s dat");
    $erg .= sprintf( "</tr>\n");

    $i = 0;
    foreach ($this->normal_2D as $zeilennummer=>$value) {
      $erg .= sprintf( "<tr>");
      $erg .= sprintf( "<td> %s ",                $this->normal_2D[$i]["datum_auto"          ] );
      $erg .= sprintf( "<td> %s ",                $this->normal_2D[$i]["erscheine"           ] );
      $erg .= sprintf( "<td> %s ",                $this->normal_2D[$i]["arbzeit_plan_dauer"  ] );
      $erg .= sprintf( "<td> %s ", $this->toText( $this->normal_2D[$i]["arbzeit_plan_anfang" ]));
      $erg .= sprintf( "<td> %s ", $this->toText( $this->normal_2D[$i]["arbeit_kommt"        ]));
      $erg .= sprintf( "<td> %s ", $this->toText( $this->normal_2D[$i]["pause1_geht"         ]));
      $erg .= sprintf( "<td> %s ", $this->toText( $this->normal_2D[$i]["pause1_kommt"        ]));
      $erg .= sprintf( "<td> %s ", $this->toText( $this->normal_2D[$i]["pause2_geht"         ]));
      $erg .= sprintf( "<td> %s ", $this->toText( $this->normal_2D[$i]["pause2_kommt"        ]));
      $erg .= sprintf( "<td> %s ", $this->toText( $this->normal_2D[$i]["arbeit_geht"         ]));
      $erg .= sprintf( "<td> %s ", $this->toText( $this->normal_2D[$i]["arbzeit_plan_ende"   ]));
      $erg .= sprintf( "<td> %s ", $this->toText( $this->normal_2D[$i]["arbende_autorisiert" ]));
      $erg .= sprintf( "<td> %s ",                $this->normal_2D[$i]["i_arbzeit_dauer"     ] );
      $erg .= sprintf( "<td> %s ",                $this->normal_2D[$i]["i_arbzeit_datum"     ] );
      $erg .= sprintf( "<td> %s ",                $this->normal_2D[$i]["i_saldo_dauer"       ] );
      $erg .= sprintf( "<td> %s ",                $this->normal_2D[$i]["i_saldo_datum"       ] );
      $erg .= sprintf( "</tr>\n");
      $i++;
    }
/* im Minuten    
 */
    foreach ($this->normal_2D as $zeilennummer=>$value) {
      $erg .= sprintf( "<tr>");
      foreach ($value as $kolumne=>$wert) {
        // $erg .= sprintf( "<td> %s ", $this->toText( $kolumne, $wert));
        $erg .= sprintf( "<td> %s ", $wert);
      }
      $erg .= sprintf( "</tr>\n");
    }

    //echo "<pre>"; print_r( $this->normal_2D); echo "!</pre><br />\n";
    return sprintf("<table  cellspacing=\"0\" cellpadding=\"2\" border=\"1\">\n%s</table>", $erg);

  }
  function kopiere_und_normalisiere() {
    $i = 0;
    foreach ($this->daten_2D as $zeilennummer=>$value) {  // Kopiere daten_2D nach normal_2D
      $this->normal_2D[$i]["datum_auto"         ]  =               $value["datum_auto"          ] ;
      $this->normal_2D[$i]["erscheine"          ]  =               $value["erscheine"           ] ;
      $this->normal_2D[$i]["arbzeit_plan_dauer" ]  =               $value["arbzeit_plan_dauer"  ] ;
      $this->normal_2D[$i]["arbzeit_plan_anfang"]  = $this->toMin( $value["arbzeit_plan_anfang" ]);
      $this->normal_2D[$i]["arbeit_kommt"       ]  = $this->toMin( $value["arbeit_kommt"        ]);
      $this->normal_2D[$i]["pause1_geht"        ]  = $this->toMin( $value["pause1_geht"         ]);
      $this->normal_2D[$i]["pause1_kommt"       ]  = $this->toMin( $value["pause1_kommt"        ]);
      $this->normal_2D[$i]["pause2_geht"        ]  = $this->toMin( $value["pause2_geht"         ]);
      $this->normal_2D[$i]["pause2_kommt"       ]  = $this->toMin( $value["pause2_kommt"        ]);
      $this->normal_2D[$i]["arbeit_geht"        ]  = $this->toMin( $value["arbeit_geht"         ]);
      $this->normal_2D[$i]["arbzeit_plan_ende"  ]  = $this->toMin( $value["arbzeit_plan_ende"   ]);
      $this->normal_2D[$i]["arbende_autorisiert"]  = $this->toMin( $value["arbende_autorisiert" ]);
      $this->normal_2D[$i]["i_arbzeit_dauer"    ]  =               $value["i_arbzeit_dauer"     ] ;
      $this->normal_2D[$i]["i_arbzeit_datum"    ]  =               $value["i_arbzeit_datum"     ] ;
      $this->normal_2D[$i]["i_saldo_dauer"      ]  =               $value["i_saldo_dauer"       ] ;
      $this->normal_2D[$i]["i_saldo_datum"      ]  =               $value["i_saldo_datum"       ] ;
      $i++;
    }
  }
}

$startzeit =  (isset( $_GET["start"])) ? $_GET["start"] : ""; // echo "M010 $startzeit";
$stopzeit  =  (isset( $_GET["stop" ])) ? $_GET["stop" ] : ""; // echo "M010 $startzeit";
if ($startzeit == "" or $stopzeit == "") {
  printf( "Die Adresse ist unvollständig. Vorschlag: füge hinzu ?start=%s&stop=%s",
    $startzeit == "" ? "2015-8" : $startzeit,
    $stopzeit == "" ? "2015-9" : $stopzeit);
}

$salden = new salden();
$salden->set_salden( 3.42, 3.42); // Anfang August 2015
schleife( datumsobjekt( $startzeit), datumsobjekt( $stopzeit), $salden);

function schleife( $laufobjekt, $stopobjekt, $salden) {
  $intervall = new DateInterval( 'P1M');
  while ( $laufobjekt < $stopobjekt) {
    //printf( "%s saldo_kum=%s  saldo_echt=%s <br />\n", $laufobjekt->format('Y-m-d'), $salden->kum, $salden->echt);

    $ein_monat = new ein_monat( $laufobjekt->format('Y-m'));
    $ein_monat->kopiere_und_normalisiere();
    $salden = $ein_monat->gearbeitete_zeit( $salden);  //   
    $laufobjekt->add( $intervall);

    //echo $ein_monat->zeige_die_verwendeten_normalisierten_daten();
  }
}


?>

<pre>
                                            gfos
05.12.2015 von 10:00 bis 12:58 = 2h 58min = 2.97 h
28.12.2015 von 17:17 bis 20:15 = 2h 58min = 2.96 h

26.09.2015 von 11.45 bis 14.35 = 2h 50min = 2.84 h
20.08 2015 von 17.25 bis 20.15 = 2h 50min = 2.83 h

</pre>
