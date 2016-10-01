<?php
require_once( "../include/konst.php");
class configure {
  private $prefix;
  public $tafel_a;
  public $tierkreis;
  public $tafel_jos_contact_details;
  public $tafel_stamm2contact_details;

  public $db_server;
  public $db_name;
  public $db_port;
  public $db_user;
  public $db_password;

  function __construct() {
    //$this->fadi__construct();
    $this->zoe__construct();
  }
/*
 * mysql -hfadi.xeo -uhanno -p"" arbeit
 * mysql  -hzoe.xeo -uhanno -p"" arbeit
 */
  
    
  function zoe__construct() { //zoe
    $this->db_server   = "zoe.xeo";
    $this->db_name     = "arbeit";
    $this->db_port     = "3306";
    $this->db_user     = "hanno";
    $this->db_password = (new konst)->parole;
    $this->prefix = "";
    $this->tafel_a = $this->prefix . "arbeit";
  }
}

class conn {
  private $fh;
  private $mysqli;
  public $debug;
  public function get_mysqli() {
    return $this->mysqli;
  }

  public function __construct() {
    $config = new configure();
    $db_name     = $config->db_name; //$db_name     = "joo251"; $db_name     = "joo1700"; $db_name     = "joo1701";
    $db_server   = $config->db_server; //$db_server   = "zoe.xeo";
    $db_port     = $config->db_port;
    $db_user     = $config->db_user;
    $db_password = $config->db_password;

    $myFile = "logging";
    $strich = "##############################################################################################################";
    $this->fh = fopen( $myFile, 'a')
      or printf( "Kann %s/%s nicht öffnen.<br />\nAls root: <br />\nf=%s/%s; touch \$f; chown www-data: \$f<br />\n",
        __DIR__, 
        $myFile,
        __DIR__,
        $myFile
      );
    if ( $this->fh) {
      fwrite( $this->fh, sprintf ( "%s %s %s %s\n", date( "Y-m-d H:i:s"), $db_server, $db_name, $strich));
    }
    //fclose($fh);

    //$mysqli = new mysqli( $db_server, $db_user, $db_password, $db_name);
    $mysqli = new mysqli( $db_server, $db_user, $db_password);
    if ($mysqli->connect_errno) {
      $meldung = "HEL010 Failed to connect to MySQL: " . $mysqli->connect_error;
      echo "<strong> $meldung </strong>";
      $this->logge( $meldung);
    }

    if (!$mysqli->set_charset("utf8")) {
      $meldung = sprintf("HEL020 Error loading character set utf8: %s", $mysqli->error);
    } else {
      $meldung = sprintf("HEL030 Current character set: %s", $mysqli->character_set_name());
    }
    if ($this->debug>3) {printf("%s<br />\n", $meldung);}
    $this->logge( $meldung);

    /*
     * <a href="http://php.net/manual/en/mysqli.set-charset.php" target="_blank">http://php.net/manual/en/mysqli.set-charset.php</a>
    $query = "SET NAMES 'utf8'";
    $res = $mysqli->query( $query, MYSQLI_STORE_RESULT);
     */
    $this->mysqli = $mysqli;
  }

  function logge( $meldung) {
    if ( $this->fh) {
      fwrite( $this->fh, sprintf ( "%s %s;\n", date( "Y-m-d H:i:s"). substr((string)microtime(), 1, 6), $meldung));
    }
  }

  function frage( $min, $query) {
    $this->logge( $query);
    $result  = $this->mysqli->query( $query); // , MYSQLI_STORE_RESULT);
    if ($result) {
      if ($this->mysqli->affected_rows >= $min) {
        return $result;
      } else {
        $meldung = sprintf ("Meldung 004: Misserfolg. Erwarte mindestens %s Ergebnisse.", $min);
        $this->logge( sprintf ("$meldung\n"));
        printf ("query %s;<br />\n%s<br />\n", $query, $meldung);
        return false;
      }
    } else {
      $meldung = sprintf ("Meldung 005: Error %s %s", $this->mysqli->errno, $this->mysqli->error);
      $this->logge( "$meldung\n");
      printf ("%s<br />\nMeldung 005: query %s;<br />\n<br />\n", $meldung, $query);
      return false;
    }
  }

  // hol_array( "SELECT …") { // liefert ein Array mehrerer Objekte mit numerischem Index
  public function hol_array_of_objects( $query) { // liefert ein Array mehrerer Objekte
    $erg = array();
    $result = $this->frage( 1, $query);
    if ( !  $result) { return $erg;}
    while ($datenfeld = $result->fetch_assoc()) {
        $erg[] = $datenfeld;
    }
    return $erg;
  }

}

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
  function auftrag() { return $this->auftrag; }

  function zeig() {
    $posted = "";
    foreach ($_POST as $key=>$value) {
      $posted .= " $key = $value <br />\n";
    }
#   echo "SV_010 posted = \"<br />\n$posted\"<br />\n";
  }

  function feld() {
    foreach ($_POST as $key=>$value) {
#     echo "SV_020 \"$key\"<br />\n";
      switch($key) {
      default        : $this->datenfeld[ $key] = $value; break;
      case 'RUFER'   : $this->rufer = $value; break;
      case 'AUSWAHL' :
      case 'aktualisiert' : break;
      case 'UPDATE'  : 
      case 'INSERT'  : $this->auftrag = $key; break;
      case 'id'      : $this->id      = $value; break;
      }
    }
#   printf( "SV_060 Auftrag = \"%s\"<br />\n", $this->auftrag); 
  }

  function get_datenfeld() { return $this->datenfeld; }

}

?>
