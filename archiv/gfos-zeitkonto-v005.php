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
    foreach ($this->normal_2D as $zeilennummer => $value) {
      $arbeitszeit = $value["arbzeit_plan_ende"  ] - $value["arbzeit_plan_anfang"];
      printf( "Z100 %02d ", $zeilennummer);
      printf( "%s ", $value["datum_auto"  ]);
      printf( "geplant %s ", $this->minToHHMM( $arbeitszeit));
#     printf( "ohne pause %s ", $this->minToHHMM( $arbeitszeit - (new pause)->get_pausenzeit_in_minuten( $arbeitszeit)));
      printf( "pause %s ", (new pause)->get_pausenzeit_in_minuten( $arbeitszeit));
      printf( "<br />\n");
      foreach ($value as $kolumne => $wert) {
        switch ($kolumne) {
        case "arbzeit_plan_ende": break;
        case "arbzeit_plan_anfang": break;
        default : break;
        }
      }
      $zeilennummer++;
    }
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
    /* Kolumnennamen als header
    foreach ($this->normal_2D[0] as $kolumne=>$wert) {
      $erg .= sprintf( "<th> %s ", $kolumne);
    }
     */
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
