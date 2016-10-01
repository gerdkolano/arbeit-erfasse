<?php
require_once( "../include/datum.php");

class stunde {
  private $wert;
  function __construct( $wert) { $this->wert = $wert; } 
  function __toString()        { return sprintf(  "%5.2f", $this->wert / 100.0); } 
  function mit_vorzeichen()    { return str_replace( "-", "⁒ ", sprintf( "%+5.2f", $this->wert / 100.0)); } //   ⁒ 
  function add( stunde $arg)   { return new stunde( $this->wert + $arg->wert); }
  function inc( stunde $arg)   { $this->wert += $arg->wert; }
  function sub( stunde $arg)   { return new stunde( $this->wert - $arg->wert); } 
  function div(        $arg)   { return new stunde( $this->wert / $arg      ); } 
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
  private $woche_über_37        ;

  function __toString() {
    if ($this->woche_gfos->lt0()) {  // Die Daten sind ungültig, damit ein leeres Formular entsteht
    $erg = "";
    $erg .= "<td>" ;
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
    $erg .= "<td>" . $this->woche_plus_minus->mit_vorzeichen() ;
    $erg .= "<td>" . $this->woche_verfall    ;
    $erg .= "<td>" . $this->woche_über_37    ;
    return $erg;
  }

  function set_start(                 $starttag             ) { $this->starttag              = $starttag  ;}
  function set_stop(                  $stoptag              ) { $this->stoptag               = $stoptag   ;}
  function set_über_37(               $arg                  ) { $this->woche_über_37         = $arg       ;}
  function vermindere_plus_minus( $arg ) {
    return $this->woche_plus_minus      = $this->woche_gesamt->sub( $arg);
  }

  function __construct( stunde $woche_gfos, stunde $woche_20_gfos, stunde $woche_50_gfos, stunde $woche_verfall) {
    $this->woche_gfos            = $woche_gfos           ;
    $this->woche_20_gfos         = $woche_20_gfos        ;
    $this->woche_50_gfos         = $woche_50_gfos        ;
    $this->woche_verfall         = $woche_verfall        ;
    $this->woche_gesamt          = $woche_gfos->add( $woche_20_gfos)->add( $woche_50_gfos);  // Reine Arbeitszeit + Zuschläge
  } 

}

class bis_zu_fünf_wochen {
  private $vier_bis_fünf_wochen ;
  private $vortrag_p_m          ;
  private $monat_gfos           ;
  private $monat_20_gfos        ;
  private $monat_50_gfos        ;
  public  $monat_verfall        ;
  public  $monat_über_37        ;
  public  $bilanz_verfall       ;
  public  $bilanz_plus_minus    ;
  private $summen               ;
  public  $angesammelt_p_m_übertrag ;
  public  $angesammelt_37_übertrag  ;
  
  function __toString() {
    $erg = "";
    foreach ( $this->vier_bis_fünf_wochen as $key=>$eine_woche) {       // Ausgabe je einer Woche
      $erg .= "<tr>$eine_woche</tr>\n";
    }
    if (true) $erg .= "<tr>$this->summen    </tr>\n";                   // Ausgabe der monatlichen Summenzeile
    return "$erg";
  }
  
  function __construct(
      datum_objekt $erster_tag,
      stunde $vortrag_p_m,
      stunde $vortrag_verfall,
      stunde $vortrag_über_37,
      stunde $beschäftigungsumfang,
      array $vier_bis_fünf_wochen                                         // Enthält in je "eine_woche" die Daten dieser Woche
  ) {
    $start_tag = clone $erster_tag;
    $stopp_tag = clone $erster_tag;
    $stopp_tag->modify( "+5 day");
#   echo "S020 " . $start_tag->format("Y-m-d\n") . $vortrag_p_m . "! " . $vortrag_verfall . "! ";
    $bumfang              = new stunde( 0);
#   $tumfang              = new stunde( 0);
#   $this->monat_gfos     = $vortrag_p_m;
    $this->monat_gfos     = new stunde( 0);
    $this->monat_20_gfos  = new stunde( 0);
    $this->monat_50_gfos  = new stunde( 0);
    $this->monat_verfall  = new stunde( 0);
    $this->bilanz_verfall = $vortrag_verfall;
  
    $this->vier_bis_fünf_wochen = $vier_bis_fünf_wochen;
    $this->monat_über_37 = new stunde( 0);

    foreach ( $this->vier_bis_fünf_wochen as $eine_woche) {    // Füge jeder $eine_woche die zu druckenden Daten hinzu
      $eine_woche->vermindere_plus_minus( $beschäftigungsumfang);
      $tarifliche_wochenarbeitszeit = new stunde( 3700);
      $std_über_37 = $eine_woche->woche_gfos->lt( $tarifliche_wochenarbeitszeit)
        ? new stunde( 0)
        : $eine_woche->woche_gfos->sub( $tarifliche_wochenarbeitszeit)->div( 4) // Mehrarbeitszuschlag 25 Prozent 25%
        ;
      $this->monat_über_37->inc( $std_über_37); 
      $eine_woche->set_über_37(  $std_über_37);
      $eine_woche->set_start( $start_tag->format( "Y-m-d"));
      $eine_woche->set_stop ( $stopp_tag->format( "Y-m-d"));
                              $start_tag->modify( "next week");
                              $stopp_tag->modify( "+1 week");
      $this->monat_verfall  ->inc( $eine_woche->woche_verfall); 
      $this->bilanz_verfall ->inc( $eine_woche->woche_verfall); 
      $this->monat_gfos     ->inc( $eine_woche->woche_gfos   ); 
      $this->monat_20_gfos  ->inc( $eine_woche->woche_20_gfos); 
      $this->monat_50_gfos  ->inc( $eine_woche->woche_50_gfos); 
      $bumfang->inc( $beschäftigungsumfang);                  // für $this->summen muss je Woche 33.3 abgezogen werden 
#     $tumfang->inc( new stunde( 3700));                      // für $this->summen muss je Woche 37.0 abgezogen werden 
    }
    $this->summen = new eine_woche(
      $this->monat_gfos    ,
      $this->monat_20_gfos ,
      $this->monat_50_gfos ,
      $this->monat_verfall  
#     $this->bilanz_verfall  
#   , $bumfang
    );    
    $this->summen->set_über_37( $this->monat_über_37);
    $this->summen->set_start(   "Sum-"              );
    $this->summen->set_stop (   "men"               );
#   $this->summen->woche_verfall = new stunde( 1608);
    $this->angesammelt_p_m_übertrag = $this->summen->vermindere_plus_minus( $bumfang);
    $this->bilanz_plus_minus = $this->summen->woche_plus_minus;
#   echo "<pre>eine_woche "; print_r( $this->summen); echo "</pre>";
#   echo "<pre>4-5_wochen "; print_r( $this); echo "</pre>";
  }
  
}

class formular_monat {
  private $anspruchsteller = "Sabine Schallehn";
  private $name_der_za_liste        = "ZA-Liste (neu:gfos 4.7plus Zeitkonto)";
  private $heute_txt                 ;
  private $vormonat_txt              ;
  private $dieser_monat_txt          ;
  private $nachmonat_txt             ;
  private $vortrag_p_m               ;
  private $übertrag                  ;
  private $verfall                   ;
  private $beschäftigungsumfang      ;
  private $prämie_in_std             ;
  private $prämie_in_std_txt            ;
  private $verkaufsstellenprämie_txt ;
  private $stundenlohn               ;
  private $auszahlung_arr            ;
  
  private $bis_zu_fünf_ausgabe;
  
  function __construct(
      datum_objekt $dieser_monat   ,
      stunde $vortrag_p_m          ,
      stunde $vortrag_verfall      ,
      stunde $vortrag_über_37      ,
      stunde $prämie_in_std        ,
      array $auszahlung_arr        ,
      $verkaufsstellenprämie_txt   ,
      $stundenlohn                 ,
      stunde $beschäftigungsumfang ,
      array $vier_oder_fünf        ,// Durchreichen an bis_zu_fünf_wochen // Enthält in je "eine_woche" die Daten dieser Woche
      $debug = false
  ) {
    #   echo "<pre>vier_oder_fünf "; print_r( $vier_oder_fünf); echo "</pre>";
    $this->mit_kontrolle = false; 
    $erster_tag                      = clone $dieser_monat;     // Der Tag bleibt unbeachtet, nur der Monat wird verwertet
    $erster_tag                      ->modify( ($erster_tag->format( 'w') < 1) ? 'monday last week' : 'monday this week');
    $this->heute_txt                 = (new datum_objekt(             ))->deutsch( "EEEE, d. MMMM YYYY");
    $this->zukunft_txt               = (new datum_objekt( "next month"))->deutsch( "MMMM YYYY");
    $dieser_monat                    = $dieser_monat->modify( "+1 week"); // $dieser_monat ist der Anfang der Abrechnungsperiode und
                                                                          // liegt vielleicht im vorangegangenen Monat
    $vormonat                        = clone $dieser_monat;
    $nachmonat                       = clone $dieser_monat;
    $this->dieser_monat_txt          = $dieser_monat->deutsch( "MMMM YYYY");                          // "März 2016";
    $this->vormonat_txt              = $vormonat->modify( "previous month")->deutsch( "MMMM YYYY");   // "Februar 2016";
    $this->nachmonat_txt             = $nachmonat->modify( "next month"    )->deutsch( "MMMM YYYY");  // "April 2016";
    $this->vortrag_p_m               = $vortrag_p_m;
    $this->vortrag_verfall           = $vortrag_verfall;
    $this->beschäftigungsumfang      = $beschäftigungsumfang;
                                                                                     // Be-Rechne :
    $this->bis_zu_fünf_ausgabe         = new bis_zu_fünf_wochen(                                  // Hole die Ausgabe von 4 bis 5 Wochen
      $erster_tag,
      clone $vortrag_p_m,
      clone $vortrag_verfall,
      clone $vortrag_über_37,
      $this->beschäftigungsumfang,
      $vier_oder_fünf
    );
    $this->vortrag_p_m_txt           = $vortrag_p_m->mit_vorzeichen()             ;
    $this->vortrag_verfall_txt       = $vortrag_verfall                           ;
    $this->übertrag_verfall_txt      = $this->bis_zu_fünf_ausgabe->bilanz_verfall ;
    $this->vortrag_über_37_txt       = $vortrag_über_37                           ;
    $this->übertrag_über_37_txt      = $vortrag_über_37->add( $this->bis_zu_fünf_ausgabe->monat_über_37) ;
    $this->prämie_in_std             = $prämie_in_std                             ;
    $this->prämie_in_std_txt         = $prämie_in_std->mit_vorzeichen()              ;
    $this->verkaufsstellenprämie_txt = $verkaufsstellenprämie_txt                 ;
    $this->stundenlohn               = $stundenlohn                               ;

    $this->auszahlung_arr = $auszahlung_arr;

    $bb_plus_vv         = $this->bis_zu_fünf_ausgabe->bilanz_plus_minus->add($vortrag_p_m);
    $bb_plus_pp         = $this->bis_zu_fünf_ausgabe->bilanz_plus_minus->add($prämie_in_std );
    $bb_plus_pp_plus_vv = $bb_plus_pp                                  ->add($vortrag_p_m );

    if ($debug) {
      echo $dieser_monat->format( "y-m-d") . "<strong> erfasse/form-monat.php</strong><br />";
      echo "bb    " . $this->bis_zu_fünf_ausgabe->bilanz_plus_minus . " Summe der Überschreitungen von 3330 in einer 4-5-Wochen-Periode<br />";
      echo "pp    " . $prämie_in_std                                . " Vst-Prämie prämie_in_std<br />";
      echo "vv    " . $vortrag_p_m                                  . " zeitkonto.php vortrag_p_m <br />";
      echo "bb+vv " . $bb_plus_vv                                   . "<br />";
      echo "bb+pp " . $bb_plus_pp                                   . "<br />";
    }
                                                                // datenbestand.php
    if ($this->bis_zu_fünf_ausgabe->bilanz_plus_minus->lt0()) { // Summe der Überschreitungen von 3330 in einer 4-5-Wochen-Periode
      $übertrag_plus_minus   = $bb_plus_vv;
    } else {
      if ($bb_plus_pp->lt0()) {
        $übertrag_plus_minus   = $this->vortrag_p_m;
      } else {
        $übertrag_plus_minus   = $bb_plus_pp_plus_vv;
      }
    }
    if (count( $this->auszahlung_arr) > 0) {
      $this->übertrag_über_37_txt =
      $this->übertrag_über_37_txt->add( $this->auszahlung_arr["ang_25_ausz"]);
      $this->spät_plus_nacht = $this->auszahlung_arr["spaet_20_ausz"]->add( $this->auszahlung_arr["nacht_50_ausz"]);
      $übertrag_plus_minus->inc( $this->spät_plus_nacht);
    }
    $this->übertrag_plus_minus_txt = $übertrag_plus_minus->mit_vorzeichen();
    /*
     * prämie_in_std is negativ,  angesammelter Übertrag ist positiv
     * Übertrag = Vortrag wenn angesammelter Übertrag              < 0 - prämie_in_std
     * Übertrag = Vortrag wenn angesammelter Übertrag + prämie_in_std < 0
      if ($this->p_m_übertrag->lt0()) $this->p_m_übertrag = new stunde( 0); // kein negativer Übertrag
    echo "<pre> prämie_in_std  "; print_r( $prämie_in_std)                                    ; echo "</pre>\n";
    echo "<pre> angesammelt "; print_r( $this->bis_zu_fünf_ausgabe->angesammelt_p_m_übertrag) ; echo "</pre>\n";
     Die Abgeltung " durch Vst-Prämie 222.03€/15.51€/2 für Januar 2016 prämie_in_std" darf nur dann abgezogen werden,
     wenn die Firma etwas zahlen müsste, wenn also
     $this->bis_zu_fünf_ausgabe->angesammelt_p_m_übertrag > 0
     ist.
    * */
#   if ($this->bis_zu_fünf_ausgabe->angesammelt_p_m_übertrag->add( $prämie_in_std)->lt0()) { 
#     $this->übertrag_plus_minus_txt   = $this->vortrag_p_m->mit_vorzeichen()  ;
#   } else {
#     $this->übertrag_plus_minus_txt   = $this->p_m_übertrag->mit_vorzeichen() ;
#   }
#     $this->übertrag_plus_minus_txt   = $this->p_m_übertrag->mit_vorzeichen() ;

  }
  
  function leer() {
    $this->vormonat_txt              = "Monat … … … … … …";
    $this->nachmonat_txt             = "Monat … … … … … …";
    $this->zukunft_txt               = "… … … …";
    $this->übertrag_plus_minus_txt   = "… …";
    $this->übertrag_verfall_txt      = "… …";
    $this->dieser_monat_txt          = "… … … …";
    $this->heute_txt                 = "…";
    $this->vortrag_p_m_txt           = "…";
    $this->vortrag_verfall_txt       = "…";
    $this->vortrag_über_37_txt       = "…";
    $this->prämie_in_std_txt         = "";
    $this->verkaufsstellenprämie_txt = "… …";
  }
  
  function __toString() {                                                           // Ausgabe der Monatstabelle
    $erg = "";
    $erg .= sprintf( "<h3> %s Monat %s </h3>\n", $this->anspruchsteller, $this->dieser_monat_txt);
#   $erg .= sprintf( "<h4>Berlin-Lichtenrade %s </h4>\n", $this->heute_txt);
    $erg .= sprintf( "<h4>Ich mache die bisher angesammelten zusätzlich geleisteten %s Arbeitsstunden und die Verfallszeit %s Stunden geltend.</h4>\n",
                       $this->übertrag_plus_minus_txt, $this->übertrag_verfall_txt);
    $erg .= sprintf( "Zusammenfassung der detaillierten Auflistung %s %s von %s<br />\n schriftlich geltend gemacht am %s\n",
                       $this->name_der_za_liste, $this->dieser_monat_txt, $this->anspruchsteller, $this->heute_txt);
    $erg .= sprintf( "<table  cellspacing='0' cellpadding='2' border='1'>\n");
    $erg .= sprintf( "<tr> "
                           . "<td colspan='6'> "
                           . "<td> %s" . ($this->mit_kontrolle ? " vv" : "")
                           . "<td> %s"
                           . "<td> %s"
                           . "<td class='breit'> Vortrag von %s "
                           . "</tr>\n", $this->vortrag_p_m_txt, $this->vortrag_verfall_txt, $this->vortrag_über_37_txt, $this->vormonat_txt);
    $erg .= sprintf( "<tr>"
                           . "\n  <th class='bodenlos' colspan=2> Woche "
                           . "\n  <th class='bodenlos'> Reine "
                           . "\n  <th class='bodenlos'> Spät zuschlag "
                           . "\n  <th class='bodenlos'> Nacht zuschlag "
                           . "\n  <th class='bodenlos'> Gesamte "
                           . "\n  <th class='bodenlos'> Plus/Minus über %sh"
                           . "\n  <th class='bodenlos'> Verfallene Zeiten "
                           . "\n  <th class='bodenlos'> über %sh je Woche "
                           . "\n  </tr>\n",
                           $this->beschäftigungsumfang, "37.00"); // Tarifliche Arbeitszeit
      $erg .= sprintf( "<tr>"
                           . "\n  <th class='bodenlos'> von "
                           . "\n  <th class='bodenlos'> bis "
                           . "\n  <th class='bodenlos'> Arbeitszeit "
                           . "\n  <th class='bodenlos'> %s "
                           . "\n  <th class='bodenlos'> %s "
                           . "\n  <th class='bodenlos'> Arbeitszeit "
                           . "\n  <th class='bodenlos'> in dieser Woche  "
                           . "\n  <th class='bodenlos'>  "
                           . "\n  <th class='bodenlos'> %s "
                           . "\n</tr>\n", "20%", "50%", "25%");
      $erg .= sprintf( "<tr>"
                             . "<th>  "
                             . "<th>  "
                             . "<th> h "
                             . "<th> h "
                             . "<th> h "
                             . "<th> h "
                             . "<th> h "
                             . "<th> h "
                             . "<th> h "
                             . "</tr>\n");
      $erg .= $this->bis_zu_fünf_ausgabe;                                                      // Ausgabe von 4 bis 5 Wochen

      if (count( $this->auszahlung_arr) > 0) {
      $erg .= sprintf( "<tr> "
                             . "<td colspan='3'> Auszahlung"
                             . "<td> %s"
                             . "<td> %s"
                             . "<td>"
                             . "<td> %s"
                             . "<td>"
                             . "<td> %s"
                             . "</tr>\n",
                                          $this->auszahlung_arr["spaet_20_ausz"]->mit_vorzeichen(),
                                          $this->auszahlung_arr["nacht_50_ausz"]->mit_vorzeichen(),
                                          $this->spät_plus_nacht->mit_vorzeichen(),
                                          $this->auszahlung_arr["ang_25_ausz"  ]->mit_vorzeichen()
                                        );
      }
      $erg .= sprintf( "<tr> "
                             . "<td colspan='6'> durch Vst-Prämie %s€/%s€/2=%s für %s abgegolten "
                             . "<td> %s"
                             . "<td>"
                             . "<td>"
                             . "<td class='links'>" . ($this->mit_kontrolle ? " pp" : "")
                             . "</tr>\n",
                                          $this->verkaufsstellenprämie_txt,
                                          $this->stundenlohn,
                                          $this->prämie_in_std,
                                          $this->dieser_monat_txt,
                                          $this->prämie_in_std_txt
                                     );
      $erg .= sprintf( "<tr> "
                             . "<td colspan='6'> bis %s angesammelt "
                             . "<td> %s"
                             . "<td> %s"
                             . "<td> %s"
                             . "<td class='breit'> Übertrag nach %s "
                             . "</tr>\n", $this->dieser_monat_txt, $this->übertrag_plus_minus_txt, $this->übertrag_verfall_txt, $this->übertrag_über_37_txt, $this->nachmonat_txt);
      $erg .= sprintf( "</table>\n");
      $erg .= sprintf( "<h4>Die bisher angesammelten zusätzlich geleisteten %s Arbeitsstunden und die Verfallszeit %s h</h4>\n",
                             $this->übertrag_plus_minus_txt, $this->übertrag_verfall_txt, $this->übertrag_über_37_txt);
      $erg .= sprintf( "<h4>sind in die $this->name_der_za_liste $this->dieser_monat_txt einzutragen</h4>\n");
      $erg .= sprintf( "<h4>oder mit der nächsten Verdienstabrechnung $this->zukunft_txt zu bezahlen.</h4>\n");
      $erg .= sprintf( "<hr>\n");
      return $erg;
  }
}
?>
