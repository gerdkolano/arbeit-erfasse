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
    # echo "Host-Name " . konst::$host_name . " ";
    switch (konst::$host_name) {
    default:
    case "zoe.xeo"     : $this->zoe__construct();        break;
    case "fadi.xeo"    : $this->fadi__construct();       break;
    case "franzimint"  : $this->franzimint__construct(); break;
    }
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

?>
