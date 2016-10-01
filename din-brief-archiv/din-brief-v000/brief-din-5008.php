<?php
function head( $stylesheet) {
  $zuletzt_aktualisiert = "Analyseprogramm zuletzt aktualisiert: Mo 2016-04-25 17:12:27";
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
    return $erg;
  }

echo head( "css-din-5008.css");
?>
<div id="#dinavier">
din a 4
<div class='briefkopf'>
Briefkopf 85mm x 210mm
</div>

<div class='absenderzeile'>
Absenderzeile 25 + 0 + 85 x 5
</div>

<div class='anschriftenfeld'>
Anschriftenfeld 25 + 0 + 85 x 40
</div>

<div class='informationsblock'>
Informationsblock 125 + 0 + 75 x 45
</div>

<div class='betreff'>
Betreff 25 + 0
</div>

<div class='textfeld'>
Textfeld 25 + 0
</div>

<div class='brieffusz'>
Brieffusz 25 + 0
</div>
din a 4
</div>

<hr />

<div id="dinavier">
  <div class='briefkopf'>
    Briefkopf 85mm x 210mm
  </div>

  <div class='absenderzeile'>
    Absenderzeile 25 + 0 + 85 x 5
  </div>

  <div class='anschriftenfeld'>
    Anschriftenfeld 25 + 0 + 85 x 40
  </div>

  <div class='informationsblock'>
    Informationsblock 125 + 0 + 75 x 45
  </div>
  
  <div class='betreff'>
    Betreff 25 + 0
  </div>
  
  <div class='textfeld'>
    Textfeld 25 + 0
  </div>
  
  <div class='brieffusz'>
  Brieffusz 25 + 0
  </div>
  </div>

</div>

<div id="rectangle">
  rectangle
  <div id="box">
    box
  </div>
Auszen
</div>

<?php
echo fusz();
?>

