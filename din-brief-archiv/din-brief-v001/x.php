<?php
function head( $stylesheet) {
  $zuletzt_aktualisiert = "Analyseprogramm zuletzt aktualisiert: Mo 2016-04-25 18:01:22";
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
echo fusz();
?>
