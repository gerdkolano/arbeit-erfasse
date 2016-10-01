<?php
require_once( "datum.php");

echo "<!DOCTYPE html><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
$debug = true;
$debug = false;
setlocale(LC_ALL,"de_DE.utf8");
echo strftime("%A"); // Wochentagsname
echo strftime("%B") . "<br />\n"; // Monatsname
setlocale(LC_TIME, "de_DE.utf8"); # hanno@zoe:/daten/srv/www/htdocs/erprobe/kalender$ locale -a
echo strftime("%A"); // Wochentagsname
echo strftime("%B") . "<br />\n"; // Monatsname
$dtz = new DateTimeZone( "Europe/Berlin");
echo $dtz->getName ( );
$heute = new DateTime();
$heute->setTimezone( $dtz);
echo $heute->format('l') . "<br />\n";
$fmt = new IntlDateFormatter(
    'de-DE',
    IntlDateFormatter::FULL,
    IntlDateFormatter::FULL,
    'Europe/Berlin',
    IntlDateFormatter::GREGORIAN
);
echo 'First Formatted output is ' . $fmt->format(3000000000) . "<br />\n";
echo 'First Formatted output is ' . $fmt->format( $heute) . "<br />\n";

$fmt = new IntlDateFormatter(
    'de-DE',
    IntlDateFormatter::FULL,
    IntlDateFormatter::FULL,
    'Europe/Berlin',
    IntlDateFormatter::GREGORIAN,
    "G yyyy-MM-dd HH:mm:ss.SS Z"   // http://www.unicode.org/reports/tr35/tr35-dates.html#Date_Field_Symbol_Table
);
echo 'Second Formatted output is ' . $fmt->format(3000000000) . "<br />\n";
echo 'Second Formatted output is ' . $fmt->format( $heute) . "<br />\n";
