<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>Einzelheiten</title>
  </head>

  <body>
    <h3>Wo befindet sich diese Seite?</h3>

<?php
# namespace TestProject;
 
 // Set namespace (works only with PHP 5.3)
# namespace TestProject;
# PHP Fatal error:  Namespace declaration statement has to be the very first statement in the script
 
 // This prints file full path and name
 echo "This file full path and file name is '" . __FILE__ . "'.<br />\n";
 
 // This prints file full path, without file name
 echo "This file full path is '" . __DIR__ . "'.<br />\n";
 
 // This prints current line number on file
 echo "This is line number " . __LINE__ . ".<br />\n";
 
 // Really simple basic test function
 function test_function_magic_constant() {
 echo "This is from '" . __FUNCTION__ . "' function.<br />\n";
 }
 
 // Prints function and used namespace
 test_function_magic_constant();
 
 // Really simple class for testing magic constants
 class TestMagicConstants {
 // Prints class name
 public function printClassName() {
 echo "This is " . __CLASS__ . " class.<br />\n";
 }
 
 // Prints class and method name
 public function printMethodName() {
 echo "This is " . __METHOD__ . " method.<br />\n";
 }
 
 // Prints function name
 public function printFunction() {
 echo "This is function '" . __FUNCTION__ . "' inside class.<br />\n";
 }
 
 // Prints namespace name (works only with PHP 5.3)
 public function printNamespace() {
 echo "Namespace name is '" . __NAMESPACE__ . "'.<br />\n";
 }
 }
 
 // Create new TestMagicConstants class
 $test_magic_constants = new TestMagicConstants;
 
 // This prints class name and used namespace
 $test_magic_constants->printClassName();
 
 // This prints method name and used namespace
 $test_magic_constants->printMethodName();
 
 // This prints function name inside class and used namespace
 // same as method name, but without class
 $test_magic_constants->printFunction();
 
 // This prints namespace name (works only with PHP 5.3)
 $test_magic_constants->printNamespace();
 
?>
 
<?php
echo "M010 " . "Referer: " . $_SERVER["REMOTE_ADDR"] . "<br>\n";
echo "M011 " . $_SERVER["REQUEST_URI"] . "<br>\n";
echo "M012 " . "Server: " . $_SERVER["SERVER_ADDR"] . ":" . $_SERVER["SERVER_PORT"] . " Software: " . $_SERVER["SERVER_SOFTWARE"] . " <br />\n";
echo "M013 <br>\n";
echo "M014 " .'system( "dig +short -x " . $_SERVER["SERVER_ADDR"])'. "<br>\n";
echo "M014 " . system( "dig +short -x " . $_SERVER["SERVER_ADDR"]) . "<br>\n";
echo "M014 " .'system( "dig +short -x " . $_SERVER["REMOTE_ADDR"])'. "<br>\n";
echo "M014 " . system( "dig +short -x " . $_SERVER["REMOTE_ADDR"]) . "<br>\n";
echo "M015 " . $_SERVER["SCRIPT_FILENAME"] . "<br>\n";
echo "M016 <br>\n";

printf( "M017 %04d-%02d-%02d %02d.%02d.%02d <br>\n", date( 'Y'), date( 'n'), date( 'd'), date( 'H'), date( 'i'), date( 's'));
printf( "M018 %s <br>\n", date( 'Y-n-d H:i:s'));

?>
<br />
<br />

<?php
$headers = apache_request_headers();

foreach ($headers as $header => $value) {
	    echo "$header: $value <br />\n";
}
flush();
$headers = apache_response_headers();

foreach ($headers as $header => $value) {
	    echo "$header: $value <br />\n";
}

?>

<pre>
<?php
  ob_end_flush();
  print_r(apache_response_headers());
?>
</pre>


<pre>
<?php
  // ob_end_flush();
  print_r(apache_request_headers());
?>
</pre>

<br />
<br />

<?php

$key = "SCRIPT_FILENAME"; $val = $_SERVER[$key];
printf( "M020 %s =>  %s <br>\n", $key, $val);  
echo "M020 " . $key . " => " . $val . "<br>\n";  
foreach($_SERVER as $key=>$val) {  
  printf( "M030 \$_SERVER['%s'] = %s <br>\n", $key, $val);  
}  
?>

<br />

<?php
setlocale (LC_ALL, 'pl_PL');
echo date( 'D') . " ";
echo date( 'd') . ".";
echo date( 'n') . ".";
echo date( 'Y') . " ";
echo date( 'H') . ":";
echo date( 'i') . ":";
echo date( 's') . " ";
echo "<br>";
echo (date( 'w') + 6) % 7 + 1 . ".Tag der ";
echo date( 'W') . ".Woche ";
echo "<br>";

gd_info();
phpinfo();

?>

    <hr>
    <address><a href="mailto:gerdkolano@wp.pl">CEO</a></address>
<!-- Created: Fri Jan 15 23:43:49 CET 2010 -->
<!-- hhmts start -->
Last modified: Sat Jan 16 10:25:08 CET 2010
<!-- hhmts end -->
  </body>
</html>
<!-- Keep this comment at the end of the file
Local variables:
mode: php
End:
-->
