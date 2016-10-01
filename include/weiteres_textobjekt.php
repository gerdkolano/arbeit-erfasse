<?php
require_once( "LoremIpsum.class.php");

class weiteres_textobjekt {

  private $adresse = array(
    "inhalt"          => ''                           ,
    "inhalt_2"        => ''                           ,
  );

  public function __construct( $arg = array()) {
#   $this->adresse["inhalt"      ] = (new LoremIpsumGenerator)->getContent( 200);
    $this->adresse["inhalt"      ] = <<<ENDE
am 
Mai 2016 
habe ich Sie mit Hilfe eines "Von-an-Zettel"s gebeten,
mich über
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
entnehme ich:
<pre>
Anscheinend gehen Sie von 54.70 Std Spätzuschlagszeit aus , weil 10.94 20% von 54.70 sind.
Anscheinend gehen Sie von 22.38 Std Nachtzuschlagszeit aus, weil 11.19 50% von 22.38 sind.
Anscheinend gehen Sie von  2.32 Std Mehrarbeitszeit aus   , weil 00.58 25% von  2.32 sind.
Mehrarbeitszeit : Nicht durch hälftige Anrechnung der Verkaufsstellenprämie abgegoltene Arbeitszeit.
</pre>
Treffen meine Vermutungen zu?

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

    if (count( $arg) > 0) {
      $ii = 0;
      foreach ($this->adresse as $key=>$val) {
        if ($ii < count($arg)) {
          $this->adresse[$key] = $arg[$ii++];
        } else {
          $this->adresse[$key] = "";
        }
      }
    }
  }
  
  function __toString() {  // Hier wäre auch Einzelbehandlung möglich
#   echo "<pre>__toString "     ; print_r( $this->adresse)    ; echo "</pre>";
    $erg = "";
    $erg .= sprintf( "<div class='inhaltsworte' >%s</div>                  \n", $this->adresse["inhalt"      ]);
#   foreach ( $this->adresse as $key=>$val) {
#     $erg .= sprintf( "<span class='infoinhalt'>%s</span><br />\n", $key.$val);
#   }
    return sprintf( "<div class='textblock'>\n%s\n</div>\n", $erg);

  }

  function erfrage_und_sende_info() {
    $erg = "";
    $f2 = "<tr><td>%s<td><textarea id='knopp' type=\"text\" class=\"%s\" name=\"%s\" value=\"%s\" cols='100' rows='50'>%s</textarea>\n";

    $erg .= sprintf( $f2, "Inhalt"      , 'inhaltsworte' , "brieftext[]", "", $this->adresse["inhalt"      ]);

    $erg = "<table class=\"ohne-gitter\">\n$erg\n</table>\n";
    return $erg;
  }

}

?>
