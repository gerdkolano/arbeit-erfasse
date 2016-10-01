<?php
require_once( "LoremIpsum.class.php");

class betreff_und_textobjekt {

  private $seitenfusz;
  private $seitenzahl;
  private $seitennummer;
  private $einzelheit;
  private $einzelheitn = array();
 
 private $inhaltsseiten = array();

  private $betr_ansch_unt_grusz = array(
    "betreff"       => 'Verdienstabrechnungen und gfos 4.7plus Zeitkonto' ,
    "anrede"        => 'Sehr geehrte Frau Giebler,'   ,
    "grusz"         => 'Mit freundlichen Grüßen'      ,
    "unterschrift"  => 'Sabine Schallehn'             ,
  );

  public function __construct( $arg = array()) {
    $this->einzelheit  = array();

#   $this->betr_ansch_unt_grusz["inhalt"        ] = (new LoremIpsumGenerator)->getContent( 200);

    $this->inhaltsseiten[] = <<<ENDE
am 26. 5. 2015 hat Herr Heyland mir mitgeteilt :

"Gern zahlen wir Sie auch jeden Monat auf null Mehrarbeitsstunden aus.
Sie müssen es uns nur wissen lassen."

Bitte informieren Sie mich darüber,
ob und wenn ja, wann mir schon einmal 
"auf null Mehrarbeitsstunden aus[gezahlt]"
wurden,
so dass ich einzuschätzen lerne,
wie dies dokumentiert wird.
Ohne Ihre Informationen sehe ich für mich keine Möglichkeit,
zu verstehen,
geschweige denn zu überprüfen,
was sie berechnen.

<p>

Am 
2. Mai 2016 
habe ich Sie mit Hilfe eines "Von-an-Zettel"s gebeten,
mich über
die Modalitäten der Abrechnung meiner Arbeitszeiten und meines Verdienstes
zu informieren.

Vielleicht ist diese Nachricht verloren gegangen,
jedenfalls haben Sie mir bis heute weder mündlich noch schriftlich eine Antwort zuteil werden lassen.

Gern wiederhole ich deshalb noch einmal meine Bitten und sehe Ihrer Antwort in der nächsten Woche entgegen.

<p>
Sie haben mir am 21.4.2015 mitgeteilt,
ich solle
anhand der "gfos 4.7plus Zeitkonto"-Ausdrucke
selbst überprüfen,
auf welchen Zeitraum
sich 54.70 Stunden aus meiner Verdienstabrechnung März 2016
erstrecken.

<p>
Das kann ich nicht.

ENDE;

    $this->inhaltsseiten[] = <<<ENDE
<p>
In meiner Verdienstabrechnung finde ich Zeitangaben wie 54.70 Std, 22.38 Std und 2.32 Std.
<p>
Diese Zeitangaben scheinen mit Angaben wie 10.94, 11.19 und 00.58
in der Spalte "Ist" meines "gfos 4.7plus Zeitkonto"-Ausdrucks zusammenzuhängen,
die in der Spalte "Bemerkungen" mit "Auszahlung" gekennzeichnet sind.

<p>
Die Materie scheint mir für eine mündliche Erörterung zu komplex zu sein.
Bitte informieren Sie mich schriftlich darüber,
in welchem Zeitraum 54.70 Std, 22,38 Std und 2,32 Std aufgelaufen sind und
wie sich die Angaben in der "Ist"-Spalte auf Kontostände meines
"gfos 4.7plus Zeitkonto"s auswirken.

<p>
Meiner Verdienstabrechnung und einem "gfos 4.7plus Zeitkonto"-Ausdruck für März 2016
<p>
glaube ich entnehmen zu können:
<pre>
Sie gehen von 54.70 Std Spätzuschlagszeit aus , weil 10.94 20% von 54.70 sind.
Sie gehen von 22.38 Std Nachtzuschlagszeit aus, weil 11.19 50% von 22.38 sind.
Sie gehen von  2.32 Std Mehrarbeitszeit aus   , weil 00.58 25% von  2.32 sind.
Mehrarbeitszeit ist die 
nicht durch hälftige Anrechnung der Verkaufsstellenprämie abgegoltene Arbeitszeit.
</pre>
Treffen meine Vermutungen zu?

ENDE;

    $this->inhaltsseiten[] = <<<ENDE
<p>
Ähnliche Zeitangaben finde ich auch in meiner Verdienstabrechnung Juli 2015,
denen aber nichts in meinen "gfos 4.7plus Zeitkonto"-Ausdrucken
entspricht.

<p>
In den mir vorliegenden "gfos 4.7plus Zeitkonto"-Ausdrucken 
sind darüber hinaus etliche Arbeits- und Urlaubszeiten falsch angegeben.
Auch sind viele Überträge zwischen den Monaten inkonsistent.
<p>
Bitte geben Sie mir aktuelle "gfos 4.7plus Zeitkonto"-Ausdrucke
einschließlich Monatskonten-Saldenübersichten
für die Zeit von April 2015 bis heute, Ende April 2016.

<p>

ENDE;

    $this->einzelheitn = array(
      "b_giebler"              => array(
        "betreff"       => 'Verdienstabrechnungen und gfos 4.7plus Zeitkonto' ,
        "anrede"        => 'Sehr geehrte Frau Giebler,'   ,
        "b_text"        => $this->inhaltsseiten           ,
        "grusz"         => 'Mit freundlichen Grüßen'      ,
        "unterschrift"  => 'Sabine Schallehn'             ,
      ) ,
      "b_heyland"              => array(
        "betreff"       => 'Verdienstabrechnungen und gfos 4.7plus Zeitkonto' ,
        "anrede"        => 'Sehr geehrter Herr Heyland,'  ,
        "b_text"        => $this->inhaltsseiten           ,
        "grusz"         => 'Mit freundlichen Grüßen'      ,
        "unterschrift"  => 'Sabine Schallehn'             ,
      ) ,
      "b_sabine"              => array(
        "betreff"       => 'Verdienstabrechnungen und gfos 4.7plus Zeitkonto' ,
        "anrede"        => 'Sehr geehrte Frau Schallehn,' ,
        "b_text"        => $this->inhaltsseiten           ,
        "grusz"         => 'Mit freundlichen Grüßen'      ,
        "unterschrift"  => 'Sabine Schallehn'             ,
      ) ,
    );

    if (isset( $_GET["brieftext"])) {
      $this->betr_ansch_unt_grusz = $_GET["brieftext"];
    }

    if (isset( $_GET["allseiten"])) {
      $this->inhaltsseiten = $_GET["allseiten"];
    }

#   ob_start(); echo "<pre>GET ";                  print_r( $_GET);                       echo "</pre>\n"; $erg .= ob_get_clean();
#               echo "<pre>betr_ansch_unt_grusz "; print_r( $this->betr_ansch_unt_grusz); echo "</pre>\n";                       ;
  }
  
  function __toString() {  // Hier wäre auch Einzelbehandlung möglich
    #   echo "<pre>__toString "     ; print_r( $this->betr_ansch_unt_grusz)    ; echo "</pre>";
    $ko = "<!-- inhaltsworte -->";
    $erg = "";
    if ($this->seitennummer == 0) {
      $erg .= sprintf( "<div class='betreffworte'>  \n\n%s</div>  \n",     $this->betr_ansch_unt_grusz["betreff"     ]);
      $erg .= sprintf( "<div class='anredeworte' >  \n%s\n</div>  \n",     $this->betr_ansch_unt_grusz["anrede"      ]);
    }
      $j = $this->seitennummer;
      $eine_seite = $this->inhaltsseiten[$j];
      $ko = "<!-- inhaltsworte $j -->";
      $erg .= sprintf( "<div class='inhaltsworte'>%s\n%s</div>%s\n", $ko, $eine_seite                   , $ko);

/*

*/
    if ($this->seitennummer == $this->seitenanzahl - 1) {
      $erg .= $this->zeige_grusz_und_unterschrift();
    }
    return sprintf( "<div class='textblock'>\n%s\n</div><!-- textblock ende -->\n", $erg);

  }

  function set_fusz(         $arg) { $this->seitenfusz   = $arg; } 
  function set_seitenanzahl( $arg) { $this->seitenanzahl = $arg; } 
  function set_seitennummer( $arg) { $this->seitennummer = $arg; }

  function umbruch() {
    $erg = "";
    $erg .= $this->seitenfusz;
    $erg = sprintf( "<div class='dinavier'>%s\n%s\n</div>%s\n",
      "<!-- dinavier a anfang -->",
      $erg,
      "<!-- dinavier a ende -->"
    );
    return $erg;
  }

  function zeige_grusz_und_unterschrift() {
    $erg = "";
    $erg .= sprintf( "<div class='gruszworte'   >%s</div>                  \n", $this->betr_ansch_unt_grusz["grusz"       ]);
    $erg .= sprintf( "<div class='unterschrift' >%s</div>                  \n", $this->betr_ansch_unt_grusz["unterschrift"]);
    return $erg;
    return sprintf( "<div class='textblock'>%s\n%s\n</div>%s\n",
      "<!-- zeige_grusz_und_unterschrift anfang -->",
      $erg,
      "<!-- zeige_grusz_und_unterschrift ende -->"
    );
  }

  function zeige_weitere_seite( $seite_nr) {
    $erg = "";
    if ($seite_nr<count( $this->inhaltsseiten)) {
      $erg .= $this->inhaltsseiten[$seite_nr];
    }
    return sprintf( "<!-- %s --><div class='textblock'>\n%s\n</div>\n", $seite_nr, $erg);
    switch ($seite_nr) {
    default :
    case 1 : $erg .= sprintf( "<div class='inhaltsworte' >%s</div>\n", $this->betr_ansch_unt_grusz["inhalt"   ]); break;
    case 2 : $erg .= sprintf( "<div class='inhaltsworte' >%s</div>\n", $this->betr_ansch_unt_grusz["inhalt_2" ]); break;
    case 3 : $erg .= sprintf( "<div class='inhaltsworte' >%s</div>\n", $this->betr_ansch_unt_grusz["inhalt_3" ]); break;
    }
    return sprintf( "<div class='textblock'>\n%s\n</div>\n", $erg);
    return $erg;
  }

  function option( $option_value, $gepostet, $label) {
    $selected = "";
    foreach ($gepostet as $posted_val) {
      if ($option_value == $posted_val) {
        $selected = " selected";
        break;
      }
    }
    return sprintf( "<option value=\"%s\"%s>%s </option>\n", $option_value , $selected, $label);
  }

  function selektiere() {
    $erg = "";
    foreach ( $this->einzelheitn as $key=>$val) {
      $erg .= $this->option( $key , $this->einzelheit, str_replace( "_", " ", $key ));
    }
/*
    $erg .= $this->option( "i_an_aldi" , $this->einzelheit, "Info Aldi"   );
    $erg .= $this->option( "i_sabine"  , $this->einzelheit, "Info Sabine" );
    $erg .= $this->option( "i_fred"    , $this->einzelheit, "Info Fred"   );
    $erg .= $this->option( "i_leer"    , $this->einzelheit, "Info Leer"   );
*/
    return sprintf( "<select id='wahl'  name=\"%s\">\n$erg</select>\n", "wahl[]");
  }

  function erfrage_und_sende_info( $gepostet) {
    $erg = "";
    $zu_senden = $gepostet == "" ? $this->einzelheit : $this->einzelheitn[$gepostet];
    $f0 = "<tr><td>%s<td><input    id='knopp' type=\"text\" class=\"%s\" name=\"%s\" value=\"%s\" size=\"100\">\n";
    $f1 = "<tr><td>%s<td><input    id='knopp' type=\"text\" class=\"%s\" name=\"%s\" value=\"%s\" size=\"50\" >\n";
    $f2 = "<tr><td>%s %s<td>\n<textarea id='knopp' type=\"text\" class=\"%s\" name=\"%s\" value=\"  \" cols='100' rows='50'>\n%s</textarea>\n";

    $erg .= sprintf( $f0, "Betreff"     ,       'betreffworte' , "brieftext[betreff]" ,     $this->betr_ansch_unt_grusz["betreff"     ]);
    $erg .= sprintf( $f0, "Anrede"      ,       'anredeworte'  , "brieftext[anrede]"  ,     $this->betr_ansch_unt_grusz["anrede"      ]);

    foreach ($this->inhaltsseiten as $k => $eine_seite) {       
      $erg .= sprintf( $f2, "Inhalt"    , $k,   'inhaltsworte' , "allseiten[]", $eine_seite);
    }                                                                             
    $erg .= sprintf( $f1, "Gruß"        ,       'gruszworte'   , "brieftext[grusz]"       , $this->betr_ansch_unt_grusz["grusz"       ]);
    $erg .= sprintf( $f1, "Unterschrift",       'unterschrift' , "brieftext[unterschrift]", $this->betr_ansch_unt_grusz["unterschrift"]);

    $erg = "<table class=\"ohne-gitter\">\n$erg\n</table>\n";
    return $erg;
  }

}

?>
