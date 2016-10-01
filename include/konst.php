<?php
class konst {
  public static $speicher_skript       = "speichere.php"     ;
  public static $erfasse_skript        = "erfasse.php"       ;
  public static $zeitausgleich_skript  = "zeitausgleich.php" ;
  public static $gfos_zeitkonto_skript = "gfos-zeitkonto.php";
  public static $table_name            = "zeiten"            ;
  public static $database_name         = "arbeit"            ;
  public static $host_name             = "zoe.xeo"           ;
 #public static $host_name             = "fadi.xeo"          ;
  public static $art_lang              = "lang"              ;
  public static $art_kurz              = "kurz"              ;
  public static $art_mini              = "mini"              ;
  public static $art_verbose           = "verbose"           ;
  public        $parole                = ""                  ;

  function __construct() {
    $this->parole = shell_exec( "koerperteil mysql");
  }
}
?>
