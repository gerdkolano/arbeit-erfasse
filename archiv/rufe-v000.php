<?php

class anzeige {

  function html_head( $stylesheet) {
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

  function html_foot() {
    $erg = "";
    $erg .= "</body>\n";
    $erg .= "</html>\n";
    return $erg;
  }

  function main() {
    $erg = "";
    $erg .= $this->html_head( "css-formblatt.css"); // "arbeit-erfasse.css"
    $erg .= $this->html_foot();
    return $erg;
  }
  
}
  
echo (new anzeige())->main();

?>

