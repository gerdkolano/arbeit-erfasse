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

  public function th() {
    return sprintf( "<th> %s\n", $this->wert);
  }

  public function header() {
    return sprintf( $this->head_format . "\n", $this->kurzname);
  }

}

class gfos_zeile {
  public $ausfelder     ;
  public $tagnr         ;
  public $tagnname      ;
  public $kommt         ;
  public $geht          ;
  public $pause         ;
  public $pause_ges     ;
  public $ist           ;
  public $soll          ;
  public $saldo_kum     ;
  public $fehlzeit_zeit ;
  public $fehlzeit_text ;
  public $vst           ;
  public $bemerkung     ;

  public function toTH() {
    $rechne = new rechne;

    $table_header = "<tr>\n";
    foreach ($this->ausfelder as $key => $val) {
      $table_header .= $val->header();
    } 
    echo $table_header;

    $table_row    = "";

  }
      
  public function toTR() {
    $rechne = new rechne;
    printf( "<tr>"
      . "<td> %s "
      . "<td> %s "
      . "<td> %s "
      . "<td> %s "
      . "<td> %s "
      . "<td> %s "
      . "<td> %s "
      . "<td> %s "
      . "<td> %s "
      . "<td> %s "
      . "<td> %s "
      . "<td> %s "
      . "<td> %s "
      . "\n"
      ,
      $this->tagnr         ,
      $this->tagnname      ,
      $this->kommt         ,
      $this->geht          ,
      $this->pause         ,
      $this->pause_ges     ,
      $this->ist           ,
      $this->soll          ,
      $this->saldo_kum     ,
      $this->fehlzeit_zeit ,
      $this->fehlzeit_text ,
      $this->vst           ,
      $this->bemerkung      
    );
      
  }
  
  function __construct() {
    $this->tagnr         = "";  // "tagnr";
    $this->tagnname      = "";  // "tagnn";
    $this->kommt         = "";  // "kommt";
    $this->geht          = "";  // "geht ";
    $this->pause         = "";  // "pause";
    $this->pause_ges     = "";  // "p_ges";
    $this->ist           = "";  // "ist  ";
    $this->soll          = "";  // "soll ";
    $this->saldo_kum     = "";  // "saldo";
    $this->fehlzeit_zeit = "";  // "fehlz";
    $this->fehlzeit_text = "";  // "ftext";
    $this->vst           = "";  // "vst  ";
    $this->bemerkung     = "";  // "bemer";
    $this->ausfelder = array (
      "tagnr"         => new  gfos_ausgabe_element("tagn" , "<th> %s " , "<td> %s "),       
      "tagnname"      => new  gfos_ausgabe_element("tagn" , "<th> %s " , "<td> %s "),          
      "kommt"         => new  gfos_ausgabe_element("komm" , "<th> %s " , "<td> %s "),       
      "geht"          => new  gfos_ausgabe_element("geht" , "<th> %s " , "<td> %s "),      
      "pause"         => new  gfos_ausgabe_element("paus" , "<th> %s " , "<td> %s "),       
      "pause_ges"     => new  gfos_ausgabe_element("paus" , "<th> %s " , "<td> %s "),           
      "ist"           => new  gfos_ausgabe_element("ist"  , "<th> %s " , "<td> %s "),     
      "soll"          => new  gfos_ausgabe_element("soll" , "<th> %s " , "<td> %s "),      
      "saldo_kum"     => new  gfos_ausgabe_element("sald" , "<th> %s " , "<td> %s "),           
      "fehlzeit_zeit" => new  gfos_ausgabe_element("fehl" , "<th> %s " , "<td> %s "),               
      "fehlzeit_text" => new  gfos_ausgabe_element("fehl" , "<th> %s " , "<td> %s "),               
      "vst"           => new  gfos_ausgabe_element("vst"  , "<th> %s " , "<td> %s "),     
      "bemerkung"     => new  gfos_ausgabe_element("beme" , "<th> %s " , "<td> %s ")            
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
  function __construct( $welcher_monat) { //  Noch zu berücksichtigen i_saldo_dauer   i_saldo_datum
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
  
  function gearbeitete_zeit( ) {

    $saldo_kum = 6.07;
    $beschäftigungsumfang = 33.30;

    $gfos_titel = "gfos 4.7plus Zeitkonto";
    printf( "<h3 style=\"text-align: center\">%s — %s </h3><br />\n", $gfos_titel, $this->start_datum->format( "MMMM yyyy"));

    printf( "<table  cellspacing=\"0\" cellpadding=\"2\" border=\"1\"> \n");
    $gfos_zeile = (new gfos_zeile())->toTH();
    
    for ($zeilennummer=0; $zeilennummer<count( $this->normal_2D); $zeilennummer++) {
    //foreach ($this->normal_2D as $zeilennummer => $value) {
      $value = $this->normal_2D[$zeilennummer];
      $arbeitszeit = $value["arbzeit_plan_ende"  ] - $value["arbzeit_plan_anfang"];
#     printf( "Z100 %02d ", $zeilennummer);
#     printf( "%s ", $value["datum_auto"  ]);
#     printf( "geplant %s ", $this->minToHHMM( $arbeitszeit));
#     printf( "ohne pause %s ", $this->minToHHMM( $arbeitszeit - (new pause)->get_pausenzeit_in_minuten( $arbeitszeit)));
#     printf( "pause %s ", (new pause)->get_pausenzeit_in_minuten( $arbeitszeit));
#     printf( "<br />\n");
#     printf( "Z110 ");

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
$zz = 3;
      $datumsobjekt = datumsobjekt( $value["datum_auto"]);
      $gfos_zeile = new gfos_zeile();
$ausfelder = $gfos_zeile->ausfelder;
echo $ausfelder["tagnr"]->kurzname;
$ausfelder["tagnr"]->set_wert( $fmt_nr  ->format( $datumsobjekt));
      $ausfelder["tagnr"]->wert = $fmt_nr  ->format( $datumsobjekt);
      $gfos_zeile->tagnr     =        $fmt_nr  ->format( $datumsobjekt);
      $gfos_zeile->tagnname  = rtrim( $fmt_name->format( $datumsobjekt), ".");
      $gfos_zeile->pause_ges = sprintf( "%.2f", $pause_ges);
      for ($ii = 0; $ii < count( $kommt_geht); $ii++) {
        $gfos_zeile->kommt     = $this->rechne->minToHHMM( $kommt_geht[$ii  ]);
        $gfos_zeile->geht      = $this->rechne->minToHHMM( $kommt_geht[$ii+1]);
        $gfos_zeile->pause     = $pause > 0.0 ? sprintf( "%.2f", $pause) : "";
        $pause = $pause > 0.25 ? $pause - 0.25 : 0.0;
        $gfos_zeile->ist       = sprintf( "%06.3f", round(($kommt_geht[$ii+1]            -        $kommt_geht[$ii])      / 60.0 , $zz));
        $gfos_zeile->ist       = sprintf( "%06.3f", round( $kommt_geht[$ii+1]/60.0, $zz) - round( $kommt_geht[$ii]/60.0, $zz));
        $saldo_kum            += $gfos_zeile->ist; 
        $gfos_zeile->saldo_kum = sprintf( "%06.3f", $saldo_kum);
        $gfos_zeile->toTR();
        
#       printf( "%s bis %s", $this->minToHHMM( $kommt_geht[$i  ]), $this->minToHHMM( $kommt_geht[$i+1]));
#       printf( "<br />\n");
        $gfos_zeile->tagnr     = "";
        $gfos_zeile->tagnname  = "";
        $gfos_zeile->pause_ges = "";
        $ii++;
      }
      if (1 == $fmt_montag->format( $datumsobjekt)) {  // Jeden Montag noch ne Zwischenzeile
        $gfos_zeile = new gfos_zeile();
        $saldo_kum -= $beschäftigungsumfang;
        $gfos_zeile->ist       = sprintf( "%.2f", $beschäftigungsumfang);
        $gfos_zeile->soll      = sprintf( "%.2f", $beschäftigungsumfang);
        $gfos_zeile->kommt     = "";
        $gfos_zeile->geht      = "";
        $gfos_zeile->bemerkung = "Sollzeit";
        $gfos_zeile->toTR();
      }
#     foreach ($kommt_geht as $key => $koge) {
#       printf( "%s ", $this->minToHHMM( $koge));
#     }
#     printf( "<br />\n");

    }
    printf( "</table> \n");
  }
  
  function toText( $kolumne, $wert) {
    switch ($kolumne) {
    case "datum_auto" : return sprintf( "%s ", $wert); break;
    default           : return sprintf( "%s ", $wert < 0 ? "" : $this->rechne->minToHHMM( $wert)); break;
    }
  }
  
  function zeige_die_normalisierten_verwendeten_daten( ) {
    $erg = "";
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
    $erg .= sprintf( "</tr>\n");
    // Kolumnennamen als header
    if (true) foreach ($this->normal_2D[0] as $kolumne=>$wert) {
      $erg .= sprintf( "<th> %s ", $kolumne);
    }

    foreach ($this->normal_2D as $zeilennummer=>$value) {
      $erg .= sprintf( "<tr>");
      foreach ($value as $kolumne=>$wert) {
        $erg .= sprintf( "<td> %s ", $this->toText( $kolumne, $wert));
      }
      $erg .= sprintf( "</tr>\n");
    }
    //echo "<pre>"; print_r( $this->normal_2D); echo "!</pre><br />\n";
    return sprintf("<table  cellspacing=\"0\" cellpadding=\"2\" border=\"1\">\n%s</table>", $erg);
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
  
  function kopiere_und_normalisiere() {
    $i = 0;
    foreach ($this->daten_2D as $zeilennummer=>$value) {  // Kopiere daten_2D nach normal_2D
      $this->normal_2D[$i]["datum_auto"         ]  =               $value["datum_auto"         ] ;
      $this->normal_2D[$i]["arbzeit_plan_anfang"]  = $this->toMin( $value["arbzeit_plan_anfang"]);
      $this->normal_2D[$i]["arbeit_kommt"       ]  = $this->toMin( $value["arbeit_kommt"       ]);
      $this->normal_2D[$i]["pause1_geht"        ]  = $this->toMin( $value["pause1_geht"        ]);
      $this->normal_2D[$i]["pause1_kommt"       ]  = $this->toMin( $value["pause1_kommt"       ]);
      $this->normal_2D[$i]["pause2_geht"        ]  = $this->toMin( $value["pause2_geht"        ]);
      $this->normal_2D[$i]["pause2_kommt"       ]  = $this->toMin( $value["pause2_kommt"       ]);
      $this->normal_2D[$i]["arbeit_geht"        ]  = $this->toMin( $value["arbeit_geht"        ]);
      $this->normal_2D[$i]["arbzeit_plan_ende"  ]  = $this->toMin( $value["arbzeit_plan_ende"  ]);
      $this->normal_2D[$i]["i_arbzeit_dauer"    ]  =               $value["i_arbzeit_dauer"    ] ;
      $this->normal_2D[$i]["i_arbzeit_datum"    ]  =               $value["i_arbzeit_datum"    ] ;
      $this->normal_2D[$i]["i_saldo_dauer"      ]  =               $value["i_saldo_dauer"      ] ;
      $this->normal_2D[$i]["i_saldo_datum"      ]  =               $value["i_saldo_datum"      ] ;
      $i++;
    }
  }
}

//    $where = "WHERE datum_auto >= '2015-10-01' and datum_auto < '2015-11-01' ORDER BY datum_auto";
$wunschmonat =  (isset( $_GET["m"])) ? $_GET["m"] : ""; // echo "M010 $wunschmonat";
$ein_monat = new ein_monat( $wunschmonat);
$ein_monat->kopiere_und_normalisiere();
$ein_monat->gearbeitete_zeit();
echo $ein_monat->zeige_die_normalisierten_verwendeten_daten();

?>
