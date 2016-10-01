<?php
require_once( "../include/konst.php");
error_reporting(E_ALL); // high level of error reporting
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
    //$this->franzimint__construct();
    //$this->fadi__construct();
    $this->zoe__construct();
  }
/*
 * mysql -hfadi.xeo -uhanno -p"" arbeit
 * mysql  -hzoe.xeo -uhanno -p"" arbeit
 */
  
    
  function franzimint__construct() {                       
    $this->db_server   = "localhost"; //                             "franzimint";
    $this->db_name     = "arbeit";
    $this->db_port     = "3306";
    $this->db_user     = "hanno";
    $this->db_password = (new konst)->parole;
    $this->prefix = "";
    $this->tafel_a = $this->prefix . "arbeit";
  }
    
  function fadi__construct() {                                // fadi
    $this->db_server   = "fadi.xeo";
    $this->db_name     = "arbeit";
    $this->db_port     = "3306";
    $this->db_user     = "hanno";
    $this->db_password = (new konst)->parole;
    $this->prefix = "";
    $this->tafel_a = $this->prefix . "arbeit";
  }
    
  function zoe__construct() {                                // zoe
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
      or printf( "Kann %s/%s nicht öffnen.<br />\nAls root: <br />\nf=%s/%s; touch \$f; chown www-data: \$f<br />\nServer addr %s Server name %s Http host %s <br />\n",
        __DIR__, 
        $myFile,
        __DIR__,
        $myFile,
        $_SERVER["SERVER_ADDR"],
        $_SERVER['SERVER_NAME'],
        $_SERVER['HTTP_HOST']
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
  public function hol_array_of_objects( $query, $mindestens = 1) { // liefert ein Array mehrerer Objekte
    $erg = array();
    $result = $this->frage( $mindestens, $query);
    if ( !  $result) { return $erg;}
    while ($datenfeld = $result->fetch_assoc()) {
        $erg[] = $datenfeld;
    }
    return $erg;
  }

  function hol_last_inserted() { // Liefert -1:Fehler, 0:nichts inserted, n: (die letzte ehe oder selbst)
    $erg = -1;
    //$query = "SELECT max(`selbst`) as erg FROM `$tafel_s`";
    if ($result = $this->frage( 1, "SELECT LAST_INSERT_ID() AS `erg`")) {
      $einzige_row = $result->fetch_assoc();
      $erg = $einzige_row['erg'];
    }
    return $erg;
  }

  function hol_einen_wert( $query, $qerg) { // Liefert -1:Fehler, 0:nichts inserted, n: (die letzte ehe oder selbst)
                                            // $erg = hol_einen_wert( "SELECT LAST_INSERT_ID() AS `erg`", "erg");
    $erg = "";
    if ($result = $this->frage( 1, $query)) {
      $einzige_row = $result->fetch_assoc();
      $erg = $einzige_row[$qerg];
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
class GET_parameter {
  public $id_name_fuer_get;
  public $id_name_fuer_datum;
  public $id_name_fuer_kurztafel;
  function __construct() {
    $this->get_name_fuer_id = "id";
    $this->get_name_fuer_datum = "datum";
    $this->get_name_fuer_kurztafel = "k"; 
    $this->get_name_fuer_verbose = "v"; 
    // Nutzung :(new GET_parameter)->id_name_fuer_get;
  }
}

/*
 * Analysiere, was gepostet wurde : Mit POST oder GET
 *
 * 'RUFER'        :
 * 'AUSWAHL'      :
 * 'aktualisiert' :
 * 'UPDATE'       :
 * 'INSERT'       :
 * 'id'           :
 *
 * Stelle zusammen, was zu senden ist. von erfasse.php an sende.php
 *
 * !!!!!!!!!!!!!!!!!!!! erfasse.php spricht GET
 *
 * !!!!!!!!!!!!!!!!!!!! erfasse.php sendet POST an speichere.php
 *
 * $fn = pathinfo(__FILE__,PATHINFO_BASENAME);
 * sprintf( "<input type=\"hidden\" name=\"RUFER\" value=\"%s%s%s\">\n", "http://",
 *  $_SERVER['SERVER_NAME'],
 *  $_SERVER['REQUEST_URI'] );
 *
 * <button type=\"SUBMIT\" name=INPUT value=\"$submit_inhalt\"> $submit_label </button>\n"
 * <button type=\"SUBMIT\" name=UPDATE value=\"$submit_inhalt\"> $submit_label </button>\n"
 *
 * !!!!!!!!!!!!!!!!!!!! speichere.php hört POST von erfasse.php
 *
 *
 *
 * !!!!!!!!!!!!!!!!!!!! speichere.php spricht POST
 *
 * !!!!!!!!!!!!!!!!!!!! speichere.php sendet GET an erfasse.php
 *
 * Nutze :(new GET_parameter)->id_name_fuer_get
 *
 * "$rufer?id=$last_inserted",  "Ändere die soeben erfassten Daten. ID Nummer $last_inserted");
 * "$rufer?id="                . $gepostet->id(), "Ändere die soeben erfassten Daten. ID Nummer " . $gepostet->id());
 *
 * !!!!!!!!!!!!!!!!!!!! erfasse.php hört GET von speichere.php und anderen
 *
 * Nutze :(new GET_parameter)->id_name_fuer_get
 *
 *    foreach ( $_GET as $key=>$arg) {
 *      # echo "<pre>"; print_r( $arg); echo "</pre>";
 *      switch ($key) {
 *      case "id"    : $eine_id    = $arg; break;
 *      case "datum" : $ein_datum  = $arg; break;
 *      case "k"     : $kurztafel  = $arg; break;
 *      default      : echo "M030 key $key arg $arg\n"; break;
 *      }                                                     
 *
 * !!!!!!!!!!!!!!!!!!!! speichere.php hört GET
 *
 *
 */
?>
