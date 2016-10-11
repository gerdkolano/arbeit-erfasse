<?php
class daten_aller_tage implements IteratorAggregate {
  private $tarr = array();

  function __construct( array $quelle = array()) {
    foreach ($quelle as $key=>$dieser_tag) {
      $schlüssel = $dieser_tag->datum_obj->format( "Y-m-d");
      $this->tarr[$schlüssel] = $dieser_tag;
    }
  }

  // return iterator
  public function getIterator() {
    return new ArrayIterator( $this->tarr);
  }

  function push_einen_tag( daten_eines_tages $dieser_tag) {
    $schlüssel = $dieser_tag->datum_obj->format( "Y-m-d");
    $this->tarr[$schlüssel] = $dieser_tag;
  }

  public function mehrarbeit_333_zum_array_aller_tage_hinzufügen( $debug = false) {
    $vormonat            =     0;
    $bilanz_abgegolten   =     0;
    $bilanz_mehr0        =     0;
    $gewinn_plus_minus   =     0;
    $übertrag_plus_minus =     0;

  if ($debug) printf( "<table><tr><th>Monat<th>Fall<th><th>m bilanz mehro<th>p prämie in std<th> gewinn plus minus<th>übertrag plus minus");
    foreach ($this as $n => $dieser_tag) {                      // Benutze den Iterator
      $testdatum = clone $dieser_tag->datum_obj;
      $abgedatum = $testdatum->donnerstag_der_woche();
      $monat = $testdatum->modify( '+1 day')->donnerstag_der_woche()->format( "m");
      if ($monat != $vormonat) {
        $bilanz_mehr1 = $dieser_tag->sald0_kum + $dieser_tag->bilanz_20_gfos + $dieser_tag->bilanz_50_gfos ;
        $bilanz_mehro = $bilanz_mehr1 - $bilanz_mehr0;                               // Summe der Überschreitungen von 3330 in einer 4-5-Wochen-Periode
        $prämie_in_std = (new abgegolten( $abgedatum, konst::$host_name))->abgegolten()->abgegoltene_zeit;
        $bilanz_abgegolten += $prämie_in_std;                                         //   Abbummeln reduziert anspruch

        if ($debug) printf( "<tr><td>%02d", $monat);
        if ($bilanz_mehro < 0 ) {                                              // z.B. im Dez 2015 aber nicht April 2016
        # <------------------bilanz_mehro--------------0--------------------------------------------->
        # if ($bilanz_mehro == -532) {
        # if (-$bilanz_mehro <= $prämie_in_std) {
        # if (             0 <=   $prämie_in_std + $bilanz_mehro) {
        # if (             0 >  - $prämie_in_std - $bilanz_mehro) {
          if ( - $bilanz_mehro - $prämie_in_std < 0) {
        # <------------------bilanz_mehro--------------0---------------------⁒bilanz_mehro----prämie_in_std----------------------->
            $gewinn_plus_minus = $bilanz_mehro - $prämie_in_std;
            if ($debug) echo "<td>Fall 0 <td>m<0 <td> $bilanz_mehro <td>$prämie_in_std<td> $gewinn_plus_minus";
          } else {
        # <------------------bilanz_mehro--------------0----prämie_in_std----⁒bilanz_mehro------------------------------->
            $gewinn_plus_minus = $bilanz_mehro;
            if ($debug) echo "<td>Fall 1 <td>m<0 <td> $bilanz_mehro <td>$prämie_in_std<td> $gewinn_plus_minus";
          }
        } else {
        # <--------------------------------------------0---------------------+bilanz_mehro----prämie_in_std----------------------->
          if (   $bilanz_mehro - $prämie_in_std < 0) {                          // z.B. im Jan 2015 Feb 2015
            $gewinn_plus_minus = 0;
            if ($debug) echo "<td>Fall 2 <td>m>=0 <td> $bilanz_mehro <td>$prämie_in_std<td> $gewinn_plus_minus";
          } else {
        # <--------------------------------------------0-----prämie_in_std---+bilanz_mehro---------------------------------------->
            $gewinn_plus_minus = $bilanz_mehro  - $prämie_in_std;
            if ($debug) echo "<td>Fall 3 <td>m>=0 <td> $bilanz_mehro <td>$prämie_in_std<td> $gewinn_plus_minus";
          }
        }
        $übertrag_plus_minus += $gewinn_plus_minus;
        $bilanz_mehr0 = $bilanz_mehr1;
        if ($debug) echo "<td> $übertrag_plus_minus\n";
      }

      $dieser_tag->bilanz_abgegolten = $bilanz_abgegolten;
      $dieser_tag->bilanz_mehrals333 = $übertrag_plus_minus;   // Kontrolle in zeitkonto.php forderung_von_4_bis_5_wochen
      $vormonat = $monat;
    }
    if ($debug) printf( "</table>");
  }

  function get_einen_tag( $d) { if (isset($this->tarr[$d]) || array_key_exists( $d, $this->tarr)) return $this->tarr[$d]; return false; } 

  function get_heute_gfos(        $d) {if (isset($this->tarr[$d])||array_key_exists($d,$this->tarr)) return $this->tarr[$d]->heute_gfos          ;else return 0;} 
  function get_heute_20_gfos(     $d) {if (isset($this->tarr[$d])||array_key_exists($d,$this->tarr)) return $this->tarr[$d]->heute_20_gfos       ;else return 0;} 
  function get_heute_50_gfos(     $d) {if (isset($this->tarr[$d])||array_key_exists($d,$this->tarr)) return $this->tarr[$d]->heute_50_gfos       ;else return 0;} 
  function get_heute_verfall(     $d) {if (isset($this->tarr[$d])||array_key_exists($d,$this->tarr)) return $this->tarr[$d]->heute_verfall       ;else return 0;} 
  function get_sald0_kum(         $d) {if (isset($this->tarr[$d])||array_key_exists($d,$this->tarr)) return $this->tarr[$d]->sald0_kum           ;else return 0;} 
  function get_bilanz_gfos(       $d) {if (isset($this->tarr[$d])||array_key_exists($d,$this->tarr)) return $this->tarr[$d]->bilanz_gfos         ;else return 0;} 
  function get_bilanz_20_gfos(    $d) {if (isset($this->tarr[$d])||array_key_exists($d,$this->tarr)) return $this->tarr[$d]->bilanz_20_gfos      ;else return 0;} 
  function get_bilanz_50_gfos(    $d) {if (isset($this->tarr[$d])||array_key_exists($d,$this->tarr)) return $this->tarr[$d]->bilanz_50_gfos      ;else return 0;} 
  function get_bilanz_verfall(    $d) {if (isset($this->tarr[$d])||array_key_exists($d,$this->tarr)) return $this->tarr[$d]->bilanz_verfall      ;else return 0;} 
  function get_bilanz_20_ausz(    $d) {if (isset($this->tarr[$d])||array_key_exists($d,$this->tarr)) return $this->tarr[$d]->bilanz_20_ausz      ;else return 0;} 
  function get_bilanz_50_ausz(    $d) {if (isset($this->tarr[$d])||array_key_exists($d,$this->tarr)) return $this->tarr[$d]->bilanz_50_ausz      ;else return 0;} 
  function get_bilanz_25_ausz(    $d) {if (isset($this->tarr[$d])||array_key_exists($d,$this->tarr)) return $this->tarr[$d]->bilanz_25_ausz      ;else return 0;} 
  function get_bilanz_25_gfos(    $d) {if (isset($this->tarr[$d])||array_key_exists($d,$this->tarr)) return $this->tarr[$d]->bilanz_25_gfos      ;else return 0;} 
  function get_bilanz_mehr_als_37($d) {if (isset($this->tarr[$d])||array_key_exists($d,$this->tarr)) return $this->tarr[$d]->bilanz_mehr_als_37  ;else return 0;} 
  function get_bilanz_mehrals333( $d) {if (isset($this->tarr[$d])||array_key_exists($d,$this->tarr)) return $this->tarr[$d]->bilanz_mehrals333   ;else return 0;} 
  function get_bilanz_abgegolten( $d) {if (isset($this->tarr[$d])||array_key_exists($d,$this->tarr)) return $this->tarr[$d]->bilanz_abgegolten   ;else return 0;} 
  function get_beschäftigungsumfang($d){if(isset($this->tarr[$d])||array_key_exists($d,$this->tarr)) return $this->tarr[$d]->beschäftigungsumfang;else return 0;} 

  function get_heute_verfall_zeitraum( $datum_arr) { $erg = 0; foreach ($datum_arr as $d) { $erg += $this->get_heute_verfall( $d); } return  $erg; }
  function get_heute_50_gfos_zeitraum( $datum_arr) { $erg = 0; foreach ($datum_arr as $d) { $erg += $this->get_heute_50_gfos( $d); } return  $erg; }
  function get_heute_20_gfos_zeitraum( $datum_arr) { $erg = 0; foreach ($datum_arr as $d) { $erg += $this->get_heute_20_gfos( $d); } return  $erg; }
  function get_heute_gfos_zeitraum(    $datum_arr) { $erg = 0; foreach ($datum_arr as $d) { $erg += $this->get_heute_gfos(    $d); } return  $erg; }

  function get_summe( $art, $datum_arr) {
    $erg = 0;
    switch ($art) {
      case "heute_gfos"                    : foreach ($datum_arr as $d) { $erg += $this->get_heute_gfos(           $d); } break;
      case "heute_20_gfos"                 : foreach ($datum_arr as $d) { $erg += $this->get_heute_20_gfos(        $d); } break;
      case "heute_50_gfos"                 : foreach ($datum_arr as $d) { $erg += $this->get_heute_50_gfos(        $d); } break;
      case "heute_verfall"                 : foreach ($datum_arr as $d) { $erg += $this->get_heute_verfall(        $d); } break;
      case "sald0_kum"                     : foreach ($datum_arr as $d) { $erg += $this->get_sald0_kum  (          $d); } break;
      case "bilanz_gfos"                   : foreach ($datum_arr as $d) { $erg += $this->get_bilanz_gfos(          $d); } break;
      case "bilanz_rein"                   : foreach ($datum_arr as $d) { $erg += 0                                
                                                                                + $this->get_bilanz_gfos(          $d) 
                                                                                + $this->get_bilanz_20_gfos(       $d) 
                                                                                + $this->get_bilanz_50_gfos(       $d)
                                                                                ;                                  
                                                                                                                        } break;
      case "bilanz_20_50_gfos"             : 
                                             foreach ($datum_arr as $d) { $erg += 0                                
                                                                                + $this->get_bilanz_20_gfos(       $d) 
                                                                                + $this->get_bilanz_50_gfos(       $d)
                                                                                ;
                                                                                                                        } break;
      case "bilanz_verfall"                : foreach ($datum_arr as $d) { $erg += $this->get_bilanz_verfall(       $d); } break;
      case "bilanz_20_ausz"                : foreach ($datum_arr as $d) { $erg += $this->get_bilanz_20_ausz(       $d); } break;
      case "bilanz_50_ausz"                : foreach ($datum_arr as $d) { $erg += $this->get_bilanz_50_ausz(       $d); } break;
      case "bilanz_25_ausz"                : foreach ($datum_arr as $d) { $erg += $this->get_bilanz_25_ausz(       $d); } break;
      case "bilanz_25_gfos"                : foreach ($datum_arr as $d) { $erg += $this->get_bilanz_25_gfos(       $d); } break;
      case "bilanz_mehr_als_37"            : foreach ($datum_arr as $d) { $erg += $this->get_bilanz_mehr_als_37(   $d); } break;
      case "bilanz_mehrals333"             : foreach ($datum_arr as $d) { $erg += $this->get_bilanz_mehrals333(    $d); } break;
      case "bilanz_abgegolten"             : foreach ($datum_arr as $d) { $erg += $this->get_bilanz_abgegolten(    $d); } break;
      default: break;
    }
    return  $erg;
  }

  function TH_aller_tage_liste() {
    return sprintf( "<th>%s <th>%s <th>%s <th>%s <th>%s <th>%s <th>%s <th>%s <th>%s <th>%s <th>%s <th>%s <th>%s <th>%s <th>%s"
                  . "<th>%s <th>%s <th>%s <th>%s <th>%s <th>%s <th>%s <th>%s "
                  . "<th>%s <th>%s <th>%s <th>%s <th>%s <th>%s <th>%s <th>%s <th>%s <th>%s <th>%s <th>%s <th>%s <th>%s <th>%s",
      "datum obj"                ,  // $this->datum_obj->format( "Y-m-d")    
      "er scheine"               ,  // $this->erscheine                      
      "arbzeit plan dauer"       ,  // $this->arbzeit_plan_dauer             
      "arbanf auto risiert"      ,  // $this->arbanf_autorisiert             
      "arbzeit plan anfang"      ,  // $this->arbzeit_plan_anfang            
      "arbeit kommt"             ,  // $this->arbeit_kommt                   
      "pause1 geht"              ,  // $this->pause1_geht                    
      "pause1 kommt"             ,  // $this->pause1_kommt                   
      "pause2 geht"              ,  // $this->pause2_geht                    
      "pause2 kommt"             ,  // $this->pause2_kommt                   
      "arbeit geht"              ,  // $this->arbeit_geht                    
      "arbzeit plan ende"        ,  // $this->arbzeit_plan_ende              
      "arbende auto risiert"     ,  // $this->arbende_autorisiert            
      "i saldo dauer"            ,  // $this->i_saldo_dauer                  
      "id"                       ,  // $this->id                             
                                                                         
      "heute gfos"               ,  // $this->heute_gfos                     
      "heute echt"               ,  // $this->heute_echt                     
      "heute verfall"            ,  // $this->heute_verfall                  
      "heute 20 gfos"            ,  // $this->heute_20_gfos                  
      "heute 50 gfos"            ,  // $this->heute_50_gfos                  
      "heute 20 echt"            ,  // $this->heute_20_echt                  
      "heute 50 echt"            ,  // $this->heute_50_echt                  
      "beschäf tigungs umfang"   ,  // $this->bilanz_gfos - $this->sald0_kum 
      "bilanz mehr als 333"      ,  // $this->bilanz_mehrals333              
      "bilanz ab ge gol ten"     ,  // $this->bilanz_abgegolten              
                                                                         
      "saldo kum"                ,  // $this->sald0_kum                      
      "bilanz gfos"              ,  // $this->bilanz_gfos                    
      "bilanz 20 gfos"           ,  // $this->bilanz_20_gfos                 
      "bilanz 50 gfos"           ,  // $this->bilanz_50_gfos                 
      "bilanz verfall"           ,  // $this->bilanz_verfall                 
      "bilanz echt"              ,  // $this->bilanz_echt                    
      "bilanz 20 echt"           ,  // $this->bilanz_20_echt                 
      "bilanz 50 echt"           ,  // $this->bilanz_50_echt                 
                                                                          
      "bilanz 20 ausz"           ,  // $this->bilanz_20_ausz                 
      "bilanz 50 ausz"           ,  // $this->bilanz_50_ausz                 
      "bilanz 25 ausz"           ,  // $this->bilanz_25_ausz                 
      "bilanz 25 gfos"           ,  // $this->bilanz_25_gfos                 
      "bilanz mehr als 37"          // $this->bilanz_mehr_als_37               
    );
  }

}

class daten_eines_tages {
  public $datum_obj            ;
  public $erscheine            ;
  public $arbzeit_plan_dauer   ;
  public $arbanf_autorisiert   ;
  public $arbzeit_plan_anfang  ;
  public $arbeit_kommt         ;
  public $pause1_geht          ;
  public $pause1_kommt         ;
  public $pause2_geht          ;
  public $pause2_kommt         ;
  public $arbeit_geht          ;
  public $arbzeit_plan_ende    ;
  public $arbende_autorisiert  ;
  public $verlasse             ;
  public $i_saldo_dauer        ;
  public $id                   ;

  public $heute_gfos           ;
  public $heute_echt           ;
  public $heute_verfall        ;
  public $heute_20_gfos        ;
  public $heute_50_gfos        ;
  public $heute_20_echt        ;
  public $heute_50_echt        ;
  public $beschäftigungsumfang ;
  public $bilanz_mehrals333    ;
  public $bilanz_abgegolten    ;

  public $sald0_kum            ;
  public $bilanz_gfos          ;
  public $bilanz_echt          ;
  public $bilanz_verfall       ;
  public $bilanz_20_gfos       ;
  public $bilanz_50_gfos       ;
  public $bilanz_20_echt       ;
  public $bilanz_50_echt       ;

  public $bilanz_20_ausz       ;
  public $bilanz_50_ausz       ;
  public $bilanz_25_ausz       ;
  public $bilanz_25_gfos       ;
  public $bilanz_mehr_als_37   ;

  function __construct(       // Für ein Element von aller_tage_liste
    datum_objekt $datum_obj   ,
    $erscheine                ,
    $arbzeit_plan_dauer       ,
    $arbanf_autorisiert       ,
    $arbzeit_plan_anfang      ,
    $arbeit_kommt             ,
    $pause1_geht              ,
    $pause1_kommt             ,
    $pause2_geht              ,
    $pause2_kommt             ,
    $arbeit_geht              ,
    $arbzeit_plan_ende        ,
    $arbende_autorisiert      ,
    $verlasse                 ,
    $i_saldo_dauer            ,
    $id                       ,

    $heute_gfos               ,
    $heute_echt               ,
    $heute_verfall            ,
    $heute_20_gfos            ,
    $heute_50_gfos            ,
    $heute_20_echt            ,
    $heute_50_echt            ,
    $beschäftigungsumfang     ,
    $bilanz_mehrals333        ,
    $bilanz_abgegolten        ,

    $sald0_kum                ,
    $bilanz_gfos              ,
    $bilanz_echt              ,
    $bilanz_verfall           ,
    $bilanz_20_gfos           ,
    $bilanz_50_gfos           ,
    $bilanz_20_echt           ,
    $bilanz_50_echt           , 

    $bilanz_20_ausz           ,
    $bilanz_50_ausz           ,
    $bilanz_25_ausz           ,
    $bilanz_25_gfos           ,
    $bilanz_mehr_als_37
  ) {
    $this->datum_obj                = $datum_obj            ;
    $this->erscheine                = $erscheine            ;
    $this->arbzeit_plan_dauer       = $arbzeit_plan_dauer   ;
    $this->arbanf_autorisiert       = $arbanf_autorisiert   ;
    $this->arbzeit_plan_anfang      = $arbzeit_plan_anfang  ;
    $this->arbeit_kommt             = $arbeit_kommt         ;
    $this->pause1_geht              = $pause1_geht          ;
    $this->pause1_kommt             = $pause1_kommt         ;
    $this->pause2_geht              = $pause2_geht          ;
    $this->pause2_kommt             = $pause2_kommt         ;
    $this->arbeit_geht              = $arbeit_geht          ;
    $this->arbzeit_plan_ende        = $arbzeit_plan_ende    ;
    $this->arbende_autorisiert      = $arbende_autorisiert  ;
    $this->verlasse                 = $verlasse             ;
    $this->arbzeit_plan_dauer       = $arbzeit_plan_dauer   ;
    $this->i_saldo_dauer            = $i_saldo_dauer        ;
    $this->id                       = $id                   ;

    $this->heute_gfos               = $heute_gfos           ;
    $this->heute_echt               = $heute_echt           ;
    $this->heute_verfall            = $heute_verfall        ;
    $this->heute_20_gfos            = $heute_20_gfos        ;
    $this->heute_50_gfos            = $heute_50_gfos        ;
    $this->heute_20_echt            = $heute_20_echt        ;
    $this->heute_50_echt            = $heute_50_echt        ;
    $this->beschäftigungsumfang     = $beschäftigungsumfang ;
    $this->bilanz_mehrals333        = $bilanz_mehrals333    ;
    $this->bilanz_abgegolten        = $bilanz_abgegolten    ;

    $this->sald0_kum                 = $sald0_kum             ;
    $this->bilanz_gfos               = $bilanz_gfos           ;
    $this->bilanz_echt               = $bilanz_echt           ;
    $this->bilanz_verfall            = $bilanz_verfall        ;
    $this->bilanz_20_gfos            = $bilanz_20_gfos        ;
    $this->bilanz_50_gfos            = $bilanz_50_gfos        ;
    $this->bilanz_20_echt            = $bilanz_20_echt        ;
    $this->bilanz_50_echt            = $bilanz_50_echt        ;

    $this->bilanz_20_ausz            = $bilanz_20_ausz        ;
    $this->bilanz_50_ausz            = $bilanz_50_ausz        ;
    $this->bilanz_25_ausz            = $bilanz_25_ausz        ;
    $this->bilanz_25_gfos            = $bilanz_25_gfos        ;
    $this->bilanz_mehr_als_37        = $bilanz_mehr_als_37    ;
  }

  function TH_titel_woechentlich_geltend( $rechner, $leer = false) {
    $anspruchsteller = "Sabine Schallehn";
    $zeitraum = $leer
      ? "… … … … …"
      : $this->datum_obj->donnerstag_der_woche()->deutsch( "MMMM YYYY") ." ab ". $this->datum_obj->deutsch( "d.M. ")
      ;
    return sprintf( "<h3>%s - Detaillierte Auflistung der Zeitausgleichsliste für Monat %s </h3>", $anspruchsteller, $zeitraum);
  }

  function TH_kopf_woechentlich_geltend( $rechner, $leer = false) {
      $auszugeben = new za_ausgabe_eines_tages(
        $this->datum_obj->tagesname(),
        "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", ""
      );
    return $auszugeben->ein_tag_za_toTH();
  }

  function TR_rumpf_woechentlich_geltend( $rechner, $leer = false) {
    if ($leer) {
      $auszugeben = new za_ausgabe_eines_tages(
        $this->datum_obj->tagesname(),
        "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", ""
      );
      return $auszugeben->ein_tag_za_toTR();
    }

    $plan_mit_pause_in_hundertsteln = $rechner->runde_dixx($this->arbzeit_plan_ende, $this->arbzeit_plan_anfang);
    $plan_ohne_pause_in_hundertsteln =
        (new pause)->get_arbeitszeit_in_hundertstel_stunden(
          $plan_mit_pause_in_hundertsteln
        );
    $zwanzig_und_fünfzig= $this->heute_20_gfos + $this->heute_50_gfos;

    $auszugeben = new za_ausgabe_eines_tages(
                           $this->datum_obj->tagesname()            ,
                           $this->datum_obj->format( ",d.m")        ,
      $rechner->minHMleer( $this->arbzeit_plan_anfang )             ,
      $rechner->minHMleer( $this->arbzeit_plan_ende   )             ,
                           $plan_ohne_pause_in_hundertsteln         ,
                           $this->erscheine                         ,
      $rechner->minHMleer( $this->arbeit_kommt        )             ,
      $rechner->minHMleer( $this->pause1_geht         )             ,
      $rechner->minHMleer( $this->pause1_kommt        )             ,
      $rechner->minHMleer( $this->pause2_geht         )             ,
      $rechner->minHMleer( $this->pause2_kommt        )             ,
      $rechner->minHMleer( $this->arbeit_geht         )             ,
      $rechner->minHMleer( $this->verlasse            )             ,
                           $this->heute_20_gfos                     ,
                           $this->heute_50_gfos                     ,
                           $zwanzig_und_fünfzig                     ,
                           $this->i_saldo_dauer                     ,
                           $zwanzig_und_fünfzig + $this->heute_gfos ,
                           $plan_mit_pause_in_hundertsteln          ,
                           $this->heute_gfos                        ,
                           $this->heute_verfall                     ,
                           $this->id
    );
    return $auszugeben->ein_tag_za_toTR();
  }

  function TH_fusz_woechentlich_geltend( $aller_tage_daten, $leer = false) {
    if ($leer) {
      $auszugeben = new za_ausgabe_eines_tages(
        "Wochen- ", "summen", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "");
      return $auszugeben->ein_tag_za_toTR();
    }

    $erscheine = "";
    $sieben_tage = $this->datum_obj->mach_7_wochentage();
    $auszugeben = new za_ausgabe_eines_tages( "Wochen- ","summen","","",0,$erscheine,"","","","","","","",
      $aller_tage_daten->get_summe( "heute_20_gfos", $sieben_tage),
      $aller_tage_daten->get_summe( "heute_50_gfos", $sieben_tage),
      0,0,0,0,                                      
      $aller_tage_daten->get_summe( "heute_gfos"   , $sieben_tage),
      $aller_tage_daten->get_summe( "heute_verfall", $sieben_tage),
      0);
    return $auszugeben->ein_tag_za_toTR();
  }

  function TR_mach_taeglich_geltend( $rechner) {
    $plan_mit_pause_in_hundertsteln = $rechner->runde_dixx($this->arbzeit_plan_ende, $this->arbzeit_plan_anfang);
    $plan_ohne_pause_in_hundertsteln =
        (new pause)->get_arbeitszeit_in_hundertstel_stunden(
          $plan_mit_pause_in_hundertsteln
        );
    $zwanzig_und_fünfzig= $this->heute_20_gfos + $this->heute_50_gfos;

    $auszugeben = new za_ausgabe_eines_tages(
                           $this->datum_obj->format( "d.m.y ")      ,
                           $this->datum_obj->tagesname()            ,
      $rechner->minHMleer( $this->arbzeit_plan_anfang )             ,
      $rechner->minHMleer( $this->arbzeit_plan_ende   )             ,
                           $plan_ohne_pause_in_hundertsteln         ,
                           $this->erscheine                         ,
      $rechner->minHMleer( $this->arbeit_kommt        )             ,
      $rechner->minHMleer( $this->pause1_geht         )             ,
      $rechner->minHMleer( $this->pause1_kommt        )             ,
      $rechner->minHMleer( $this->pause2_geht         )             ,
      $rechner->minHMleer( $this->pause2_kommt        )             ,
      $rechner->minHMleer( $this->arbeit_geht         )             ,
      $rechner->minHMleer( $this->verlasse            )             ,
                           $this->heute_20_gfos                     ,
                           $this->heute_50_gfos                     ,
                           $zwanzig_und_fünfzig                     ,
                           $this->i_saldo_dauer                     ,
                           $zwanzig_und_fünfzig + $this->heute_gfos ,
                           $plan_mit_pause_in_hundertsteln          ,
                           $this->heute_gfos                        ,
                           $this->heute_verfall                     ,
                           $this->id
    );
    return $auszugeben->ein_tag_za_viel_toTR();
  }

  function TH_mach_taeglich_geltend( $rechner) {
    $auszugeben = new za_ausgabe_eines_tages( 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    return $auszugeben->ein_tag_za_viel_toTH();
  }

  function TR_aller_tage_liste() {
    return sprintf( "<td>%s <td>%s <td>%s <td>%s <td>%s <td>%s <td>%s <td>%s <td>%s <td>%s <td>%s <td>%s <td>%s <td>%s <td>%s"
                  . "<td>%s <td>%s <td>%s <td>%s <td>%s <td>%s <td>%s <td>%s "
                  . "<td>%s <td>%s <td>%s <td>%s <td>%s <td>%s <td>%s <td>%s <td>%s <td>%s <td>%s <td>%s <td>%s <td>%s <td>%s",
      $this->datum_obj->format( "Y-m-d")    ,
      $this->erscheine                      ,
      $this->arbzeit_plan_dauer             ,
      $this->arbanf_autorisiert             ,
      $this->arbzeit_plan_anfang            ,
      $this->arbeit_kommt                   ,
      $this->pause1_geht                    ,
      $this->pause1_kommt                   ,
      $this->pause2_geht                    ,
      $this->pause2_kommt                   ,
      $this->arbeit_geht                    ,
      $this->arbzeit_plan_ende              ,
      $this->arbende_autorisiert            ,
      $this->i_saldo_dauer                  ,
      $this->id                             ,
                                           
      $this->heute_gfos                     ,
      $this->heute_echt                     ,
      $this->heute_verfall                  ,
      $this->heute_20_gfos                  ,
      $this->heute_50_gfos                  ,
      $this->heute_20_echt                  ,
      $this->heute_50_echt                  ,
      $this->bilanz_gfos - $this->sald0_kum , // - 476        , // $this->beschäftigungsumfang        ,
      $this->bilanz_mehrals333              ,
      $this->bilanz_abgegolten              ,
                                           
      $this->sald0_kum                      ,
      $this->bilanz_gfos                    ,
      $this->bilanz_20_gfos                 ,
      $this->bilanz_50_gfos                 ,
      $this->bilanz_verfall                 ,
      $this->bilanz_echt                    ,
      $this->bilanz_20_echt                 ,
      $this->bilanz_50_echt                 ,
                                            
      $this->bilanz_20_ausz                 ,
      $this->bilanz_50_ausz                 ,
      $this->bilanz_25_ausz                 ,
      $this->bilanz_25_gfos                 ,
      $this->bilanz_mehr_als_37               
    );
  }

  function TH_aller_tage_liste_obsolet() {
    return sprintf( "<th>%s <th>%s <th>%s <th>%s <th>%s <th>%s <th>%s <th>%s <th>%s <th>%s <th>%s <th>%s <th>%s <th>%s <th>%s"
                  . "<th>%s <th>%s <th>%s <th>%s <th>%s <th>%s <th>%s <th>%s",
      "datum obj"                ,
      "er scheine"               ,
      "arbzeit plan dauer"       ,
      "arbanf auto risiert"      ,
      "arbzeit plan anfang"      ,
      "arbeit kommt"             ,
      "pause1 geht"              ,
      "pause1 kommt"             ,
      "pause2 geht"              ,
      "pause2 kommt"             ,
      "arbeit geht"              ,
      "arbzeit plan ende"        ,
      "arbende auto risiert"     ,
      "i saldo dauer"            ,
      "id"                       ,

      "heute gfos"               ,
      "heute echt"               ,
      "heute verfall"            ,
      "heute 20 gfos"            ,
      "heute 50 gfos"            ,
      "heute 20 echt"            ,
      "heute 50 echt"            ,
      "beschäf tigungs umfang"
    );
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

  function ein_tag_za_toTH() {
    $spalto = array(
#     "<th rowspan=2>Datum",
      "<th colspan=3 class='geplant'>geplant",
    # "<th>bis",
    # "<th>geplant Std",
      "<th rowspan=2>erscheine im Laden",
      "<th class='geplant'>komme",
      "<th class='geplant'>gehe",
      "<th class='geplant'>komme",
      "<th class='geplant'>gehe",
      "<th class='geplant'>komme",
      "<th class='geplant'>gehe",
      "<th rowspan=2>verlasse den Laden",
      "<th rowspan=2>reine Arbeitszeit",
      "<th rowspan=2>20% Spät- zuschlag",
      "<th rowspan=2>50% Nacht- zuschlag",
      "<th rowspan=2>reine Arbz. + Spät- + Nacht- zuschlag",
      "<th rowspan=2>Verfallszeit"
    );
    $spaltu = array(
    # "<th>Datum",
      "<th>von",
      "<th>bis",
      "<th>Stunden",
    # "<th>er- scheine",
      "<th>Arbeit anfangen",
      "<th>in die 1. Pause",
      "<th>aus der 1. Pause",
      "<th>in die 2. Pause",
      "<th>aus der 2. Pause",
      "<th>Arbeit beenden",
    # "<th>verlasse", 
    # "<th>reine Arbeits- zeit",
    # "<th>20%",
    # "<th>50%",
    # "<th>50%",
    # "<th>Verfalls- zeit" 
    );
    
    $erg = "";
    $erg .= "<thead>\n";
    $erg .= "<tr><th rowspan=2> Datum";
    foreach ( $spalto as $skey=>$sval) {
      $erg .= "$sval\n";
    }
    $erg .= "</tr>\n";
    $erg .= "<tr>";
    foreach ( $spaltu as $skey=>$sval) {  // Warum darf $spaltu weniger Felder haben als $spalto ?
      $erg .= "$sval\n";                  // Weil rowspan=2 in $spalto die hier fehlenden Felder füllt.
    }
    $erg .= "</tr>\n";
    $erg .= "</thead>\n";
    return $erg;
  }

  function ein_tag_za_toTR() {
    $erg = "";
    $erg .= sprintf( "<tr>");
    $erg .= sprintf( "<td>%s",              $this->datum_nr . $this->datum_name             );
    $erg .= sprintf( "<td>%s",              $this->plan_anfang                              );
    $erg .= sprintf( "<td>%s",              $this->plan_ende                                );
    $erg .= sprintf( "<td>%s", $this->leer( $this->plan_ohne_pause_in_hund_std             ));
    $erg .= sprintf( "<td>%s",              $this->erscheine                                );
    $erg .= sprintf( "<td>%s",              $this->arbeit_kommt                             );
    $erg .= sprintf( "<td>%s",              $this->pause1_geht                              );
    $erg .= sprintf( "<td>%s",              $this->pause1_kommt                             );
    $erg .= sprintf( "<td>%s",              $this->pause2_geht                              );
    $erg .= sprintf( "<td>%s",              $this->pause2_kommt                             );
    $erg .= sprintf( "<td>%s",              $this->arbeit_geht                              );
    $erg .= sprintf( "<td>%s",              $this->verlasse                                 );
    $erg .= sprintf( "<td>%s", $this->leer( $this->reine                                   ));
    $erg .= sprintf( "<td>%s", $this->leer( $this->zwanzig                                 ));
    $erg .= sprintf( "<td>%s", $this->leer( $this->fünfzig                                 ));
    $erg .= sprintf( "<td>%s", $this->leer( $this->reine + $this->zwanzig + $this->fünfzig ));
    $erg .= sprintf( "<td>%s", $this->leer( $this->verfall                                 ));
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

?>
