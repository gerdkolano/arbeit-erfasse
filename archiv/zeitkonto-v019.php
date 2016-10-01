<?php
/*
 * 2016-04-13 Akzeptiere gf*s Rundungsregel. Verwende dixx, nicht mehr diff.
 * 2016-04-13 Ersetze ekum durch sver
 * 2016-04-13 Ersetze verfalle durch verfall
 * 2016-04-13 Ersetze summe_verfall durch summe_verfall
 * 2016-05-02 Erzeug Sabines wöchentliche ZA-Liste
 * */

require_once( "konstante.php");
require_once( "datum.php");
require_once( "helfer.php");
require_once( "tabelle.php");
require_once( "form-monat.php");
require_once( "form-woche.php");
require_once( "abgeltung.php");
require_once( "datenbestand.php");

  function fusz( ) {
  $zuletzt_aktualisiert = "Analyseprogramm zuletzt aktualisiert: Do 2016-05-05 22:23:50";
    $erg = "";
  $erg .= sprintf( "%s <br />\n", $zuletzt_aktualisiert);
    $erg .= "</body>";
    $erg .= "</html>";
    return $erg;
  }

function head( $stylesheet) {
  $erg = "";
  $erg .= "<!DOCTYPE html>\n";
  $erg .= "<html>\n";
  $erg .= "<head>\n";
  $erg .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
  $erg .= "<link rel=\"stylesheet\" href=\"$stylesheet\" type=\"text/css\">\n";
  $erg .= "</head>\n";
  $erg .= "<body>\n";
  return $erg;
}

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
    case "h" : return $this->wert == ""  // == 0 // === 0
      ? "<td>"
      : sprintf( $this->row_format, $this->wert / 100.0)
      ;
      break;
    case " " : return sprintf( $this->row_format, $this->wert); break;
    case "x" :
      if ($this->wert === "") {
        $wert = "";
      } else {
        if ($this->wert == 0) {
          $wert = "0.00";
        } else {
          $wert = sprintf( "%05.2f", $this->wert / 100.0);
        }
      }
      return sprintf( $this->row_format, $wert);
    case "y" :
      if ($this->wert == 0) {
        if (is_numeric( $this->wert)) {
          $wert = "0.00";
        } else {
          $wert = $this->wert;
        }
      } else {
        $wert = sprintf( "%05.2f", $this->wert / 100.0);
      }
      return sprintf( $this->row_format, $wert);
    default  : return sprintf( $this->row_format, $this->wert); break;
    }
  }

}

class salden  {
  public $tarifliche_wochenarbeitszeit ;
  public $beschäftigungsumfang         ;
  public $wie_gfos                     ;
  public $kum                          ;  // Nicht autorisierte Überziehungszeiten vefallen
  public $bilanz_ist       ;
  public $bilanz_soll      ;
# public $bilanz_verfall   ;

  public $heute_20_gfos    ;
  public $heute_50_gfos    ;
  public $woche_20_gfos    ;
  public $woche_50_gfos    ;
  public $monat_20_gfos    ;
  public $monat_50_gfos    ;
  public $bilanz_20_gfos   ;
  public $bilanz_50_gfos   ;

  public $heute_20_echt    ;
  public $heute_50_echt    ;
  public $woche_20_echt    ;
  public $woche_50_echt    ;
  public $monat_20_echt    ;
  public $monat_50_echt    ;
  public $bilanz_20_echt   ;
  public $bilanz_50_echt   ;

  public $heute_gfos       ;
  public $heute_echt       ;
  public $woche_gfos       ;
  public $woche_echt       ;
  public $heute_verfall    ;
  public $woche_verfall    ;
  public $monat_verfall    ;
  public $bilanz_verfall   ;  // Nichts verfällt
  public $bilanz_gfos      ;
  public $bilanz_echt      ;
                                    
  public $liste_aller_wochen     ;
  public $liste_aller_tage       ;
  public $daten_aller_tage       ;
  public $bilanz_pl_min_333_gfos ;
  public $woche_über_333_bum     ;
  public $bilanz_über_333_bum    ;

  public function __construct(
      $tarifliche_wochenarbeitszeit,
      $beschäftigungsumfang,
      $wie_gfos,
      $bilanz_verfall_vortrag
    ) {
    $this->tarifliche_wochenarbeitszeit = $tarifliche_wochenarbeitszeit;
    $this->beschäftigungsumfang         = $beschäftigungsumfang;
    $this->wie_gfos                     = $wie_gfos;
    $this->mehrarbeit_37                = 0;
    $this->woche_über_333_bum           = 0;
    $this->bilanz_über_333_bum          = 0;
    $this->kum                          = 0;
    $this->bilanz_ist                   = 0;
    $this->bilanz_soll                  = 0;

    $this->heute_20_gfos  = 0;
    $this->heute_50_gfos  = 0;
    $this->woche_20_gfos  = 0;
    $this->woche_50_gfos  = 0;
    $this->monat_20_gfos  = 0;
    $this->monat_50_gfos  = 0;
    $this->bilanz_20_gfos = 0;
    $this->bilanz_50_gfos = 0;

    $this->heute_20_echt  = 0;
    $this->heute_50_echt  = 0;
    $this->woche_20_echt  = 0;
    $this->woche_50_echt  = 0;
    $this->monat_20_echt  = 0;
    $this->monat_50_echt  = 0;
    $this->bilanz_20_echt = 0;
    $this->bilanz_50_echt = 0;

    $this->woche_gfos             = 0;
    $this->woche_echt             = 0;
    $this->woche_verfall          = 0;
    $this->monat_verfall          = 0;
    $this->bilanz_verfall         = $bilanz_verfall_vortrag;
    $this->heute_gfos             = 0;
    $this->bilanz_pl_min_333_gfos = 0;
    $this->liste_aller_wochen     = array();  // für mach_monatlich_geltend_version_04
    $this->liste_aller_tage       = array();
    $this->daten_aller_tage       = new daten_aller_tage();
  }

  public function inc_mehrarbeit_37() {
    $this->mehrarbeit_37 += max( $this->woche_gfos - $this->tarifliche_wochenarbeitszeit, 0);
  }

  public function inc_bilanz_über_333_bum() {
    $this->bilanz_über_333_bum += $this->woche_gfos - $this->beschäftigungsumfang;
  }

  public function set_bilanz_pl_min_333_gfos( $arg) {
    $this->bilanz_pl_min_333_gfos = $arg;
  }

  public function inc_soll( $arg) {
    $this->bilanz_soll += $arg;
  }

  public function inc_zges( $zwanzig_gfos, $fünfzig_gfos, $zwanzig_echt, $fünfzig_echt ) {
    $this->heute_20_gfos   += $zwanzig_gfos;
    $this->heute_50_gfos   += $fünfzig_gfos;
    $this->woche_20_gfos   += $zwanzig_gfos;
    $this->woche_50_gfos   += $fünfzig_gfos;
    $this->monat_20_gfos   += $zwanzig_gfos;
    $this->monat_50_gfos   += $fünfzig_gfos;
    $this->bilanz_20_gfos  += $zwanzig_gfos;
    $this->bilanz_50_gfos  += $fünfzig_gfos;

    $this->heute_20_echt   += $zwanzig_echt;
    $this->heute_50_echt   += $fünfzig_echt;
    $this->woche_20_echt   += $zwanzig_echt;
    $this->woche_50_echt   += $fünfzig_echt;
    $this->monat_20_echt   += $zwanzig_echt;
    $this->monat_50_echt   += $fünfzig_echt;
    $this->bilanz_20_echt  += $zwanzig_echt;
    $this->bilanz_50_echt  += $fünfzig_echt;
  }

  public function set_heute_zges( $zwanzig_gfos, $fünfzig_gfos) {
    $this->heute_20_gfos  = $zwanzig_gfos;
    $this->heute_50_gfos  = $fünfzig_gfos;
  }

  public function set_woche_zges( $zwanzig_gfos, $fünfzig_gfos) {
    $this->woche_20_gfos  = $zwanzig_gfos;
    $this->woche_50_gfos  = $fünfzig_gfos;
  }

  public function set_monat_zges( $zwanzig_gfos, $fünfzig_gfos) {
    $this->monat_20_gfos  = $zwanzig_gfos;
    $this->monat_50_gfos  = $fünfzig_gfos;
  }

  public function set_bilanz_zges( $zwanzig_gfos, $fünfzig_gfos) {
    $this->bilanz_20_gfos  = $zwanzig_gfos;
    $this->bilanz_50_gfos  = $fünfzig_gfos;
  }

  public function dec_bilanz_zges( $zwanzig_gfos, $fünfzig_gfos) {
    $this->bilanz_20_gfos -= $zwanzig_gfos;
    $this->bilanz_50_gfos -= $fünfzig_gfos;
  }

  public function set_kum_und_echt( $kum, $echt) {
    $this->kum   = $kum;
  }

  public function set_heute_kum( $kum, $echt) {
    $this->heute_gfos  = $kum;
  }

  public function set_ist_und_soll( $ist, $soll) {
    $this->bilanz_ist  = $ist ;
    $this->bilanz_soll = $soll;
  }

  public function dec_salden_kum( $kum) {
    $this->kum              -= $kum;
  }

  public function inc_salden_kum_und_verfall( $kum, $verfall) {
    $this->kum                 += $kum;
    $this->heute_gfos          += $kum;
    $this->woche_gfos          += $kum;
    $this->woche_echt          += $verfall;
    $this->heute_verfall        = $verfall;
    $this->woche_verfall       += $verfall;
    $this->monat_verfall       += $verfall;
    $this->bilanz_gfos         += $kum;
    $this->bilanz_echt         += $verfall;
    $this->bilanz_verfall      += $verfall;
  }

  public function reset_heutesalden() {
    $this->heute_gfos      = 0;
    $this->heute_20_gfos   = 0;
    $this->heute_50_gfos   = 0;
    $this->heute_20_echt   = 0;
    $this->heute_50_echt   = 0;
  }
  
  public function reset_wochensalden() {
    $this->woche_gfos      = 0;
    $this->woche_verfall   = 0;
  # $this->monat_verfall   = 0;
  # $this->bilanz_verfall  = 0;
    $this->woche_20_gfos   = 0;
    $this->woche_50_gfos   = 0;
    $this->woche_20_echt   = 0;
    $this->woche_50_echt   = 0;
  }
  
  public function reset_monatssalden() {
    $this->monat_gfos      = 0;
    $this->monat_echt      = 0;
    $this->monat_verfall   = 0;
  # $this->bilanz_verfall  = 0;
    $this->monat_20_gfos   = 0;
    $this->monat_50_gfos   = 0;
    $this->monat_20_echt   = 0;
    $this->monat_50_echt   = 0;
  }
  
  public function reset_bilanzen() {
    $this->bilanz_gfos      = 0;
    $this->bilanz_echt      = 0;
    $this->bilanz_20_gfos   = 0;
    $this->bilanz_50_gfos   = 0;
    $this->bilanz_20_echt   = 0;
    $this->bilanz_50_echt   = 0;
  }
  
  public function monatsnr_des_donnerstags( datum_objekt $woche) {
    $donnerstag = clone $woche;
    $donnerstag->sub( new DateInterval( 'P3D'));
    return $donnerstag->format( "n");
  }
  
  public function assoc_array_aller_tage() {
    $erg = "";
    $aller_tage_daten = $this->daten_aller_tage;
#   $aller_tage_daten = new daten_aller_tage( $this->liste_aller_tage);
#   $aller_tage_daten->lade_von( $this->liste_aller_tage);
    $erg .= $aller_tage_daten->tage_array["2015-05-07"]->datum_obj;
    $erg .= "<br />\n";
    $erg .= $aller_tage_daten->get_heute_gfos("2015-05-07");
    $erg .= "<br />\n";
    $erg .= $aller_tage_daten->get_heute_gfos("2015-05-08");
    $erg .= "<br />\n";
    $erg .= $aller_tage_daten->get_heute_gfos("2015-05-09");
    $erg .= "<br />2015-05-09 2015-05-08 2015-05-07\n";
    $erg .= $aller_tage_daten->get_heute_gfos_zeitraum( array( "2015-05-09", "2015-05-08", "2015-05-07"));
    $erg .= "<br />mach_7_wochentage\n";
    $erg .= $aller_tage_daten->get_heute_gfos_zeitraum( (new datum_objekt( "2015-05-09"))->mach_7_wochentage());
    $erg .= "<br />mach_tage_eines_monats\n";
    $erg .= $aller_tage_daten->get_heute_gfos_zeitraum( (new datum_objekt( "2015-05-09"))->mach_tage_eines_monats());
    $erg .= "<br />mach_tage_einer_abrechnungsperiode\n";
    $erg .= $aller_tage_daten->get_heute_gfos_zeitraum( (new datum_objekt( "2015-05-09"))->mach_tage_einer_abrechnungsperiode());
    $erg .= "<br />\n";

    return $erg;
  }
  
  public function zeige_aller_tage_liste() {
    $erg = "";
    $erg .= sprintf( "<tr> %s</tr>\n", $this->liste_aller_tage[0]->TH_aller_tage_liste());
    foreach ($this->liste_aller_tage as $key=>$dieser_tag) {
      $erg .= sprintf( "<tr> %s</tr>\n", $dieser_tag->TR_aller_tage_liste());
    }
    return sprintf( "<h3>ZA Täglich</h3>\n<table cellspacing=\"0\" cellpadding=\"2\" border=\"1\">\n%s</table>\n", $erg);
  }
  
  public function mach_taeglich_geltend( $rechner) {
    $erg = "";
    $dieser_tag = $this->liste_aller_tage[0];
    if ($dieser_tag->datum_obj->format( "N") != "1")
      $erg .= sprintf( "<tr> %s</tr>\n", $dieser_tag->TH_mach_taeglich_geltend( $rechner));
    foreach ($this->liste_aller_tage as $key=>$dieser_tag) {
      if ($dieser_tag->datum_obj->format( "N") == "1")
        $erg .= sprintf( "<tr> %s</tr>\n", $dieser_tag->TH_mach_taeglich_geltend( $rechner));
      $erg .= sprintf( "<tr> %s</tr>\n", $dieser_tag->TR_mach_taeglich_geltend( $rechner));
    }
    return sprintf( "<h3>ZA Täglich</h3>\n<table cellspacing=\"0\" cellpadding=\"2\" border=\"1\">\n%s</table>\n", $erg);
  }
  
  public function mach_woechentlich_geltend( $rechner) {
    $erg = "";
    // Erzeuge ein leeres Formblatt
    $za_liste = new za_liste( new inhalt());
    $erg .= $za_liste->male( "Sabine Schallehn");
    $erg .= "<br /><br /><br /><br />\n";
    $erg .= "<div class=\"page-break\"></div>\n";

    $ii = 0;

    // Geh zum ersten Montag
    while ($ii<count($this->liste_aller_tage) and $this->liste_aller_tage[$ii]->datum_obj->format( "N") != "1") { $ii++; }

    $dieser_tag = $this->liste_aller_tage[$ii];
    $wochen_ergebnis = $dieser_tag->TH_kopf_woechentlich_geltend( $rechner);
    while ($ii < count($this->liste_aller_tage) - 1) {
      $dieser_tag = $this->liste_aller_tage[$ii];
      $wochen_ergebnis .= sprintf( "%s\n", $dieser_tag->TR_rumpf_woechentlich_geltend( $rechner));
      $ii++;
      $nächster_tag  = $this->liste_aller_tage[$ii];
      if ($nächster_tag->datum_obj->format( "N") < $dieser_tag->datum_obj->format( "N")) {
        $wochen_ergebnis .= sprintf( "%s\n", $dieser_tag->TH_fusz_woechentlich_geltend( $this->daten_aller_tage));
        $erg .= sprintf( "<h3>ZA %s</h3>\n<table cellspacing=\"0\" cellpadding=\"2\" border=\"1\">\n%s</table>\n", $dieser_tag->datum_obj->deutsch( "MMMM YYYY"), $wochen_ergebnis);
        $wochen_ergebnis = $nächster_tag->TH_kopf_woechentlich_geltend( $rechner);
      }
    }
    if ($nächster_tag->datum_obj->format( "N") > "5") {
      $wochen_ergebnis .= sprintf( "%s\n", $nächster_tag->TR_rumpf_woechentlich_geltend( $rechner));
      $wochen_ergebnis .= sprintf( "%s\n", $nächster_tag->TH_fusz_woechentlich_geltend( $this->daten_aller_tage));
      $erg .= sprintf( "<h3>ZA %s</h3>\n<table cellspacing=\"0\" cellpadding=\"2\" border=\"1\">\n%s</table>\n", $dieser_tag->datum_obj->deutsch( "MMMM YYYY"), $wochen_ergebnis);
    }
    return $erg;
    return sprintf( "<h3>ZA ZA</h3>\n%s\n", $erg);
  }
  
  public function mach_monatlich_geltend_version_04( $anspruchsteller) {

    $leeres_formblatt = new formular_monat( new datum_objekt( ""), new stunde( 0), new stunde( 0), new stunde( 0), "", new stunde( $this->beschäftigungsumfang), array (
      new eine_woche( /*"2014-12-22", "2014-12-27",*/ new stunde( -1), new stunde( -1), new stunde( -1), new stunde( 0)),
      new eine_woche( /*"2014-12-22", "2014-12-27",*/ new stunde( -1), new stunde( -1), new stunde( -1), new stunde( 0)),
      new eine_woche( /*"2014-12-22", "2014-12-27",*/ new stunde( -1), new stunde( -1), new stunde( -1), new stunde( 0)),
      new eine_woche( /*"2014-12-22", "2014-12-27",*/ new stunde( -1), new stunde( -1), new stunde( -1), new stunde( 0)),
      new eine_woche( /*"2014-12-22", "2014-12-27",*/ new stunde( -1), new stunde( -1), new stunde( -1), new stunde( 0)),
    ));

    $leeres_formblatt->leer();
#   echo $leeres_formblatt;

    $enderg = $leeres_formblatt;
    $enderg .= "<div class=\"page-break\"></div>\n";
    $monerg = "";
#   ob_start();
#   echo "<pre>"; print_r( $this->liste_aller_wochen[0]); echo "</pre>\n";
#   $contents = ob_get_clean();
#   $erg .= $contents;

    $ii = 0;
    $erste_woche_eines_monats = $ii;
    $bilanz_pl_min_333_gfos   = $this->bilanz_pl_min_333_gfos;
    $bilanz_verfall           = $this->bilanz_verfall;
    $einige_wochen            = array();
    while ($ii < count( $this->liste_aller_wochen)) {
      $diese_woche = $this->liste_aller_wochen[$ii];
      $einige_wochen[] = new eine_woche(
        new stunde( $diese_woche->woche_gfos    ),
        new stunde( $diese_woche->woche_20_gfos ),
        new stunde( $diese_woche->woche_50_gfos ),
        new stunde( $diese_woche->woche_verfall )
      );

/*
 * Erzeuge PHP-Programmtext
      $monerg .= sprintf( "new eine_woche( /*%02d \"%s\", \"%s\",%s%s new stunde( %4d), new stunde( %4d), new stunde( %4d), new stunde( %4d)), <br />\n",
        $erste_woche_eines_monats,
      $diese_woche->woche->erster_werktag_der_woche()->format( "m Y-m-d")      ,
      $diese_woche->woche->letzter_werktag_der_woche()->format( "Y-m-d") ,
      "*" , "/"                     ,
      $diese_woche->woche_gfos      ,
      $diese_woche->woche_20_gfos   ,
      $diese_woche->woche_50_gfos   ,
      $diese_woche->woche_verfall
      );
*/

        $this->bilanz_pl_min_333_gfos        += $diese_woche->woche_pl_min_333_gfos();
        $this->bilanz_verfall                += $diese_woche->woche_verfall          ;
      $vormonatsnummer = $diese_woche->woche->monatsnummer_der_woche();
      $ii++;
      $monatsabschluss = $ii >= count( $this->liste_aller_wochen );

      if( !$monatsabschluss) {
        $monatsabschluss = $vormonatsnummer != $this->liste_aller_wochen[$ii]->woche->monatsnummer_der_woche();
      }

      if ($monatsabschluss) {
        $abgegolten = (new abgegolten( $this->liste_aller_wochen[$erste_woche_eines_monats]->woche))->abgegolten();
        $abgegoltene_zeit = $abgegolten->abgegoltene_zeit;
        $verkaufsstellenprämie_txt = $abgegolten->verkaufsstellenprämie;
        $this->bilanz_pl_min_333_gfos        -= $abgegoltene_zeit;
#             $bilanz_pl_min_333_gfos        -= $abgegoltene_zeit;
        $enderg .= new formular_monat( $this->liste_aller_wochen[$erste_woche_eines_monats]->woche,
          new stunde(   $bilanz_pl_min_333_gfos      ),
          new stunde(   $bilanz_verfall              ),
          new stunde( - $abgegoltene_zeit            ),
          $verkaufsstellenprämie_txt                  ,
          new stunde(   $this->beschäftigungsumfang  ),
          $einige_wochen
        );
        $einige_wochen = array();

/*
 * Erzeuge PHP-Programmtext
        $monerg .= sprintf( "echo new formular_monat( new datum_objekt( \"%s\"), new stunde( %5d), new stunde( %5d), new stunde( %5d), array (<br />\n"
                            . " )); <br />\n",
                            $this->liste_aller_wochen[$erste_woche_eines_monats]->woche->format( "Y-m-d"),
                            $bilanz_pl_min_333_gfos,
                            $bilanz_verfall        ,
                            $this->beschäftigungsumfang                    
                          );
*/
        $enderg .= $monerg;
        $enderg .= "<div class=\"page-break\"></div>\n";
#       $enderg .= sprintf( "%s<br />\n", $monerg);
        $monerg = "";
        $erste_woche_eines_monats = $ii;
        $bilanz_pl_min_333_gfos   = $this->bilanz_pl_min_333_gfos;
        $bilanz_verfall           = $this->bilanz_verfall;
#   echo "<pre>"; print_r( $einige_wochen); echo "</pre>\n";
      }

    }
    return $enderg;
  }
  
  public function rette_salden_einer_woche( datum_objekt $woche) {
    $this->liste_aller_wochen[] = new daten_einer_einzelnen_woche(
      $woche                     ,
      $this->woche_gfos          ,
      $this->woche_echt          ,
      $this->woche_verfall       ,
      $this->woche_20_gfos       ,
      $this->woche_50_gfos       , 
      $this->woche_20_echt       ,
      $this->woche_50_echt       , 
      $this->beschäftigungsumfang
    );
  # printf( "<td>s010 %d %s %s\n", count( $this->liste_aller_wochen), $woche->format( 'Y-m-d'), $woche->Ymd());
  }
  
  public function falscher_saldoübertrag( $alt, $neu) {
    $delta = $alt - $neu;
    $this->dec_salden_kum( $delta);
    $anmerkung = "";
    $anmerkung .= sprintf( "Der Übertrag des \"Saldo kum\" zwischen den gf*s-ZeitK0NT0-Auszügen ist falsch. ");
    $anmerkung .= sprintf( "Von %.2f um %.2f zu %.2f korrigiert.", $alt/100.0, - $delta/100.0, $neu/100.0);
    return sprintf( "<h4>%s</h4>\n", $anmerkung);
  }

}

class za_ausgabe_eines_tages {
  public $datum_nr                    ;
  public $datum_name                  ;
  public $plan_anfang                 ;
  public $plan_ende                   ;
  public $plan_ohne_pause_in_hund_std ;
  public $erscheine                   ;
  public $arbeit_kommt                ;
  public $pause1_geht                 ;
  public $pause1_kommt                ;
  public $pause2_geht                 ;
  public $pause2_kommt                ;
  public $arbeit_geht                 ;
  public $verlasse                    ;
  public $zwanzig                     ;
  public $fünfzig                     ;
  public $zwanzig_und_fünfzig         ;
  public $infotaste_saldo             ;
  public $reine_mit_gutschrift        ;
  public $plan_diff_mit_pause_in_std  ;
  public $verfall                     ;
  public $reine                       ;
  public $id                          ;

function __construct(
    $datum_nr       ,
    $datum_name     ,
    $plan_anfang                 ,
    $plan_ende                   ,
    $plan_ohne_pause_in_hund_std ,
    $erscheine                   ,
    $arbeit_kommt                ,
    $pause1_geht                 ,
    $pause1_kommt                ,
    $pause2_geht                 ,
    $pause2_kommt                ,
    $arbeit_geht                 ,
    $verlasse                    ,
    $zwanzig                     ,
    $fünfzig                     ,
    $zwanzig_und_fünfzig         ,
    $infotaste_saldo             ,
    $reine_mit_gutschrift        ,
    $plan_mit_pause_in_std       ,
    $reine                       ,
    $verfall                     ,
    $id                           
  ) {
    $this->datum_nr                    = $datum_nr                    ;
    $this->datum_name                  = $datum_name                  ;
    $this->plan_anfang                 = $plan_anfang                 ;
    $this->plan_ende                   = $plan_ende                   ;
    $this->plan_ohne_pause_in_hund_std = $plan_ohne_pause_in_hund_std ;
    $this->erscheine                   = $erscheine                   ;
    $this->arbeit_kommt                = $arbeit_kommt                ;
    $this->pause1_geht                 = $pause1_geht                 ;
    $this->pause1_kommt                = $pause1_kommt                ;
    $this->pause2_geht                 = $pause2_geht                 ;
    $this->pause2_kommt                = $pause2_kommt                ;
    $this->arbeit_geht                 = $arbeit_geht                 ;
    $this->verlasse                    = $verlasse                    ;
    $this->zwanzig                     = $zwanzig                     ;
    $this->fünfzig                     = $fünfzig                     ;
    $this->zwanzig_und_fünfzig         = $zwanzig_und_fünfzig         ;
    $this->infotaste_saldo             = $infotaste_saldo             ;
    $this->reine_mit_gutschrift        = $reine_mit_gutschrift        ;
    $this->plan_mit_pause_in_std       = $plan_mit_pause_in_std       ;
    $this->reine                       = $reine                       ;
    $this->verfall                     = $verfall                     ;
    $this->id                          = $id                          ;
  }

  function leer( $real) {
    return $real == 0 ? "" : sprintf( "%.2f", $real/100.0); ;
    return $real <= 0.001 ? "" : sprintf( "%.2f", $real/100.0); ;
  }
  
  function ein_tag_za_toTR() {
    $erg = "";
    $erg .= sprintf( "<tr>");
    $erg .= sprintf( "<td>%s",              $this->datum_nr . $this->datum_name   );
    $erg .= sprintf( "<td>%s",              $this->plan_anfang                    );
    $erg .= sprintf( "<td>%s",              $this->plan_ende                      );
    $erg .= sprintf( "<td>%s", $this->leer( $this->plan_ohne_pause_in_hund_std   ));
    $erg .= sprintf( "<td>%s",              $this->erscheine                      );
    $erg .= sprintf( "<td>%s",              $this->arbeit_kommt                   );
    $erg .= sprintf( "<td>%s",              $this->pause1_geht                    );
    $erg .= sprintf( "<td>%s",              $this->pause1_kommt                   );
    $erg .= sprintf( "<td>%s",              $this->pause2_geht                    );
    $erg .= sprintf( "<td>%s",              $this->pause2_kommt                   );
    $erg .= sprintf( "<td>%s",              $this->arbeit_geht                    );
    $erg .= sprintf( "<td>%s",              $this->verlasse                       );
    $erg .= sprintf( "<td>%s", $this->leer( $this->reine                         ));
    $erg .= sprintf( "<td>%s", $this->leer( $this->zwanzig                       ));
    $erg .= sprintf( "<td>%s", $this->leer( $this->fünfzig                       ));
    $erg .= sprintf( "<td>%s", $this->leer( $this->verfall                       ));
    $erg .= sprintf( "</tr>");
    return $erg;
  }

  function ein_tag_za_viel_toTR() {
    $erg = "";
    $erg .= sprintf( "<tr>");
    $erg .= sprintf( "    <td> %s",                              $this->datum_nr                       );
    $erg .= sprintf( "    <td> %s",                              $this->datum_name                     );
    $erg .= sprintf( "    <td> %s",                              $this->plan_anfang                    );
    $erg .= sprintf( "    <td> %s",                              $this->plan_ende                      );
    $erg .= sprintf( "    <td> %s",                 $this->leer( $this->plan_ohne_pause_in_hund_std   ));
    $erg .= sprintf( "    <td> %s",                              $this->erscheine                      );
    $erg .= sprintf( "    <td> %s",                              $this->arbeit_kommt                   );
    $erg .= sprintf( "    <td> %s",                              $this->pause1_geht                    );
    $erg .= sprintf( "    <td> %s",                              $this->pause1_kommt                   );
    $erg .= sprintf( "    <td> %s",                              $this->pause2_geht                    );
    $erg .= sprintf( "    <td> %s",                              $this->pause2_kommt                   );
    $erg .= sprintf( "    <td> %s",                              $this->arbeit_geht                    );
    $erg .= sprintf( "    <td> %s",                              $this->verlasse                       );
    $erg .= sprintf( "    <td> %s",                 $this->leer( $this->zwanzig                       ));
    $erg .= sprintf( "    <td> %s",                 $this->leer( $this->fünfzig                       ));
    $erg .= sprintf( "    <td> %s",                 $this->leer( $this->zwanzig_und_fünfzig           ));
    $erg .= sprintf( "    <td> %s",                 $this->leer( $this->reine                         ));
    $erg .= sprintf( "    <td> %s",                 $this->leer( $this->reine_mit_gutschrift          ));
    $erg .= sprintf( "    <td> %s",                 $this->leer( $this->verfall                       ));
    $erg .= sprintf( "    <td> %s",                 $this->leer( $this->infotaste_saldo               ));
    $erg .= sprintf( "    <td> %s",                 $this->leer( $this->plan_mit_pause_in_std         ));
    $erg .= sprintf( "    <td> %s",                              $this->id                             );
    $erg .= "\n";
    return $erg;
  }

  function ein_tag_za_viel_toTH() {
    $erg_oben = "";                                                   $erg_unten = "";
    $erg_oben .= sprintf( "<tr>"                                );    $erg_unten .= sprintf( "<tr>"                                );
    $erg_oben .= sprintf( "    <th colspan=2> %s", ""           );    $erg_unten .= sprintf( "    <th colspan=2> %s", "Datum"      ); /**/
    $erg_oben .= sprintf( "    <th colspan=3> %s", "geplant"    );    $erg_unten .= sprintf( "    <th> %s",           "von"        ); /**/
 /* $erg_oben .= sprintf( "    <th> %s",           "plan_ende  "); */ $erg_unten .= sprintf( "    <th> %s",           "bis"        ); /**/
 /* $erg_oben .= sprintf( "    <th> %s",           "plan_arb   "); */ $erg_unten .= sprintf( "    <th> %s",           "Std"        ); /**/
    $erg_oben .= sprintf( "    <th> %s",           "ersch"      );    $erg_unten .= sprintf( "    <th> %s",           "eine"       ); /**/
    $erg_oben .= sprintf( "    <th> %s",           "Anf"        );    $erg_unten .= sprintf( "    <th> %s",           "kom"        ); /**/
    $erg_oben .= sprintf( "    <th colspan=2> %s", "Pause 1"    );    $erg_unten .= sprintf( "    <th> %s",           "geh"        ); /**/
 /* $erg_oben .= sprintf( "    <th> %s",           "kom"        ); */ $erg_unten .= sprintf( "    <th> %s",           "kom"        ); /**/
    $erg_oben .= sprintf( "    <th colspan=2> %s", "Pause 2"    );    $erg_unten .= sprintf( "    <th> %s",           "geh"        ); /**/
 /* $erg_oben .= sprintf( "    <th> %s",           "kom"        ); */ $erg_unten .= sprintf( "    <th> %s",           "kom"        ); /**/
    $erg_oben .= sprintf( "    <th> %s",           "Ende"       );    $erg_unten .= sprintf( "    <th> %s",           "geh"        ); /**/
    $erg_oben .= sprintf( "    <th> %s",           "ver-"       );    $erg_unten .= sprintf( "    <th> %s",           "lasse"      ); /**/
    $erg_oben .= sprintf( "    <th colspan=3> %s", "Gutschrift" );    $erg_unten .= sprintf( "    <th> %s",           "20%"        ); /**/
 /* $erg_oben .= sprintf( "    <th> %s",           "50%"        ); */ $erg_unten .= sprintf( "    <th> %s",           "50%"        ); /**/
 /* $erg_oben .= sprintf( "    <th> %s",           "gut"        ); */ $erg_unten .= sprintf( "    <th> %s",           "zus."       ); /**/
    $erg_oben .= sprintf( "    <th> %s",           "Rein"       );    $erg_unten .= sprintf( "    <th> %s",           "Arbz"       ); /**/
    $erg_oben .= sprintf( "    <th> %s",           "mit"        );    $erg_unten .= sprintf( "    <th> %s",           "gut"        ); /**/
    $erg_oben .= sprintf( "    <th> %s",           "Ver-"       );    $erg_unten .= sprintf( "    <th> %s",           "fall"       ); /**/
    $erg_oben .= sprintf( "    <th> %s",           "info"       );    $erg_unten .= sprintf( "    <th> %s",           "saldo"      ); /**/
    $erg_oben .= sprintf( "    <th> %s",           "Plan"       );    $erg_unten .= sprintf( "    <th> %s",           "+Pau"       ); /**/
    $erg_oben .= sprintf( "    <th> %s",           ""           );    $erg_unten .= sprintf( "    <th> %s",           "id"         ); /**/
    $erg_oben .= "\n";                                                $erg_unten .= "\n";
    return $erg_oben . $erg_unten;
  }

}

class daten_einer_einzelnen_woche {
  public $woche         ;
  public $woche_gfos    ;
  public $woche_verfall ;
  public $woche_20_gfos ;
  public $woche_50_gfos ;
  public $woche_20_echt ;
  public $woche_50_echt ;
  public $beschäftigungsumfang ;

  function toTR_woche_von_bis() {
    $wochenarbzeit_gfos
      = $this->woche_20_gfos
      + $this->woche_50_gfos
      + $this->woche_gfos
      ;
    $wochenarbzeit_echt
      = $this->woche_20_echt
      + $this->woche_50_echt
      + $this->woche_gfos
      + $this->woche_verfall
      ;
  
    $start_woche = clone $this->woche;
    $stopp_woche = clone $this->woche;
    $start_woche->sub( new DateInterval( 'P6D')); 
    $stopp_woche->sub( new DateInterval( 'P1D')); 
    return sprintf( "<td> %s <td> %s <td> %05.2f <td> %05.2f <td> %05.2f <td> %05.2f <td> %+05.2f <td> %05.2f",
      $start_woche->format( "d.m.y")           , 
      $stopp_woche->format( "d.m.y")           , 
      $this->woche_gfos                / 100.0 ,
      $this->woche_20_gfos             / 100.0 ,
      $this->woche_50_gfos             / 100.0 ,
      $wochenarbzeit_gfos              / 100.0 ,
      $this->woche_pl_min_333_gfos()   / 100.0 ,
      $this->woche_verfall             / 100.0  
    );
  }
  
  function __construct (
    datum_objekt $woche   ,
    $woche_gfos           ,
    $woche_echt           ,
    $woche_verfall        ,
    $woche_20_gfos        ,
    $woche_50_gfos        , 
    $woche_20_echt        ,
    $woche_50_echt        , 
    $beschäftigungsumfang   
  ) {
    $this->woche                = $woche                ;
    $this->woche_gfos           = $woche_gfos           ;
    $this->woche_echt           = $woche_echt           ;
    $this->woche_verfall        = $woche_verfall        ;
    $this->woche_20_gfos        = $woche_20_gfos        ;
    $this->woche_50_gfos        = $woche_50_gfos        ;
    $this->woche_20_echt        = $woche_20_echt        ;
    $this->woche_50_echt        = $woche_50_echt        ;
    $this->beschäftigungsumfang = $beschäftigungsumfang ;
  }
  
  function verlust_durch_verfall() {
    return $this->woche_verfall;
  }
  
  function woche_pl_min_333_gfos() {
    return
      $this->woche_gfos
    + $this->woche_20_gfos
    + $this->woche_50_gfos
    - $this->beschäftigungsumfang
    ;    
  }
  
  function woche_pl_min_333_echt() {
    return
      $this->woche_echt
    + $this->woche_20_echt
    + $this->woche_50_echt
    + $this->woche_verfall
    - $this->beschäftigungsumfang
    ;    
  }
  
  function toString() {
    $wochenarbzeit_echt
      = $this->woche_20_echt
      + $this->woche_50_echt
      + $this->woche_verfall
      ;

    $verlust_durch_verfall = $this->verlust_durch_verfall();
  
    $start_woche = clone $this->woche;
    $stopp_woche = clone $this->woche;
    $start_woche->sub( new DateInterval( 'P6D')); 
    $stopp_woche->sub( new DateInterval( 'P1D')); 
    return sprintf( "%s bis %s : %05.2f  = ( %05.2f + %05.2f + %05.2f). %+05.2f  ## Verlust durch Verfall: %05.2f ",
      $start_woche->format( "d.m.y")           , 
      $stopp_woche->format( "d.m.y")           , 
      $wochenarbzeit_echt              / 100.0 ,
      $this->woche_verfall             / 100.0 ,
      $this->woche_20_echt             / 100.0 ,
      $this->woche_50_echt             / 100.0 ,
      $this->woche_pl_min_333_echt()   / 100.0 ,
      $this->verlust_durch_verfall()   / 100.0  
    );
  }
}


class gfos_zeile {
  public $ausfelder;
  private $fmt_wochentagsnummer;
  private $fmt_tagesnummer;
  private $fmt_tagesname;
  private $value;
  public $salden;
  private $zeile_rechne;
  private $fmt_std;
  private $datumsobjekt;
  
  public function inkrementiere_salden() {                                                      // saldo += ist
    $this->salden->bilanz_ist                  += $this->ausfelder["ist_gfos"     ]->wert;
    $this->salden->inc_salden_kum_und_verfall(    $this->ausfelder["ist_gfos"     ]->wert,
                                                  $this->ausfelder["verfalle"     ]->wert);
    $this->salden->inc_zges(                      $this->ausfelder["spät_20_gfos" ]->wert,
                                                  $this->ausfelder["nacht_50_gfos"]->wert,
                                                  $this->ausfelder["spät_20_echt" ]->wert,
                                                  $this->ausfelder["nacht_50_echt"]->wert);
                                                  $this->ausfelder["saldo_kum"    ]->wert = $this->salden->kum ;
  }

/*
      $this->woche_gfos          ,
      $this->woche_echt          ,
      $this->woche_verfall       ,
      $this->woche_20_gfos ,
      $this->woche_50_gfos ,
      $this->woche_20_echt ,
      $this->woche_50_echt ,
      $this->beschäftigungsumfang
function push_einen_tag( daten_eines_tages $dieser_tag) {

*/

  public function rette_taegliche_daten() {
    $einer = new daten_eines_tages(
      new datum_objekt(
      $this->value["datum_auto"          ]),
#     $this->value["datum_auto"          ],
      $this->value["erscheine"           ],
      $this->value["arbzeit_plan_dauer"  ],
      $this->value["arbanf_autorisiert"  ],
      $this->value["arbzeit_plan_anfang" ],
      $this->value["arbeit_kommt"        ],
      $this->value["pause1_geht"         ],
      $this->value["pause1_kommt"        ],
      $this->value["pause2_geht"         ],
      $this->value["pause2_kommt"        ],
      $this->value["arbeit_geht"         ],
      $this->value["arbzeit_plan_ende"   ],
      $this->value["arbende_autorisiert" ],
      $this->value["verlasse"            ],
      $this->value["i_saldo_dauer"       ],
      $this->value["id"                  ],
      $this->salden->heute_gfos           ,
      $this->salden->heute_echt           ,
      $this->salden->heute_verfall        ,
      $this->salden->heute_20_gfos        ,
      $this->salden->heute_50_gfos        ,
      $this->salden->heute_20_echt        ,
      $this->salden->heute_50_echt        ,
      $this->salden->beschäftigungsumfang
    );
    $this->salden->liste_aller_tage[] = $einer;
    $this->salden->daten_aller_tage->push_einen_tag( $einer);
  }

  public function set( $ausfeld, $wert) {
    $this->ausfelder[$ausfeld]->wert = $wert;
  }

  public function set_bemerkung_und_fehz( $bemerkung, $fehz) {
    $this->ausfelder["bemerkung"    ]->wert = $bemerkung;
    $this->ausfelder["fehlzeit_zeit"]->wert = $fehz;
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
  # $this->ausfelder["ges_wochenarbecht" ]->wert  = $this->ausfelder["ist_gfos" ]->wert;
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
  # $this->ausfelder["ges_wochenarbecht" ]->wert  = $this->ausfelder["ist_gfos" ]->wert;
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
    $ausgabe = "";
    foreach ($this->ausfelder as $key => $val) {
      $ausgabe .= sprintf( "<tr><td>%s<td>%s<td>%s\n", $val->kurzname, $val->langname, $key);
    }
    return "<table>\n" . $ausgabe . "\n</table>\n";
  }

  public function toTH() {
    $table_zeile = "\n<tr>";
    foreach ($this->ausfelder as $key => $val) {
      $table_zeile .= $val->header();
    }
    return $table_zeile;
  }

  public function toTR__( $zeilenfarbe = "") {
    if  ($bunt=($this->ausfelder["reine_arbeitszeit"]->wert != "" and
         $this->ausfelder["reine_arbeitszeit"]->wert != $this->ausfelder["i_arbzeit"]->wert)) {
      $rette_row_format_gfos = $this->ausfelder["reine_arbeitszeit"]->row_format;
      $rette_row_format_echt = $this->ausfelder["i_arbzeit"        ]->row_format;
      $this->ausfelder["reine_arbeitszeit"]->row_format = "<td class=\"ist_gfos\"> " . $this->fmt_std;
      $this->ausfelder["i_arbzeit"        ]->row_format = "<td class=\"ist_gfos\"> " . $this->fmt_std;
    }
    $table_zeile = "\n<tr$zeilenfarbe>";
    foreach ($this->ausfelder as $key => $val) {
      $table_zeile .= $val->row();
    }
    foreach ($this->ausfelder as $key => $val) {
      $val->wert = "";
    }
    if  ($bunt) {
      $this->ausfelder["reine_arbeitszeit"]->row_format = $rette_row_format_gfos;
      $this->ausfelder["i_arbzeit"        ]->row_format = $rette_row_format_echt;
    }
    return $table_zeile;
  }

  public function es_ist_sonntag() { // jeden sonntag noch ne zwischenzeile
    $datumsobjekt = datumsobjekt( $this->value["datum_auto"]);
    return 7 == $this->fmt_wochentagsnummer->format( $datumsobjekt);    // jeden montag noch ne zwischenzeile
  }

  public function es_ist_montag() { // jeden montag noch ne zwischenzeile
    $datumsobjekt = datumsobjekt( $this->value["datum_auto"]);
    return 1 == $this->fmt_wochentagsnummer->format( $datumsobjekt);    // jeden montag noch ne zwischenzeile
  }

  public function null_leer( $arg) {
    return $arg == 0 ? "null" : sprintf( "%05.2f", $this->wert / 100.0);
  }

  public function toTR_heutesalden( $letzte_zeile, $i_arbzeit) {
    if ($letzte_zeile) {                                                    // letzte zeile des tages
      $ges_heutearbzeit_gfos
        = $this->salden->heute_20_gfos
        + $this->salden->heute_50_gfos
        + $this->salden->heute_gfos
        ;
    # $ges_heutearbzeit_echt
    #   = $this->salden->heute_20_echt 
    #   + $this->salden->heute_50_echt
    #   ;
      $isd = $this->value["i_saldo_dauer"];
      $this->ausfelder["i_saldo"              ]->wert = $isd;
      $this->ausfelder["i_sald_kum"           ]->wert = (is_numeric( $isd) and $isd != 0) ? $isd-$this->salden->kum : "";
      $this->ausfelder["nacht_50_gfos"        ]->wert = $this->salden->heute_50_gfos;
      $this->ausfelder["nacht_50_gfos_bilanz" ]->wert = $this->salden->heute_50_gfos == 0 ? "" : $this->salden-> bilanz_50_gfos;
      $this->ausfelder["spät_20_gfos"         ]->wert = $this->salden->heute_20_gfos;
      $this->ausfelder["spät_20_gfos_bilanz"  ]->wert = $this->salden->heute_20_gfos == 0 ? "" : $this->salden-> bilanz_20_gfos;
      $this->ausfelder["nacht_50_echt"        ]->wert = $this->salden->heute_50_echt;
      $this->ausfelder["spät_20_echt"         ]->wert = $this->salden->heute_20_echt;
      $this->ausfelder["ges_wochenarbzeit"    ]->wert = $ges_heutearbzeit_gfos;
    # $this->ausfelder["ges_wochenarbecht"    ]->wert = $ges_heutearbzeit_echt;
    # $this->ausfelder["mehrarbeit_37_std"    ]->wert = $this->salden->heute_20_gfos + $this->salden->heute_50_gfos;
    # $this->ausfelder["mehrarbeit_37_std"    ]->wert = $this->salden->heute_gfos - $this->tarifliche_wochenarbeitszeit;
      $this->ausfelder["reine_arbeitszeit"    ]->wert = $this->salden->heute_gfos;
      $this->ausfelder["i_arbzeit"            ]->wert = $i_arbzeit;
      $this->rette_taegliche_daten();
      return $this->toTR__();
    } else {
      return $this->toTR__();
    }
  }

  public function toTR_sonntags_salden() { // jeden sonntag noch ne zwischenzeile
    if ($this->es_ist_sonntag()) {  // jeden sonntag noch ne zwischenzeile
      $erg = "";
      $auszahlung = $this->value["spaet_20"] > 0 or $this->value["nacht_50"];
      if ($auszahlung) {
        $this->ausfelder["pause_ges"           ]->wert = "";
        $this->ausfelder["saldo_kum"           ]->wert = "";
        $this->ausfelder["bemerkung"           ]->wert =                                                        "Auszahlung";
        $this->ausfelder["spät_20_gfos_bilanz" ]->wert = $this->value["spaet_20"];
        $this->ausfelder["nacht_50_gfos_bilanz"]->wert = $this->value["nacht_50"];
        $erg .= $this->toTR__( " class=\"auszahlung\"");
        $this->salden->dec_bilanz_zges( $this->value["spaet_20"], $this->value["nacht_50"]);
      }

      $mehrabeit_37 = max( $this->salden->woche_gfos - $this->salden->tarifliche_wochenarbeitszeit, 0);
      $this->salden->inc_mehrarbeit_37();
      $this->salden->inc_bilanz_über_333_bum();
      $woche_über_333_bum = $this->salden->woche_gfos - $this->salden->beschäftigungsumfang;

      $ges_wochenarbzeit_gfos
        = $this->salden->woche_20_gfos 
        + $this->salden->woche_50_gfos
        + $this->salden->woche_gfos
        ;
      $this->ausfelder["änd_kz"             ]->row_format = "<td colspan='5' class='links'> %s";
      $this->ausfelder["änd_kz"             ]->wert       =                                                     "Wochensummen";
      $this->ausfelder["kommt"              ]->row_format = "";
      $this->ausfelder["geht"               ]->row_format = "";
      $this->ausfelder["pause"              ]->row_format = "";
      $this->ausfelder["pause_ges"          ]->row_format = "";
      $this->ausfelder["saldo_kum"          ]->wert = "";
      $this->ausfelder["ist_gfos"           ]->wert = $this->salden->woche_gfos;
      $this->ausfelder["verfalle"           ]->wert = $this->salden->woche_verfall;
      $this->ausfelder["nacht_50_gfos"      ]->wert = $this->salden->woche_50_gfos;
      $this->ausfelder["spät_20_gfos"       ]->wert = $this->salden->woche_20_gfos;
      $this->ausfelder["nacht_50_echt"      ]->wert = $this->salden->woche_50_echt;
      $this->ausfelder["spät_20_echt"       ]->wert = $this->salden->woche_20_echt;
      $this->ausfelder["ges_wochenarbzeit"  ]->wert = $ges_wochenarbzeit_gfos;
      $this->ausfelder["woche_über_333_bum" ]->wert = $woche_über_333_bum;
      $this->ausfelder["mehrarbeit_37_std"  ]->wert = $mehrabeit_37;
      $this->ausfelder["reine_arbeitszeit"  ]->wert = $this->salden->woche_gfos;
      $this->set_datum( " class=\"sonntag\"");
      $erg .= $this->toTR__(    " class=\"wochensummen\"");

      $this->ausfelder["änd_kz"                ]->row_format = "<td colspan='5' class='links'> %s";
      $this->ausfelder["änd_kz"                ]->wert       =                                                  "Salden-Bilanz";
      $this->ausfelder["kommt"                 ]->row_format = "";
      $this->ausfelder["geht"                  ]->row_format = "";
      $this->ausfelder["pause"                 ]->row_format = "";
      $this->ausfelder["pause_ges"             ]->row_format = "";
      $this->ausfelder["saldo_kum"             ]->wert = $this->salden->kum;
      $this->ausfelder["verfalle"              ]->wert = $this->salden->bilanz_verfall;
      $this->ausfelder["nacht_50_gfos_bilanz"  ]->wert = $this->salden->bilanz_50_gfos;
      $this->ausfelder["spät_20_gfos_bilanz"   ]->wert = $this->salden->bilanz_20_gfos;
      $this->ausfelder["woche_über_333_bum"    ]->wert = $this->salden->bilanz_über_333_bum;
      $this->ausfelder["mehrarbeit_37_std"     ]->wert = $this->salden->mehrarbeit_37;
      $this->set_datum( " class=\"sonntag\"");
      $erg .= $this->toTR__(    " class=\"salden\"");
      
    # printf( "<td>s020 %s %s \n", $this->datumsobjekt->format( 'Y-m-d'), $this->datumsobjekt->Ymd());
      $this->salden->rette_salden_einer_woche( $this->datumsobjekt);
      $this->salden->reset_wochensalden();
      return $erg;
    }
  }

  public function toTR_montags_sollzeit() { // jeden montag noch ne zwischenzeile
    if ($this->es_ist_montag()) {  // jeden montag noch ne zwischenzeile
      $erg = $this->montags_sollzeit_hinzufügen();

      $erg .= $this->toTR__();
      $this->set_datum();
      return $erg;
    }

  }

  public function montags_sollzeit_hinzufügen() { // jeden montag noch ne zwischenzeile

    $this->salden->inc_soll(                     $this->salden->beschäftigungsumfang);
    $this->salden->dec_salden_kum(                     $this->salden->beschäftigungsumfang);
    $this->ausfelder["saldo_kum"             ]->wert = $this->salden->kum                  ;
    $this->ausfelder["soll"                  ]->wert = $this->salden->beschäftigungsumfang ;
    $this->ausfelder["tagnr"                 ]->wert = "";
    $this->ausfelder["tagnname"              ]->wert = "";
    $this->ausfelder["ist_gfos"              ]->wert = $this->salden->beschäftigungsumfang ;
  # $this->ausfelder["modulo"                ]->wert = "";
    $this->ausfelder["kommt"                 ]->wert = "";
    $this->ausfelder["geht"                  ]->wert = "";
    $this->ausfelder["bemerkung"             ]->wert = "Sollzeit";
  }

  function toTR_monatssummen() {                 
    $this->ausfelder["tagnr"                 ]->wert =                                                          "Monatssummen"; 
    $this->ausfelder["tagnr"                 ]->row_format = "<td class='links' colspan='7'> %s";
    $this->ausfelder["tagnname"              ]->row_format = "";
    $this->ausfelder["änd_kz"                ]->row_format = "";
    $this->ausfelder["kommt"                 ]->row_format = "";
    $this->ausfelder["geht"                  ]->row_format = "";
    $this->ausfelder["pause"                 ]->row_format = "";
    $this->ausfelder["pause_ges"             ]->row_format = "";
    $this->ausfelder["ist_gfos"              ]->row_format = "<td>";                    $this->ausfelder["ist_gfos"]->wert = $this->salden->bilanz_ist;
  # $this->ausfelder["modulo"                ]->row_format = "<td>";
    $this->ausfelder["soll"                  ]->row_format = "<td> " . $this->fmt_std ; $this->ausfelder["soll"    ]->wert = $this->salden->bilanz_soll;
  # $this->ausfelder["saldo_kum"             ]->row_format = "<td class=\"saldo_kum\"> " . $this->fmt_std;
  # $this->ausfelder["saldo_kum"             ]->wert = $this->salden->kum ;
    $this->ausfelder["verfalle"              ]->wert = $this->salden->monat_verfall;
    $this->ausfelder["spät_20_gfos"          ]->wert = $this->salden->monat_20_gfos;
  # $this->ausfelder["spät_20_gfos_bilanz"   ]->wert = $this->salden->bilanz_20_gfos;
    $this->ausfelder["nacht_50_gfos"         ]->wert = $this->salden->monat_50_gfos;
  # $this->ausfelder["nacht_50_gfos_bilanz"  ]->wert = $this->salden->bilanz_50_gfos;
    $this->ausfelder["spät_20_echt"          ]->wert = $this->salden->monat_20_echt;
    $this->ausfelder["nacht_50_echt"         ]->wert = $this->salden->monat_50_echt;
    return $this->toTR__();
  }

  function toTR_monatsüberträge() {
    $this->ausfelder["tagnr"                 ]->wert =                                                          "Übertrag";
    $this->ausfelder["tagnr"                 ]->row_format = "<td class='links' colspan='7'> %s";
    $this->ausfelder["tagnname"              ]->row_format = "";
    $this->ausfelder["änd_kz"                ]->row_format = "";
    $this->ausfelder["kommt"                 ]->row_format = "";
    $this->ausfelder["geht"                  ]->row_format = "";
    $this->ausfelder["pause"                 ]->row_format = "";
    $this->ausfelder["pause_ges"             ]->row_format = "";
    $this->ausfelder["saldo_kum"             ]->row_format = "<td class=\"saldo_kum\"> " . $this->fmt_std;
    $this->ausfelder["saldo_kum"             ]->wert = $this->salden->kum ;
    $this->ausfelder["verfalle"              ]->wert = $this->salden->bilanz_verfall;
  # $this->ausfelder["spät_20_gfos"          ]->wert = $this->salden->monat_20_gfos;
    $this->ausfelder["spät_20_gfos_bilanz"   ]->wert = $this->salden->bilanz_20_gfos;
  # $this->ausfelder["nacht_50_gfos"         ]->wert = $this->salden->monat_50_gfos;
    $this->ausfelder["nacht_50_gfos_bilanz"  ]->wert = $this->salden->bilanz_50_gfos;
  # $this->ausfelder["spät_20_echt"          ]->wert = $this->salden->monat_20_echt;
  # $this->ausfelder["nacht_50_echt"         ]->wert = $this->salden->monat_50_echt;
    return $this->toTR__();
  }

  function toTR_monatsvorträge() {
    $this->ausfelder["tagnr"                 ]->wert =                                                          "Vortrag aus dem Vormonat";
    $this->ausfelder["tagnr"                 ]->row_format = "<td class='links' colspan='7'> %s";
    $this->ausfelder["tagnname"              ]->row_format = "";
    $this->ausfelder["änd_kz"                ]->row_format = "";
    $this->ausfelder["kommt"                 ]->row_format = "";
    $this->ausfelder["geht"                  ]->row_format = "";
    $this->ausfelder["pause"                 ]->row_format = "";
    $this->ausfelder["pause_ges"             ]->row_format = "";
    $this->ausfelder["ist_gfos"              ]->row_format = "<td>";                    $this->ausfelder["ist_gfos"]->wert = $this->salden->bilanz_ist;
  # $this->ausfelder["modulo"                ]->row_format = "<td>";
    $this->ausfelder["soll"                  ]->row_format = "<td> " . $this->fmt_std ; $this->ausfelder["soll"    ]->wert = $this->salden->bilanz_soll;
    $this->ausfelder["saldo_kum"             ]->row_format = "<td class=\"saldo_kum\"> " . $this->fmt_std;
    $this->ausfelder["saldo_kum"             ]->wert = $this->salden->kum ;
    $this->ausfelder["verfalle"              ]->wert = $this->salden->bilanz_verfall;
    $this->ausfelder["spät_20_gfos"          ]->wert = $this->salden->monat_20_gfos;
    $this->ausfelder["spät_20_gfos_bilanz"   ]->wert = $this->salden->bilanz_20_gfos;
    $this->ausfelder["nacht_50_gfos"         ]->wert = $this->salden->monat_50_gfos;
    $this->ausfelder["nacht_50_gfos_bilanz"  ]->wert = $this->salden->bilanz_50_gfos;
    $this->ausfelder["spät_20_echt"          ]->wert = $this->salden->monat_20_echt;
    $this->ausfelder["nacht_50_echt"         ]->wert = $this->salden->monat_50_echt;
    return $this->toTR__();
  }

  function set_datum( $sonntagsfarbe = "") {
    $this->datumsobjekt = new datum_objekt( $this->value["datum_auto"]);
    $this->ausfelder["tagnr"      ]->wert =        $this->datumsobjekt->format( "d");
    $this->ausfelder["tagnname"   ]->wert =        $this->datumsobjekt->tagesname()  ;
 #  $this->ausfelder["tagnr"      ]->wert =        $this->datumsobjekt->tagesnummer();
 #  $this->ausfelder["tagnname"   ]->wert =        $this->datumsobjekt->tagesname()  ;
#   printf( "<td>s030§%s§%s§%s\n", $this->datumsobjekt->format( 'Y-m-d'), $this->value["datum_auto"],  $this->datumsobjekt->Ymd());
#   $this->ausfelder["tagnname"   ]->row_format = "<td$sonntagsfarbe> %s ";
#   $this->ausfelder["tagnname"   ]->row_format = "<td class=\"sonntag\"> %s ";
  }

  function __construct( $value, $salden, $rechner) {
    $this->fmt_std = "%05.2f";
  # $this->fmt_std = "%s";
    $this->zeile_rechne = $rechner;
    $this->salden = $salden;
    $this->value = $value;
    $this->ausfelder = array (
      "tagnr"               => new  gfos_ausgabe_element("tag"       , "Nr. des Tages im Monat", " ", "<th colspan='2'> %s ", "<td> %s "),
      "tagnname"            => new  gfos_ausgabe_element("tagname"   , "Kurzname des Tages"    , " ", "        ", "<td class=\"links\">%s"),
      "änd_kz"              => new  gfos_ausgabe_element("ä"         , "gf*s Änderungskz."     , " ", "<th> %s ", "<td> %s "),
      "kommt"               => new  gfos_ausgabe_element("kom"       , "gf*s Kommt"            , " ", "<th> %s ", "<td> %s "),
      "geht"                => new  gfos_ausgabe_element("geh"       , "gf*s Geht"             , " ", "<th> %s ", "<td> %s "),
      "pause"               => new  gfos_ausgabe_element("paus"      , "gf*s Pause"            , " ", "<th> %s ", "<td> %s "),
      "pause_ges"           => new  gfos_ausgabe_element("pges"      , "gf*s Pause ges."       , " ", "<th> %s ", "<td> %s "),
      "ist_gfos"            => new  gfos_ausgabe_element(" ist"      , "gf*s Ist"              , "h", "<th> %s ", "<td>  " . $this->fmt_std),
    # "modulo"              => new  gfos_ausgabe_element(" mod"      , "Modulo"                , " ", "<th> %s ", "<td> %s "),
      "soll"                => new  gfos_ausgabe_element("soll"      , "gf*s Soll"             , "h", "<th> %s ", "<td>  " . $this->fmt_std),
      "saldo_kum"           => new  gfos_ausgabe_element("skum"      , "gf*s Saldo kum"        , "y", "<th> %s ", "<td> %s "),
      "fehlzeit_zeit"       => new  gfos_ausgabe_element("fehz"      , "gf*s Fehlzeit (Zeit)"  , " ", "<th> %s ", "<td class=\"links\">%s"),
    # "fehlzeit_text"       => new  gfos_ausgabe_element("fehl"      , "Fehlzeit"              , " ", "<th> %s ", "<td> %s "),
    # "vst"                 => new  gfos_ausgabe_element(" vst"      , "VST"                   , " ", "<th> %s ", "<td> %s "),
      "bemerkung"           => new  gfos_ausgabe_element("bemerkung" , "gf*s Bemerkung"        , " ", "<th> %s ", "<td class=\"links\">%s"),
      "autorisiert_anf"     => new  gfos_ausgabe_element("auto"      , "Anfang autorisiert"    , " ", "<th> %s ", "<td> %s "),
    # "autorisiert_ende"    => new  gfos_ausgabe_element("ende"      , "Ende autorisiert"      , " ", "<th> %s ", "<td> %s "),
      "reine_arbeitszeit"   => new  gfos_ausgabe_element("rein"      , "Reine Arbeitszeit"     , "h", "<th> %s ", "<td>  " . $this->fmt_std),
      "spät_20_gfos"        => new  gfos_ausgabe_element("20%g"      , "Spätzuschlag 20%"      , "h", "<th> %s ", "<td>  " . $this->fmt_std),
      "nacht_50_gfos"       => new  gfos_ausgabe_element("50%g"      , "Nachtzuschlag 50%"     , "h", "<th> %s ", "<td>  " . $this->fmt_std),
      "ges_wochenarbzeit"   => new  gfos_ausgabe_element("gewo"      , "gesamte Arbeitszeit"   , "h", "<th> %s ", "<td>  " . $this->fmt_std),
      "verfalle"            => new  gfos_ausgabe_element("verf"      , "Verfallszeit"          , "h", "<th> %s ", "<td>  " . $this->fmt_std),
      "woche_über_333_bum"  => new  gfos_ausgabe_element(">33.3"     , "Mehr als Besch.Umfang" , "h", "<th> %s ", "<td>  " . $this->fmt_std),
      "i_saldo"             => new  gfos_ausgabe_element("isal"      , "Infotaste Saldo"       , "h", "<th> %s ", "<td>  " . $this->fmt_std),
      "i_sald_kum"          => new  gfos_ausgabe_element("i-ku"    , "Info Saldo - Saldo kum"  , "h", "<th> %s ", "<td>  " . $this->fmt_std),
      "spät_20_gfos_bilanz" => new  gfos_ausgabe_element("20su"    , "Spätzuschlag 20% Summe", "x", "<th> %s ", "<td>%s"),
      "spät_20_echt"        => new  gfos_ausgabe_element("20%e"      , "Spätzuschlag 20%"      , "h", "<th> %s ", "<td>  " . $this->fmt_std),
      "nacht_50_gfos_bilanz"=> new  gfos_ausgabe_element("50su"   , "Nachtzuschlag 50% Summe", "x", "<th> %s ", "<td>%s"),
      "nacht_50_echt"       => new  gfos_ausgabe_element("50%e"      , "Nachtzuschlag 50%"     , "h", "<th> %s ", "<td>  " . $this->fmt_std),
      "mehrarbeit_37_std"   => new  gfos_ausgabe_element(">37"       , "Mehrarbeit in Woche"   , "h", "<th> %s ", "<td>  " . $this->fmt_std),
    # "ges_wochenarbecht"   => new  gfos_ausgabe_element("echt"      , "gesamte Arbeitsecht"   , "h", "<th> %s ", "<td>  " . $this->fmt_std),
      "i_arbzeit"           => new  gfos_ausgabe_element("iarb"     , "Infotaste Arbeitszeit"  , "h", "<th> %s ", "<td>  " . $this->fmt_std),
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

  public function minHMleer( $wert) {
    return $wert < 0 ? "" : $this->minToHHMM( $wert);
  }

  public function minToHHMM( $wert) {
    return sprintf( "%02d.%02d", $wert/60, $wert%60);
  }

  private function kkk( $arg) { fwrite( $this->fh, ($arg == "  " ? $arg : sprintf( "%+d", $arg) ) . " \n");}

  function runde_dixx( $minuend, $subtrahend) {  // Erwartet Minuten, liefert hundertstel Stunden
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

class kommt_geht {
  public $zeit;
  public $art;
  function __construct( $value, $art) {
    $this->art  = $art;
    $this->zeit = $value[$art];
  }
}

class za_liste_obsolet {
  public $datum;
  public $art;
  function __construct( $value, $art) {
    $this->art  = $art;
    $this->zeit = $value[$art];
  }
}

class ein_monat {
  private $ein_tag       ;
  private $tabelle       ;
  private $daten_2D      ;
  private $normal_2D     ;
  private $conn          ;
  private $kalkulator    ;
  private $start_datum   ;
  private $database_name ;
  private $table_name    ;
  private $aktualisiert  ;
  function __construct( $welcher_monat, $taschenrechner) { //  Noch zu berücksichtigen i_saldo_datum
    //                                                  und           i_arbzeit_dauer i_arbzeit_datum

    $this->start_datum = new ein_datum( $welcher_monat == "" ? "first day of this month" : $welcher_monat);
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
    /*
     */
    $this->aktualisiert = $conn->hol_einen_wert( "SELECT MAX(aktualisiert) as xxx FROM $this->table_name $where", "xxx");

    // Hole die nicht normaliserten Daten aus der Datenbank
    $this->daten_2D = $conn->hol_array_of_objects( "$query");
    $normal_2D = array();
  }

  public function i_taste( $database_name, $table_name, $datum) {
    $query = sprintf( "SELECT i_arbzeit_dauer FROM %s WHERE i_arbzeit_datum = '%s'", $table_name, $datum);
    $conn = new conn();
    $erg = $conn->frage( 0, "USE $database_name");
    $schon_da = $conn->hol_array_of_objects( "$query", 0); // todo Fehlerbehandlung
    return $schon_da ? (int) (100 * $schon_da[0]["i_arbzeit_dauer"]) : "";
  }

  function in_diesem_monat_gearbeitete_zeit( $salden) {
    $monerg = "";
    $gfos_titel = "sali 2.1-HS Sausekonto";
    $gfos_titel = "gf*s 4.7p1us ZeitK0NT0";
    $monerg .= sprintf( "<h3 style=\"text-align: center\">%s — %s </h3><br />\n", $gfos_titel, $this->start_datum->format( "MMMM yyyy"));
    $gfos_zeile = new gfos_zeile( "", $salden, $this->kalkulator);
    $monerg .= sprintf("Die Daten wurden zuletzt aktualisiert : %s\n", $this->aktualisiert);

    $monerg .= sprintf( "<table cellspacing=\"0\" cellpadding=\"2\" border=\"1\"> \n");
    $monerg .= $gfos_zeile->toTR_monatsvorträge();
    $monerg .= $gfos_zeile->toTH();

    for ($zeilennummer=0; $zeilennummer<count( $this->normal_2D); $zeilennummer++) { // Soviele Tage enthält die Datenbank
    //foreach ($this->normal_2D as $zeilennummer => $value) {
      $value = $this->normal_2D[$zeilennummer];
#$salden->set_ist_und_soll( 0.0, 0.0);
      $mit_pausen_geplante_arbeitszeit = $value["arbzeit_plan_ende"  ] - $value["arbzeit_plan_anfang"];

      $pause_ges = (new pause)->get_pausenzeit_in_stunden( $mit_pausen_geplante_arbeitszeit);
      $pause     = $pause_ges;

      $kommt_geht = array();
      if ($value["arbeit_kommt"] >= 0) { $kommt_geht[] = new kommt_geht( $value, "arbeit_kommt");}
      if ($value["pause1_geht" ] >= 0) { $kommt_geht[] = new kommt_geht( $value, "pause1_geht" );}
      if ($value["pause1_kommt"] >= 0) { $kommt_geht[] = new kommt_geht( $value, "pause1_kommt");}
      if ($value["pause2_geht" ] >= 0) { $kommt_geht[] = new kommt_geht( $value, "pause2_geht" );}
      if ($value["pause2_kommt"] >= 0) { $kommt_geht[] = new kommt_geht( $value, "pause2_kommt");}
      if ($value["arbeit_geht" ] >= 0) { $kommt_geht[] = new kommt_geht( $value, "arbeit_geht" );}
      if (count( $kommt_geht) % 2 == 1) {
        $monerg .= sprintf( "Z099 datum=%s Eine \"Kommt-\" oder \"Geht\"-Zeit fehlt oder ist überzählig <br />\n", $value["datum_auto"]);
        continue; // mit dem nächsten Tag
      }
      $gfos_zeile = new gfos_zeile( $value, $gfos_zeile->salden, $this->kalkulator);
      $gfos_zeile->salden->reset_heutesalden();
      $gfos_zeile->set_datum();

      switch ( $value["erscheine"]) {
      case "BA"       :
            $monerg .= $gfos_zeile->toTR_montags_sollzeit();
            $gfos_zeile->set_bemerkung_und_fehz( "Betriebsausschuss"    , "br");
            $gfos_zeile->set_ist_gfos_von_dauer_oder_kommt_und_geht();
            $gfos_zeile->inkrementiere_salden();
            $gfos_zeile->rette_taegliche_daten();
            $monerg .= $gfos_zeile->toTR__();
      break;
      case "BR"       :
            $monerg .= $gfos_zeile->toTR_montags_sollzeit();
            $gfos_zeile->set_ist_gfos_von_dauer_oder_kommt_und_geht();
            $gfos_zeile->set_bemerkung_und_fehz( "Betriebsrat"          , "br");
            $gfos_zeile->inkrementiere_salden();
            $gfos_zeile->rette_taegliche_daten();
            $monerg .= $gfos_zeile->toTR__();
      break;
      case "BV"       :
            $monerg .= $gfos_zeile->toTR_montags_sollzeit();
            $gfos_zeile->set_bemerkung_und_fehz( "Betriebsversammlung"  , "br");
            $gfos_zeile->set_ist_gfos_von_dauer_oder_kommt_und_geht();
            $gfos_zeile->inkrementiere_salden();
            $gfos_zeile->rette_taegliche_daten();
            $monerg .= $gfos_zeile->toTR__();
      break;
      case "Seminar"  :
            $monerg .= $gfos_zeile->toTR_montags_sollzeit();
            $gfos_zeile->set_bemerkung_und_fehz( "Seminar"              , "br");
            $gfos_zeile->set_ist_gfos_von_dauer_oder_kommt_und_geht();
            $gfos_zeile->inkrementiere_salden();
            $gfos_zeile->rette_taegliche_daten();
            $monerg .= $gfos_zeile->toTR__();
      break;
      case "Feiertag" :
            $monerg .= $gfos_zeile->toTR_montags_sollzeit();
            $gfos_zeile->set_bemerkung_und_fehz( $value["erscheine"]    , "fei");
         #  $gfos_zeile->set( "soll", "");
            $gfos_zeile->set_ist_gfos_von_dauer_oder_kommt_und_geht();
            $gfos_zeile->inkrementiere_salden();
            $gfos_zeile->rette_taegliche_daten();
            $monerg .= $gfos_zeile->toTR__();
      break;
      case "plan"     :
      case "krank"    :
            $monerg .= $gfos_zeile->toTR_montags_sollzeit();
            $gfos_zeile->set_bemerkung_und_fehz( $value["erscheine"]    , ""  );
         #  $gfos_zeile->set( "soll", "");
            $gfos_zeile->set_ist_gfos_von_dauer_oder_geplant();
            $gfos_zeile->inkrementiere_salden();
            $gfos_zeile->rette_taegliche_daten();
            $monerg .= $gfos_zeile->toTR__();
      break;
      case "Urlaub"   :
            $monerg .= $gfos_zeile->toTR_montags_sollzeit();
            $gfos_zeile->set_bemerkung_und_fehz( $value["erscheine"]    , "u" );
         #  $gfos_zeile->set( "soll", "");
            $gfos_zeile->set( "ist_gfos", $value["arbzeit_plan_dauer"]);
            $gfos_zeile->inkrementiere_salden();
            $gfos_zeile->rette_taegliche_daten();
            $monerg .= $gfos_zeile->toTR__();
      break;
      case "frei"     :
            $monerg .= $gfos_zeile->toTR_montags_sollzeit();
            $gfos_zeile->set_bemerkung_und_fehz( $value["erscheine"]    , "f");
            $gfos_zeile->rette_taegliche_daten();
            $monerg .= $gfos_zeile->toTR__();
      break;
      default         :
#       printf( "auto010 %s %s\n", $value["arbanf_autorisiert"], $value["arbende_autorisiert"]);
      $fm_pause = "%.2f";
      $gfos_zeile->set( "pause_ges", sprintf( $fm_pause, $pause_ges));
      $gfos_zeile->set( "autorisiert_anf", $this->toText( $value["arbanf_autorisiert"  ]) . " "
                                    . $this->toText( $value["arbende_autorisiert" ]));
#     $gfos_zeile->set( "autorisiert_ende", $this->toText( $value["arbende_autorisiert"]);

      for ($ii = 0; $ii < count( $kommt_geht); $ii++) {
        $gfos_zeile->set( "kommt", $this->kalkulator->minToHHMM( $kommt_geht[$ii  ]->zeit));
        $gfos_zeile->set( "geht", $this->kalkulator->minToHHMM( $kommt_geht[$ii+1]->zeit));
        $gfos_zeile->set( "pause", $pause > 0.0 ? sprintf( $fm_pause, $pause) : "");
        $pause = $pause > 0.25 ? $pause - 0.25 : 0.0;
                                                                                                        // Autorisierung
        $plan_ende = max( $value["arbzeit_plan_ende"], $value["arbende_autorisiert"]); // $value["arbende_autorisiert"] kann -1 sein
        $endzeit   = min( $kommt_geht[$ii+1]->zeit, $plan_ende);
                                                                                                        // verfrühte Arbeitsaufnahme
        $plan_anfang = $value["arbanf_autorisiert"] < 0                                // $value["arbende_autorisiert"] kann -1 sein
          ? $value["arbzeit_plan_anfang"]
          : min( $value["arbzeit_plan_anfang"], $value["arbanf_autorisiert"])
          ;
#       printf( "auto%s planf%s kogg%s \n", $value["arbanf_autorisiert"], $plan_anfang, $kommt_geht[$ii]->zeit);
        $anfangszeit = max( $plan_anfang, $kommt_geht[$ii]->zeit);

        $ist_gfos      = $this->kalkulator->runde_dixx(    $endzeit                , $anfangszeit);
        $spät_20_gfos  = $this->kalkulator->runde_zwanzig( $endzeit                , $anfangszeit);
        $nacht_50_gfos = $this->kalkulator->runde_fünfzig( $endzeit                , $anfangszeit);
        $ist_echt      = $this->kalkulator->runde_dixx(    $kommt_geht[$ii+1]->zeit, $kommt_geht[$ii]->zeit);
        $spät_20_echt  = $this->kalkulator->runde_zwanzig( $kommt_geht[$ii+1]->zeit, $kommt_geht[$ii]->zeit);
        $nacht_50_echt = $this->kalkulator->runde_fünfzig( $kommt_geht[$ii+1]->zeit, $kommt_geht[$ii]->zeit);

        $gfos_zeile->set( "ist_gfos"     , $ist_gfos      );
        $gfos_zeile->set( "spät_20_gfos" , $spät_20_gfos  );
        $gfos_zeile->set( "nacht_50_gfos", $nacht_50_gfos );
        $gfos_zeile->set( "spät_20_echt" , $spät_20_echt  );
        $gfos_zeile->set( "nacht_50_echt", $nacht_50_echt );

        $gfos_zeile->set( "verfalle",
            $ist_echt      - $ist_gfos
          + $spät_20_echt  - $spät_20_gfos 
          + $nacht_50_echt - $nacht_50_gfos
        );
  
        # $gfos_zeile->set( "modulo",                  (($endzeit- $kommt_geht[$ii]->zeit)     ) . " § " . $kommt_geht[$ii]->zeit);

        $gfos_zeile->inkrementiere_salden();

        $ii++;
        $monerg .= $gfos_zeile->toTR_heutesalden(                                                     // nur wenn letzte Zeile des Tages
          $ii+1 >= count( $kommt_geht),                                                    // nur wenn letzte Zeile des Tages
          $this->i_taste( $this->database_name, $this->table_name, $value["datum_auto"])   // nur wenn letzte Zeile des Tages
        );                                                                                 // nur wenn letzte Zeile des Tages
      }
      $monerg .= $gfos_zeile->toTR_sonntags_salden();
      $monerg .= $gfos_zeile->toTR_montags_sollzeit();

      break;
      }

    }
    $gfos_zeile = new gfos_zeile( "", $gfos_zeile->salden, $this->kalkulator);
    $monerg .= $gfos_zeile->toTR_monatssummen();
    $monerg .= $gfos_zeile->toTR_monatsüberträge();
    $monerg .= sprintf( "</table> \n");
    $gfos_zeile->salden->reset_monatssalden();
    return $monerg;
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

  function zeige_die_verwendeten_normalisierten_daten() {
    $erg = "";
#     (new ein_datum( $this->normal_2D[0]["datum_auto"]))->format( "MMMM yyyy"));
    // Kolumnennamen als header
    if (false) foreach ($this->normal_2D[0] as $kolumne=>$wert) {
      $erg .= sprintf( "%s \n", $kolumne);
    }
    $erg .= sprintf( "<tr>");
    $erg .= sprintf( "<th>%s", "Datum"); 
    $erg .= sprintf( "<th>%s", "ersch"); 
    $erg .= sprintf( "<th>%s", "daur" ); 
    $erg .= sprintf( "<th>%s", "auta" ); 
    $erg .= sprintf( "<th>%s", "panf" ); 
    $erg .= sprintf( "<th>%s", "kom"  ); 
    $erg .= sprintf( "<th>%s", "geh"  ); 
    $erg .= sprintf( "<th>%s", "kom"  ); 
    $erg .= sprintf( "<th>%s", "geh"  ); 
    $erg .= sprintf( "<th>%s", "kom"  ); 
    $erg .= sprintf( "<th>%s", "geh"  ); 
    $erg .= sprintf( "<th>%s", "plen" ); 
    $erg .= sprintf( "<th>%s", "aute" ); 
    $erg .= sprintf( "<th>%s", "vrls" ); 
    $erg .= sprintf( "<th>%s", "idau" ); 
    $erg .= sprintf( "<th>%s", "idat" ); 
    $erg .= sprintf( "<th>%s", "sdau" ); 
    $erg .= sprintf( "<th>%s", "id"   ); 
    $erg .= sprintf( "<th>%s", "sdat" ); 
    $erg .= sprintf( "<th>%s", "20%"  ); 
    $erg .= sprintf( "<th>%s", "50%"  ); 
    $erg .= sprintf( "<th>%s", "25%"  ); 
    $erg .= sprintf( "<th>%s", "sges" ); 
    $erg .= sprintf( "</tr>\n");

    $i = 0;
    foreach ($this->normal_2D as $zeilennummer=>$value) {
      $erg .= sprintf( "<tr>");
      $erg .= sprintf( "<td> %s",                $this->normal_2D[$i]["datum_auto"          ] );
      $erg .= sprintf( "<td> %s",                $this->normal_2D[$i]["erscheine"           ] );
      $erg .= sprintf( "<td> %s",                $this->normal_2D[$i]["arbzeit_plan_dauer"  ] );
      $erg .= sprintf( "<td> %s", $this->toText( $this->normal_2D[$i]["arbanf_autorisiert"  ]));
      $erg .= sprintf( "<td> %s", $this->toText( $this->normal_2D[$i]["arbzeit_plan_anfang" ]));
      $erg .= sprintf( "<td> %s", $this->toText( $this->normal_2D[$i]["arbeit_kommt"        ]));
      $erg .= sprintf( "<td> %s", $this->toText( $this->normal_2D[$i]["pause1_geht"         ]));
      $erg .= sprintf( "<td> %s", $this->toText( $this->normal_2D[$i]["pause1_kommt"        ]));
      $erg .= sprintf( "<td> %s", $this->toText( $this->normal_2D[$i]["pause2_geht"         ]));
      $erg .= sprintf( "<td> %s", $this->toText( $this->normal_2D[$i]["pause2_kommt"        ]));
      $erg .= sprintf( "<td> %s", $this->toText( $this->normal_2D[$i]["arbeit_geht"         ]));
      $erg .= sprintf( "<td> %s", $this->toText( $this->normal_2D[$i]["arbzeit_plan_ende"   ]));
      $erg .= sprintf( "<td> %s", $this->toText( $this->normal_2D[$i]["arbende_autorisiert" ]));
      $erg .= sprintf( "<td> %s", $this->toText( $this->normal_2D[$i]["verlasse"            ]));
      $erg .= sprintf( "<td> %s",                $this->normal_2D[$i]["i_arbzeit_dauer"     ] );
      $erg .= sprintf( "<td> %s",                $this->normal_2D[$i]["i_arbzeit_datum"     ] );
      $erg .= sprintf( "<td> %s",                $this->normal_2D[$i]["i_saldo_dauer"       ] );
      $erg .= sprintf( "<td> %s",                $this->normal_2D[$i]["id"       ] );
      $erg .= sprintf( "<td> %s",                $this->normal_2D[$i]["i_saldo_datum"       ] );
      $erg .= sprintf( "<td> %s",                $this->normal_2D[$i]["spaet_20"            ] );
      $erg .= sprintf( "<td> %s",                $this->normal_2D[$i]["nacht_50"            ] );
      $erg .= sprintf( "<td> %s",                $this->normal_2D[$i]["ang_25"              ] );
      $erg .= sprintf( "<td> %s",                $this->normal_2D[$i]["zges_woche"          ] );
      $erg .= sprintf( "</tr>\n");
      $i++;
    }
/* im Minuten
 */
    $erg .= sprintf( "<tr>");
    $value = $this->normal_2D[0];
    foreach ($value as $kolumne=>$wert) {
      // $erg .= sprintf( "<td> %s ", $this->toText( $kolumne, $wert));
      $erg .= sprintf( "<td class='links'> %s\n", str_replace( "_", " ", $kolumne));
    }
    $erg .= sprintf( "</tr>\n");
    
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
      $this->normal_2D[$i]["arbanf_autorisiert" ]  = $this->toMin( $value["arbanf_autorisiert"  ]);
      $this->normal_2D[$i]["arbzeit_plan_anfang"]  = $this->toMin( $value["arbzeit_plan_anfang" ]);
      $this->normal_2D[$i]["arbeit_kommt"       ]  = $this->toMin( $value["arbeit_kommt"        ]);
      $this->normal_2D[$i]["pause1_geht"        ]  = $this->toMin( $value["pause1_geht"         ]);
      $this->normal_2D[$i]["pause1_kommt"       ]  = $this->toMin( $value["pause1_kommt"        ]);
      $this->normal_2D[$i]["pause2_geht"        ]  = $this->toMin( $value["pause2_geht"         ]);
      $this->normal_2D[$i]["pause2_kommt"       ]  = $this->toMin( $value["pause2_kommt"        ]);
      $this->normal_2D[$i]["arbeit_geht"        ]  = $this->toMin( $value["arbeit_geht"         ]);
      $this->normal_2D[$i]["arbzeit_plan_ende"  ]  = $this->toMin( $value["arbzeit_plan_ende"   ]);
      $this->normal_2D[$i]["arbende_autorisiert"]  = $this->toMin( $value["arbende_autorisiert" ]);
      $this->normal_2D[$i]["verlasse"           ]  = $this->toMin( $value["verlasse"            ]);
      $this->normal_2D[$i]["i_arbzeit_dauer"    ]  = $this->toHun( $value["i_arbzeit_dauer"     ]);
      $this->normal_2D[$i]["i_arbzeit_datum"    ]  =               $value["i_arbzeit_datum"     ] ;
      $this->normal_2D[$i]["i_saldo_dauer"      ]  = $this->toHun( $value["i_saldo_dauer"       ]);
      $this->normal_2D[$i]["id"                 ]  =               $value["id"                  ] ;
      $this->normal_2D[$i]["i_saldo_datum"      ]  =               $value["i_saldo_datum"       ] ;
      $this->normal_2D[$i]["spaet_20"           ]  = $this->toHun( $value["spaetzuschlag_20"    ]);
      $this->normal_2D[$i]["nacht_50"           ]  = $this->toHun( $value["nachtzuschlag_50"    ]);
      $this->normal_2D[$i]["ang_25"             ]  = $this->toHun( $value["ang_zuschlag_25"     ]);
      $this->normal_2D[$i]["zges_woche"         ]  = $this->toHun( $value["saldo_zges_woche"    ]);
      $i++;
    }
  }
}

function erprobe_rechne( rechne $rechne) {
  $erg = "\n";
  $erg .= sprintf( "Beispiele für's Runden beim Umrechnen von Uhrzeitdifferenzen in hundertstel Stunden.<br />\n");
  $erg .= sprintf( "gf*s macht's regelgerecht und ehrlich.<br />\n");
  $erg .= sprintf( "Zeitdifferenzen wie  2.033 h oder  2.083 h werden auf 2.04 h und 2.09 h aufgerundet,<br />\n");
  $erg .= sprintf( "Zeitdifferenzen wie  2.017 h oder  2.067 h werden auf 2.01 h und 2.06 h abgerundet.<br />\n");
  $erg .= sprintf( "Im Durchschnitt treten diese Auf- und Abrundungen gleich häufig auf,<br />\n");
  $erg .= sprintf( "so dass sich die Rundungsfehler über längere Abrechnungszeiträume aufheben.<br />\n");
  for ($minuend=120; $minuend<135; $minuend++) {
    for ($komme=600; $komme<603; $komme++) {
      $gehe = $minuend+$komme;
        $xx = $rechne->runde_dixx( $gehe, $komme);
      $erg .= sprintf( "(%s bis %s) (%3d - %3d) min  =  %5.2f h %6.3f%+d  #_#  ",
        $rechne->minToHHMM( $komme),
        $rechne->minToHHMM( $gehe),
        $gehe,
        $komme,
        $xx / 100.0,
        ($gehe - $komme) / 60.0,
        $xx - $rechne->runde_diff( $gehe, $komme)
      );
    }
    $erg .= sprintf( "<br />\n");
  }
  $erg .= sprintf( "<br />\n");
  $erg .= "<pre>                                              \n";
  $erg .= "                                            gfos   \n";
  $erg .= "05.12.2015 von 10:00 bis 12:58 = 2h 58min = 2.97 h \n";
  $erg .= "28.12.2015 von 17:17 bis 20:15 = 2h 58min = 2.96 h \n";
  $erg .= "                                                   \n";
  $erg .= "26.09.2015 von 11.45 bis 14.35 = 2h 50min = 2.84 h \n";
  $erg .= "20.08 2015 von 17.25 bis 20.15 = 2h 50min = 2.83 h \n";
  $erg .= "                                                   \n";
  $erg .= "</pre>                                             \n";
  $erg .= "<table border>                                     \n";
  $erg .= "<tr><th>     <th>   <th colspan=3> Minuend         \n";
  $erg .= "<tr><th> Sub <th>   <th> 0 <th>  1 <th>  2         \n";
  $erg .= "<tr><td> tra <td> 0 <td> 0 <td>  1 <td>  2         \n";
  $erg .= "<tr><td> hen <td> 1 <td> 2 <td>  0 <td>  1         \n";
  $erg .= "<tr><td> d   <td> 2 <td> 1 <td>  2 <td>  0         \n";
  $erg .= "</table>                                           \n";
  $erg .= "                                                   \n";
  $erg .= "<table border>                                     \n";
  $erg .= "<tr><th>     <th>   <th colspan=3> Differenz       \n";
  $erg .= "<tr><th> Sub <th>   <th> 0 <th>  1 <th>  2         \n";
  $erg .= "<tr><td> tra <td> 0 <td> 0 <td>  1 <td>  2         \n";
  $erg .= "<tr><td> hen <td> 1 <td> 1 <td>  2 <td>  0         \n";
  $erg .= "<tr><td> d   <td> 2 <td> 2 <td>  0 <td>  1         \n";
  $erg .= "</table>                                           \n";
  return $erg;
}

function schleife( DateTime $laufobjekt, DateTime $stopobjekt, salden $salden) {
  $ges_erg = "";
  $ein_rechner = new rechne;
  $ges_erg .= (new gfos_zeile( null, null, $ein_rechner))->erkläre_abkürzungen() . "<br />\n";
  $ges_erg .= sprintf( "<h3 style=\"text-align: center\"> Am %s sind die Konten ausgeglichen.<br />\n \"%s\", \"%s\" und \"%s\" stehen also auf 0.</h3><br />\n",
    "4.Januar 2015",
    "skum",
    "20su",
    "50su"
  );
  $ges_erg .= "<div class=\"page-break\"></div>\n";
  
  switch ($laufobjekt->format('Y-m')) {                                    // Anfangswerte
  case "2014-12" : $salden->set_kum_und_echt( -  922 + 809-363, -  922 + 809-363);
                                                               $salden->set_bilanz_pl_min_333_gfos(  3043); break;
                                                            // $salden->set_bilanz_zges(        0,     0);  break;
  case "2015-01" : $salden->set_kum_und_echt( - 2881, - 2881); $salden->set_bilanz_pl_min_333_gfos(    0);  break;
  case "2015-02" : $salden->set_kum_und_echt( -  732, -  732);                                            break;
  case "2015-03" : $salden->set_kum_und_echt( -  648, -  648);                                            break;
  case "2015-04" : $salden->set_kum_und_echt( - 1228, - 1228);                                            break;
  case "2015-05" : $salden->set_kum_und_echt(    687,    687);                                            break;
  case "2015-06" : $salden->set_kum_und_echt(   1887,   1887);                                            break;
  case "2015-07" : $salden->set_kum_und_echt( - 1879, - 1879);                                            break;
  case "2015-08" : $salden->set_kum_und_echt(    342,    342);                                            break;
    default : break;
    }
  $salden->set_ist_und_soll( 0, 0);                                           // hundertstel
  $intervall = new DateInterval( 'P1M');
  while ( $laufobjekt < $stopobjekt) {
  # printf( "lauf=%s stop=%s saldo_kum=%s  summe_verfall=%s <br />\n",
  #   $laufobjekt->format('Y-m-d'),
  #   $stopobjekt->format('Y-m-d'),
  #   $salden->kum,
  #   $salden->bilanz_verfall
  # );

    $ein_monat = new ein_monat( $laufobjekt->format('Y-m'), $ein_rechner);
    $ein_monat->kopiere_und_normalisiere();
  # $salden = $ein_monat->in_diesem_monat_gearbeitete_zeit( $salden);  //
    $ges_erg .= $ein_monat->in_diesem_monat_gearbeitete_zeit( $salden);  //
    $salden->set_ist_und_soll( 0, 0);
    $laufobjekt->add( $intervall);
    if ($salden->wie_gfos) {
      switch ($laufobjekt->format('Y-m')) {                                    // Korrekturen zwischendurch
        //                                  vorher         nachher  
        case "2016-03" : $ges_erg .= $salden->falscher_saldoübertrag(  - 2545        ,  - 3190); break;
        case "2016-02" : $ges_erg .= $salden->falscher_saldoübertrag(    3285        ,  -  226); break;
        case "2015-12" : $ges_erg .= $salden->falscher_saldoübertrag(    1988        ,    1709); break;
        case "2015-11" : $ges_erg .= $salden->falscher_saldoübertrag(    2056        ,    2941); break;
        case "2015-08" : $ges_erg .= $salden->falscher_saldoübertrag(    1798        ,     342); break;
        case "2015-07" : $ges_erg .= $salden->falscher_saldoübertrag(  - 2254        ,  - 1879); break;
        case "2015-06" : $ges_erg .= $salden->falscher_saldoübertrag(    3392        ,    1887); break;
        default : break;
      }
    }

    if (isset( $_GET["verbose"]) && $_GET["verbose"]=="norm")
      printf( "<h3 style=\"text-align: center\"> Verwendete Daten — </h3>\n %s\n", $ein_monat->zeige_die_verwendeten_normalisierten_daten());
    $ges_erg .= "<div class=\"page-break\"></div>\n";
  }
  if (!isset( $_GET["verbose"]) or $_GET["verbose"] == "gesamt") echo $ges_erg;
  return $ein_rechner;
}

if (isset( $_GET["verbose"]) && ($_GET["verbose"]=="monat" or $_GET["verbose"]=="woche")) {
  echo head( "css-formblatt.css");
} else {
  echo head( "arbeit-erfasse.css");
}

$gepostet = new gepostet();
$gepostet->toString();


$startzeit =  (isset( $_GET["start"])) ? $_GET["start"] : ""; # echo "M010 startzeit = $startzeit ";
$stopzeit  =  (isset( $_GET["stop" ])) ? $_GET["stop" ] : ""; # echo "M012 stopzeit  = $stopzeit  ";
$wie_gfos  =  (isset( $_GET["gfos" ])) ? $_GET["gfos" ] : ""; # echo "M012 wie_gfos  = $wie_gfos  "; echo "\n";
if ($startzeit == "") {
  $erster  = datumsobjekt( "first day of last month");
  $letzter = datumsobjekt( $stopzeit );
} else {
  $erster  = datumsobjekt( $startzeit);
  $letzter = datumsobjekt( $stopzeit );
}
// printf( "startzeit=%s stopzeit=%s parameter=%s datumsobjekt startzeit = %s datumsobjekt stopzeit = %s<br />\n",
//   $startzeit, $stopzeit, $parameter, datumsobjekt( $startzeit)->format('Y-m-d'), datumsobjekt( $stopzeit)->format('Y-m-d'));

$salden = new salden( 3700, 3330, $wie_gfos, -220); // Beschäftigungsumfang, , bilanz_verfall_vortrag
  // public function __construct( $tarifliche_wochenarbeitszeit, $beschäftigungsumfang, $wie_gfos, $bilanz_verfall_vortrag) {
$mein_rechner = schleife( $erster, $letzter, $salden);

if (isset( $_GET["verbose"]) && $_GET["verbose"]=="monat" ) echo $salden->mach_monatlich_geltend_version_04( "Sabine Schallehn");
if (isset( $_GET["verbose"]) && $_GET["verbose"]=="tage"  ) echo $salden->mach_taeglich_geltend( $mein_rechner);
if (isset( $_GET["verbose"]) && $_GET["verbose"]=="woche" ) echo $salden->mach_woechentlich_geltend( $mein_rechner);
if (isset( $_GET["verbose"]) && $_GET["verbose"]=="liste" ) echo $salden->zeige_aller_tage_liste( $mein_rechner);
if (isset( $_GET["verbose"]) && $_GET["verbose"]=="assoc" ) echo $salden->assoc_array_aller_tage( $mein_rechner);

if (isset( $_GET["verbose"]) && $_GET["verbose"]=="rechne") echo erprobe_rechne( $mein_rechner);
echo fusz();
?>
