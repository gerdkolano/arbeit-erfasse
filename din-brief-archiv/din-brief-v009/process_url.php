<?php
/*
 *
 * Nutzung: cat generiere-brief.url | php process_url.php
 *
 * */
$haystack = file_get_contents( "php://stdin");

$zu_ersetzen = array(
  "\n"     => ""                      ,
  "%5B"     => "["                    ,
  "%5D"     => "]"                    ,
  "&"      => "\"\n . \"&"            ,
  "?"      => "\"\n . \"?"            ,
  "%0D%0A" => "%0D%0A\"\n . \""       ,
);

foreach ( $zu_ersetzen as $needle => $ersatz) {
#  $replace = "\"\n . \"" . $needle;
  $haystack = str_replace( $needle, $ersatz, $haystack);
}
echo "\"" . $haystack . "\"\n";


?>
