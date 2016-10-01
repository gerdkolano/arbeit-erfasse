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

class ein_monat {
  private $ein_tag;
  private $tabelle;
  private $daten_2D;
  private $conn;
  function __construct() {
    $kommt_geht = array();
    $tabelle = new tabelle();
    $this->tabelle = $tabelle;
    $spalte = $tabelle->kurzfelder;
    $comma_separated = implode(",", $tabelle->zeitkonto);
    $table = "zeiten";
    $where = "WHERE datum_auto >= '2016-01-01' and datum_auto < '2016-02-01' ORDER BY datum_auto";
    $query = "SELECT $comma_separated  FROM $table $where";
    # echo "<pre>"; print_r( $this->tabelle->felder); echo "!</pre><br />\n";
    $conn = new conn();
    $conn->frage( 0, "USE arbeit");
    $this->daten_2D = $conn->hol_array_of_objects( "$query");
  }

  function normalisiere() {
    // printf( "T010 %s %s %s<br />\n", 0, "datum_auto", $daten_2D[0]["datum_auto"]);
    // printf( "T010 %s %s %s<br />\n", 1, "datum_auto", $daten_2D[1]["datum_auto"]);

    foreach ($this->daten_2D as $zeilennummer=>$value) {
      printf( "zeile nummer %s ", $zeilennummer);
/*
      printf( "%s", $value["id"                 ] );
      printf( "%s", $value["datum_auto"         ] );
      printf( "%s", $value["arbzeit_plan_anfang"] );
      printf( "%s", $value["arbzeit_plan_ende"  ] );
      printf( "%s", $value["arbeit_kommt"       ] );
      printf( "%s", $value["pause1_geht"        ] );
      printf( "%s", $value["pause1_kommt"       ] );
      printf( "%s", $value["pause2_geht"        ] );
      printf( "%s", $value["pause2_kommt"       ] );
      printf( "%s", $value["arbeit_geht"        ] );
      printf( "%s", $value["i_saldo_dauer"      ] );
      printf( "%s", $value["i_saldo_datum"      ] );

      printf( "%s <br />\n", " #");
      printf( "%s <br />\n", " #");
 */
      foreach ($value as $kolumne=>$wert) {
        printf( "%s ", $wert);
      }
      printf( "%s <br />\n", " #");
      $value["arbzeit_plan_anfang"]  = toMin( $value["arbzeit_plan_anfang"]);
      $value["arbzeit_plan_ende"  ]  = toMin( $value["arbzeit_plan_ende"  ]);
      $value["arbeit_kommt"       ]  = toMin( $value["arbeit_kommt"       ]); $kommt_geht[] = $value["arbeit_kommt"       ];
      $value["pause1_geht"        ]  = toMin( $value["pause1_geht"        ]); $kommt_geht[] = $value["pause1_geht"        ];
      $value["pause1_kommt"       ]  = toMin( $value["pause1_kommt"       ]); $kommt_geht[] = $value["pause1_kommt"       ];
      $value["pause2_geht"        ]  = toMin( $value["pause2_geht"        ]); $kommt_geht[] = $value["pause2_geht"        ];
      $value["pause2_kommt"       ]  = toMin( $value["pause2_kommt"       ]); $kommt_geht[] = $value["pause2_kommt"       ];
      $value["arbeit_geht"        ]  = toMin( $value["arbeit_geht"        ]); $kommt_geht[] = $value["arbeit_geht"        ];
      foreach ($value as $kolumne=>$wert) {
        printf( "%s ", $wert);
      }
      printf( "%s <br />\n", " #");
    }
      foreach ($kommt_geht as $nr=>$wert) {
        printf( "KG010 %s <br />\n", $wert);
      }

  }
}

$ein_monat = new ein_monat();
$ein_monat->normalisiere();

function toMin( $wort) { // string ziffern nichtziffern ziffern
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
?>
