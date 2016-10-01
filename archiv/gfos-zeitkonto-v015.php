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

class zeitpunkte {
public $id                 ;
public $datum_auto         ;
public $arbzeit_plan_anfang;
public $arbzeit_plan_ende  ;
public $arbeit_kommt       ;
public $pause1_geht        ;
public $pause1_kommt       ;
public $pause2_geht        ;
public $pause2_kommt       ;
public $arbeit_geht        ;
public $i_saldo_dauer      ;
public $i_saldo_datum      ;

}

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

  public function __construct( $kurzname, $head_format, $row_format) {
    $this->wert         = ""           ;
    $this->kurzname     = $kurzname    ;
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

class gfos_zeile {
  public $ausfelder     ;
  public function toTH() {

    $table_zeile = "<tr>\n";
    foreach ($this->ausfelder as $key => $val) {
      $table_zeile .= $val->header();
    } 
    echo $table_zeile;

  }
      
  public function toTR__() {

    $table_zeile = "<tr>\n";
    foreach ($this->ausfelder as $key => $val) {
      $table_zeile .= $val->row();
    } 
    echo $table_zeile;

  }
      
  function __construct() {
    $this->ausfelder = array (
      "tagnr"         => new  gfos_ausgabe_element("tag"   , "<th> %s " , "<td> %s "),       
      "tagnname"      => new  gfos_ausgabe_element("tag"   , "<th> %s " , "<td> %s "),          
      "kommt"         => new  gfos_ausgabe_element("komt"  , "<th> %s " , "<td> %s "),       
      "geht"          => new  gfos_ausgabe_element("geht"  , "<th> %s " , "<td> %s "),      
      "pause"         => new  gfos_ausgabe_element("paus"  , "<th> %s " , "<td> %s "),       
      "pause_ges"     => new  gfos_ausgabe_element("paug"  , "<th> %s " , "<td> %s "),           
      "ist_gfos"      => new  gfos_ausgabe_element(" ist"  , "<th> %s " , "<td> %s "), // Nicht autorisierte Überziehungen verfallen 5.10.15
      "soll"          => new  gfos_ausgabe_element("soll"  , "<th> %s " , "<td> %s "),      
      "saldo_kum"     => new  gfos_ausgabe_element("skum"  , "<th> %s " , "<td> %s "),           
      "saldo_zeg"     => new  gfos_ausgabe_element("szeg"  , "<th> %s " , "<td> %s "),           
      "fehlzeit_zeit" => new  gfos_ausgabe_element("fehz"  , "<th> %s " , "<td> %s "),               
      "fehlzeit_text" => new  gfos_ausgabe_element("fehl"  , "<th> %s " , "<td> %s "),               
      "vst"           => new  gfos_ausgabe_element("vst"   , "<th> %s " , "<td> %s "),     
      "bemerkung"     => new  gfos_ausgabe_element("bemer" , "<th> %s " , "<td> %s "),           
      "ist_echt"      => new  gfos_ausgabe_element("eist"  , "<th> %s " , "<td> %s "),     
      "saldo_echt"    => new  gfos_ausgabe_element("ekum"  , "<th> %s " , "<td> %s "), // Nicht autorisierte Überziehungen verfallen 5.10.15        
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
  
  function gearbeitete_zeit( $salden) {
    $saldo_kum  = $salden->kum;
    $saldo_echt = $salden->echt;

    $beschäftigungsumfang = 33.30;

    $gfos_titel = "gfos 4.7plus Zeitkonto";
    printf( "<h3 style=\"text-align: center\">%s — %s </h3><br />\n", $gfos_titel, $this->start_datum->format( "MMMM yyyy"));

    printf( "<table  cellspacing=\"0\" cellpadding=\"2\" border=\"1\"> \n");
    $gfos_zeile = (new gfos_zeile())->toTH();
    
    for ($zeilennummer=0; $zeilennummer<count( $this->normal_2D); $zeilennummer++) {
    //foreach ($this->normal_2D as $zeilennummer => $value) {
      $value = $this->normal_2D[$zeilennummer];
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
      $fmt_nr = new IntlDateFormatter(
        'de-DE',
        IntlDateFormatter::FULL,
        IntlDateFormatter::FULL,
        'Europe/Berlin',
        IntlDateFormatter::GREGORIAN,
        "dd"   // http://www.unicode.org/reports/tr35/tr35-dates.html#Date_Field_Symbol_Table
        );
      
        $fmt_name = new IntlDateFormatter(
        'de-DE',
        IntlDateFormatter::FULL,
        IntlDateFormatter::FULL,
        'Europe/Berlin',
        IntlDateFormatter::GREGORIAN,
        "EEE"   // http://www.unicode.org/reports/tr35/tr35-dates.html#Date_Field_Symbol_Table
        );
      
        $fmt_montag = new IntlDateFormatter(
        'de-DE',
        IntlDateFormatter::FULL,
        IntlDateFormatter::FULL,
        'Europe/Berlin',
        IntlDateFormatter::GREGORIAN,
        "e"   // http://www.unicode.org/reports/tr35/tr35-dates.html#Date_Field_Symbol_Table
      );
$nachkomma = 4;
$fzwei = "%05.2f";
      $datumsobjekt = datumsobjekt( $value["datum_auto"]);
      $gfos_zeile = new gfos_zeile();
      $af = $gfos_zeile->ausfelder;
      $af["tagnr"    ]->wert =        $fmt_nr  ->format( $datumsobjekt);
      $af["tagnname" ]->wert = rtrim( $fmt_name->format( $datumsobjekt), ".");
      $af["pause_ges"]->wert = sprintf( "%.2f", $pause_ges);
      for ($ii = 0; $ii < count( $kommt_geht); $ii++) {
        $af["kommt"    ]->wert = $this->rechne->minToHHMM( $kommt_geht[$ii  ]);
        $af["geht"     ]->wert = $this->rechne->minToHHMM( $kommt_geht[$ii+1]);
        $af["pause"    ]->wert = $pause > 0.0 ? sprintf( "%.2f", $pause) : "";
        $pause = $pause > 0.25 ? $pause - 0.25 : 0.0;
        $af["ist_echt" ]->wert = sprintf( $fzwei, round(($kommt_geht[$ii+1]                   -        $kommt_geht[$ii])/60.0, $nachkomma));
        $af["ist_echt" ]->wert = sprintf( $fzwei, round( $kommt_geht[$ii+1]/60.0, $nachkomma) - round( $kommt_geht[$ii] /60.0, $nachkomma));
        $endzeit = min( $kommt_geht[$ii+1], $value["arbzeit_plan_ende"]);
        $af["ist_gfos" ]->wert = sprintf( $fzwei, round( $endzeit          /60.0, $nachkomma) - round( $kommt_geht[$ii] /60.0, $nachkomma));
        $saldo_kum            += $af["ist_gfos"]->wert; 
        $saldo_echt           += $af["ist_echt"]->wert; 
        $af["saldo_kum" ]->wert = sprintf( $fzwei, $saldo_kum );
        $af["saldo_echt"]->wert = sprintf( $fzwei, $saldo_echt);
        $af["saldo_zeg" ]->wert = $value["i_saldo_dauer"];
        $gfos_zeile->toTR__();
        
#       printf( "%s bis %s", $this->minToHHMM( $kommt_geht[$i  ]), $this->minToHHMM( $kommt_geht[$i+1]));
#       printf( "<br />\n");
        $af["tagnr"    ]->wert = "";
        $af["tagnname" ]->wert = "";
        $af["pause_ges"]->wert = "";
        $ii++;
      }
      if (1 == $fmt_montag->format( $datumsobjekt)) {  // Jeden Montag noch ne Zwischenzeile
        $gfos_zeile = new gfos_zeile();
        $af = $gfos_zeile->ausfelder;
        $saldo_kum                             -= $beschäftigungsumfang;
        $saldo_echt                            -= $beschäftigungsumfang;
        $af["ist_echt"  ]->wert = sprintf( $fzwei, $beschäftigungsumfang);
        $af["soll"      ]->wert = sprintf( $fzwei, $beschäftigungsumfang);
        $af["kommt"     ]->wert = "";
        $af["geht"      ]->wert = "";
        $af["saldo_kum" ]->wert = sprintf( $fzwei, $saldo_kum);
        $af["saldo_echt"]->wert = sprintf( $fzwei, $saldo_echt);
        $af["bemerkung" ]->wert = "Sollzeit";
        $gfos_zeile->toTR__();
      }

    }
    printf( "</table> \n");

    //$salden = new salden();
    $salden->kum  = $saldo_kum;
    $salden->echt = $saldo_echt;
    return $salden;
  }
  
  function toText( $wert) {
    return sprintf( "%s ", $wert < 0 ? "" : $this->rechne->minToHHMM( $wert));
  }
  
  function toText_obsolet( $kolumne, $wert) {
    switch ($kolumne) {
    case "datum_auto" : return sprintf( "%s ", $wert); break;
    default           : return sprintf( "%s ", $wert < 0 ? "" : $this->rechne->minToHHMM( $wert)); break;
    }
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
  
  function zeige_die_normalisierten_verwendeten_daten( ) {
    $erg = "";
    // Kolumnennamen als header
    if (true) foreach ($this->normal_2D[0] as $kolumne=>$wert) {
      $erg .= sprintf( "%s \n", $kolumne);
    }
    $erg .= sprintf( "<h3 style=\"text-align: center\"> Verwendete Daten —  %s </h3><br />\n",
      (new ein_datum( $this->normal_2D[0]["datum_auto"]))->format( "MMMM yyyy"));
    $erg .= sprintf( "<tr>");
    $erg .= sprintf( "<th>Datum");
    $erg .= sprintf( "<th>panf");
    $erg .= sprintf( "<th>kom");
    $erg .= sprintf( "<th>geh");
    $erg .= sprintf( "<th>kom");
    $erg .= sprintf( "<th>geh");
    $erg .= sprintf( "<th>kom");
    $erg .= sprintf( "<th>geh");
    $erg .= sprintf( "<th>pend");
    $erg .= sprintf( "<th>arbz");
    $erg .= sprintf( "<th>a dat");
    $erg .= sprintf( "<th>saldo");
    $erg .= sprintf( "<th>s dat");
    $erg .= sprintf( "</tr>\n");

    $i = 0;
    foreach ($this->normal_2D as $zeilennummer=>$value) {
      $erg .= sprintf( "<tr>");
      $erg .= sprintf( "<td> %s ",                $this->normal_2D[$i]["datum_auto"          ] );
      $erg .= sprintf( "<td> %s ", $this->toText( $this->normal_2D[$i]["arbzeit_plan_anfang" ]));
      $erg .= sprintf( "<td> %s ", $this->toText( $this->normal_2D[$i]["arbeit_kommt"        ]));
      $erg .= sprintf( "<td> %s ", $this->toText( $this->normal_2D[$i]["pause1_geht"         ]));
      $erg .= sprintf( "<td> %s ", $this->toText( $this->normal_2D[$i]["pause1_kommt"        ]));
      $erg .= sprintf( "<td> %s ", $this->toText( $this->normal_2D[$i]["pause2_geht"         ]));
      $erg .= sprintf( "<td> %s ", $this->toText( $this->normal_2D[$i]["pause2_kommt"        ]));
      $erg .= sprintf( "<td> %s ", $this->toText( $this->normal_2D[$i]["arbeit_geht"         ]));
      $erg .= sprintf( "<td> %s ", $this->toText( $this->normal_2D[$i]["arbzeit_plan_ende"   ]));
      $erg .= sprintf( "<td> %s ",                $this->normal_2D[$i]["i_arbzeit_dauer"     ] );
      $erg .= sprintf( "<td> %s ",                $this->normal_2D[$i]["i_arbzeit_datum"     ] );
      $erg .= sprintf( "<td> %s ",                $this->normal_2D[$i]["i_saldo_dauer"       ] );
      $erg .= sprintf( "<td> %s ",                $this->normal_2D[$i]["i_saldo_datum"       ] );
      $erg .= sprintf( "</tr>\n");
      $i++;
    }
/* im Minuten    
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
 */
  }
  function kopiere_und_normalisiere() {
    $i = 0;
    foreach ($this->daten_2D as $zeilennummer=>$value) {  // Kopiere daten_2D nach normal_2D
      $this->normal_2D[$i]["datum_auto"         ]  =               $value["datum_auto"          ] ;
      $this->normal_2D[$i]["arbzeit_plan_anfang"]  = $this->toMin( $value["arbzeit_plan_anfang" ]);
      $this->normal_2D[$i]["arbeit_kommt"       ]  = $this->toMin( $value["arbeit_kommt"        ]);
      $this->normal_2D[$i]["pause1_geht"        ]  = $this->toMin( $value["pause1_geht"         ]);
      $this->normal_2D[$i]["pause1_kommt"       ]  = $this->toMin( $value["pause1_kommt"        ]);
      $this->normal_2D[$i]["pause2_geht"        ]  = $this->toMin( $value["pause2_geht"         ]);
      $this->normal_2D[$i]["pause2_kommt"       ]  = $this->toMin( $value["pause2_kommt"        ]);
      $this->normal_2D[$i]["arbeit_geht"        ]  = $this->toMin( $value["arbeit_geht"         ]);
      $this->normal_2D[$i]["arbzeit_plan_ende"  ]  = $this->toMin( $value["arbzeit_plan_ende"   ]);
      $this->normal_2D[$i]["i_arbzeit_dauer"    ]  =               $value["i_arbzeit_dauer"     ] ;
      $this->normal_2D[$i]["i_arbzeit_datum"    ]  =               $value["i_arbzeit_datum"     ] ;
      $this->normal_2D[$i]["i_saldo_dauer"      ]  =               $value["i_saldo_dauer"       ] ;
      $this->normal_2D[$i]["i_saldo_datum"      ]  =               $value["i_saldo_datum"       ] ;
      $i++;
    }
  }
}
class salden  {
  public $echt; // Nichts verfällt
  public $kum;  // Nicht autorisierte Überziehungszeiten vefallen
}

$startzeit =  (isset( $_GET["start"])) ? $_GET["start"] : ""; // echo "M010 $startzeit";
$stopzeit  =  (isset( $_GET["stop" ])) ? $_GET["stop" ] : ""; // echo "M010 $startzeit";

$saldo_kum =  6.07;
$saldo_kum =  7.10;
$saldo_kum = 13.17;
$saldo_kum = 13.16;
$salden = new salden();
$salden->kum = 13.16; $salden->echt = $salden->kum;
$salden->kum =  3.42; $salden->echt = $salden->kum;
schleife( datumsobjekt( $startzeit), datumsobjekt( $stopzeit), $salden);

function schleife( $laufobjekt, $stopobjekt, $salden) {
  $intervall = new DateInterval( 'P1M');
  while ( $laufobjekt < $stopobjekt) {
    printf( "%s saldo_kum=%s  saldo_echt=%s <br />\n", $laufobjekt->format('Y-m-d'), $salden->kum, $salden->echt);

    $ein_monat = new ein_monat( $laufobjekt->format('Y-m'));
    $ein_monat->kopiere_und_normalisiere();
    $salden = $ein_monat->gearbeitete_zeit( $salden);  //   
    echo $ein_monat->zeige_die_normalisierten_verwendeten_daten();

    $laufobjekt->add( $intervall);
  }
}


?>