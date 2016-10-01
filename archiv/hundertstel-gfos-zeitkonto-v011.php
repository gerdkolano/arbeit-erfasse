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
    $erg .= "<link rel=\"stylesheet\" href=\"arbeit-erfasse.css\" type=\"text/css\">\n";
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
    $this->mein_format  = $mein_format ;
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

class meine_monatliche_zusammenfassung {
  public $woche                    ;
  public $woche_gfos               ;
  public $woche_echt               ;
  public $woche_summe_zwanzig_gfos ;
  public $woche_summe_fünfzig_gfos ;
  public $woche_summe_zwanzig_echt ;
  public $woche_summe_fünfzig_echt ;
  public $beschäftigungsumfang ;

  function __construct (
    DateTime $woche            ,
    $woche_gfos                ,
    $woche_echt                ,
    $woche_summe_zwanzig_gfos  ,
    $woche_summe_fünfzig_gfos  , 
    $woche_summe_zwanzig_echt  ,
    $woche_summe_fünfzig_echt  , 
    $beschäftigungsumfang   
  ) {
    $this->woche                     = $woche                     ;
    $this->woche_gfos                = $woche_gfos                ;
    $this->woche_echt                = $woche_echt                ;
    $this->woche_summe_zwanzig_gfos  = $woche_summe_zwanzig_gfos  ;
    $this->woche_summe_fünfzig_gfos  = $woche_summe_fünfzig_gfos  ;
    $this->woche_summe_zwanzig_echt  = $woche_summe_zwanzig_echt  ;
    $this->woche_summe_fünfzig_echt  = $woche_summe_fünfzig_echt  ;
    $this->beschäftigungsumfang = $beschäftigungsumfang ;
  }

  function verlust_durch_verfall() {
    return $this->woche_echt - $this->woche_echt;
  }

  function plus_minus_echt() {
    return
      $this->woche_summe_zwanzig_echt
    + $this->woche_summe_fünfzig_echt
    + $this->woche_echt
    - $this->beschäftigungsumfang
    ;    
  }

  function toTR_woche_von_bis() {
    $wochenarbzeit_gfos
      = $this->woche_summe_zwanzig_gfos
      + $this->woche_summe_fünfzig_gfos
      + $this->woche_gfos
      ;
    $wochenarbzeit_echt
      = $this->woche_summe_zwanzig_echt
      + $this->woche_summe_fünfzig_echt
      + $this->woche_echt
      ;
    $plus_minus = $this-> plus_minus_echt();

    $start_woche = clone $this->woche;
    $stopp_woche = clone $this->woche;
    $start_woche->sub( new DateInterval( 'P6D')); 
    $stopp_woche->sub( new DateInterval( 'P1D')); 
    return sprintf( "<td>von %s bis %s <td> %05.2f  = ( %05.2f + %05.2f + %05.2f) <td class=\"right\"> %+05.2f",
      $start_woche->format( "d.m.y")           , 
      $stopp_woche->format( "d.m.y")           , 
      $wochenarbzeit_echt              / 100.0 ,
      $this->woche_echt                / 100.0 ,
      $this->woche_summe_zwanzig_echt  / 100.0 ,
      $this->woche_summe_fünfzig_echt  / 100.0 ,
      $this->plus_minus_echt()         / 100.0  
    );
  }

  function toTR_plus_minus() {
    $plus_minus = $this-> plus_minus_echt();

    return sprintf( "<td class=\"right\"> %+05.2f",
      $this->plus_minus_echt()       / 100.0  
    );
  }

  function toString() {
    $wochenarbzeit_echt
      = $this->woche_summe_zwanzig_echt
      + $this->woche_summe_fünfzig_echt
      + $this->woche_echt
      ;
    $plus_minus = $this-> plus_minus_echt();
    $verlust_durch_verfall = $this->verlust_durch_verfall();

    $start_woche = clone $this->woche;
    $stopp_woche = clone $this->woche;
    $start_woche->sub( new DateInterval( 'P6D')); 
    $stopp_woche->sub( new DateInterval( 'P1D')); 
    return sprintf( "%s bis %s : %05.2f  = ( %05.2f + %05.2f + %05.2f). %+05.2f  ## Verlust durch Verfall: %05.2f ",
      $start_woche->format( "d.m.y")         , 
      $stopp_woche->format( "d.m.y")         , 
      $wochenarbzeit_echt              / 100.0 ,
      $this->woche_echt                / 100.0 ,
      $this->woche_summe_zwanzig_echt  / 100.0 ,
      $this->woche_summe_fünfzig_echt  / 100.0 ,
      $this->plus_minus_echt()         / 100.0 ,
      $this->verlust_durch_verfall()   / 100.0  
    );
  }
}

class salden  {
  public $beschäftigungsumfang        ;
  public $echt                        ;  // Nichts verfällt
  public $kum                         ;  // Nicht autorisierte Überziehungszeiten vefallen
  public $ewig_summe_ist              ;
  public $ewig_summe_soll             ;

  public $heute_summe_zwanzig_gfos    ;
  public $heute_summe_fünfzig_gfos    ;
  public $woche_summe_zwanzig_gfos    ;
  public $woche_summe_fünfzig_gfos    ;
  public $monat_summe_zwanzig_gfos    ;
  public $monat_summe_fünfzig_gfos    ;
  public $ewig_summe_zwanzig_gfos     ;
  public $ewig_summe_fünfzig_gfos     ;

  public $heute_summe_zwanzig_echt    ;
  public $heute_summe_fünfzig_echt    ;
  public $woche_summe_zwanzig_echt    ;
  public $woche_summe_fünfzig_echt    ;
  public $monat_summe_zwanzig_echt    ;
  public $monat_summe_fünfzig_echt    ;
  public $ewig_summe_zwanzig_echt     ;
  public $ewig_summe_fünfzig_echt     ;

  public $heute_gfos                  ;
  public $heute_echt                  ;
  public $woche_gfos                  ;
  public $woche_echt                  ;
  public $ewig_gfos                   ;
  public $ewig_echt                   ;
                                    
  public $mache_monatlich_geltend = array()     ;
  public $ewig_verlust_durch_verfall  ;
  public $ewig_plus_minus_echt        ;

  public function __construct( $beschäftigungsumfang) {
    $this->beschäftigungsumfang      = $beschäftigungsumfang;
    $this->kum                       = 0;
    $this->echt                      = 0;
    $this->ewig_summe_ist            = 0;
    $this->ewig_summe_soll           = 0;

    $this->heute_summe_zwanzig_gfos  = 0;
    $this->heute_summe_fünfzig_gfos  = 0;
    $this->woche_summe_zwanzig_gfos  = 0;
    $this->woche_summe_fünfzig_gfos  = 0;
    $this->monat_summe_zwanzig_gfos  = 0;
    $this->monat_summe_fünfzig_gfos  = 0;
    $this->ewig_summe_zwanzig_gfos   = 0;
    $this->ewig_summe_fünfzig_gfos   = 0;

    $this->heute_summe_zwanzig_echt  = 0;
    $this->heute_summe_fünfzig_echt  = 0;
    $this->woche_summe_zwanzig_echt  = 0;
    $this->woche_summe_fünfzig_echt  = 0;
    $this->monat_summe_zwanzig_echt  = 0;
    $this->monat_summe_fünfzig_echt  = 0;
    $this->ewig_summe_zwanzig_echt   = 0;
    $this->ewig_summe_fünfzig_echt   = 0;

    $this->woche_gfos                = 0;
    $this->woche_echt                = 0;
    $this->heute_gfos                = 0;
    $this->heute_echt                = 0;
    $this->mache_monatlich_geltend = array()      ;
    $this->ewig_verlust_durch_verfall =0;
    $this->ewig_plus_minus_echt       = $beschäftigungsumfang;
  }

  public function set_ewig_plus_minus_echt( $ewig_plus_minus_echt) {
    $this->ewig_plus_minus_echt = $ewig_plus_minus_echt;
  }

  public function inc_summe_ist( $increment) {
    $this->ewig_summe_ist += $increment;
  }

  public function inc_summe_soll( $increment) {
    $this->ewig_summe_soll += $increment;
  }

  public function inc_summe_ist_und_summe_soll( $summe_ist, $summe_soll) {
    $this->ewig_summe_ist  += $summe_ist;
    $this->ewig_summe_soll += $summe_soll;
  }

  public function inc_zges( $zwanzig_gfos, $fünfzig_gfos, $zwanzig_echt, $fünfzig_echt ) {
    $this->heute_summe_zwanzig_gfos += $zwanzig_gfos;
    $this->heute_summe_fünfzig_gfos += $fünfzig_gfos;
    $this->woche_summe_zwanzig_gfos += $zwanzig_gfos;
    $this->woche_summe_fünfzig_gfos += $fünfzig_gfos;
    $this->monat_summe_zwanzig_gfos += $zwanzig_gfos;
    $this->monat_summe_fünfzig_gfos += $fünfzig_gfos;
    $this->ewig_summe_zwanzig_gfos  += $zwanzig_gfos;
    $this->ewig_summe_fünfzig_gfos  += $fünfzig_gfos;

    $this->heute_summe_zwanzig_echt += $zwanzig_echt;
    $this->heute_summe_fünfzig_echt += $fünfzig_echt;
    $this->woche_summe_zwanzig_echt += $zwanzig_echt;
    $this->woche_summe_fünfzig_echt += $fünfzig_echt;
    $this->monat_summe_zwanzig_echt += $zwanzig_echt;
    $this->monat_summe_fünfzig_echt += $fünfzig_echt;
    $this->ewig_summe_zwanzig_echt  += $zwanzig_echt;
    $this->ewig_summe_fünfzig_echt  += $fünfzig_echt;
  }

  public function set_heute_zges( $zwanzig_gfos, $fünfzig_gfos) {
    $this->heute_summe_zwanzig_gfos  = $zwanzig_gfos;
    $this->heute_summe_fünfzig_gfos  = $fünfzig_gfos;
  }

  public function set_woche_zges( $zwanzig_gfos, $fünfzig_gfos) {
    $this->woche_summe_zwanzig_gfos  = $zwanzig_gfos;
    $this->woche_summe_fünfzig_gfos  = $fünfzig_gfos;
  }

  public function set_monat_zges( $zwanzig_gfos, $fünfzig_gfos) {
    $this->monat_summe_zwanzig_gfos  = $zwanzig_gfos;
    $this->monat_summe_fünfzig_gfos  = $fünfzig_gfos;
  }

  public function set_ewig_zges( $zwanzig_gfos, $fünfzig_gfos) {
    $this->ewig_summe_zwanzig_gfos  = $zwanzig_gfos;
    $this->ewig_summe_fünfzig_gfos  = $fünfzig_gfos;
  }

  public function set_kum_und_echt( $kum, $echt) {
    $this->kum   = $kum;
    $this->echt  = $echt;
  }

  public function set_heute_kum( $kum, $echt) {
    $this->heute_gfos  = $kum;
    $this->heute_echt  = $echt;
  }

  public function set_summe_ist_und_summe_soll( $ist, $soll) {
    $this->ewig_summe_ist  = $ist ;
    $this->ewig_summe_soll = $soll;
  }

  public function dec_salden_kum_und_echt( $kum, $echt) {
    $this->kum                     -= $kum;
    $this->echt                    -= $echt;
  }

  public function inc_salden_kum_und_echt( $kum, $echt) {
    $this->kum         += $kum;
    $this->echt        += $echt;
    $this->heute_gfos  += $kum;
    $this->heute_echt  += $echt;
    $this->woche_gfos  += $kum;
    $this->woche_echt  += $echt;
    $this->ewig_gfos   += $kum;
    $this->ewig_echt   += $echt;
  }

  public function inc_wochensalden( $kum, $echt) {
    $this->woche_gfos += $kum;
    $this->woche_echt += $echt;
  }

  public function reset_heutesalden() {
    $this->heute_gfos                 = 0;
    $this->heute_echt                 = 0;
    $this->heute_summe_zwanzig_gfos   = 0;
    $this->heute_summe_fünfzig_gfos   = 0;
    $this->heute_summe_zwanzig_echt   = 0;
    $this->heute_summe_fünfzig_echt   = 0;
  }

  public function reset_wochensalden() {
    $this->woche_gfos                 = 0;
    $this->woche_echt                 = 0;
    $this->woche_summe_zwanzig_gfos   = 0;
    $this->woche_summe_fünfzig_gfos   = 0;
    $this->woche_summe_zwanzig_echt   = 0;
    $this->woche_summe_fünfzig_echt   = 0;
  }

  public function reset_monatssalden() {
    $this->monat_gfos                 = 0;
    $this->monat_echt                 = 0;
    $this->monat_summe_zwanzig_gfos   = 0;
    $this->monat_summe_fünfzig_gfos   = 0;
    $this->monat_summe_zwanzig_echt   = 0;
    $this->monat_summe_fünfzig_echt   = 0;
  }

  public function reset_ewigsalden() {
    $this->ewig_gfos                 = 0;
    $this->ewig_echt                 = 0;
    $this->ewig_summe_zwanzig_gfos   = 0;
    $this->ewig_summe_fünfzig_gfos   = 0;
    $this->ewig_summe_zwanzig_echt   = 0;
    $this->ewig_summe_fünfzig_echt   = 0;
  }

  public function monatsnr_des_donnerstags( DateTime $woche) {
    $donnerstag = clone $woche;
    $donnerstag->sub( new DateInterval( 'P3D'));
    return $donnerstag->format( "n");
  }

  public function zeige_wochensalden_verbose() {
    $fmt_string = "MMMM yyyy";
    $fmt_monat = new IntlDateFormatter(
      'de-DE',
      IntlDateFormatter::FULL,
      IntlDateFormatter::FULL,
      'Europe/Berlin',
      IntlDateFormatter::GREGORIAN,
      $fmt_string
    );

    printf( "S090 %d<br />\n", count( $this->mache_monatlich_geltend));
    #   printf( "<pre>\n");
    $vormonatsnr = $this->monatsnr_des_donnerstags( $this->mache_monatlich_geltend[0]->woche);
    foreach ($this->mache_monatlich_geltend as $key=>$meine_monatliche_zusammenfassung) {
      $monatsnr = $this->monatsnr_des_donnerstags( $meine_monatliche_zusammenfassung->woche);
      printf( "V010 %d %d ", $vormonatsnr, $monatsnr);
      if ($vormonatsnr != $monatsnr) {
        $vormonatsnr = $monatsnr;      
        printf( "%s Vortrag %+5.2f <br />\n", $fmt_monat-> format( $meine_monatliche_zusammenfassung->woche), $this->ewig_plus_minus_echt/100.0);
      }
      $this->ewig_verlust_durch_verfall  += $meine_monatliche_zusammenfassung->verlust_durch_verfall();
      $this->ewig_plus_minus_echt        += $meine_monatliche_zusammenfassung->plus_minus_echt();
      printf( "%s %+5.2f %+5.2f <br />\n", $meine_monatliche_zusammenfassung->toString(), $this->ewig_plus_minus_echt/100.0, $this->ewig_verlust_durch_verfall/100.0);
    }
  }

  public function zeige_wochensalden( $anspruchsteller) {
    $fmt_string = "MMMM yyyy";
    $fmt_monat = new IntlDateFormatter(
      'de-DE',
      IntlDateFormatter::FULL,
      IntlDateFormatter::FULL,
      'Europe/Berlin',
      IntlDateFormatter::GREGORIAN,
      $fmt_string
    );

    $erg = "";
    $tafel_1 = "";
    $tafel_2 = "";
    $vormonatsnr = $this->monatsnr_des_donnerstags( $this->mache_monatlich_geltend[0]->woche);
    $vormonatsnr = -1;
    foreach ($this->mache_monatlich_geltend as $key=>$meine_monatliche_zusammenfassung) {
      $monatsnr = $this->monatsnr_des_donnerstags( $meine_monatliche_zusammenfassung->woche);
      if ($vormonatsnr != $monatsnr) {
        $vormonatsnr = $monatsnr;      
        $nächster_monat = clone $meine_monatliche_zusammenfassung->woche;
        $voriger_monat = clone $meine_monatliche_zusammenfassung->woche;
    $nächster_monat->add( new DateInterval( 'P1M')); 
    $voriger_monat->sub( new DateInterval( 'P1M')); 
        $erg .= "Zusammenfassung der detaillierten Auflistung ZA Liste 20 von $anspruchsteller schriftlich geltend gemacht am 20";
        $erg .= sprintf( "<table  cellspacing=\"0\" cellpadding=\"2\" border=\"1\">%s</table>", $tafel_1);
        $erg .= "Ich mache folgendes geltend:";
        $tafel_2 .= sprintf( "<tr><td class=\"right\">%+5.2f<td>%s %s</tr>\n", $this->ewig_plus_minus_echt/100.0, "Übertrag nach",
          $fmt_monat->format( $meine_monatliche_zusammenfassung->woche)
        );
        $erg .= sprintf( "<table  cellspacing=\"0\" cellpadding=\"2\" border=\"1\">%s</table>", $tafel_2);
        $erg .= "die in die ZA-Liste 20 einzutragen sind und für die Vor- und Nacharbeit sind in die ZA-Liste 20 einzutragen.";

        $erg .= sprintf( "<h3>%s </h3>\n", $fmt_monat->format( $meine_monatliche_zusammenfassung->woche));
        $tafel_1  = sprintf( "<tr><th>%s<th>%s<th>%s</tr>\n", "Woche", "gesamte Wochen-Arbeitszeit in h", "Plus/Minus in h");
        $tafel_2  = sprintf( "<tr><th colspan=\"2\">%s</tr>\n", "Plus/Minus in h");
        $tafel_2 .= sprintf( "<tr><td class=\"right\">%+5.2f<td>%s %s</tr>\n", $this->ewig_plus_minus_echt/100.0, "Vortrag von",
          $fmt_monat->format( $voriger_monat)
        );
      }
      $this->ewig_plus_minus_echt        += $meine_monatliche_zusammenfassung->plus_minus_echt();
      $tafel_1 .= sprintf( "<tr>%s</tr>\n", $meine_monatliche_zusammenfassung->toTR_woche_von_bis());
      $tafel_2 .= sprintf( "<tr>%s</tr>\n", $meine_monatliche_zusammenfassung->toTR_plus_minus());
    }
    return $erg;
  }

  public function rette_wochensalden( datetime $woche) {
    $this->mache_monatlich_geltend[] = new meine_monatliche_zusammenfassung(
      $woche                     ,
      $this->woche_gfos          ,
      $this->woche_echt          ,
      $this->woche_summe_zwanzig_gfos ,
      $this->woche_summe_fünfzig_gfos , 
      $this->woche_summe_zwanzig_echt ,
      $this->woche_summe_fünfzig_echt , 
      $this->beschäftigungsumfang
    );
#   printf( "s010 %d %s <br />\n", count( $this->mache_monatlich_geltend), $woche->format( 'y-m-d'));
  }

  public function delta( $alt, $neu) {
    $delta = $alt - $neu;
    $this->dec_salden_kum_und_echt( $delta, $delta);
    printf( "<h4>der saldoübertrag ist falsch. von %.2f um %.2f zu %.2f korrigiert.</h4>", $alt/100.0, - $delta/100.0, $neu/100.0);
  }

}


class gfos_zeile {
  public $ausfelder;
  private $fmt_wochentagsnummer;
  private $fmt_tagesnummer;
  private $fmt_tagesname;
  private $value;
  private $salden;
  private $zeile_rechne;
  private $fmt_std;
  private $datumsobjekt;

  public function inkrementiere_salden() {                             // saldo += ist
    $this->salden->inc_salden_kum_und_echt(       $this->ausfelder["ist_gfos"  ]->wert,
                                                  $this->ausfelder["ist_echt"  ]->wert);
    $this->salden->inc_zges(                      $this->ausfelder["spät_20_gfos" ]->wert,
                                                  $this->ausfelder["nacht_50_gfos"]->wert,
                                                  $this->ausfelder["spät_20_echt" ]->wert,
                                                  $this->ausfelder["nacht_50_echt"]->wert);
    return $this->salden;
  }

  public function rette_salden_fürs_zeigen( $salden) {
    $this->ausfelder["saldo_kum" ]->wert = $salden->kum ;     // hundertstel
    $this->ausfelder["saldo_echt"]->wert = $salden->echt;  
  }

  public function set_ist_gfos_von_dauer_oder_geplant() {
    if (!is_numeric( $this->value["arbzeit_plan_dauer"])) {
      $this->ausfelder["ist_gfos" ]->wert =
        (new pause)->get_arbeitszeit_in_hundertstel_stunden( 
          $this->zeile_rechne->runde_dixx( $this->value["arbzeit_plan_ende"], $this->value["arbzeit_plan_anfang"])
        );
      $this->ausfelder["kommt" ]->wert = $this->zeile_rechne->minToHHMM( $this->value["arbzeit_plan_anfang"]);
      $this->ausfelder["geht"  ]->wert = $this->zeile_rechne->minToHHMM( $this->value["arbzeit_plan_ende"  ]);
    } else {
        $this->ausfelder["ist_gfos" ]->wert  = $this->value["arbzeit_plan_dauer"];
    }
    $this->ausfelder["ges_wochenarbzeit" ]->wert  = $this->ausfelder["ist_gfos" ]->wert;
    $this->ausfelder["ges_wochenarbecht" ]->wert  = $this->ausfelder["ist_gfos" ]->wert;
    $this->ausfelder["reine_arbeitszeit" ]->wert  = $this->ausfelder["ist_gfos" ]->wert;
    # $this->ausfelder["modulo"   ]->wert = ($this->value["arbeit_geht"]- $this->value["arbeit_kommt"]) . " § " . $this->value["arbeit_kommt"];
  }

  public function set_ist_gfos_von_dauer_oder_kommt_und_geht() {
    if (!is_numeric( $this->value["arbzeit_plan_dauer"])) {
      $this->ausfelder["ist_gfos" ]->wert =
        $this->zeile_rechne->runde_dixx( $this->value["arbeit_geht"], $this->value["arbeit_kommt"]);
      $this->ausfelder["kommt" ]->wert = $this->zeile_rechne->minToHHMM( $this->value["arbeit_kommt"]);
      $this->ausfelder["geht"  ]->wert = $this->zeile_rechne->minToHHMM( $this->value["arbeit_geht" ]);
    } else {
        $this->ausfelder["ist_gfos" ]->wert  = $this->value["arbzeit_plan_dauer"];
    }
    $this->ausfelder["ges_wochenarbzeit" ]->wert  = $this->ausfelder["ist_gfos" ]->wert;
    $this->ausfelder["ges_wochenarbecht" ]->wert  = $this->ausfelder["ist_gfos" ]->wert;
    $this->ausfelder["reine_arbeitszeit" ]->wert  = $this->ausfelder["ist_gfos" ]->wert;
    # $this->ausfelder["modulo"   ]->wert = ($this->value["arbeit_geht"]- $this->value["arbeit_kommt"]) . " § " . $this->value["arbeit_kommt"];
  }

  public function set_ist_gfos_von_dauer_oder_geplant_obsolet() {
    
    $this->ausfelder["ist_gfos" ]->wert =
      !is_numeric( $this->value["arbzeit_plan_dauer"])
        ? $this->ausfelder["ist_gfos" ]->wert
          = (new pause)->get_arbeitszeit_in_hundertstel_stunden( 
            $this->zeile_rechne->runde_dixx( $this->value["arbzeit_plan_ende"], $this->value["arbzeit_plan_anfang"])
            )
        : $this->ausfelder["ist_gfos" ]->wert  = $this->value["arbzeit_plan_dauer"]
        ;
      # $this->ausfelder["modulo"   ]->wert = ($this->value["arbzeit_plan_ende"]- $this->value["arbzeit_plan_anfang"]) . " § " . $this->value["arbzeit_plan_anfang"];
  }

  public function set_ist_gfos_von_dauer_oder_kommt_und_geht_obsolet() {
    $this->ausfelder["ist_gfos" ]->wert =
      !is_numeric( $this->value["arbzeit_plan_dauer"])
        ? $this->ausfelder["ist_gfos" ]->wert = $this->zeile_rechne->runde_dixx( $this->value["arbeit_geht"], $this->value["arbeit_kommt"])
        : $this->ausfelder["ist_gfos" ]->wert  = $this->value["arbzeit_plan_dauer"]
        ;
      # $this->ausfelder["modulo"   ]->wert = ($this->value["arbeit_geht"]- $this->value["arbeit_kommt"]) . " § " . $this->value["arbeit_kommt"];
  }

  public function erkläre_abkürzungen() {
    foreach ($this->ausfelder as $key => $val) {
      printf( "%s=\"%s\" %s | ", $val->kurzname, $val->langname, $key);
    }
  }

  public function toth() {
    $table_zeile = "<tr>\n";
    foreach ($this->ausfelder as $key => $val) {
      $table_zeile .= $val->header();
    }
    echo $table_zeile;
  }

  public function toTR__( $zeilenfarbe = "") {
    // $gfos_zeile->toTR__( $af["ist_gfos" ]->wert, $af["ist_echt" ]->wert);
    // if  (0 !== strcmp( $ist_gfos, $ist_echt)) {
    // if  ($ist_gfos != $ist_echt) {
    if  ($bunt=($this->ausfelder["ist_gfos"]->wert != "" and
         $this->ausfelder["ist_gfos"]->wert != $this->ausfelder["ist_echt"]->wert)) {
      $rette_row_format_gfos = $this->ausfelder["ist_gfos"]->row_format;
      $rette_row_format_echt = $this->ausfelder["ist_echt"]->row_format;
      $this->ausfelder["ist_gfos"]->row_format = "<td class=\"ist_gfos\"> " . $this->fmt_std;
      $this->ausfelder["ist_echt"]->row_format = "<td class=\"ist_echt\"> " . $this->fmt_std;
    }
    $table_zeile = "<tr$zeilenfarbe>\n";
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

  public function es_ist_sonntag() { // jeden sonntag noch ne zwischenzeile
    $datumsobjekt = datumsobjekt( $this->value["datum_auto"]);
    return 7 == $this->fmt_wochentagsnummer->format( $datumsobjekt);    // jeden montag noch ne zwischenzeile
  }

  public function es_ist_montag() { // jeden montag noch ne zwischenzeile
    $datumsobjekt = datumsobjekt( $this->value["datum_auto"]);
    return 1 == $this->fmt_wochentagsnummer->format( $datumsobjekt);    // jeden montag noch ne zwischenzeile
  }

  public function toTR_heutesalden( $letzte_zeile, $i_arbzeit) {
    if ($letzte_zeile) {                           // letzte zeile des tages
      $ges_wochenarbzeit_gfos
        = $this->salden->heute_summe_zwanzig_gfos
        + $this->salden->heute_summe_fünfzig_gfos
        + $this->salden->heute_gfos
        ;
      $ges_wochenarbzeit_echt
        = $this->salden->heute_summe_zwanzig_echt 
        + $this->salden->heute_summe_fünfzig_echt
        + $this->salden->heute_echt
        ;
      $this->ausfelder["i_saldo"          ]->wert = $this->value["i_saldo_dauer"];
      $this->ausfelder["nacht_50_gfos"      ]->wert = $this->salden->heute_summe_fünfzig_gfos;
      $this->ausfelder["nacht_50_gfos_ewig" ]->wert = $this->salden-> ewig_summe_fünfzig_gfos;
      $this->ausfelder["spät_20_gfos"       ]->wert = $this->salden->heute_summe_zwanzig_gfos;
      $this->ausfelder["spät_20_gfos_ewig"  ]->wert = $this->salden-> ewig_summe_zwanzig_gfos;
      $this->ausfelder["nacht_50_echt"      ]->wert = $this->salden->heute_summe_fünfzig_echt;
      $this->ausfelder["spät_20_echt"       ]->wert = $this->salden->heute_summe_zwanzig_echt;
      $this->ausfelder["ges_wochenarbzeit"  ]->wert = $ges_wochenarbzeit_gfos;
      $this->ausfelder["ges_wochenarbecht"  ]->wert = $ges_wochenarbzeit_echt;
      $this->ausfelder["woche_plus_minus"   ]->wert = $this->salden->heute_summe_zwanzig_gfos + $this->salden->heute_summe_fünfzig_gfos;
      $this->ausfelder["reine_arbeitszeit"  ]->wert = $this->salden->heute_gfos;
      $this->ausfelder["i_arbzeit"          ]->wert = $i_arbzeit;
      $this->toTR__();
      $this->ausfelder["i_arbzeit"          ]->wert = "";
      $this->ausfelder["ges_wochenarbzeit"  ]->wert = "";
      $this->ausfelder["ges_wochenarbecht"  ]->wert = "";
      $this->ausfelder["woche_plus_minus"   ]->wert = "";
      $this->ausfelder["spät_20_gfos"       ]->wert = "";
      $this->ausfelder["spät_20_gfos_ewig"  ]->wert = "";
      $this->ausfelder["nacht_50_gfos"      ]->wert = "";
      $this->ausfelder["spät_20_echt"       ]->wert = "";
      $this->ausfelder["nacht_50_echt"      ]->wert = "";
    } else {
      $this->toTR__();
    }
    $this->ausfelder["tagnr"           ]->wert = "";
    $this->ausfelder["tagnname"        ]->wert = "";
    $this->ausfelder["pause_ges"       ]->wert = "";
  # $this->ausfelder["autorisiert_ende"]->wert = "";
    $this->ausfelder["autorisiert_anf" ]->wert = "";
  }

  public function toTR_sonntags_salden() { // jeden sonntag noch ne zwischenzeile
    if ($this->es_ist_sonntag()) {  // jeden sonntag noch ne zwischenzeile
      $ges_wochenarbzeit_gfos
        = $this->salden->woche_summe_zwanzig_gfos 
        + $this->salden->woche_summe_fünfzig_gfos
        + $this->salden->woche_gfos
        ;
      $ges_wochenarbzeit_echt
        = $this->salden->woche_summe_zwanzig_echt 
        + $this->salden->woche_summe_fünfzig_echt
        + $this->salden->woche_echt
        ;
      $this->ausfelder["pause_ges"         ]->wert = "";
      $this->ausfelder["ist_gfos"          ]->wert = $this->salden->woche_gfos;
      $this->ausfelder["ist_echt"          ]->wert = $this->salden->woche_echt;
      $this->ausfelder["nacht_50_gfos"     ]->wert = $this->salden->woche_summe_fünfzig_gfos;
      $this->ausfelder["nacht_50_gfos_ewig"]->wert = $this->salden-> ewig_summe_fünfzig_gfos;
      $this->ausfelder["spät_20_gfos"      ]->wert = $this->salden->woche_summe_zwanzig_gfos;
      $this->ausfelder["spät_20_gfos_ewig" ]->wert = $this->salden-> ewig_summe_zwanzig_gfos;
      $this->ausfelder["nacht_50_echt"     ]->wert = $this->salden->woche_summe_fünfzig_echt;
      $this->ausfelder["spät_20_echt"      ]->wert = $this->salden->woche_summe_zwanzig_echt;
      $this->ausfelder["ges_wochenarbzeit" ]->wert = $ges_wochenarbzeit_gfos;
      $this->ausfelder["ges_wochenarbecht" ]->wert = $ges_wochenarbzeit_echt;
      $this->ausfelder["woche_plus_minus"  ]->wert = $this->salden->woche_summe_zwanzig_echt + $this->salden->woche_summe_fünfzig_echt;
      $this->ausfelder["reine_arbeitszeit" ]->wert = $this->salden->woche_echt;
      $this->set_datum( " class=\"sonntag\"");
      $this->toTR__( " class=\"update\"");
      $this->salden->rette_wochensalden( $this->datumsobjekt);
      $this->salden->reset_wochensalden();
    }
  }

  public function toTR_montags_sollzeit() { // jeden montag noch ne zwischenzeile
    if ($this->es_ist_montag()) {  // jeden montag noch ne zwischenzeile
      $this->montags_sollzeit_hinzufügen();

      $this->toTR__();
      $this->set_datum();
    }

  }

  public function montags_sollzeit_hinzufügen() { // jeden montag noch ne zwischenzeile

    $this->salden->inc_summe_soll  (        $this->salden->beschäftigungsumfang);
    $this->salden->dec_salden_kum_und_echt( $this->salden->beschäftigungsumfang,
                                            $this->salden->beschäftigungsumfang);
    $this->ausfelder["saldo_kum" ]->wert = $this->salden->kum                 ;
    $this->ausfelder["saldo_echt"]->wert = $this->salden->echt                ;
    $this->ausfelder["ist_echt"  ]->wert = $this->salden->beschäftigungsumfang;
    $this->ausfelder["soll"      ]->wert = $this->salden->beschäftigungsumfang;
    $this->ausfelder["tagnr"     ]->wert = "";
    $this->ausfelder["tagnname"  ]->wert = "";
    $this->ausfelder["ist_gfos"  ]->wert = $this->salden->beschäftigungsumfang;
  # $this->ausfelder["modulo"    ]->wert = "";
    $this->ausfelder["kommt"     ]->wert = "";
    $this->ausfelder["geht"      ]->wert = "";
    $this->ausfelder["bemerkung" ]->wert = "sollzeit";
  }

  function zeige_vormonatssummen( $salden, $kennung) {
    $this->ausfelder["tagnr"              ]->wert = $kennung;
    $this->ausfelder["tagnr"              ]->row_format = "<td  colspan='7'> %s";
    $this->ausfelder["tagnname"           ]->row_format = "";
    $this->ausfelder["änd_kz"             ]->row_format = "";
    $this->ausfelder["kommt"              ]->row_format = "";
    $this->ausfelder["geht"               ]->row_format = "";
    $this->ausfelder["pause"              ]->row_format = "";
    $this->ausfelder["pause_ges"          ]->row_format = "";
    $this->ausfelder["ist_gfos"           ]->row_format = "<td>"; $this->ausfelder["ist_gfos"      ]->wert = $salden->ewig_summe_ist;
  # $this->ausfelder["modulo"             ]->row_format = "<td>";
    $this->ausfelder["soll"               ]->row_format = "<td> " . $this->fmt_std ; $this->ausfelder["soll"          ]->wert = $salden->ewig_summe_soll;
    $this->ausfelder["saldo_kum"          ]->row_format = "<td class=\"saldo_kum\"> " . $this->fmt_std;
    $this->ausfelder["saldo_kum"          ]->wert = $salden->kum ;
    $this->ausfelder["saldo_echt"         ]->wert = $salden->echt;
    $this->ausfelder["spät_20_gfos"       ]->wert = $salden->monat_summe_zwanzig_gfos;
    $this->ausfelder["spät_20_gfos_ewig"  ]->wert = $salden-> ewig_summe_zwanzig_gfos;
    $this->ausfelder["nacht_50_gfos"      ]->wert = $salden->monat_summe_fünfzig_gfos;
    $this->ausfelder["nacht_50_gfos_ewig" ]->wert = $salden-> ewig_summe_fünfzig_gfos;
    $this->ausfelder["spät_20_echt"       ]->wert = $salden->monat_summe_zwanzig_echt;
    $this->ausfelder["nacht_50_echt"      ]->wert = $salden->monat_summe_fünfzig_echt;
  }

  function set_datum( $sonntagsfarbe = "") {
    $this->datumsobjekt = datumsobjekt( $this->value["datum_auto"]);
    $this->ausfelder["tagnr"      ]->wert =        $this->fmt_tagesnummer  ->format( $this->datumsobjekt);
    $this->ausfelder["tagnname"   ]->wert = rtrim( $this->fmt_tagesname->format( $this->datumsobjekt), ".");
    $this->ausfelder["tagnname"   ]->row_format = "<td$sonntagsfarbe> %s ";
#   $this->ausfelder["tagnname"   ]->row_format = "<td class=\"sonntag\"> %s ";
  }

  function __construct( $value, $salden, $rechner) {
    $this->fmt_std = "%05.2f";
  # $this->fmt_std = "%s";
    $this->zeile_rechne = $rechner;
    $this->salden = $salden;
    $this->value = $value;
    $this->ausfelder = array (
      "tagnr"             => new  gfos_ausgabe_element("tag"       , ""                    , " ", "<th colspan='2'> %s ", "<td> %s "),
      "tagnname"          => new  gfos_ausgabe_element("tag"       , ""                    , " ", "        ", "<td> %s "),
      "änd_kz"            => new  gfos_ausgabe_element("ä"         , "Änderungskz."        , " ", "<th> %s ", "<td> %s "),
      "kommt"             => new  gfos_ausgabe_element("komt"      , "Kommt"               , " ", "<th> %s ", "<td> %s "),
      "geht"              => new  gfos_ausgabe_element("geht"      , "Geht"                , " ", "<th> %s ", "<td> %s "),
      "pause"             => new  gfos_ausgabe_element("paus"      , "Pause"               , " ", "<th> %s ", "<td> %s "),
      "pause_ges"         => new  gfos_ausgabe_element("pges"      , "Pause ges."          , " ", "<th> %s ", "<td> %s "),
      "ist_gfos"          => new  gfos_ausgabe_element(" ist"      , "Ist"                 , "h", "<th> %s ", "<td class=\"right\">  " . $this->fmt_std),
    # "modulo"            => new  gfos_ausgabe_element(" mod"      , "Modulo"              , " ", "<th> %s ", "<td> %s "),
      "soll"              => new  gfos_ausgabe_element("soll"      , "Soll"                , "h", "<th> %s ", "<td class=\"right\">  " . $this->fmt_std),
      "saldo_kum"         => new  gfos_ausgabe_element("skum"      , "Saldo kum"           , "h", "<th> %s ", "<td class=\"right\">  " . $this->fmt_std),
      "fehlzeit_zeit"     => new  gfos_ausgabe_element("fehz"      , "Fehlzeit (Zeit)"     , " ", "<th> %s ", "<td> %s "),
    # "fehlzeit_text"     => new  gfos_ausgabe_element("fehl"      , "Fehlzeit"            , " ", "<th> %s ", "<td> %s "),
    # "vst"               => new  gfos_ausgabe_element(" vst"      , "VST"                 , " ", "<th> %s ", "<td> %s "),
      "bemerkung"         => new  gfos_ausgabe_element("bemerkung" , "Bemerkung"           , " ", "<th> %s ", "<td> %s "),
      "autorisiert_anf"   => new  gfos_ausgabe_element("auto"      , "Anfang autorisiert"  , " ", "<th> %s ", "<td> %s "),
    # "autorisiert_ende"  => new  gfos_ausgabe_element("ende"      , "Ende autorisiert"    , " ", "<th> %s ", "<td> %s "),
      "ist_echt"          => new  gfos_ausgabe_element("eist"      , "Ist echt"            , "h", "<th> %s ", "<td class=\"right\">  " . $this->fmt_std),
      "saldo_echt"        => new  gfos_ausgabe_element("ekum"      , "Saldo echt"          , "h", "<th> %s ", "<td class=\"right\">  " . $this->fmt_std),
      "i_saldo"           => new  gfos_ausgabe_element("isal"      , "Infotaste Saldo"     , " ", "<th> %s ", "<td> %s "),
      "spät_20_gfos_ewig" => new  gfos_ausgabe_element("20su"    , "Spätzuschlag 20 % ewig", "h", "<th> %s ", "<td class=\"right\">  " . $this->fmt_std),
      "spät_20_gfos"      => new  gfos_ausgabe_element("20%g"      , "Spätzuschlag 20 %"   , "h", "<th> %s ", "<td class=\"right\">  " . $this->fmt_std),
      "spät_20_echt"      => new  gfos_ausgabe_element("20%e"      , "Spätzuschlag 20 %"   , "h", "<th> %s ", "<td class=\"right\">  " . $this->fmt_std),
      "nacht_50_gfos_ewig"=> new  gfos_ausgabe_element("50su"   , "Nachtzuschlag 50 % ewig", "h", "<th> %s ", "<td class=\"right\">  " . $this->fmt_std),
      "nacht_50_gfos"     => new  gfos_ausgabe_element("50%g"      , "Nachtzuschlag 50 %"  , "h", "<th> %s ", "<td class=\"right\">  " . $this->fmt_std),
      "nacht_50_echt"     => new  gfos_ausgabe_element("50%e"      , "Nachtzuschlag 50 %"  , "h", "<th> %s ", "<td class=\"right\">  " . $this->fmt_std),
      "woche_plus_minus"  => new  gfos_ausgabe_element("+/-"       , "Plus Minus in Woche" , "h", "<th> %s ", "<td class=\"right\">  " . $this->fmt_std),
      "ges_wochenarbzeit" => new  gfos_ausgabe_element("gewo"      , "gesamte Wochenzeit"  , "h", "<th> %s ", "<td class=\"right\">  " . $this->fmt_std),
      "ges_wochenarbecht" => new  gfos_ausgabe_element("echt"      , "gesamte Wochenecht"  , "h", "<th> %s ", "<td class=\"right\">  " . $this->fmt_std),
      "reine_arbeitszeit" => new  gfos_ausgabe_element("rein"      , "reine_arbeitszeit"   , "h", "<th> %s ", "<td class=\"right\">  " . $this->fmt_std),
      "i_arbzeit"         => new  gfos_ausgabe_element("iarb"     , "Infotaste Arbeitszeit", "h", "<th> %s ", "<td class=\"right\">  " . $this->fmt_std),
    );
 // Nicht autorisierte Überziehungen verfallen 5.10.15
    $this->fmt_tagesnummer = new IntlDateFormatter(
        'de-DE',
        IntlDateFormatter::FULL,
        IntlDateFormatter::FULL,
        'Europe/Berlin',
        IntlDateFormatter::GREGORIAN,
        "dd"   // http://www.unicode.org/reports/tr35/tr35-dates.html#Date_Field_Symbol_Table
    );

    $this->fmt_tagesname = new IntlDateFormatter(
        'de-DE',
        IntlDateFormatter::FULL,
        IntlDateFormatter::FULL,
        'Europe/Berlin',
        IntlDateFormatter::GREGORIAN,
        "EEE"   // http://www.unicode.org/reports/tr35/tr35-dates.html#Date_Field_Symbol_Table
    );

    $this->fmt_wochentagsnummer = new IntlDateFormatter(
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
  private $minute_1830 = 1110;
  private $minute_2000 = 1200;
  private $minute_2400 = 1440;
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
      fwrite( $this->fh, sprintf ( "%5s %5s %5s %2s %5s %s %s\n", "min", "sub", "md", "ms", "dez", "korr", date( "Y-m-d H:i:s")));
#     fwrite( $this->fh, "\n");
    }
    
  }

  public function minToHHMM( $wert) {
    return sprintf( "%02d.%02d", $wert/60, $wert%60);
  }
  private function kkk( $arg) { fwrite( $this->fh, ($arg == "  " ? $arg : sprintf( "%+d", $arg) ) . " \n");}

  function runde_dixx( $minuend, $subtrahend) {  // hundertstel
    $md = $minuend - $subtrahend;
    $diff = (int) round( $md *10/6);
    fwrite( $this->fh, sprintf ( "%5d %5d %5d %d%d %5d", $minuend, $subtrahend, $md, $minuend%3, $subtrahend%3, $diff));
    if ($minuend == 1170 and $subtrahend == 1049 ) { $delta=+0;$this->kkk("+0"      ); return    $diff;} // vermeide case "02"
    if ($minuend ==  608 and $subtrahend ==  375 ) { $delta=-1;$this->kkk($delta    ); return -- $diff;} // vermeide case "20"
    if ($minuend ==  860 and $subtrahend ==  720 ) { $delta=+0;$this->kkk("+0"      ); return    $diff;} // vermeide case "20"
    if ($minuend ==  890 and $subtrahend ==  600 ) { $delta=+0;$this->kkk("+0"      ); return    $diff;} // vermeide case "20"
    if ($minuend ==  824 and $subtrahend ==  600 ) { $delta=+0;$this->kkk("+0"      ); return    $diff;} // vermeide case "20"
    if ($minuend ==  866 and $subtrahend ==  600 ) { $delta=+0;$this->kkk("+0"      ); return    $diff;} // vermeide case "20"
    if ($minuend ==  875 and $subtrahend ==  600 ) { $delta=+0;$this->kkk("+0"      ); return    $diff;} // vermeide case "20"
    if ($minuend ==  920 and $subtrahend ==  600 ) { $delta=+0;$this->kkk("+0"      ); return    $diff;} // vermeide case "20"
    switch ($minuend%3 . $subtrahend%3) {
    case "00" : $delta=+0;           $this->kkk("  "      ); break;
    case "01" : $delta=+0;           $this->kkk("  "      ); break;
    case "02" : $delta=-1; $diff --; $this->kkk($delta    ); break;
    case "10" : $delta=+0;           $this->kkk("  "      ); break;
    case "11" : $delta=+0;           $this->kkk("  "      ); break;
    case "12" : $delta=+0;           $this->kkk("  "      ); break;
    case "20" : $delta=+1; $diff ++; $this->kkk($delta    ); break;
    case "21" : $delta=+0;           $this->kkk("  "      ); break;
    case "22" : $delta=+0;           $this->kkk("  "      ); break;
    default   : $delta=+0;           $this->kkk("  "      ); break;
    }
    return $diff;
  }

  function runde_zwanzig( $endzeit, $anfangszeit) {
    $minuend = min( $endzeit, $this->minute_2000); $subtrahend = max( $anfangszeit, $this->minute_1830);
    return $minuend <= $subtrahend ? 0 : (int) round( $this->runde_dixx( $minuend, $subtrahend) * 2 / 10);

    $erg = min( $endzeit, $this->minute_2000) - max( $anfangszeit, $this->minute_1830);
    return $erg < 0 ? 0 : $erg;
  }

  function runde_fünfzig( $endzeit, $anfangszeit) {
    $minuend = min( $endzeit, $this->minute_2400); $subtrahend = max( $anfangszeit, $this->minute_2000);
    return $minuend <= $subtrahend ? 0 : (int) round( $this->runde_dixx( $minuend, $subtrahend) * 5 / 10);

    $erg = min( $endzeit, $this->minute_2400) - max( $anfangszeit, $this->minute_2000);
    return $erg < 0 ? 0 : $erg;
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
  private $conn          ;
  private $kalkulator    ;
  private $start_datum   ;
  private $database_name ;
  private $table_name    ;
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
    $spalte_obsolet = $tabelle->kurzfelder;
    $comma_separated = implode(",", $tabelle->gfos_zeitkonto); // Welche Daten werden geholt

    $this->table_name = (new konstante)->table_name;
    $this->database_name = (new konstante)->database_name;

    $where = "WHERE datum_auto >= '$anfangstag' and datum_auto < '$schlusstag' ORDER BY datum_auto";
    $query = "SELECT $comma_separated  FROM $this->table_name $where";
    # echo "<pre>"; print_r( $this->tabelle->felder); echo "!</pre><br />\n";
    $conn = new conn();
    $conn->frage( 0, "USE " . $this->database_name);

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

  public function i_taste( $database_name, $table_name, $datum) {
    $query = sprintf( "SELECT i_arbzeit_dauer FROM %s WHERE i_arbzeit_datum = '%s'", $table_name, $datum);
    $conn = new conn();
    $erg = $conn->frage( 0, "USE $database_name");
    $schon_da = $conn->hol_array_of_objects( "$query", 0); // todo Fehlerbehandlung
    return $schon_da ? (int) (100 * $schon_da[0]["i_arbzeit_dauer"]) : "";
  }

  function in_diesem_monat_gearbeitete_zeit( $salden) {

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
#$salden->set_summe_ist_und_summe_soll( 0.0, 0.0);
      $mit_pausen_geplante_arbeitszeit = $value["arbzeit_plan_ende"  ] - $value["arbzeit_plan_anfang"];

      $pause_ges = (new pause)->get_pausenzeit_in_stunden( $mit_pausen_geplante_arbeitszeit);
      $pause     = $pause_ges;

      $kommt_geht = array();
      if ($value["arbeit_kommt"] >= 0) { $kommt_geht[] = $value["arbeit_kommt"];}
      if ($value["pause1_geht" ] >= 0) { $kommt_geht[] = $value["pause1_geht" ];}
      if ($value["pause1_kommt"] >= 0) { $kommt_geht[] = $value["pause1_kommt"];}
      if ($value["pause2_geht" ] >= 0) { $kommt_geht[] = $value["pause2_geht" ];}
      if ($value["pause2_kommt"] >= 0) { $kommt_geht[] = $value["pause2_kommt"];}
      if ($value["arbeit_geht" ] >= 0) { $kommt_geht[] = $value["arbeit_geht" ];}
      if (count( $kommt_geht) % 2 == 1) {
        printf( "Z099 datum=%s Eine \"Kommt-\" oder \"Geht\"-Zeit fehlt oder ist überzählig <br />\n", $value["datum_auto"]);
        continue; // mit dem nächsten Tag
      }
      $gfos_zeile = new gfos_zeile( $value, $salden, $this->kalkulator);
      $salden->reset_heutesalden();
      $af = $gfos_zeile->ausfelder;
      $gfos_zeile->set_datum();

      switch ( $value["erscheine"]) {
      case "BA"       :
            $gfos_zeile->toTR_montags_sollzeit();
            $gfos_zeile->ausfelder["bemerkung"]->wert = "Betriebsausschuss"    ; $gfos_zeile->ausfelder["fehlzeit_zeit"]->wert = "br" ;
            $gfos_zeile->set_ist_gfos_von_dauer_oder_kommt_und_geht();
            $gfos_zeile->ausfelder["ist_echt" ]->wert = $gfos_zeile->ausfelder["ist_gfos" ]->wert;
          $salden->inc_summe_ist( $af["ist_gfos" ]->wert);

            $salden = $gfos_zeile->inkrementiere_salden();
            $gfos_zeile->rette_salden_fürs_zeigen( $salden);
            $gfos_zeile->toTR__();
      break;
      case "BR"       :
            $gfos_zeile->toTR_montags_sollzeit();
            $gfos_zeile->set_ist_gfos_von_dauer_oder_kommt_und_geht();
            $af["bemerkung"]->wert = "Betriebsrat"          ; $af["fehlzeit_zeit"]->wert = "br" ;
            $af["ist_echt" ]->wert = $af["ist_gfos" ]->wert;
          $salden->inc_summe_ist( $af["ist_gfos" ]->wert);
            $salden = $gfos_zeile->inkrementiere_salden();
            $gfos_zeile->rette_salden_fürs_zeigen( $salden);
            $gfos_zeile->toTR__();
      break;
      case "BV"       :
            $gfos_zeile->toTR_montags_sollzeit();
            $af["bemerkung"]->wert = "Betriebsversammlung"  ; $af["fehlzeit_zeit"]->wert = "br" ;
            $gfos_zeile->set_ist_gfos_von_dauer_oder_kommt_und_geht();
            $af["ist_echt" ]->wert = $af["ist_gfos" ]->wert;
          $salden->inc_summe_ist( $af["ist_gfos" ]->wert);
            $salden = $gfos_zeile->inkrementiere_salden();
            $gfos_zeile->rette_salden_fürs_zeigen( $salden);
            $gfos_zeile->toTR__();
      break;
      case "Seminar"  :
            $gfos_zeile->toTR_montags_sollzeit();
            $af["bemerkung"]->wert = "Seminar"              ; $af["fehlzeit_zeit"]->wert = "br" ;
            $gfos_zeile->set_ist_gfos_von_dauer_oder_kommt_und_geht();
            $af["ist_echt" ]->wert = $af["ist_gfos" ]->wert;
          $salden->inc_summe_ist( $af["ist_gfos" ]->wert);
            $salden = $gfos_zeile->inkrementiere_salden();
            $gfos_zeile->rette_salden_fürs_zeigen( $salden);
            $gfos_zeile->toTR__();
      break;
      case "Feiertag" :
            $gfos_zeile->toTR_montags_sollzeit();
            $gfos_zeile->set_ist_gfos_von_dauer_oder_kommt_und_geht();
            $af["bemerkung"]->wert = "Feiertag"             ; $af["fehlzeit_zeit"]->wert = "fei";
            $af["ist_echt" ]->wert  = $af["ist_gfos" ]->wert;
          $salden->inc_summe_ist( $af["ist_gfos" ]->wert);
            $salden = $gfos_zeile->inkrementiere_salden();
            $gfos_zeile->rette_salden_fürs_zeigen( $salden);
            $af["soll"     ]->wert  = "";
            $gfos_zeile->toTR__();
      break;
      case "krank"    :
            $gfos_zeile->toTR_montags_sollzeit();
            $af["bemerkung"]->wert = "krank"                ; $af["fehlzeit_zeit"]->wert = ""   ;
            $gfos_zeile->set_ist_gfos_von_dauer_oder_geplant();
          $salden->inc_summe_ist( $af["ist_gfos" ]->wert);
            $gfos_zeile->ausfelder["ist_echt" ]->wert = $gfos_zeile->ausfelder["ist_gfos" ]->wert;

            $salden = $gfos_zeile->inkrementiere_salden();
            $gfos_zeile->rette_salden_fürs_zeigen( $salden);
            $af["soll"     ]->wert  = "";
            $gfos_zeile->toTR__();
      break;
      case "Urlaub"   :
            $gfos_zeile->toTR_montags_sollzeit();
            $af["bemerkung"]->wert = "Urlaub"               ; $af["fehlzeit_zeit"]->wert = "u"  ;
            $af["ist_gfos" ]->wert  = $value["arbzeit_plan_dauer"];
            $af["ist_echt" ]->wert  = $af["ist_gfos" ]->wert;
          $salden->inc_summe_ist( $af["ist_gfos" ]->wert);
            $salden = $gfos_zeile->inkrementiere_salden();
            $gfos_zeile->rette_salden_fürs_zeigen( $salden);
            $af["soll"     ]->wert  = "";
            $gfos_zeile->toTR__();
      break;
      case "frei"     :
            $gfos_zeile->toTR_montags_sollzeit();
        $af["bemerkung"]->wert = "frei"                 ; $af["fehlzeit_zeit"]->wert = "f"  ;
            $af["ist_gfos"   ]->wert  = "";
            $af["ist_echt"   ]->wert  = "";
            $af["saldo_kum"  ]->wert  = "";
            $af["saldo_echt" ]->wert  = "";
            $af["soll"     ]->wert  = "";
            $gfos_zeile->toTR__();
      break;
      default         :
#       printf( "auto010 %s %s\n", $value["arbanf_autorisiert"], $value["arbende_autorisiert"]);
      $fm_pause = "%.2f";
      $af["pause_ges"  ]->wert = sprintf( $fm_pause, $pause_ges);
      $af["autorisiert_anf" ]->wert = $this->toText( $value["arbanf_autorisiert"  ]) . " "
                                    . $this->toText( $value["arbende_autorisiert" ]);
#     $af["autorisiert_ende"]->wert = $this->toText( $value["arbende_autorisiert"]);

      for ($ii = 0; $ii < count( $kommt_geht); $ii++) {
        $af["kommt"    ]->wert = $this->kalkulator->minToHHMM( $kommt_geht[$ii  ]);
        $af["geht"     ]->wert = $this->kalkulator->minToHHMM( $kommt_geht[$ii+1]);
        $af["pause"    ]->wert = $pause > 0.0 ? sprintf( $fm_pause, $pause) : "";
        $pause = $pause > 0.25 ? $pause - 0.25 : 0.0;
        $af["ist_echt" ]->wert = $this->kalkulator->runde_diff( $kommt_geht[$ii+1], $kommt_geht[$ii]);
                                                                                                        // Autorisierung
        $plan_ende = max( $value["arbzeit_plan_ende"], $value["arbende_autorisiert"]); // $value["arbende_autorisiert"] kann -1 sein
        $endzeit   = min( $kommt_geht[$ii+1], $plan_ende);
                                                                                                        // verfrühte Arbeitsaufnahme
        $plan_anfang = $value["arbanf_autorisiert"] < 0                                // $value["arbende_autorisiert"] kann -1 sein
          ? $value["arbzeit_plan_anfang"]
          : min( $value["arbzeit_plan_anfang"], $value["arbanf_autorisiert"])
          ;
#       printf( "auto%s planf%s kogg%s \n", $value["arbanf_autorisiert"], $plan_anfang, $kommt_geht[$ii]);
        $anfangszeit = max( $plan_anfang, $kommt_geht[$ii]);

        $af["spät_20_gfos" ]->wert = $this->kalkulator->runde_zwanzig( $endzeit, $anfangszeit);
        $af["nacht_50_gfos"]->wert = $this->kalkulator->runde_fünfzig( $endzeit, $anfangszeit);
        $af["spät_20_echt" ]->wert = $this->kalkulator->runde_zwanzig( $kommt_geht[$ii+1], $kommt_geht[$ii]);
        $af["nacht_50_echt"]->wert = $this->kalkulator->runde_fünfzig( $kommt_geht[$ii+1], $kommt_geht[$ii]);
        # if ( $af["spät_20_gfos"  ]->wert == $af["spät_20_echt"  ]->wert) $af["spät_20_gfos"  ]->wert = "";
        # if ( $af["nacht_50_gfos" ]->wert == $af["nacht_50_echt" ]->wert) $af["nacht_50_gfos" ]->wert = "";
        $af["ist_gfos"     ]->wert = $this->kalkulator->runde_dixx(    $endzeit, $anfangszeit);
      # $af["modulo"       ]->wert =                  (($endzeit- $kommt_geht[$ii])     ) . " § " . $kommt_geht[$ii];
        $salden->inc_summe_ist( $af["ist_gfos" ]->wert);

        $salden = $gfos_zeile->inkrementiere_salden();

        $gfos_zeile->rette_salden_fürs_zeigen( $salden);

        $ii++;
        $gfos_zeile->toTR_heutesalden(                                                 // letzte Zeile des Tages
          $ii+1 >= count( $kommt_geht),
          $this->i_taste( $this->database_name, $this->table_name, $value["datum_auto"])
        );
      }
      $gfos_zeile->toTR_sonntags_salden();
      $gfos_zeile->toTR_montags_sollzeit();

      break;
      }

    }
    $gfos_zeile = new gfos_zeile( "", $salden, $this->kalkulator);
    $gfos_zeile->zeige_vormonatssummen( $salden, "Monatssummen");
    $gfos_zeile->toTR__();
    $salden->reset_monatssalden();
    printf( "</table> \n");

    return $salden;
  }

  function toText( $wert) {
    return sprintf( "%s ", $wert < 0 ? "" : $this->kalkulator->minToHHMM( $wert));
  }

  function toHun( $wort) {
    return $wort == NULL ? $wort : (int) (100 * $wort);
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
      $erg .= sprintf( "<td> %s   ",                $this->normal_2D[$i]["datum_auto"          ] );
      $erg .= sprintf( "<td> %s   ",                $this->normal_2D[$i]["erscheine"           ] );
      $erg .= sprintf( "<td> %s   ",                $this->normal_2D[$i]["arbzeit_plan_dauer"  ] );
      $erg .= sprintf( "<td> %s   ", $this->toText( $this->normal_2D[$i]["arbzeit_plan_anfang" ]));
      $erg .= sprintf( "<td> %s   ", $this->toText( $this->normal_2D[$i]["arbeit_kommt"        ]));
      $erg .= sprintf( "<td> %s   ", $this->toText( $this->normal_2D[$i]["pause1_geht"         ]));
      $erg .= sprintf( "<td> %s   ", $this->toText( $this->normal_2D[$i]["pause1_kommt"        ]));
      $erg .= sprintf( "<td> %s   ", $this->toText( $this->normal_2D[$i]["pause2_geht"         ]));
      $erg .= sprintf( "<td> %s   ", $this->toText( $this->normal_2D[$i]["pause2_kommt"        ]));
      $erg .= sprintf( "<td> %s   ", $this->toText( $this->normal_2D[$i]["arbeit_geht"         ]));
      $erg .= sprintf( "<td> %s   ", $this->toText( $this->normal_2D[$i]["arbzeit_plan_ende"   ]));
      $erg .= sprintf( "<td> %s %s", $this->toText( $this->normal_2D[$i]["arbanf_autorisiert"  ]),
                                     $this->toText( $this->normal_2D[$i]["arbende_autorisiert" ]));
      $erg .= sprintf( "<td> %s   ",                $this->normal_2D[$i]["i_arbzeit_dauer"     ] );
      $erg .= sprintf( "<td> %s   ",                $this->normal_2D[$i]["i_arbzeit_datum"     ] );
      $erg .= sprintf( "<td> %s   ",                $this->normal_2D[$i]["i_saldo_dauer"       ] );
      $erg .= sprintf( "<td> %s   ",                $this->normal_2D[$i]["i_saldo_datum"       ] );
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
      $this->normal_2D[$i]["arbzeit_plan_dauer" ]  = $this->toHun( $value["arbzeit_plan_dauer"  ]);
      $this->normal_2D[$i]["arbzeit_plan_anfang"]  = $this->toMin( $value["arbzeit_plan_anfang" ]);
      $this->normal_2D[$i]["arbeit_kommt"       ]  = $this->toMin( $value["arbeit_kommt"        ]);
      $this->normal_2D[$i]["pause1_geht"        ]  = $this->toMin( $value["pause1_geht"         ]);
      $this->normal_2D[$i]["pause1_kommt"       ]  = $this->toMin( $value["pause1_kommt"        ]);
      $this->normal_2D[$i]["pause2_geht"        ]  = $this->toMin( $value["pause2_geht"         ]);
      $this->normal_2D[$i]["pause2_kommt"       ]  = $this->toMin( $value["pause2_kommt"        ]);
      $this->normal_2D[$i]["arbeit_geht"        ]  = $this->toMin( $value["arbeit_geht"         ]);
      $this->normal_2D[$i]["arbzeit_plan_ende"  ]  = $this->toMin( $value["arbzeit_plan_ende"   ]);
      $this->normal_2D[$i]["arbanf_autorisiert" ]  = $this->toMin( $value["arbanf_autorisiert"  ]);
      $this->normal_2D[$i]["arbende_autorisiert"]  = $this->toMin( $value["arbende_autorisiert" ]);
      $this->normal_2D[$i]["i_arbzeit_dauer"    ]  = $this->toHun( $value["i_arbzeit_dauer"     ]);
      $this->normal_2D[$i]["i_saldo_dauer"      ]  = $this->toHun( $value["i_saldo_dauer"       ]);
      $this->normal_2D[$i]["i_saldo_datum"      ]  =               $value["i_saldo_datum"       ] ;
      $this->normal_2D[$i]["spaet_20"           ]  = $this->toHun( $value["spaetzuschlag_20"    ]);
      $this->normal_2D[$i]["nacht_50"           ]  = $this->toHun( $value["nachtzuschlag_50"    ]);
      $this->normal_2D[$i]["ang_25"             ]  = $this->toHun( $value["ang_zuschlag_25"     ]);
      $this->normal_2D[$i]["zges_woche"         ]  = $this->toHun( $value["saldo_zges_woche"    ]);
      $i++;
    }
  }
}

function schleife( DateTime $laufobjekt, DateTime $stopobjekt, salden $salden) {
  $ein_rechner = new rechne;

# $salden = new salden();   
  switch ($laufobjekt->format('Y-m')) {                                    // Anfangswerte
  case "2014-12" : $salden->set_kum_und_echt( -  922 +1409, -  922 +1409); $salden->set_ewig_plus_minus_echt(  3043);
                                                               $salden->set_ewig_zges(    - 1731, - 279); break;
# case "2015-01" : $salden->set_kum_und_echt( -  705, -  705);                                            break;
  case "2015-01" : $salden->set_kum_und_echt( - 2881, - 2881); $salden->set_ewig_plus_minus_echt(    0);  break;
# case "2015-02" : $salden->set_kum_und_echt( -  461, -  461);                                            break;
  case "2015-02" : $salden->set_kum_und_echt( -  732, -  732);                                            break;
# case "2015-03" : $salden->set_kum_und_echt( -  377, -  377);                                            break;
  case "2015-03" : $salden->set_kum_und_echt( -  648, -  648);                                            break;
# case "2015-04" : $salden->set_kum_und_echt( -  923, -  923);                                            break;
  case "2015-04" : $salden->set_kum_und_echt( - 1228, - 1228);                                            break;
# case "2015-05" : $salden->set_kum_und_echt(    975,    975);                                            break;
  case "2015-05" : $salden->set_kum_und_echt(    687,    687);                                            break;
  case "2015-06" : $salden->set_kum_und_echt(   1887,   1887);                                            break;
  case "2015-07" : $salden->set_kum_und_echt( - 1879, - 1879);                                            break;
  case "2015-08" : $salden->set_kum_und_echt(    342,    342);                                            break;
    default : break;
    }
  $salden->set_summe_ist_und_summe_soll( 0, 0);                                           // hundertstel
  $intervall = new DateInterval( 'P1M');
  while ( $laufobjekt < $stopobjekt) {
#   printf( "%s saldo_kum=%s  saldo_echt=%s <br />\n", $laufobjekt->format('Y-m-d'), $salden->kum, $salden->echt);

    $ein_monat = new ein_monat( $laufobjekt->format('Y-m'), $ein_rechner);
    $ein_monat->kopiere_und_normalisiere();
    $salden = $ein_monat->in_diesem_monat_gearbeitete_zeit( $salden);  //
    $salden->set_summe_ist_und_summe_soll( 0, 0);
    $laufobjekt->add( $intervall);
    switch ($laufobjekt->format('Y-m')) {                                    // Korrekturen zwischendurch
        //                                  vorher         nachher  
        case "2016-03" : $salden->delta(  - 2545        ,  - 3190); break;
        case "2016-02" : $salden->delta(    3285        ,  -  226); break;
        case "2015-12" : $salden->delta(    1988        ,    1709); break;
        case "2015-11" : $salden->delta(    2056        ,    2941); break;
        case "2015-08" : $salden->delta(    1798        ,     342); break;
        case "2015-07" : $salden->delta(  - 2254        ,  - 1879); break;
      # case "2015-06" : $salden->delta(    2704        ,    1887); break;
      # case "2015-06" : $salden->delta(    2687        ,    1887); break;
        case "2015-06" : $salden->delta(    2416        ,    1887); break;
        default : break;
    }

    //echo $ein_monat->zeige_die_verwendeten_normalisierten_daten();
  }
  return $salden;
}


$startzeit =  (isset( $_GET["start"])) ? $_GET["start"] : "";  # echo "M010 $startzeit ";
$stopzeit  =  (isset( $_GET["stop" ])) ? $_GET["stop" ] : "";  # echo "M012 $stopzeit ";
if ($startzeit == "" or $stopzeit == "") {
  $parameter = sprintf( "?start=%s&stop=%s",
    $startzeit == "" ? "2015-8" : $startzeit,
    $stopzeit == "" ? "2015-9" : $stopzeit
  );
  printf( "Die Adresse ist unvollständig. Vorschlag:<br />\n");

  $url = sprintf("http://%s%s%s", $_SERVER["SERVER_NAME"], $_SERVER["SCRIPT_NAME"], $parameter); //  __DIR__, __FILE__,
  printf("E030 Versuche <a href=\"%s\"> %s </a><br />\n", $url, $url);
}

$salden = new salden( 3330); // Bechäftigungsumfang
schleife( datumsobjekt( $startzeit), datumsobjekt( $stopzeit), $salden);

echo $salden->zeige_wochensalden( "Sabine Schallehn");
$salden->zeige_wochensalden_verbose();

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

