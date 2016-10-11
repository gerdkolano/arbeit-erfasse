<?php
class konst {
  public static $verdienst_speicher_skript   = "verdienstspeichere.php" ;
  public static $verdienst_erfasse_skript    = "verdiensterfasse.php"   ;
  public static $speicher_skript             = "speichere.php"          ;
  public static $erfasse_skript              = "erfasse.php"            ;
  public static $zeitausgleich_skript        = "zeitausgleich.php"      ;
  public static $gfos_zeitkonto_skript       = "gfos-zeitkonto.php"     ;
  public static $table_name                  = "zeiten"                 ;
  public static $verdienst_tafel_name        = "verdienst"              ;
  public static $database_name               = "arbeit"                 ;
  public static $host_name                   = "zoe.xeo"                ;
 #public static $host_name                   = "fadi.xeo"               ;
 #public static $host_name                   = "franzimint"             ;
  public static $art_lang                    = "lang"                   ;
  public static $art_kurz                    = "kurz"                   ;
  public static $art_mini                    = "mini"                   ;
  public static $art_planung                 = "planung"                ;
  public static $art_br                      = "BR"                     ;
  public static $art_ba                      = "BA"                     ;
  public static $art_bv                      = "BV"                     ;
  public static $art_urlaub                  = "urlaub"                 ;
  public static $art_feiertag                = "feiertag"               ;
  public static $art_frei                    = "frei"                   ;
  public static $art_verbose                 = "verbose"                ;
  public        $parole                      = ""                       ;

  function __construct() {
    $this->parole = shell_exec( "koerperteil mysql");
  }
}
?>
