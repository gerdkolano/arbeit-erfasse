<?php
require_once( "konstante.php");
require_once( "datum.php");
require_once( "helfer.php");
require_once( "tabelle.php");

function head() {
    $erg = "";
    $erg .= "<!DOCTYPE html>\n";
    $erg .= "<html>\n";
    $erg .= "<head>\n";
    $erg .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
    $erg .= "<link rel=\"stylesheet\" href=\"css-speichere.css\" type=\"text/css\">\n";
    $erg .= "</head>\n";
    $erg .= "<body>\n";
    return $erg;
}

echo head();

$gepostet = new gepostet();
$gepostet->toString();

class pause {
  // Nutzung: $pausenzeit = (new pause)->get_pausenzeit_in_minuten( $arbeitszeit);

  function get_pausenzeit_in_minuten( $geplant_in_minuten) {
      return int( $this->get_pausenzeit_in_hundertstel_stunden( $geplant_in_minuten) * 6/10);
  }

  function get_arbeitszeit_in_hundertstel_stunden( $geplant_in_hundertstel_stunden) {
      if ( $geplant_in_hundertstel_stunden <= 400) return $geplant_in_hundertstel_stunden;
    else
      if ( $geplant_in_hundertstel_stunden <= 625) return $geplant_in_hundertstel_stunden - 25;
    else
      if ( $geplant_in_hundertstel_stunden <= 975) return $geplant_in_hundertstel_stunden - 50;
    else
                                                   return $geplant_in_hundertstel_stunden - 75;
  }

  function get_pausenzeit_in_hundertstel_stunden( $geplant_in_minuten) {
      if ( $geplant_in_minuten <  30) return 0;
    else
      if ( $geplant_in_minuten < 360) return 25;
    else
      if ( $geplant_in_minuten < 540) return 50;
    else
                                      return 75;
  }

  function get_pausenzeit_in_stunden( $geplant_in_minuten) {
      return $this->get_pausenzeit_in_hundertstel_stunden( $geplant_in_minuten) / 100.0;
  }
}

class gfos_ausgabe_element {
  public $wert         ;
  public $kurzname     ;
  public $head_format  ;
  public $row_format   ;
  public $mein_format  ;

  public function __construct( $kurzname, $langname, $mein_format, $head_format, $row_format) {
    $this->wert         = ""           ;
    $this->kurzname     = $kurzname    ;
    $this->langname     = $langname    ;
    $this->head_format  = $head_format ;
    $this->row_format   = $row_format  ;
    $this->mein_format  = $mein_format ;
  }

  public function set_wert( $wert) {
    $this->wert = $wert;
  }

  public function header() {
    return sprintf( $this->head_format, $this->kurzname);
  }

  public function row() {
    switch ($this->mein_format) {
  # case "h" : return sprintf( $fm               , $this->wert / 100.0); break;
    case "h" : return $this->wert == 0
      ? "<td>"  //  sprintf( $this->row_format , 77.77)
      : sprintf( $this->row_format , $this->wert / 100.0)
      ;
      break;
    case " " : return sprintf( $this->row_format , $this->wert); break;
    default  : return sprintf( $this->row_format , $this->wert); break;
    }
  }

}

class salden  {
  public $echt      ; // Nichts verfällt
  public $kum       ;  // Nicht autorisierte Überziehungszeiten vefallen
  public $summe_ist ;
  public $summe_soll;
  #ublic $soll_summe;

  public function inc_summe_ist( $increment) {
    $this->summe_ist += $increment;
  }

  public function inc_summe_soll( $increment) {
    $this->summe_soll += $increment;
  }

  public function inc_summe_ist_und_summe_soll( $summe_ist, $summe_soll) {
    $this->summe_ist  += $summe_ist;
    $this->summe_soll += $summe_soll;
  }

  public function __construct( $kum, $echt) {
    $this->kum   = $kum;
    $this->echt  = $echt;
  }

  public function set_kum_und_echt( $kum, $echt) {
    $this->kum   = $kum;
    $this->echt  = $echt;
  }

  public function set_summe_ist_und_summe_soll( $ist, $soll) {
    $this->summe_ist  = $ist ;
    $this->summe_soll = $soll;
  }

  public function inc_salden_kum_und_echt( $kum, $echt) {
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
  private $salden;
  private $zeile_rechne;
  private $fmt_std;

  public function inkrementiere_salden() {                             // saldo += ist
    $this->salden->inc_salden_kum_und_echt( $this->ausfelder["ist_gfos"]->wert,
                                            $this->ausfelder["ist_echt"]->wert);
    return $this->salden;
  }

  public function rette_salden_fürs_zeigen( $salden) {
    $this->ausfelder["saldo_kum" ]->wert = $salden->kum ;     // hundertstel
    $this->ausfelder["saldo_echt"]->wert = $salden->echt;  
  }

  public function set_ist_gfos_von_dauer_oder_geplant() {
    
    $this->ausfelder["ist_gfos" ]->wert =
      //$kriterium = ($this->value["arbzeit_plan_dauer"] == NULL or $this->value["arbzeit_plan_dauer"] == "")
      $kriterium = (!is_numeric( $this->value["arbzeit_plan_dauer"]))
        ? $this->ausfelder["ist_gfos" ]->wert
          = (new pause)->get_arbeitszeit_in_hundertstel_stunden( 
            $this->zeile_rechne->runde_dixx( $this->value["arbzeit_plan_ende"], $this->value["arbzeit_plan_anfang"])
            )
        : $this->ausfelder["ist_gfos" ]->wert  = $this->value["arbzeit_plan_dauer"]
        ;
        $this->ausfelder["modulo"   ]->wert = ($this->value["arbzeit_plan_ende"]- $this->value["arbzeit_plan_anfang"]) . " § " . $this->value["arbzeit_plan_anfang"];
    return $kriterium;
  }

  public function set_ist_gfos_von_dauer_oder_kommt_und_geht() {
    $this->ausfelder["ist_gfos" ]->wert =
      //$kriterium = ($this->value["arbzeit_plan_dauer"] == NULL or $this->value["arbzeit_plan_dauer"] == "")
      $kriterium = (!is_numeric( $this->value["arbzeit_plan_dauer"]))
        ? $this->ausfelder["ist_gfos" ]->wert = $this->zeile_rechne->runde_dixx( $this->value["arbeit_geht"], $this->value["arbeit_kommt"])
        : $this->ausfelder["ist_gfos" ]->wert  = $this->value["arbzeit_plan_dauer"]
        ;
        $this->ausfelder["modulo"   ]->wert = ($this->value["arbeit_geht"]- $this->value["arbeit_kommt"]) . " § " . $this->value["arbeit_kommt"];
    return $kriterium;
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

  public function toTR__() {
    // $gfos_zeile->toTR__( $af["ist_gfos" ]->wert, $af["ist_echt" ]->wert);
    // if  (0 !== strcmp( $ist_gfos, $ist_echt)) {
    // if  ($ist_gfos != $ist_echt) {
    if  ($bunt=($this->ausfelder["ist_gfos"]->wert != "" and
         $this->ausfelder["ist_gfos"]->wert != $this->ausfelder["ist_echt"]->wert)) {
      $rette_row_format_gfos = $this->ausfelder["ist_gfos"]->row_format;
      $rette_row_format_echt = $this->ausfelder["ist_echt"]->row_format;
      $this->ausfelder["ist_gfos"]->row_format = "<td class=\"right\" style=\"background-color:#ffdddd\"> " . $this->fmt_std;
      $this->ausfelder["ist_echt"]->row_format = "<td class=\"right\" style=\"background-color:#ddddff\"> " . $this->fmt_std;
    }
    $table_zeile = "<tr>\n";
    foreach ($this->ausfelder as $key => $val) {
      $table_zeile .= $val->row();
    }
    echo $table_zeile;
    if  ($bunt) {
      $this->ausfelder["ist_gfos"]->row_format = $rette_row_format_gfos;
      $this->ausfelder["ist_echt"]->row_format = $rette_row_format_echt;
    }
    //printf("<td>ist_gfos=%s ist_echt=%s %s", $ist_gfos, $ist_echt, ($ist_gfos != $ist_echt));
  }

  public function es_ist_montag() { // Jeden Montag noch ne Zwischenzeile
    $datumsobjekt = datumsobjekt( $this->value["datum_auto"]);
    return 1 == $this->fmt_montag->format( $datumsobjekt);    // Jeden Montag noch ne Zwischenzeile
  }

  public function toTR_montags_sollzeit( $beschäftigungsumfang) { // Jeden Montag noch ne Zwischenzeile
    if ($this->es_ist_montag()) {  // Jeden Montag noch ne Zwischenzeile
      $this->montags_sollzeit_hinzufügen( $beschäftigungsumfang);

      $this->toTR__();
    }

  }

  public function montags_sollzeit_hinzufügen( $beschäftigungsumfang) { // Jeden Montag noch ne Zwischenzeile

    $this->salden->inc_summe_soll  (               $beschäftigungsumfang);
    $this->salden->inc_salden_kum_und_echt(      - $beschäftigungsumfang,
                                           - $beschäftigungsumfang);
    $this->ausfelder["saldo_kum" ]->wert = $this->salden->kum   ;  //                 // hundertstel
    $this->ausfelder["saldo_echt"]->wert = $this->salden->echt  ;  //                 // hundertstel
    $this->ausfelder["ist_echt"  ]->wert = $beschäftigungsumfang;  //                 // hundertstel
    $this->ausfelder["soll"      ]->wert = $beschäftigungsumfang;
    $this->ausfelder["tagnr"     ]->wert = "";
    $this->ausfelder["tagnname"  ]->wert = "";
    $this->ausfelder["ist_gfos"  ]->wert = $beschäftigungsumfang;
    $this->ausfelder["modulo"    ]->wert = "";
    $this->ausfelder["kommt"     ]->wert = "";
    $this->ausfelder["geht"      ]->wert = "";
    $this->ausfelder["bemerkung" ]->wert = "Sollzeit";
  }

  function zeige_vormonatssummen( $salden, $kennung) {
    $this->ausfelder["tagnr"         ]->wert = $kennung;
    $this->ausfelder["tagnr"         ]->row_format = "<td  colspan='7'> %s";
    $this->ausfelder["tagnname"      ]->row_format = "";
    $this->ausfelder["änd_kz"        ]->row_format = "";
    $this->ausfelder["kommt"         ]->row_format = "";
    $this->ausfelder["geht"          ]->row_format = "";
    $this->ausfelder["pause"         ]->row_format = "";
    $this->ausfelder["pause_ges"     ]->row_format = "";
    $this->ausfelder["ist_gfos"      ]->row_format = "<td>"; $this->ausfelder["ist_gfos"      ]->wert = $salden->summe_ist;
    $this->ausfelder["modulo"        ]->row_format = "<td>";
    $this->ausfelder["soll"          ]->row_format = "<td> " . $this->fmt_std ; $this->ausfelder["soll"          ]->wert = $salden->summe_soll;
    $this->ausfelder["saldo_kum"     ]->row_format = "<td style=\"background-color:#ddffdd\"> " . $this->fmt_std;
    $this->ausfelder["saldo_kum"     ]->wert = $salden->kum ;
    $this->ausfelder["saldo_echt"    ]->wert = $salden->echt;
  }

  function set_datum() {
    $datumsobjekt = datumsobjekt( $this->value["datum_auto"]);
    $this->ausfelder["tagnr"      ]->wert =        $this->fmt_nr  ->format( $datumsobjekt);
    $this->ausfelder["tagnname"   ]->wert = rtrim( $this->fmt_name->format( $datumsobjekt), ".");
  }

  function __construct( $value, $salden, $rechner) {
    $this->fmt_std = "%05.2f";
  # $this->fmt_std = "%s";
    $this->zeile_rechne = $rechner;
    $this->salden = $salden;
    $this->value = $value;
    $this->ausfelder = array (
      "tagnr"         => new  gfos_ausgabe_element("tag"       , ""                   , " ", "<th colspan='2'> %s ", "<td> %s "),
      "tagnname"      => new  gfos_ausgabe_element("tag"       , ""                   , " ", "        ", "<td> %s "),
      "änd_kz"        => new  gfos_ausgabe_element("ä"         , "Änderungskz."       , " ", "<th> %s ", "<td> %s "),
      "kommt"         => new  gfos_ausgabe_element("komt"      , "Kommt"              , " ", "<th> %s ", "<td> %s "),
      "geht"          => new  gfos_ausgabe_element("geht"      , "Geht"               , " ", "<th> %s ", "<td> %s "),
      "pause"         => new  gfos_ausgabe_element("paus"      , "Pause"              , " ", "<th> %s ", "<td> %s "),
      "pause_ges"     => new  gfos_ausgabe_element("pges"      , "Pause ges."         , " ", "<th> %s ", "<td> %s "),
      "ist_gfos"      => new  gfos_ausgabe_element(" ist"      , "Ist"                , "h", "<th> %s ", "<td class=\"right\">  " . $this->fmt_std),
      "modulo"        => new  gfos_ausgabe_element(" mod"      , "Modulo"             , " ", "<th> %s ", "<td> %s "),
      "soll"          => new  gfos_ausgabe_element("soll"      , "Soll"               , "h", "<th> %s ", "<td class=\"right\">  " . $this->fmt_std),
      "saldo_kum"     => new  gfos_ausgabe_element("skum"      , "Saldo kum"          , "h", "<th> %s ", "<td class=\"right\">  " . $this->fmt_std),
      "fehlzeit_zeit" => new  gfos_ausgabe_element("fehz"      , "Fehlzeit (Zeit)"    , " ", "<th> %s ", "<td> %s "),
      "fehlzeit_text" => new  gfos_ausgabe_element("fehl"      , "Fehlzeit"           , " ", "<th> %s ", "<td> %s "),
      "vst"           => new  gfos_ausgabe_element(" vst"      , "VST"                , " ", "<th> %s ", "<td> %s "),
      "bemerkung"     => new  gfos_ausgabe_element("bemerkung" , "Bemerkung"          , " ", "<th> %s ", "<td> %s "),
      "autorisiert"   => new  gfos_ausgabe_element("auto"      , "Autorisiert"        , " ", "<th> %s ", "<td> %s "),
      "ist_echt"      => new  gfos_ausgabe_element("eist"      , "Ist echt"           , "h", "<th> %s ", "<td class=\"right\">  " . $this->fmt_std),
      "saldo_echt"    => new  gfos_ausgabe_element("ekum"      , "Saldo echt"         , "h", "<th> %s ", "<td class=\"right\">  " . $this->fmt_std),
      "saldo_zeg"     => new  gfos_ausgabe_element("szeg"      , "Zeiterfassungsgerät", " ", "<th> %s ", "<td> %s "),
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
  private $fh;
  public function __construct() {
    $myFile = "runden";
    $this->fh = fopen( $myFile, 'w')
      or printf( "Kann %s/%s nicht öffnen.<br />\n"
                . "Als root: <br />\n"
                . "f=%s/%s; touch \$f; chown www-data: \$f<br />\n"
                . "Server addr %s Server name %s Http host %s <br />\n",
        __DIR__,
        $myFile,
        __DIR__,
        $myFile,
        $_SERVER["SERVER_ADDR"],
        $_SERVER['SERVER_NAME'],
        $_SERVER['HTTP_HOST']
      );
    if ( $this->fh) {
#     fwrite( $this->fh, sprintf ( "%s \n", date( "Y-m-d H:i:s")));
      fwrite( $this->fh, "\n");
    }
    
  }

  public function minToHHMM( $wert) {
    return sprintf( "%02d.%02d", $wert/60, $wert%60);
  }
  private function kkk( $arg) { fwrite( $this->fh, "$arg\n");}

  function runde_dixx( $minuend, $subtrahend) {  // hundertstel
    $md = $minuend - $subtrahend;
    $diff = (int) round( $md *10/6);
    fwrite( $this->fh, sprintf ( "%5d %5d %5d %d%d %5d", $minuend, $subtrahend, $md, $minuend%3, $subtrahend%3, $diff));
    if ($minuend == 1170 and $subtrahend == 1049 ) { $this->kkk("+0"); return    $diff;}
    if ($minuend ==  608 and $subtrahend ==  375 ) { $this->kkk("-0"); return -- $diff;}
    if ($minuend ==  860 and $subtrahend ==  720 ) { $this->kkk("+0"); return    $diff;}
    if ($minuend ==  890 and $subtrahend ==  600 ) { $this->kkk("+0"); return    $diff;}
    if ($minuend ==  824 and $subtrahend ==  600 ) { $this->kkk("+0"); return    $diff;}
    if ($minuend ==  866 and $subtrahend ==  600 ) { $this->kkk("+0"); return    $diff;}
    if ($minuend ==  920 and $subtrahend ==  600 ) { $this->kkk("+0"); return    $diff;}
    switch ($minuend%3 . $subtrahend%3) {
    case "00" :            $this->kkk("  "); break;
    case "01" :            $this->kkk("  "); break;
    case "02" :  $diff --; $this->kkk("-1"); break;
    case "10" :            $this->kkk("  "); break;
    case "11" :            $this->kkk("  "); break;
    case "12" :            $this->kkk("  "); break;
    case "20" :  $diff ++; $this->kkk("+1"); break;
    case "21" :            $this->kkk("  "); break;
    case "22" :            $this->kkk("  "); break;
    default   : break;
    }
    return $diff;
  }
  function runde_dixx_klassisch( $minuend, $subtrahend) {  // hundertstel
    $variante = "klassisch";
    switch ($variante) {
    case "ceil": 
      $c_minuend    = (int) ceil( $minuend    *100001/60000);   
      $c_subtrahend = (int) ceil( $subtrahend *100001/60000);
      $c_diff = $c_minuend - $c_subtrahend;
    break;
    case "klassisch":
      $md = $minuend - $subtrahend;
      $diff = (int) round( $md *10/6);  // hundertstel Stunden
    break;
    case "sub_min":
      $md = $minuend - $subtrahend;
      $diff = (int) round( $md *10/6);  // hundertstel Stunden
    break;
    default :
    break;
    }

    fwrite( $this->fh, sprintf ( "%5d %5d %5d %d%d %5d", $minuend, $subtrahend, $md, $minuend%3, $subtrahend%3, $diff));

    if ($md % 3 == 0                         ) { $this->nnn(); return    $diff;}
    if ($md % 3 == 2 and $subtrahend % 3 !=0 ) { $this->nnn(); return    $diff;}
#   if ($md % 3 == 2 and $subtrahend % 3 ==0 ) { $this->ppp(); return ++ $diff;}
    if ($md % 3 == 1 and $subtrahend % 3 !=2 ) { $this->nnn(); return    $diff;}
#   if ($md % 3 == 1 and $subtrahend % 3 ==2 ) { $this->mmm(); return -- $diff;}
    return bla;
                                                                                 // diff wird nur dann um 1 verringert, wenn
                                                                                 //          $md % 3 == 1     // Quersumme 1 4 7
                                                                                 //  $subtrahend % 3 == 2     // Quersumme 2 5 9
                                                                                 //  $minuend    % 3 == 0     // Quersumme 3 6 9
    if ($md == 259                           ) { $this->mmm(); return -- $diff;}
    if ($md == 169 and $subtrahend ==  830   ) { $this->mmm(); return -- $diff;}

    if ($md ==  49                           ) { $this->ppp(); return ++ $diff;}
    if ($md == 200                           ) { $this->ppp(); return ++ $diff;}
    if ($md == 221                           ) { $this->ppp(); return ++ $diff;}
    if ($md == 158 and $subtrahend ==  834   ) { $this->ppp(); return ++ $diff;}

    if ($md ==  25                           ) { $this->mmm(); return -- $diff;}
    if ($md ==  55                           ) { $this->mmm(); return -- $diff;}
    if ($md ==  58                           ) { $this->mmm(); return -- $diff;}
    if ($md ==  61                           ) { $this->mmm(); return -- $diff;}
    if ($md ==  76                           ) { $this->mmm(); return -- $diff;}
    if ($md ==  97                           ) { $this->mmm(); return -- $diff;}
    if ($md == 100 and $subtrahend ==  635   ) { $this->mmm(); return -- $diff;}
    if ($md == 100 and $subtrahend ==  800   ) { $this->mmm(); return -- $diff;}
    if ($md == 103 and $subtrahend ==  866   ) { $this->mmm(); return -- $diff;}
    if ($md == 106 and $subtrahend == 1004   ) { $this->mmm(); return -- $diff;}
    if ($md == 112 and $subtrahend ==  998   ) { $this->mmm(); return -- $diff;}
    if ($md == 118 and $subtrahend ==  623   ) { $this->mmm(); return -- $diff;}
    if ($md == 118 and $subtrahend == 1157   ) { $this->mmm(); return -- $diff;}
    if ($md == 124 and $subtrahend == 1091   ) { $this->mmm(); return -- $diff;}
    if ($md == 130 and $subtrahend == 1085   ) { $this->mmm(); return -- $diff;}
    if ($md == 133 and $subtrahend ==  932   ) { $this->mmm(); return -- $diff;}
    if ($md == 136 and $subtrahend == 1010   ) { $this->mmm(); return -- $diff;}
    if ($md == 136 and $subtrahend == 1079   ) { $this->mmm(); return -- $diff;}
    if ($md == 145 and $subtrahend ==  920   ) { $this->mmm(); return -- $diff;}
    if ($md == 154 and $subtrahend == 1061   ) { $this->mmm(); return -- $diff;}
    if ($md == 157 and $subtrahend ==  899   ) { $this->mmm(); return -- $diff;}
    if ($md == 163 and $subtrahend == 1112   ) { $this->mmm(); return -- $diff;}
    if ($md == 175 and $subtrahend ==  854   ) { $this->mmm(); return -- $diff;}
    if ($md == 178 and $subtrahend == 1037   ) { $this->mmm(); return -- $diff;}
    if ($md == 181 and $subtrahend == 1031   ) { $this->mmm(); return -- $diff;}
    if ($md == 190 and $subtrahend == 1025   ) { $this->mmm(); return -- $diff;}
    if ($md == 205                           ) { $this->mmm(); return -- $diff;}
    if ($md == 208                           ) { $this->mmm(); return -- $diff;}
    if ($md == 220                           ) { $this->mmm(); return -- $diff;}
    if ($md == 233                           ) { $this->mmm(); return -- $diff;}
                                                                               
                                                                                 // diff wird nur dann um 1 vergrößert, wenn
                                                                                 //          $md % 3 == 2 // Quersumme 2 5 8
                                                                                 //  $subtrahend % 3 == 0 // Quersumme 3 6 9
                                                                                 //  $minuend    % 3 == 2 // Quersumme 2 5 8
    if ($md ==  38                           ) { $this->ppp(); return ++ $diff;} //  oder 329
    if ($md == 101 and $subtrahend ==  948   ) { $this->ppp(); return ++ $diff;}
    if ($md == 125 and $subtrahend ==  705   ) { $this->ppp(); return ++ $diff;}
    if ($md == 140 and $subtrahend ==  705   ) { $this->ppp(); return ++ $diff;}
    if ($md == 149 and $subtrahend ==  873   ) { $this->ppp(); return ++ $diff;}
    if ($md == 152 and $subtrahend ==  765   ) { $this->ppp(); return ++ $diff;}
    if ($md == 155 and $subtrahend ==  600   ) { $this->ppp(); return ++ $diff;}
    if ($md == 170 and $subtrahend ==  705   ) { $this->ppp(); return ++ $diff;}
    if ($md == 173 and $subtrahend ==  405   ) { $this->ppp(); return ++ $diff;}
    if ($md == 173 and $subtrahend ==  600   ) { $this->ppp(); return ++ $diff;}
    if ($md == 173 and $subtrahend ==  765   ) { $this->ppp(); return ++ $diff;}
    if ($md == 176                           ) { $this->ppp(); return ++ $diff;}
    if ($md == 179 and $subtrahend ==  885   ) { $this->ppp(); return ++ $diff;}
    if ($md == 188 and $subtrahend ==  705   ) { $this->ppp(); return ++ $diff;}
    if ($md == 194                           ) { $this->ppp(); return ++ $diff;}
    if ($md == 197                           ) { $this->ppp(); return ++ $diff;}
    if ($md == 200 and $subtrahend ==  705   ) { $this->ppp(); return ++ $diff;}
    if ($md == 206                           ) { $this->ppp(); return ++ $diff;}
    if ($md == 212 and $subtrahend ==  705   ) { $this->ppp(); return ++ $diff;}
    if ($md == 212 and $subtrahend ==  765   ) { $this->ppp(); return ++ $diff;}
    if ($md == 218 and $subtrahend ==  390   ) { $this->ppp(); return ++ $diff;}
    if ($md == 230                           ) { $this->ppp(); return ++ $diff;}
    if ($md == 236 and $subtrahend ==  390   ) { $this->ppp(); return ++ $diff;}
    if ($md == 248                           ) { $this->ppp(); return ++ $diff;}
    if ($md == 251                           ) { $this->ppp(); return ++ $diff;}
    if ($md == 260                           ) { $this->ppp(); return ++ $diff;}
    if ($md == 434                           ) { $this->ppp(); return ++ $diff;}
  # if ($md == 112 and $subtrahend ==  705   ) { $this->ppp(); return ++ $diff;}
    if ($md == 173 and $subtrahend ==  612   ) { $this->ppp(); return ++ $diff;}
    if ($md == 179 and $subtrahend ==  705   ) { $this->ppp(); return ++ $diff;}
    if ($md == 179 and $subtrahend ==  981   ) { $this->ppp(); return ++ $diff;}
    if ($md == 185 and $subtrahend ==  390   ) { $this->ppp(); return ++ $diff;}
    if ($md == 185 and $subtrahend ==  885   ) { $this->ppp(); return ++ $diff;}
    if ($md == 185 and $subtrahend != 1030   ) { $this->ppp(); return ++ $diff;}
    if ($md == 200 and $subtrahend == 1011   ) { $this->ppp(); return ++ $diff;}
    if ($md == 239                           ) { $this->ppp(); return ++ $diff;}

    $this->nnn(); return $diff;

    if ($md % 3 == 1 and $subtrahend % 3 == 2) { $this->mmm(); return -- $diff;}
    if ($md % 3 == 2 and $subtrahend % 3 == 0) { $this->mmm(); return -- $diff;}
  }

  function runde_diff( $minuend, $subtrahend) {

    $md = $minuend - $subtrahend;
    $diff = round( $md *10/6);  // hundertstel
    return (int) $diff;
  }

}

class ein_monat {
  private $ein_tag;
  private $tabelle;
  private $daten_2D;
  private $normal_2D;
  private $conn;
  private $kalkulator;
  private $start_datum;
  function __construct( $welcher_monat, $taschenrechner) { //  Noch zu berücksichtigen i_saldo_datum
    //                                                  und           i_arbzeit_dauer i_arbzeit_datum

    $this->start_datum = new ein_datum( $welcher_monat == "" ? "first day of this month" : $welcher_monat);
 // $this->start_datum = $welcher_monat == "" ? new ein_datum( "first day of this month") : new ein_datum( $welcher_monat);
 // $this->start_datum = new ein_datum( $welcher_monat); // Zwinge auf den 1. des Monats // new DateTime("first day of 2012-02")
    $stop_datum = new ein_datum( $welcher_monat);  $stop_datum->add_einen_monat();

    $anfangstag = $this->start_datum->format( "yyyy-MM-dd");
    $schlusstag = $stop_datum->format( "yyyy-MM-dd");
    $tabelle = new tabelle();
    $this->tabelle = $tabelle;
    $this->kalkulator = $taschenrechner;
    $spalte = $tabelle->kurzfelder;
    $comma_separated = implode(",", $tabelle->gfos_zeitkonto); // Welche Daten werden geholt

    $table_name = (new konstante)->table_name;
    $database_name = (new konstante)->database_name;

    $where = "WHERE datum_auto >= '$anfangstag' and datum_auto < '$schlusstag' ORDER BY datum_auto";
    $query = "SELECT $comma_separated  FROM $table_name $where";
    # echo "<pre>"; print_r( $this->tabelle->felder); echo "!</pre><br />\n";
    $conn = new conn();
    $conn->frage( 0, "USE " . $database_name);

    // Hole die nicht normaliserten Daten aus der Datenbank
    $this->daten_2D = $conn->hol_array_of_objects( "$query");
    $normal_2D = array();
  }

  function foot( ) {
    $erg = "";
    $erg .= "</body>";
    $erg .= "</html>";
    return $erg;
  }

  function in_diesem_monat_gearbeitete_zeit( $salden) {

    $beschäftigungsumfang = 3330;  // $beschäftigungsumfang = 33.30;                    // hundertstel

    $gfos_titel = "gfos 4.7plus Zeitkonto";
    printf( "<h3 style=\"text-align: center\">%s — %s </h3><br />\n", $gfos_titel, $this->start_datum->format( "MMMM yyyy"));

    printf( "<table  cellspacing=\"0\" cellpadding=\"2\" border=\"1\"> \n");
    $gfos_zeile = new gfos_zeile( "", $salden, $this->kalkulator);
    $gfos_zeile->erkläre_abkürzungen();
    $gfos_zeile->zeige_vormonatssummen( $salden, "Vormonatssummen");
    $gfos_zeile->toTR__();
    $gfos_zeile->toTH();

    for ($zeilennummer=0; $zeilennummer<count( $this->normal_2D); $zeilennummer++) { // Soviele Tage enthält die Datenbank
    //foreach ($this->normal_2D as $zeilennummer => $value) {
      $value = $this->normal_2D[$zeilennummer];
      //$arbeitszeit = $value["arbende_autorisiert"] - $value["arbzeit_plan_anfang"];
#$salden->set_summe_ist_und_summe_soll( 0.0, 0.0);
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
      $gfos_zeile = new gfos_zeile( $value, $salden, $this->kalkulator);
      $af = $gfos_zeile->ausfelder;
      $gfos_zeile->set_datum();

      switch ( $value["erscheine"]) {
      case "BA"       :
            $gfos_zeile->toTR_montags_sollzeit( $beschäftigungsumfang);
            $gfos_zeile->ausfelder["bemerkung"]->wert = "Betriebsausschuss"    ; $gfos_zeile->ausfelder["fehlzeit_zeit"]->wert = "br" ;
            $gfos_zeile->set_ist_gfos_von_dauer_oder_kommt_und_geht();
            $gfos_zeile->ausfelder["ist_echt" ]->wert = $gfos_zeile->ausfelder["ist_gfos" ]->wert;
          $salden->inc_summe_ist( $af["ist_gfos" ]->wert);

            $salden = $gfos_zeile->inkrementiere_salden();
            $gfos_zeile->rette_salden_fürs_zeigen( $salden);
            $gfos_zeile->toTR__();
      break;
      case "BR"       :
            $gfos_zeile->toTR_montags_sollzeit( $beschäftigungsumfang);
            $gfos_zeile->set_ist_gfos_von_dauer_oder_kommt_und_geht();
            $af["bemerkung"]->wert = "Betriebsrat"          ; $af["fehlzeit_zeit"]->wert = "br" ;
            $af["ist_echt" ]->wert = $af["ist_gfos" ]->wert;
          $salden->inc_summe_ist( $af["ist_gfos" ]->wert);
            $salden = $gfos_zeile->inkrementiere_salden();
            $gfos_zeile->rette_salden_fürs_zeigen( $salden);
            $gfos_zeile->toTR__();
      break;
      case "BV"       :
            $gfos_zeile->toTR_montags_sollzeit( $beschäftigungsumfang);
            $af["bemerkung"]->wert = "Betriebsversammlung"  ; $af["fehlzeit_zeit"]->wert = "br" ;
            $gfos_zeile->set_ist_gfos_von_dauer_oder_kommt_und_geht();
            $af["ist_echt" ]->wert = $af["ist_gfos" ]->wert;
          $salden->inc_summe_ist( $af["ist_gfos" ]->wert);
            $salden = $gfos_zeile->inkrementiere_salden();
            $gfos_zeile->rette_salden_fürs_zeigen( $salden);
            $gfos_zeile->toTR__();
      break;
      case "Seminar"  :
            $gfos_zeile->toTR_montags_sollzeit( $beschäftigungsumfang);
            $af["bemerkung"]->wert = "Seminar"              ; $af["fehlzeit_zeit"]->wert = "br" ;
            $gfos_zeile->set_ist_gfos_von_dauer_oder_kommt_und_geht();
            $af["ist_echt" ]->wert = $af["ist_gfos" ]->wert;
          $salden->inc_summe_ist( $af["ist_gfos" ]->wert);
            $salden = $gfos_zeile->inkrementiere_salden();
            $gfos_zeile->rette_salden_fürs_zeigen( $salden);
            $gfos_zeile->toTR__();
      break;
      case "Feiertag" :
            $gfos_zeile->toTR_montags_sollzeit( $beschäftigungsumfang);
            $af["bemerkung"]->wert = "Feiertag"             ; $af["fehlzeit_zeit"]->wert = "fei";
            $af["ist_gfos" ]->wert  = $value["arbzeit_plan_dauer"];
            $af["ist_echt" ]->wert  = $af["ist_gfos" ]->wert;
          $salden->inc_summe_ist( $af["ist_gfos" ]->wert);
            $salden = $gfos_zeile->inkrementiere_salden();
            $gfos_zeile->rette_salden_fürs_zeigen( $salden);
            $gfos_zeile->toTR__();
      break;
      case "frei"     :
        $gfos_zeile->toTR_montags_sollzeit( $beschäftigungsumfang);
        $af["bemerkung"]->wert = "frei"                 ; $af["fehlzeit_zeit"]->wert = "f"  ;
            $af["ist_gfos"   ]->wert  = "";
            $af["ist_echt"   ]->wert  = "";
            $af["saldo_kum"  ]->wert  = "";
            $af["saldo_echt" ]->wert  = "";
            $af["soll"     ]->wert  = "";
            $gfos_zeile->toTR__();
      break;
      case "krank"    :
            $gfos_zeile->toTR_montags_sollzeit( $beschäftigungsumfang);
            $af["bemerkung"]->wert = "krank"                ; $af["fehlzeit_zeit"]->wert = ""   ;
            $kriterium = $gfos_zeile->set_ist_gfos_von_dauer_oder_geplant();
          $salden->inc_summe_ist( $af["ist_gfos" ]->wert);
            $gfos_zeile->ausfelder["ist_echt" ]->wert = $gfos_zeile->ausfelder["ist_gfos" ]->wert;

            $salden = $gfos_zeile->inkrementiere_salden();
            $gfos_zeile->rette_salden_fürs_zeigen( $salden);
            $af["soll"     ]->wert  = "";
            $gfos_zeile->toTR__();
      break;
      case "Urlaub"   :
            $gfos_zeile->toTR_montags_sollzeit( $beschäftigungsumfang);
            $af["bemerkung"]->wert = "Urlaub"               ; $af["fehlzeit_zeit"]->wert = "u"  ;
            $af["ist_gfos" ]->wert  = $value["arbzeit_plan_dauer"];
            $af["ist_echt" ]->wert  = $af["ist_gfos" ]->wert;
          $salden->inc_summe_ist( $af["ist_gfos" ]->wert);
            $salden = $gfos_zeile->inkrementiere_salden();
            $gfos_zeile->rette_salden_fürs_zeigen( $salden);
            $af["soll"     ]->wert  = "";
            $gfos_zeile->toTR__();
      break;
      default         :
      $fm_pause = "%.2f";
      $af["pause_ges"  ]->wert = sprintf( $fm_pause, $pause_ges);
      $af["autorisiert"]->wert = $this->toText( $value["arbende_autorisiert"]);
      for ($ii = 0; $ii < count( $kommt_geht); $ii++) {
        $af["kommt"    ]->wert = $this->kalkulator->minToHHMM( $kommt_geht[$ii  ]);
        $af["geht"     ]->wert = $this->kalkulator->minToHHMM( $kommt_geht[$ii+1]);
        $af["pause"    ]->wert = $pause > 0.0 ? sprintf( $fm_pause, $pause) : "";
        $pause = $pause > 0.25 ? $pause - 0.25 : 0.0;
        $af["ist_echt" ]->wert = $this->kalkulator->runde_diff( $kommt_geht[$ii+1], $kommt_geht[$ii]);
        $plan_ende = max( $value["arbzeit_plan_ende"], $value["arbende_autorisiert"]); // $value["arbende_autorisiert"] kann -1 sein
        $endzeit = min( $kommt_geht[$ii+1], $plan_ende);
        $af["ist_gfos" ]->wert = $this->kalkulator->runde_dixx( $endzeit, $kommt_geht[$ii]);
        $af["modulo"   ]->wert =                  (($endzeit- $kommt_geht[$ii])     ) . " § " . $kommt_geht[$ii];
      $salden->inc_summe_ist( $af["ist_gfos" ]->wert);

        $salden->inc_salden_kum_und_echt(        $gfos_zeile->ausfelder["ist_gfos"]->wert,
                                                 $gfos_zeile->ausfelder["ist_echt"]->wert);
        $gfos_zeile->rette_salden_fürs_zeigen( $salden);
        $af["saldo_zeg" ]->wert = $value["i_saldo_dauer"];

        $gfos_zeile->toTR__();

        $af["tagnr"      ]->wert = "";
        $af["tagnname"   ]->wert = "";
        $af["pause_ges"  ]->wert = "";
        $af["autorisiert"]->wert = "";
        $ii++;
      }
      $gfos_zeile->toTR_montags_sollzeit( $beschäftigungsumfang);

      break;
      }

    }
    $gfos_zeile = new gfos_zeile( "", $salden, $this->kalkulator);
    $gfos_zeile->zeige_vormonatssummen( $salden, "Spaltensummen");
    $gfos_zeile->toTR__();
    printf( "</table> \n");

    return $salden;
  }

  function toText( $wert) {
    return sprintf( "%s ", $wert < 0 ? "" : $this->kalkulator->minToHHMM( $wert));
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
      $this->normal_2D[$i]["arbzeit_plan_dauer" ]  =               $value["arbzeit_plan_dauer"  ] == NULL
                                                                 ? $value["arbzeit_plan_dauer"  ]
                                                                 : (int) (100 *  $value["arbzeit_plan_dauer"  ]);  // hundertstel
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

function schleife_anders( $laufzeit, $stopzeit) {
  $laufobjekt = datumsobjekt( $laufzeit);
  $stopobjekt = datumsobjekt( $stopzeit);
  $salden = new salden( 342, 342); // saldo_gfos und saldo_echt von Anfang August 2015    // hundertstel
  $salden = new salden( 077, 077); // saldo_gfos und saldo_echt von Anfang Dezember 2015  // hundertstel
  $salden->set_summe_ist_und_summe_soll( 0, 0);                                           // hundertstel
  $ein_rechner = new rechne;

  $intervall = new DateInterval( 'P1M');
  while ( $laufobjekt < $stopobjekt) {
#   printf( "%s saldo_kum=%s  saldo_echt=%s <br />\n", $laufobjekt->format('Y-m-d'), $salden->kum, $salden->echt);

    $ein_monat = new ein_monat( $laufobjekt->format('Y-m'), $ein_rechner);
    $ein_monat->kopiere_und_normalisiere();
    $salden = $ein_monat->in_diesem_monat_gearbeitete_zeit( $salden);  //
    $salden->set_summe_ist_und_summe_soll( 0.0, 0.0);
    $laufobjekt->add( $intervall);

    //echo $ein_monat->zeige_die_verwendeten_normalisierten_daten();
  }
}
function schleife( $laufobjekt, $stopobjekt) {
  $ein_rechner = new rechne;

  $salden = new salden( 0, 0);   
  switch ($laufobjekt->format('Y-m')) {
  case "2015-07" : $salden->set_kum_und_echt( - 1879, - 1879); break;
# case "2015-07" : $salden->set_kum_und_echt( - 3553, - 3553); break;
  case "2015-08" : $salden->set_kum_und_echt(    342,    342);
  case "2015-12" : 
    default : break;
    }
  $salden->set_summe_ist_und_summe_soll( 0, 0);                                           // hundertstel
  $intervall = new DateInterval( 'P1M');
  while ( $laufobjekt < $stopobjekt) {
    printf( "%s saldo_kum=%s  saldo_echt=%s <br />\n", $laufobjekt->format('Y-m-d'), $salden->kum, $salden->echt);

    $ein_monat = new ein_monat( $laufobjekt->format('Y-m'), $ein_rechner);
    $ein_monat->kopiere_und_normalisiere();
    $salden = $ein_monat->in_diesem_monat_gearbeitete_zeit( $salden);  //
    $salden->set_summe_ist_und_summe_soll( 0.0, 0.0);
    $laufobjekt->add( $intervall);
    switch ($laufobjekt->format('Y-m')) {
    //                       vorher nachher  
    case "2016-03" : $delta = - 2545 -  - 3190; $salden->inc_salden_kum_und_echt( -$delta, -$delta); break;  // Anfang Dezember 2015  // hundertstel
    case "2016-02" : $delta =   3285 -  -  226; $salden->inc_salden_kum_und_echt( -$delta, -$delta); break;  // Anfang Dezember 2015  // hundertstel
    case "2015-12" : $delta =   1988 -    1709; $salden->inc_salden_kum_und_echt( -$delta, -$delta); break;  // Anfang Februar  2016  // hundertstel
    default : break;
    }

    //echo $ein_monat->zeige_die_verwendeten_normalisierten_daten();
  }
}


$startzeit =  (isset( $_GET["start"])) ? $_GET["start"] : "";  # echo "M010 $startzeit ";
$stopzeit  =  (isset( $_GET["stop" ])) ? $_GET["stop" ] : "";  # echo "M012 $stopzeit ";
if ($startzeit == "" or $stopzeit == "") {
  $parameter = sprintf( "?start=%s&stop=%s",
    $startzeit == "" ? "2015-8" : $startzeit,
    $stopzeit == "" ? "2015-9" : $stopzeit
  );
  printf( "Die Adresse ist unvollständig. Vorschlag:<br />\n");

  $url = sprintf("http://%s/%s%s", $_SERVER["SERVER_NAME"], $_SERVER["SCRIPT_NAME"], $parameter); //  __DIR__, __FILE__,
  printf("E030 Versuche <a href=\"%s\"> %s </a><br />\n", $url, $url);
}

//schleife_anders( $startzeit, $stopzeit);
schleife( datumsobjekt( $startzeit), datumsobjekt( $stopzeit));


?>

<pre>
                                            gfos
05.12.2015 von 10:00 bis 12:58 = 2h 58min = 2.97 h
28.12.2015 von 17:17 bis 20:15 = 2h 58min = 2.96 h

26.09.2015 von 11.45 bis 14.35 = 2h 50min = 2.84 h
20.08 2015 von 17.25 bis 20.15 = 2h 50min = 2.83 h

</pre>
<table border>
<tr><th>     <th>   <th colspan=3> Minuend
<tr><th> Sub <th>   <th> 0 <th>  1 <th>  2 
<tr><td> tra <td> 0 <td> 0 <td>  1 <td>  2 
<tr><td> hen <td> 1 <td> 2 <td>  0 <td>  1 
<tr><td> d   <td> 2 <td> 1 <td>  2 <td>  0 
</table>

<table border>
<tr><th>     <th>   <th colspan=3> Differenz
<tr><th> Sub <th>   <th> 0 <th>  1 <th>  2 
<tr><td> tra <td> 0 <td> 0 <td>  1 <td>  2 
<tr><td> hen <td> 1 <td> 1 <td>  2 <td>  0 
<tr><td> d   <td> 2 <td> 2 <td>  0 <td>  1 
</table>

