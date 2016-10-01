<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css-formblatt.css" type="text/css">
</head>
<body>
<?php
require_once( "../include/datum.php");

$form = new formular_monat( new datum_objekt( ""), new stunde( 0), new stunde( 0), new stunde( 3330), array (
new eine_woche( /*"2014-12-22", "2014-12-27",*/ new stunde( -1), new stunde( -1), new stunde( -1), new stunde( 0)),
new eine_woche( /*"2014-12-22", "2014-12-27",*/ new stunde( -1), new stunde( -1), new stunde( -1), new stunde( 0)),
new eine_woche( /*"2014-12-22", "2014-12-27",*/ new stunde( -1), new stunde( -1), new stunde( -1), new stunde( 0)),
new eine_woche( /*"2014-12-22", "2014-12-27",*/ new stunde( -1), new stunde( -1), new stunde( -1), new stunde( 0)),
new eine_woche( /*"2014-12-22", "2014-12-27",*/ new stunde( -1), new stunde( -1), new stunde( -1), new stunde( 0)),
));
$form->leer();
echo $form;
echo "<div class=\"page-break\"></div>\n";
echo new formular_monat( new datum_objekt( "2014-12-07"), new stunde( 3043), new stunde( 286), new stunde( 3330), array (
new eine_woche( /*"2014-12-01", "2014-12-06",*/ new stunde( 3105), new stunde( 120), new stunde( 92), new stunde( 19)),
new eine_woche( /*"2014-12-08", "2014-12-13",*/ new stunde( 3464), new stunde( 10), new stunde( 0), new stunde( 146)),
new eine_woche( /*"2014-12-15", "2014-12-20",*/ new stunde( 3500), new stunde( 0), new stunde( 0), new stunde( 30)),
new eine_woche( /*"2014-12-22", "2014-12-27",*/ new stunde( 3510), new stunde( 30), new stunde( 25), new stunde( 25)),
));
echo new formular_monat( new datum_objekt( "2015-01-04"), new stunde( 3579), new stunde( 506), new stunde( 3330), array (
new eine_woche( /*"2014-12-29", "2015-01-03",*/ new stunde( 3547), new stunde( 40), new stunde( 25), new stunde( 0)),
new eine_woche( /*"2015-01-05", "2015-01-10",*/ new stunde( 3450), new stunde( 10), new stunde( 0), new stunde( 9)),
new eine_woche( /*"2015-01-12", "2015-01-17",*/ new stunde( 3374), new stunde( 100), new stunde( 88), new stunde( 104)),
new eine_woche( /*"2015-01-19", "2015-01-24",*/ new stunde( 3410), new stunde( 10), new stunde( 0), new stunde( 9)),
new eine_woche( /*"2015-01-26", "2015-01-31",*/ new stunde( 3330), new stunde( 0), new stunde( 0), new stunde( 0)),
));
echo new formular_monat( new datum_objekt( "2015-02-08"), new stunde( 4313), new stunde( 628), new stunde( 3330), array (
new eine_woche( /*"2015-02-02", "2015-02-07",*/ new stunde( 3150), new stunde( 100), new stunde( 59), new stunde( 2)),
new eine_woche( /*"2015-02-09", "2015-02-14",*/ new stunde( 3336), new stunde( 35), new stunde( 20), new stunde( 8)),
new eine_woche( /*"2015-02-16", "2015-02-21",*/ new stunde( 3392), new stunde( 70), new stunde( 38), new stunde( 0)),
new eine_woche( /*"2015-02-23", "2015-02-28",*/ new stunde( 3526), new stunde( 40), new stunde( 13), new stunde( 0)),
));
echo new formular_monat( new datum_objekt( "2015-03-08"), new stunde( 4772), new stunde( 638), new stunde( 3330), array (
new eine_woche( /*"2015-03-02", "2015-03-07",*/ new stunde( 2859), new stunde( 70), new stunde( 26), new stunde( 13)),
new eine_woche( /*"2015-03-09", "2015-03-14",*/ new stunde( 3700), new stunde( 0), new stunde( 0), new stunde( 0)),
new eine_woche( /*"2015-03-16", "2015-03-21",*/ new stunde( 3900), new stunde( 70), new stunde( 26), new stunde( 9)),
new eine_woche( /*"2015-03-23", "2015-03-28",*/ new stunde( 4014), new stunde( 10), new stunde( 0), new stunde( 4)),
));
echo new formular_monat( new datum_objekt( "2015-04-05"), new stunde( 6127), new stunde( 664), new stunde( 3330), array (
new eine_woche( /*"2015-03-30", "2015-04-04",*/ new stunde( 3752), new stunde( 0), new stunde( 0), new stunde( 4)),
new eine_woche( /*"2015-04-06", "2015-04-11",*/ new stunde( 3305), new stunde( 30), new stunde( 13), new stunde( 4)),
new eine_woche( /*"2015-04-13", "2015-04-18",*/ new stunde( 3387), new stunde( 90), new stunde( 60), new stunde( 2)),
new eine_woche( /*"2015-04-20", "2015-04-25",*/ new stunde( 3989), new stunde( 40), new stunde( 13), new stunde( 4)),
new eine_woche( /*"2015-04-27", "2015-05-02",*/ new stunde( 3581), new stunde( 0), new stunde( 0), new stunde( 8)),
));
echo new formular_monat( new datum_objekt( "2015-05-10"), new stunde( 7737), new stunde( 686), new stunde( 3330), array (
new eine_woche( /*"2015-05-04", "2015-05-09",*/ new stunde( 3165), new stunde( 26), new stunde( 21), new stunde( 3)),
new eine_woche( /*"2015-05-11", "2015-05-16",*/ new stunde( 3788), new stunde( 60), new stunde( 26), new stunde( 11)),
new eine_woche( /*"2015-05-18", "2015-05-23",*/ new stunde( 3383), new stunde( 39), new stunde( 18), new stunde( 5)),
new eine_woche( /*"2015-05-25", "2015-05-30",*/ new stunde( 3531), new stunde( 60), new stunde( 28), new stunde( 2)),
));
echo new formular_monat( new datum_objekt( "2015-06-07"), new stunde( 8562), new stunde( 707), new stunde( 3330), array (
new eine_woche( /*"2015-06-01", "2015-06-06",*/ new stunde( 3417), new stunde( 30), new stunde( 9), new stunde( 0)),
new eine_woche( /*"2015-06-08", "2015-06-13",*/ new stunde( 2438), new stunde( 60), new stunde( 44), new stunde( 0)),
new eine_woche( /*"2015-06-15", "2015-06-20",*/ new stunde( 2427), new stunde( 60), new stunde( 39), new stunde( 7)),
new eine_woche( /*"2015-06-22", "2015-06-27",*/ new stunde( 3427), new stunde( 30), new stunde( 10), new stunde( 4)),
));
echo new formular_monat( new datum_objekt( "2015-07-05"), new stunde( 7233), new stunde( 718), new stunde( 3330), array (
new eine_woche( /*"2015-06-29", "2015-07-04",*/ new stunde( 3767), new stunde( 63), new stunde( 26), new stunde( 4)),
new eine_woche( /*"2015-07-06", "2015-07-11",*/ new stunde( 3087), new stunde( 30), new stunde( 13), new stunde( 2)),
new eine_woche( /*"2015-07-13", "2015-07-18",*/ new stunde( 3750), new stunde( 0), new stunde( 0), new stunde( 0)),
new eine_woche( /*"2015-07-20", "2015-07-25",*/ new stunde( 3500), new stunde( 0), new stunde( 0), new stunde( 0)),
new eine_woche( /*"2015-07-27", "2015-08-01",*/ new stunde( 3693), new stunde( 120), new stunde( 57), new stunde( 12)),
));
echo new formular_monat( new datum_objekt( "2015-08-09"), new stunde( 8689), new stunde( 736), new stunde( 3330), array (
new eine_woche( /*"2015-08-03", "2015-08-08",*/ new stunde( 3792), new stunde( 0), new stunde( 0), new stunde( 2)),
new eine_woche( /*"2015-08-10", "2015-08-15",*/ new stunde( 3187), new stunde( 0), new stunde( 0), new stunde( 0)),
new eine_woche( /*"2015-08-17", "2015-08-22",*/ new stunde( 3284), new stunde( 60), new stunde( 26), new stunde( 5)),
new eine_woche( /*"2015-08-24", "2015-08-29",*/ new stunde( 3683), new stunde( 30), new stunde( 13), new stunde( 0)),
));
echo new formular_monat( new datum_objekt( "2015-09-06"), new stunde( 9444), new stunde( 743), new stunde( 3330), array (
new eine_woche( /*"2015-08-31", "2015-09-05",*/ new stunde( 3330), new stunde( 0), new stunde( 0), new stunde( 0)),
new eine_woche( /*"2015-09-07", "2015-09-12",*/ new stunde( 3330), new stunde( 0), new stunde( 0), new stunde( 0)),
new eine_woche( /*"2015-09-14", "2015-09-19",*/ new stunde( 3330), new stunde( 0), new stunde( 0), new stunde( 0)),
new eine_woche( /*"2015-09-21", "2015-09-26",*/ new stunde( 3663), new stunde( 120), new stunde( 58), new stunde( 0)),
));
echo new formular_monat( new datum_objekt( "2015-10-04"), new stunde( 9955), new stunde( 743), new stunde( 3330), array (
new eine_woche( /*"2015-09-28", "2015-10-03",*/ new stunde( 3522), new stunde( 30), new stunde( 19), new stunde( 0)),
new eine_woche( /*"2015-10-05", "2015-10-10",*/ new stunde( 3467), new stunde( 30), new stunde( 13), new stunde( 2)),
new eine_woche( /*"2015-10-12", "2015-10-17",*/ new stunde( 3756), new stunde( 60), new stunde( 28), new stunde( 4)),
new eine_woche( /*"2015-10-19", "2015-10-24",*/ new stunde( 3330), new stunde( 0), new stunde( 0), new stunde( 0)),
new eine_woche( /*"2015-10-26", "2015-10-31",*/ new stunde( 3330), new stunde( 0), new stunde( 0), new stunde( 0)),
));
echo new formular_monat( new datum_objekt( "2015-11-08"), new stunde( 10890), new stunde( 749), new stunde( 3330), array (
new eine_woche( /*"2015-11-02", "2015-11-07",*/ new stunde( 3767), new stunde( 120), new stunde( 61), new stunde( 0)),
new eine_woche( /*"2015-11-09", "2015-11-14",*/ new stunde( 3459), new stunde( 33), new stunde( 13), new stunde( 4)),
new eine_woche( /*"2015-11-16", "2015-11-21",*/ new stunde( 3881), new stunde( 80), new stunde( 31), new stunde( 0)),
new eine_woche( /*"2015-11-23", "2015-11-28",*/ new stunde( 3790), new stunde( 0), new stunde( 0), new stunde( 0)),
));
echo new formular_monat( new datum_objekt( "2015-12-06"), new stunde( 12805), new stunde( 753), new stunde( 3330), array (
new eine_woche( /*"2015-11-30", "2015-12-05",*/ new stunde( 4230), new stunde( 89), new stunde( 51), new stunde( 0)),
new eine_woche( /*"2015-12-07", "2015-12-12",*/ new stunde( 3418), new stunde( 0), new stunde( 0), new stunde( 0)),
new eine_woche( /*"2015-12-14", "2015-12-19",*/ new stunde( 3761), new stunde( 150), new stunde( 70), new stunde( 0)),
new eine_woche( /*"2015-12-21", "2015-12-26",*/ new stunde( 3376), new stunde( 30), new stunde( 13), new stunde( 4)),
new eine_woche( /*"2015-12-28", "2016-01-02",*/ new stunde( 3800), new stunde( 90), new stunde( 39), new stunde( 2)),
));
echo new formular_monat( new datum_objekt( "2016-01-10"), new stunde( 15272), new stunde( 759), new stunde( 3330), array (
new eine_woche( /*"2016-01-04", "2016-01-09",*/ new stunde( 2615), new stunde( 60), new stunde( 38), new stunde( 0)),
new eine_woche( /*"2016-01-11", "2016-01-16",*/ new stunde( 2014), new stunde( 60), new stunde( 20), new stunde( 0)),
new eine_woche( /*"2016-01-18", "2016-01-23",*/ new stunde( 2472), new stunde( 90), new stunde( 50), new stunde( 0)),
new eine_woche( /*"2016-01-25", "2016-01-30",*/ new stunde( 3330), new stunde( 0), new stunde( 0), new stunde( 0)),
));
echo new formular_monat( new datum_objekt( "2016-02-07"), new stunde( 12701), new stunde( 759), new stunde( 3330), array (
new eine_woche( /*"2016-02-01", "2016-02-06",*/ new stunde( 3588), new stunde( 150), new stunde( 71), new stunde( 9)),
new eine_woche( /*"2016-02-08", "2016-02-13",*/ new stunde( 3757), new stunde( 30), new stunde( 9), new stunde( 0)),
new eine_woche( /*"2016-02-15", "2016-02-20",*/ new stunde( 3492), new stunde( 90), new stunde( 35), new stunde( 9)),
new eine_woche( /*"2016-02-22", "2016-02-27",*/ new stunde( 2944), new stunde( 60), new stunde( 30), new stunde( 0)),
));
echo new formular_monat( new datum_objekt( "2016-03-06"), new stunde( 13637), new stunde( 777), new stunde( 3330), array (
new eine_woche( /*"2016-02-29", "2016-03-05",*/ new stunde( 3559), new stunde( 109), new stunde( 198), new stunde( 2)),
new eine_woche( /*"2016-03-07", "2016-03-12",*/ new stunde( 3447), new stunde( 80), new stunde( 195), new stunde( 7)),
new eine_woche( /*"2016-03-14", "2016-03-19",*/ new stunde( 3143), new stunde( 140), new stunde( 197), new stunde( 0)),
new eine_woche( /*"2016-03-21", "2016-03-26",*/ new stunde( 3681), new stunde( 0), new stunde( 0), new stunde( 0)),
new eine_woche( /*"2016-03-28", "2016-04-02",*/ new stunde( 3396), new stunde( 58), new stunde( 126), new stunde( 0)),
));
echo new formular_monat( new datum_objekt( "2016-04-10"), new stunde( 15316), new stunde( 786), new stunde( 3330), array (
new eine_woche( /*"2016-04-04", "2016-04-09",*/ new stunde( 3200), new stunde( 57), new stunde( 126), new stunde( 6)),
new eine_woche( /*"2016-04-11", "2016-04-16",*/ new stunde( 2477), new stunde( 20), new stunde( 0), new stunde( 0)),
new eine_woche( /*"2016-04-18", "2016-04-23",*/ new stunde( 2400), new stunde( 0), new stunde( 0), new stunde( 0)),
new eine_woche( /*"2016-04-25", "2016-04-30",*/ new stunde( 0), new stunde( 0), new stunde( 0), new stunde( 0)),
));
echo new formular_monat( new datum_objekt( "2016-05-08"), new stunde( 10276), new stunde( 792), new stunde( 3330), array (
new eine_woche( /*"2016-05-02", "2016-05-07",*/ new stunde( 0), new stunde( 0), new stunde( 0), new stunde( 0)),
new eine_woche( /*"2016-05-09", "2016-05-14",*/ new stunde( 0), new stunde( 0), new stunde( 0), new stunde( 0)),
new eine_woche( /*"2016-05-16", "2016-05-21",*/ new stunde( 0), new stunde( 0), new stunde( 0), new stunde( 0)),
new eine_woche( /*"2016-05-23", "2016-05-28",*/ new stunde( 0), new stunde( 0), new stunde( 0), new stunde( 0)),
)); 


class stunde {
  private $wert;
  function __construct( $wert) { $this->wert = $wert; } 
  function __toString()        { return sprintf( "%05.2f", $this->wert / 100.0); } 
  function add( stunde $arg)   { return new stunde( $this->wert +  $arg->wert); }
  function inc( stunde $arg)   { $this->wert += $arg->wert; }
  function sub( stunde $arg)   { return new stunde( $this->wert -  $arg->wert); } 
  function  lt( stunde $arg)   { return $this->wert < $arg->wert; } 
  function lt0()               { return $this->wert < 0; } 
}

class eine_woche {
  private $starttag             ;
  private $stoptag              ;
  public  $woche_gfos           ;
  public  $woche_20_gfos        ;
  public  $woche_50_gfos        ;
  public  $woche_verfall        ;
  public  $woche_plus_minus     ;
  private $woche_gesamt         ;

  function __toString() {
    if ($this->woche_gfos->lt0()) {
    $erg = "";
    $erg .= "<td>" ;
    $erg .= "<td>" ;
    $erg .= "<td>" ;
    $erg .= "<td>" ;
    $erg .= "<td>" ;
    $erg .= "<td>" ;
    $erg .= "<td>" ;
    $erg .= "<td>" ;
    return $erg;
    }
    $erg = "";
    $erg .= "<td>" . $this->starttag         ;
    $erg .= "<td>" . $this->stoptag          ;
    $erg .= "<td>" . $this->woche_gfos       ;
    $erg .= "<td>" . $this->woche_20_gfos    ;
    $erg .= "<td>" . $this->woche_50_gfos    ;
    $erg .= "<td>" . $this->woche_gesamt     ;
    $erg .= "<td>" . $this->woche_plus_minus ;
    $erg .= "<td>" . $this->woche_verfall    ;
    return $erg;
  }

  function set_start(                 $starttag             ) { $this->starttag              = $starttag;              }
  function set_stop (                 $stoptag              ) { $this->stoptag               = $stoptag;               }
  function set_plus_minus ( $beschäftigungsumfang ) {
    $this->woche_plus_minus      = $this->woche_gesamt->sub( $beschäftigungsumfang);
  }

  function __construct( stunde $woche_gfos, stunde $woche_20_gfos, stunde $woche_50_gfos, stunde $woche_verfall) {
    $this->woche_gfos            = $woche_gfos           ;
    $this->woche_20_gfos         = $woche_20_gfos        ;
    $this->woche_50_gfos         = $woche_50_gfos        ;
    $this->woche_verfall         = $woche_verfall        ;
    $this->woche_gesamt          = $woche_gfos->add( $woche_20_gfos)->add( $woche_50_gfos);
  } 

}

class bis_fünf_wochen {
  private $vier_bis_fünf_wochen ;
  private $vortrag_p_m          ;
  private $monat_gfos           ;
  private $monat_20_gfos        ;
  private $monat_50_gfos        ;
  public  $monat_verfall        ;
  public  $bilanz_plus_minus    ;
  private $summen               ;
  
  function __toString() {
    $erg = "";
    foreach ( $this->vier_bis_fünf_wochen as $key=>$val) {
      $erg .= "<tr>$val</tr>\n";
    }
  # $erg .= "<tr>$this->summen</tr>\n";
    return "$erg";
    return "<table>\n$erg</table>\n";
  }
  
  function __construct( datum_objekt $erster_tag, stunde $vortrag_p_m, stunde $vortrag_verfall, stunde $beschäftigungsumfang, array $vier_bis_fünf_wochen) {
    $start_tag = clone $erster_tag;
    $stopp_tag = clone $erster_tag;
    $stopp_tag->modify( "+5 day");
#   echo "S020 " . $start_tag->format("Y-m-d\n") . $vortrag_p_m . "! " . $vortrag_verfall . "! ";
    $bumfang             = new stunde( 0);
    $this->monat_gfos    = $vortrag_p_m;
    $this->monat_20_gfos = new stunde( 0);
    $this->monat_50_gfos = new stunde( 0);
    $this->monat_verfall = new stunde( 0);
    $this->monat_verfall = $vortrag_verfall;
  
    $this->vier_bis_fünf_wochen = $vier_bis_fünf_wochen;

    foreach ( $this->vier_bis_fünf_wochen as $key=>$val) {
      $val->set_plus_minus ( $beschäftigungsumfang);
      $val->set_start( $start_tag->format( "Y-m-d"));
      $val->set_stop ( $stopp_tag->format( "Y-m-d"));
                       $start_tag->modify( "next week");
                       $stopp_tag->modify( "+1 week");
      $this->monat_verfall->inc( $val->woche_verfall); 
      $this->monat_gfos   ->inc( $val->woche_gfos   ); 
      $this->monat_20_gfos->inc( $val->woche_20_gfos); 
      $this->monat_50_gfos->inc( $val->woche_50_gfos); 
      $bumfang->inc( $beschäftigungsumfang); 
    }
    $this->summen = new eine_woche(
      $this->monat_gfos    ,
      $this->monat_20_gfos ,
      $this->monat_50_gfos ,
      $this->monat_verfall ,
      $bumfang
    );    
    $this->summen->set_start( "von");
    $this->summen->set_stop ( "bis");
    $this->summen->set_plus_minus ( $bumfang);
    $this->bilanz_plus_minus = $this->summen->woche_plus_minus;
  }
  
}

class formular_monat {
  private $anspruchsteller = "Sabine Schallehn";
  private $za_liste        = "ZA-Liste (neu:gfos 4.7plus Zeitkonto)";
  private $heute_txt            ;
  private $vormonat_txt         ;
  private $dieser_monat_txt     ;
  private $nachmonat_txt        ;
  private $vortrag_p_m          ;
  private $übertrag             ;
  private $verfall              ;
  private $beschäftigungsumfang ;
  
  private $vier_bis_fünf_wochen;
  
  function __construct( datum_objekt $dieser_monat, stunde $vortrag_p_m, stunde $vortrag_verfall, stunde $beschäftigungsumfang, array $vier_oder_fünf) {
    $this->beschäftigungsumfang  = $beschäftigungsumfang;
  
    $this->heute_txt   = (new datum_objekt(             ))->deutsch( "EEEE, d. MMMM YYYY");
    $this->zukunft_txt = (new datum_objekt( "next month"))->deutsch( "MMMM YYYY");
    $this->dieser_monat_txt = $dieser_monat->deutsch( "MMMM YYYY");                     // "März 2016";
    $vormonat   = clone $dieser_monat;
    $nachmonat  = clone $dieser_monat;
    $erster_tag = clone $dieser_monat;
                          $erster_tag->modify( ($erster_tag->format( 'w') < 1) ? 'monday last week' : 'monday this week');
    $this->vormonat_txt  =  $vormonat->modify( "previous month")->deutsch( "MMMM YYYY"); // "Februar 2016";
    $this->nachmonat_txt = $nachmonat->modify( "next month"    )->deutsch( "MMMM YYYY"); // "April 2016";
    $this->vortrag_p_m      = $vortrag_p_m;
    $this->vortrag_verfall  = $vortrag_verfall;
    $this->vier_bis_fünf_wochen = new bis_fünf_wochen( $erster_tag, clone $vortrag_p_m, clone $vortrag_verfall, $this->beschäftigungsumfang, $vier_oder_fünf);
    $this->vortrag_p_m_txt      = $vortrag_p_m;
    $this->vortrag_verfall_txt  = $vortrag_verfall;
    $this->übertrag_txt = $this->vier_bis_fünf_wochen->bilanz_plus_minus;
    $this->verfall_txt  = $this->vier_bis_fünf_wochen->monat_verfall;
  }
  
  function leer() {
    $this->vormonat_txt         = "Monat … … … … … …";
    $this->nachmonat_txt        = "Monat … … … … … …";
    $this->zukunft_txt          = "… … … …";
    $this->übertrag_txt         = "… …";
    $this->verfall_txt          = "… …";
    $this->dieser_monat_txt     = "… … … …";
    $this->heute_txt            = "…";
    $this->vortrag_p_m_txt      = "…";
    $this->vortrag_verfall_txt  = "…";
  }
  
  function __toString() {
    $erg = "";
    $erg .= sprintf( "<h3> $this->anspruchsteller $this->heute_txt </h3>\n");
    $erg .= sprintf( "<h4>Monat $this->dieser_monat_txt </h4>\n");
    $erg .= sprintf( "<h4>Ich mache die bisher angesammelten zusätzlich geleisteten %s Arbeitsstunden und die Verfallszeit %s Stunden geltend.</h4>\n",
                       $this->übertrag_txt, $this->verfall_txt);
    $erg .= sprintf( "Zusammenfassung der detaillierten Auflistung %s %s von %s<br />\n schriftlich geltend gemacht am %s\n",
                       $this->za_liste, $this->dieser_monat_txt, $this->anspruchsteller, $this->heute_txt);
    $erg .= sprintf( "<table  cellspacing='0' cellpadding='2' border='1'>\n");
    $erg .= sprintf( "<tr> "
                           . "<td colspan='6'> "
                           . "<td> %s"
                           . "<td> %s"
                           . "<td class='breit'> Vortrag von %s "
                           . "</tr>\n", $this->vortrag_p_m_txt, $this->vortrag_verfall_txt, $this->vormonat_txt);
    $erg .= sprintf( "<tr>"
                           . "\n  <th class='bodenlos' colspan=2> Woche "
                           . "\n  <th class='bodenlos'> Reine "
                           . "\n  <th class='bodenlos'> Spät "
                           . "\n  <th class='bodenlos'> Nacht "
                           . "\n  <th class='bodenlos'> Gesamte "
                           . "\n  <th class='bodenlos'> Plus/Minus über %sh"
                           . "\n  <th class='bodenlos'> Verfall "
                           . "\n  </tr>\n",
                           $this->beschäftigungsumfang);
      $erg .= sprintf( "<tr>"
                           . "\n  <th class='bodenlos'> von "
                           . "\n  <th class='bodenlos'> bis "
                           . "\n  <th class='bodenlos'> Arbeitszeit "
                           . "\n  <th class='bodenlos'> %s "
                           . "\n  <th class='bodenlos'> %s "
                           . "\n  <th class='bodenlos'> Arbeitszeit "
                           . "\n  <th class='bodenlos'> in dieser Woche  "
                           . "\n  <th class='bodenlos'>  "
                           . "\n</tr>\n", "20%", "50%");
      $erg .= sprintf( "<tr>"
                             . "<th>  "
                             . "<th>  "
                             . "<th> h "
                             . "<th> h "
                             . "<th> h "
                             . "<th> h "
                             . "<th> h "
                             . "<th> h "
                             . "</tr>\n");
      $erg .= $this->vier_bis_fünf_wochen;
      $erg .= sprintf( "<tr> "
                             . "<td colspan='6'> bis %s angesammelt "
                             . "<td> %s"
                             . "<td> %s"
                             . "<td class='breit'> Übertrag nach %s "
                             . "</tr>\n", $this->dieser_monat_txt, $this->übertrag_txt, $this->verfall_txt, $this->nachmonat_txt);
      $erg .= sprintf( "</table>\n");
      $erg .= sprintf( "<h4>Die bisher angesammelten zusätzlich geleisteten %s Arbeitsstunden und die Verfallszeit %s h</h4>\n",
                             $this->übertrag_txt, $this->verfall_txt);
      $erg .= sprintf( "<h4>sind in die $this->za_liste $this->dieser_monat_txt einzutragen</h4>\n");
      $erg .= sprintf( "<h4>oder mit der nächsten Verdienstabrechnung $this->zukunft_txt zu bezahlen.</h4>\n");
      $erg .= sprintf( "<hr>\n");
      return $erg;
  }
}
?>
</body>
</html>
