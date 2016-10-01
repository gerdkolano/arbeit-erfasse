<?php
class gepostet {
  private $datenfeld = array();
  private $rufer;
  private $auftrag;
  private $id;

  function __construct() {
#   echo "SV_005 gepostet<br />\n";
    $this->auftrag = "Kein Auftrag";
    $this->feld();
  }

  function id() { return $this->id; }
  function rufer() { return $this->rufer; }
  function geposteter_auftrag() { return $this->auftrag; }

  function toString() {
    $posted = "";
    foreach ($_POST as $key=>$value) {
      $posted .= " $key = $value <br />\n";
    }
    #   echo "SV_010 posted = \"<br />\n$posted\"<br />\n";
    return $posted;
  }

  function feld() {
    foreach ($_POST as $key=>$value) {
#     echo "SV_020 \"$key\"<br />\n";
      switch($key) {
      default             : $this->datenfeld[ $key] = $value; break;
      case 'RUFER'        : $this->rufer = $value;            break;
      case 'AUSWAHL'      :
      case 'aktualisiert' :                                   break;
      case 'UPDATE'       :
      case 'INSERT'       : $this->auftrag = $key;            break;
      case 'id'           : $this->id      = $value;          break;
      }
    }
#   printf( "SV_060 Auftrag = \"%s\"<br />\n", $this->auftrag); 
  }

  function get_datenfeld() { return $this->datenfeld; }

}
?>
