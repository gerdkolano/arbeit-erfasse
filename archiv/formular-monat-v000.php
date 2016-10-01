<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css-formblatt.css" type="text/css">
</head>
<body>
<?php
require_once( "datum.php");

$formular_monat =  new formular_monat( new datum_objekt( "2016-03-01"), new stunde( 14128), new stunde( 1000), new stunde( 3330));

echo $formular_monat->html();

class stunde {
  private $wert;
  function __construct( $wert) { $this->wert = $wert; } 
  function __toString()        { return sprintf( "%05.2f", $this->wert / 100.0); } 
  function add( stunde $arg)   { return new stunde( $this->wert +  $arg->wert); }
  function inc( stunde $arg)   { $this->wert += $arg->wert;}
  function sub( stunde $arg)   { return new stunde( $this->wert -  $arg->wert); } 
}

class eine_woche {
  private $beschäftigungsumfang ;
  private $starttag                ;
  private $stoptag                ;
  public  $woche_gfos           ;
  public  $woche_20_gfos        ;
  public  $woche_50_gfos        ;
  public  $woche_verfall        ;
  public  $woche_plus_minus     ;
  private $woche_gesamt         ;

  function __toString() {
    $erg = "";
    $erg .= "<td>" . $this->starttag            ;
    $erg .= "<td>" . $this->stoptag            ;
    $erg .= "<td>" . $this->woche_gfos       ;
    $erg .= "<td>" . $this->woche_20_gfos    ;
    $erg .= "<td>" . $this->woche_50_gfos    ;
    $erg .= "<td>" . $this->woche_gesamt     ;
    $erg .= "<td>" . $this->woche_plus_minus ;
    $erg .= "<td>" . $this->woche_verfall    ;
    return $erg;
  }

  function __construct( $woche, stunde $woche_gfos, stunde $woche_20_gfos, stunde $woche_50_gfos, stunde $woche_verfall, stunde $beschäftigungsumfang) {
    $this->beschäftigungsumfang  = $beschäftigungsumfang ;
    $this->starttag                 = $woche                ;
    $this->stoptag                 = $woche                ;
    $this->woche_gfos            = $woche_gfos           ;
    $this->woche_20_gfos         = $woche_20_gfos        ;
    $this->woche_50_gfos         = $woche_50_gfos        ;
    $this->woche_verfall         = $woche_verfall        ;
    $this->woche_gesamt          = $woche_gfos->add( $woche_20_gfos)->add( $woche_50_gfos);
    $this->woche_plus_minus      = $this->woche_gesamt->sub( $this->beschäftigungsumfang);
  } 

}

class vier_bis_fünf_wochen {
  private $beschäftigungsumfang ;
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
  
  function __construct( datum_objekt $erster_tag, stunde $vortrag_p_m, stunde $vortrag_verfall, stunde $beschäftigungsumfang) {
    echo "S020 " . $erster_tag->format("Y-m-d\n") . $vortrag_p_m . "! " . $vortrag_verfall . "! ";
    $this->beschäftigungsumfang  = $beschäftigungsumfang;
    $bu                  = new stunde( 0);
    $this->monat_gfos    = $vortrag_p_m;
    $this->monat_20_gfos = new stunde( 0);
    $this->monat_50_gfos = new stunde( 0);
    $this->monat_verfall = new stunde( 0);
    $this->monat_verfall = $vortrag_verfall;
  
    $this->vier_bis_fünf_wochen = array (
       new eine_woche( "29.02.16", new stunde( 3559), new stunde(  109), new stunde(  198), new stunde(    2), $this->beschäftigungsumfang),
       new eine_woche( "07.03.16", new stunde( 3447), new stunde(   80), new stunde(  195), new stunde(    7), $this->beschäftigungsumfang),
       new eine_woche( "14.03.16", new stunde( 3143), new stunde(  140), new stunde(  197), new stunde(    0), $this->beschäftigungsumfang),
       new eine_woche( "21.03.16", new stunde( 3681), new stunde(    0), new stunde(    0), new stunde(    0), $this->beschäftigungsumfang),
       new eine_woche( "28.03.16", new stunde( 3396), new stunde(   58), new stunde(  126), new stunde(    0), $this->beschäftigungsumfang) 
    );
    foreach ( $this->vier_bis_fünf_wochen as $key=>$val) {
      $this->monat_verfall->inc( $val->woche_verfall); 
      $this->monat_gfos   ->inc( $val->woche_gfos   ); 
      $this->monat_20_gfos->inc( $val->woche_20_gfos); 
      $this->monat_50_gfos->inc( $val->woche_50_gfos); 
      $bu->inc( $this->beschäftigungsumfang); 
    }
    $this->summen = new eine_woche( "Summa",
      $this->monat_gfos    ,
      $this->monat_20_gfos ,
      $this->monat_50_gfos ,
      $this->monat_verfall ,
      $bu
    );
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

function __construct( datum_objekt $dieser_monat, stunde $vortrag_p_m, stunde $vortrag_verfall, stunde $beschäftigungsumfang) {
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
  $this->beschäftigungsumfang  = $beschäftigungsumfang;
  $this->vier_bis_fünf_wochen = new vier_bis_fünf_wochen( $erster_tag, clone $vortrag_p_m, clone $vortrag_verfall, $this->beschäftigungsumfang);
  $this->übertrag = $this->vier_bis_fünf_wochen->bilanz_plus_minus;
  $this->verfall  = $this->vier_bis_fünf_wochen->monat_verfall;
}

function html() {
  $erg = "";
  $erg .= sprintf( "<h3> $this->anspruchsteller $this->heute_txt </h3>\n");
  $erg .= sprintf( "<h4>Monat $this->dieser_monat_txt </h4>\n");
  $erg .= sprintf( "<h4>Ich mache die bisher angesammelten zusätzlich geleisteten %s Arbeitsstunden und die Verfallszeit %s Stunden geltend.</h4>\n",
                     $this->übertrag, $this->verfall);
  $erg .= sprintf( "Zusammenfassung der detaillierten Auflistung %s %s von %s<br />\n schriftlich geltend gemacht am %s\n",
                     $this->za_liste, $this->dieser_monat_txt, $this->anspruchsteller, $this->heute_txt);
  $erg .= sprintf( "<table  cellspacing='0' cellpadding='2' border='1'>\n");
  $erg .= sprintf( "<tr> "
                         . "<td colspan='7'> %s "
                         . "<td> %s"
                         . "<td class='breit'> Vortrag von %s "
                         . "</tr>\n", $this->vortrag_p_m, $this->vortrag_verfall, $this->vormonat_txt);
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
                           . "<td colspan='7'> bis %s angesammelt %s "
                           . "<td> %s"
                           . "<td class='breit'> Übertrag nach %s "
                           . "</tr>\n", $this->dieser_monat_txt, $this->übertrag, $this->verfall, $this->nachmonat_txt);
    $erg .= sprintf( "</table>\n");
    $erg .= sprintf( "<h4>Die bisher angesammelten zusätzlich geleisteten %s Arbeitsstunden und die Verfallszeit %s h</h4>\n",
                           $this->übertrag, $this->verfall);
    $erg .= sprintf( "<h4>sind in die $this->za_liste $this->dieser_monat_txt einzutragen</h4>\n");
    $erg .= sprintf( "<h4>oder mit der nächsten Verdienstabrechnung $this->zukunft_txt zu bezahlen.</h4>\n");
    $erg .= sprintf( "<hr>\n");
    return $erg;
  }
}
?>
</body>
</html>
