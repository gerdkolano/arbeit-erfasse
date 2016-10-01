<?php
require_once( "../include/konst.php");
# konst::$art_verbose

class eine_liste {
  public $liste;

  function __construct( array $arg) {
    $this->liste = $arg;
  }
    
  function union( eine_liste $arg) {
    foreach ($arg->liste as $key => $val) {
    # echo "$key $val <br />\n"; 
      $this->liste["xx " . $key] = $val; 
    }
    return $this;
  }
    
}

class anzeige {
  private $programm;
  private $editiere;
  public $programm_liste;
  public $editiere_liste;

  function html_head() {
    $erg = "";
    $erg .= "<!DOCTYPE html>";
    $erg .= "<html>";
    $erg .= "<head>";
    $erg .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
    $erg .= "<title>\nKonten</title>\n";
    $erg .= "</head>";
    $erg .= "<body>";
    return $erg;
  }
  
  function html_foot() {
    $erg = "";
    $erg .= "</body>";
    $erg .= "</html>";
    return $erg;
  }
  
  function __construct() {
    $verbose = konst::$art_verbose;
    $this->programm = array (
      "Mach einen Monat geltend : Wähle einen Monat"               => "rufe.php"                                              ,
      "Mach einen Monat geltend : Dezember 2014"                   => "zeitkonto.php?$verbose=5wochen&wann=2014-12"           ,
      "Mach einen Monat geltend : Januar 2015"                     => "zeitkonto.php?$verbose=5wochen&wann=2015-1"            ,
      "Mach einen Monat geltend : April 2016"                      => "zeitkonto.php?$verbose=5wochen&wann=2016-04"           ,
      "Leeres Monats-Formular"                                     => "form-monat-leer.html"                                  ,
      "Leeres Formular und Mach eine Woche geltend"                => "zeitkonto.php?start=2016-03&$verbose=woche"            ,
      "Leeres Formular und Mach einen Monat geltend"               => "zeitkonto.php?start=2016-03&$verbose=monat"            ,
      "Leeres Formular : Mach eine Woche geltend. Daten veraltet"  => "formular-woche.php"                                    ,
      "Zeitkonto Vor- und jetziger Monat"                          => "zeitkonto.php"                                         ,
      "Zeitkonto Vor- und jetziger Monat. Zeige normalisierte"     => "zeitkonto.php?$verbose=norm"                           ,
      "Mach Monate ab März 2016 geltend"                           => "zeitkonto.php?start=2016-03&$verbose=monat"            ,
      "Mach Monate ab Dezember 2014 geltend"                       => "zeitkonto.php?start=2014-11&$verbose=monat"            ,
      "Liste aller Tage Rohdaten"                                  => "zeitkonto.php?start=2014-11&$verbose=liste"            ,
      "Liste aller Tage menschenlesbar"                            => "zeitkonto.php?start=2014-11&$verbose=tage"             ,
      "gf*s 4.7plus Zeitkonto ab Dezember 2014"                    => "zeitkonto.php?start=2014-11"                           ,
      "gf*s 4.7plus Zeitkonto ab Dezember 2014 bis März 2016"      => "zeitkonto.php?start=2014-11&stop=2016-5&$verbose=tage" ,
      "gf*s 4.7plus Zeitkonto mit Saldo-Inkonsistenzen"            => "zeitkonto.php?start=2014-11&stop=2016-5&gfos=ja"       ,
      "Mach geltend - Erste Version"                               => "mache_geltend.php?datum=2014-11-24&anzahl=77"          ,
      "Saldo, gezeigt von der Infotaste"                           => "../kalender/anal-to-html.php"                          ,
      "Lies Mich"                                                  => "README"                                                ,
      "Zusammenhänge zwischen Verdienst und gf*s"                  => "../verdienst/abgegolten.txt"                           ,
      "Nichts"                                                     => "?edit"                                                 ,
    );                                                             
    $this->editiere = array (                                            
      "&nbsp;"                                                     => ""                                                      ,
      "Tägliche Daten erfassen : Hier bitte einsteigen"            => "speichere.php"                                         ,
      "keym"                                                       => "erfasse.php"                                           ,
      "an-giebler"                                                 => "an-giebler.html"                                       ,
      "Verdienstabrechnung erfassen : Hier bitte einsteigen"       => "../verdienst/verdienstspeichere.php"                   ,
      "Verdienstabrechnung erfassen"                               => "../verdienst/verdiensterfasse.php?id=1"                ,
      "abgegolten"                                                 => "../simpel/vimpel.php"                                  ,
    );
    $this->programm_liste = new eine_liste( $this->programm);
    $this->editiere_liste = new eine_liste( $this->programm);
  }
  
  function fern( $host, eine_liste $programm, $adressat) {
    $erg = "";
    $erg .= "<h3> $adressat </h3>\n";
    $erg .= "<table border>\n";
    $erg .= "<!-- >Fern " . $_SERVER['REMOTE_ADDR'] . " -->\n";
    foreach ($programm->liste as $key=>$val) {
      $erg .= sprintf( "<tr><td><a href=\"http://%s/arbeit/erfasse/%s\" target=\"_blank\">%s </a><td>%s\n", $host, $val, $val, $key);
    }
    $erg .= "</table>";
    return $erg;
  }
  
  function liste_dateinamen_mit_suffix( $wo, $str) {
    $liste = array();
    if ($handle = opendir( $wo)) {
      /* Das ist der korrekte Weg, ein Verzeichnis zu durchlaufen. */
      while ($filename = readdir($handle)) {
        if (strpos($filename, $str) == strlen($filename) - strlen($str)) $liste[] = $filename; 
      }
      closedir( $handle);
    
      #sort( $liste);
    }
    return $liste;
  }
    
  function zeige_dateinamen_mit_suffix( $str) {
    $erg = "";
    if ($handle = opendir('.')) {
      /* Das ist der korrekte Weg, ein Verzeichnis zu durchlaufen. */
      while ($filename = readdir($handle)) {
        $liste[] = $filename; 
      }
      closedir( $handle);
    
      sort( $liste);
    
      foreach ($liste as $key => $filename) {
        if (strpos($filename, $str) == strlen($filename) - strlen($str)) {
          $erg .= sprintf( "<td>%s<td>%s\n<br />", $filename, $filename);
        }
      }
    }
    return $erg;
  }
    
  function finde_alle() {
    if ($handle = opendir('.')) {
      /* Das ist der korrekte Weg, ein Verzeichnis zu durchlaufen. */
      while ($filename = readdir($handle)) {
        $liste[] = $filename; 
      }
      closedir( $handle);
    
      $str = ".pdf";
      foreach ($liste as $key => $filename) {
        if (strpos($filename, $str) == strlen($filename) - strlen($str)) {
          echo "$filename\n<br />";
        }
      }
    
      echo "ende\n<br />";
    
      sort( $liste);
    
      foreach ($liste as $key => $filename) {
      echo "$filename\n<br />";
      }
    }
  }
  
  function main() {
    echo $this->html_head();
    if (preg_match("/^192\.168\./", $_SERVER['REMOTE_ADDR'])
    or  preg_match("/^127\.0\./"  , $_SERVER['REMOTE_ADDR'])) {
      $pdf_list1 = new eine_liste( $this->liste_dateinamen_mit_suffix( "."     , ".pdf"));
      $pdf_list2 = new eine_liste( $this->liste_dateinamen_mit_suffix( "../pdf", ".pdf"));
    # $this->programm_liste->union( $this->editiere_liste)->union( $pdf_list1)->union( $pdf_list2);
      $this->programm_liste                               ->union( $pdf_list1)->union( $pdf_list2);
      echo $this->fern( "zoe.xeo", $this->programm_liste, "Daheim.");
    } else {
      $pdf_list1 = new eine_liste( $this->liste_dateinamen_mit_suffix( "."     , ".pdf"));
      $pdf_list2 = new eine_liste( $this->liste_dateinamen_mit_suffix( "../pdf", ".pdf"));
    # $this->programm_liste->union( $this->editiere_liste)->union( $pdf_list1)->union( $pdf_list2);
      $this->programm_liste                               ->union( $pdf_list1)->union( $pdf_list2);
      if ($_SERVER['QUERY_STRING'] == "edit") {
        echo $this->fern( "gerd.dyndns.za.net", $this->programm_liste, "Für Kenner.");
      } else {
        echo $this->fern( "gerd.dyndns.za.net", $this->programm_liste,
          "Für Fred. Wenn's unverständlich ist, bitte fragen. ");
      }
    }
    echo $this->html_foot();
  }
  
}
  
(new anzeige())->main();

?>

