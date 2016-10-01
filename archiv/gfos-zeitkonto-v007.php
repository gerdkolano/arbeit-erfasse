<?php
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

# echo "<pre>"; print_r( $_POST); echo "</pre>";

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

class gfos_zeile {
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

  public function toTR() {
    $rechne = new rechne;
    printf( "<tr>"
      . "<td> %s "
      . "<td> %s "
      . "<td> %s "
      . "<td> %s "
      . "<td> P%s " // %.2f "
      . "<td> %.2f "
      . "<td> %s "
      . "<td> %s "
      . "<td> %s "
      . "<td> %s "
      . "<td> %s "
      . "<td> %s "
      . "<td> %s "
      ,
      $this->tagnr         ,
      $this->tagnname      ,
      $rechne->minToHHMM( $this->kommt)         ,
      $rechne->minToHHMM( $this->geht )         ,
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
    $this->tagnr         = "tagnr";
    $this->tagnname      = "tagnn";
    $this->kommt         = "kommt";
    $this->geht          = "geht ";
    $this->pause         = "pause";
    $this->pause_ges     = "p_ges";
    $this->ist           = "ist  ";
    $this->soll          = "soll ";
    $this->saldo_kum     = "saldo";
    $this->fehlzeit_zeit = "fehlz";
    $this->fehlzeit_text = "ftext";
    $this->vst           = "vst  ";
    $this->bemerkung     = "bemer";
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
    return sprintf( "%d.%02d", $wert/60, $wert%60);
  }
}

class ein_monat {
  private $ein_tag;
  private $tabelle;
  private $daten_2D;
  private $normal_2D;
  private $conn;
  function __construct() {
    $tabelle = new tabelle();
    $this->tabelle = $tabelle;
    $spalte = $tabelle->kurzfelder;
    $comma_separated = implode(",", $tabelle->zeitkonto);
    $table = "zeiten";
    $where = "WHERE datum_auto >= '2016-02-01' and datum_auto < '2016-03-01' ORDER BY datum_auto";
    $where = "WHERE datum_auto >= '2015-10-01' and datum_auto < '2015-11-01' ORDER BY datum_auto";
    $query = "SELECT $comma_separated  FROM $table $where";
    # echo "<pre>"; print_r( $this->tabelle->felder); echo "!</pre><br />\n";
    $conn = new conn();
    $conn->frage( 0, "USE arbeit");
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
  
  function minToHHMM( $wert) {
    return sprintf( "%d.%02d", $wert/60, $wert%60);
  }
  
  function toText( $kolumne, $wert) {
    switch ($kolumne) {
    case "datum_auto" : return sprintf( "%s ", $wert); break;
    default           : return sprintf( "%s ", $wert < 0 ? "" : $this->minToHHMM( $wert)); break;
    }
  }
  
  function gearbeitete_zeit( ) {
     //foreach ($this->normal_2D as $zeilennummer => $value) {
    printf( "<table  cellspacing=\"0\" cellpadding=\"2\" border=\"1\"> \n");
    for ($zeilennummer=0; $zeilennummer<count( $this->normal_2D); $zeilennummer++) {
      $value = $this->normal_2D[$zeilennummer];
      $arbeitszeit = $value["arbzeit_plan_ende"  ] - $value["arbzeit_plan_anfang"];
#     printf( "Z100 %02d ", $zeilennummer);
#     printf( "%s ", $value["datum_auto"  ]);
#     printf( "geplant %s ", $this->minToHHMM( $arbeitszeit));
#     printf( "ohne pause %s ", $this->minToHHMM( $arbeitszeit - (new pause)->get_pausenzeit_in_minuten( $arbeitszeit)));
#     printf( "pause %s ", (new pause)->get_pausenzeit_in_minuten( $arbeitszeit));
#     printf( "<br />\n");
#     printf( "Z110 ");
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
      $gfos_zeile = new gfos_zeile();
      for ($i = 0; $i < count( $kommt_geht); $i++) {
        $gfos_zeile->tagnr     = $value["datum_auto"];
        $gfos_zeile->kommt     = $kommt_geht[$i   ];
        $gfos_zeile->geht      = $kommt_geht[$ii+1];
        $gfos_zeile->pause     = "ppp";
        $gfos_zeile->pause_ges = (new pause)->get_pausenzeit_in_stunden( $arbeitszeit);
        $gfos_zeile->ist       = round( ($kommt_geht[$ii+1] - $kommt_geht[$i]) / 60.0, 2);
        $gfos_zeile->toTR();
#       printf( "%s bis %s", $this->minToHHMM( $kommt_geht[$i  ]), $this->minToHHMM( $kommt_geht[$i+1]));
#       printf( "<br />\n");
        $i++;
      }
#     foreach ($kommt_geht as $key => $koge) {
#       printf( "%s ", $this->minToHHMM( $koge));
#     }
#     printf( "<br />\n");

    }
    printf( "</table> \n");
  }
  
  function toHTML( ) {
    $erg = "";
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
    if (false) foreach ($this->normal_2D[0] as $kolumne=>$wert) {
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
  
  function normalisiere() {
    $i = 0;
    foreach ($this->daten_2D as $zeilennummer=>$value) {
      $this->normal_2D[$i]["datum_auto"]           =               $value["datum_auto"         ] ;
      $this->normal_2D[$i]["arbzeit_plan_anfang"]  = $this->toMin( $value["arbzeit_plan_anfang"]);
      $this->normal_2D[$i]["arbeit_kommt"       ]  = $this->toMin( $value["arbeit_kommt"       ]);
      $this->normal_2D[$i]["pause1_geht"        ]  = $this->toMin( $value["pause1_geht"        ]);
      $this->normal_2D[$i]["pause1_kommt"       ]  = $this->toMin( $value["pause1_kommt"       ]);
      $this->normal_2D[$i]["pause2_geht"        ]  = $this->toMin( $value["pause2_geht"        ]);
      $this->normal_2D[$i]["pause2_kommt"       ]  = $this->toMin( $value["pause2_kommt"       ]);
      $this->normal_2D[$i]["arbeit_geht"        ]  = $this->toMin( $value["arbeit_geht"        ]);
      $this->normal_2D[$i]["arbzeit_plan_ende"  ]  = $this->toMin( $value["arbzeit_plan_ende"  ]);
      $i++;
    }
  }
}

$ein_monat = new ein_monat();
$ein_monat->normalisiere();
echo $ein_monat->toHTML();
$ein_monat->gearbeitete_zeit();
?>
