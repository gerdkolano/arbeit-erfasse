<?php

class stunde {
  private $wert;
  function __construct( $wert) { $this->wert = $wert; } 
  function __toString()        { return sprintf(  "%5.2f", $this->wert / 100.0); } 
  function mit_vorzeichen()    { return str_replace( "-", "⁒ ", sprintf( "%+5.2f", $this->wert / 100.0)); } //   ⁒ 
  function add( stunde $arg)   { return new stunde( $this->wert +  $arg->wert); }
  function inc( stunde $arg)   { $this->wert += $arg->wert; }
  function sub( stunde $arg)   { return new stunde( $this->wert -  $arg->wert); } 
  function  lt( stunde $arg)   { return $this->wert < $arg->wert; } 
  function lt0()               { return $this->wert < 0; } 
}

?>
