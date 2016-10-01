<?php
function head( $stylesheet) {
  $zuletzt_aktualisiert = "Analyseprogramm zletzt aktualisiert: Di 2016-04-26 10:30:46";
  $erg = "";
  $erg .= "<!DOCTYPE html>\n";
  $erg .= "<html>\n";
  $erg .= "<head>\n";
  $erg .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
  $erg .= "<link rel=\"stylesheet\" href=\"$stylesheet\" type=\"text/css\">\n";
  $erg .= "</head>\n";
  $erg .= "<body>\n";
  $erg .= sprintf( "<!-- %s -->\n", $zuletzt_aktualisiert);
  return $erg;
}
  function fusz( ) {
    $erg = "";
    $erg .= "</body>";
    $erg .= "</html>";
    $erg .= "\n";
    return $erg;
  }

# echo head( "css-din-5008.css");

  function briefkopf( $arg) {
    return sprintf( "<div class='briefkopf'>\n%s xxx %s\n</div>\n", $arg, kiste(  6,  3));
  }

  function absenderzeile( $arg) {
    return sprintf( "<div class='absenderzeile'>\n%s\n</div>\n", $arg);
  }

  function anschriftenfeld( $arg) {
    return sprintf( "<div class='anschriftenfeld'>\n%s\n</div>\n", $arg);
  }

  function informationsblock( $arg) {
    return sprintf( "<div class='informationsblock'>\n%s\n</div>\n", $arg);
  }

  function betreff( $arg) {
    return sprintf( "<div class='betreff'>\n%s\n</div>\n", $arg);
  }

  function textfeld( $arg) {
    return sprintf( "<div class='textfeld'>\n%s\n</div>\n", $arg);
  }

  function brieffusz( $arg) {
    return sprintf( "<div class='brieffusz'>\n%s\n</div>\n", $arg);
  }

  function alles() {
    $erg ="";
    $erg .= briefkopf( "Sabine Schallehn<br />\nBerlin-Lichtenrade");
    $erg .= absenderzeile( "Sabine Schallehn Löwenbrucher Weg 24c D-12307 Berlin");
    $erg .= anschriftenfeld( "");
    $erg .= informationsblock( "");
    $erg .= betreff( "");
    $erg .= textfeld( "");
    $erg .= brieffusz( "IBAN DE49 10090000 205 306 4003 BIC BEVODDBE");
    return $erg;
  }
  echo head( "css-din-5008.css");
  echo alles();
  echo "<br />\n";
  function kiste( $f, $tief) {
    $d = 8; $e = $f - $d;
    $d = 4; $e = $f - $d;
    $d = 2; $e = $f - $d; $k = 0;
    printf( "<div style='border:solid black %dpx; width:%dpx; height:%dpx; padding:%dpx 0px 0px %dpx; margin:0px 0px 0px 0px'>\n",
      $d,                              // 1 
    ( $tief * $f - $d) * 2 + $k,        // 2 5.5
    ( $tief * $f - $d) * 2 + $k,        // 4 4
      $e,
      $e 
    );
    if ($tief>1) {
      kiste( $f, $tief-1);
    } else {
      # echo $f;
    }
    echo "</div>\n";
  }
  kiste( 12, 32);
?>
<br>
<br>
<br>
<br>
<br>
<div style="border:1px solid black; width:66mm">Whatever</div>
<div id="rectangle">
  rectangle
  <div id="box">
    box
    <div id="re" style='width:29mm;  height: 29mm; margin  : 4mm 4mm 4mm 4mm; '>
      bbb
      <div id="re" style='width:9mm; height: 9mm; margin  : 5mm 0mm 0mm 6mm; '>
        box
      </div>
      <div id="re" style='width:9mm; height: 9mm; margin  : 5mm 0mm 0mm 6mm; '>
        box
      </div>
  </div>
Auszen
</div>

<svg>
  <rect x="0" y="0" width="50" height="50"/>
</svg>
<?php
  echo fusz();
?>
