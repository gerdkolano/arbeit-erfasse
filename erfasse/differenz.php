<?php
function minute( $wort_mit_punkt, $runde) {
  preg_match( "/([\d]*)(.)([\d]*)/", $wort_mit_punkt, $matches);
# printf( "%dh %dmin<br />\n", $matches[1], $matches[3]);
  $stunden = $matches[1];
  $minuten = $matches[3];
  $in_minuten = $stunden *60 + $minuten;
# printf( "%dh + %dmin = %fh <br />\n", $stunden, $minuten, $in_minuten);
  return $in_minuten;
}

function fliesz( $wort_mit_punkt, $runde) {
  preg_match( "/([\d]*)(.)([\d]*)/", $wort_mit_punkt, $matches);
# printf( "%dh %dmin<br />\n", $matches[1], $matches[3]);
  $stunden = $matches[1];
  $minuten = $matches[3];
  $in_stunden = $stunden + $minuten / 60.0;

  switch ($runde) {
  case "auf"   : $in_stunden = round( $in_stunden + 0.005, 2); break;
  case "abb"    : $in_stunden = round( $in_stunden - 0.005, 2); break;
  case "din"   : $in_stunden = round( $in_stunden        , 2); break;
  default      : break;
  }

# printf( "%dh + %dmin = %fh <br />\n", $stunden, $minuten, $in_stunden);
  return $in_stunden;
}

function differenz_in_std ( $term, $runde_arg_1, $runde_arg_2) {
  preg_match( "/([.\d]*)(-)([.\d]*)/", $term, $matches);
  # printf( "a %s b %s c%s<br />\n", $matches[1], $matches[2], $matches[3]);
  
  $minuend_string = $matches[1];
  $subtrahend_string = $matches[3];
  
  # printf( "%.f %.f<br />\n", $minuend_string, $subtrahend_string);
  
  $minuend_float    = fliesz( $minuend_string,    $runde_arg_1);
  $subtrahend_float = fliesz( $subtrahend_string, $runde_arg_2);
  
  # printf( "%s = %f<br />\n", $minuend_string,    fliesz( $minuend_string));
  # printf( "%s = %f<br />\n", $subtrahend_string, fliesz( $subtrahend_string));
  
  printf( "f %3s %3s %s - %s = %f - %f = %.3f\n", $runde_arg_1, $runde_arg_2, $minuend_string, $subtrahend_string, $minuend_float, $subtrahend_float, $minuend_float - $subtrahend_float);
# printf( "f %s + %s = %f + %f = %f<br />\n", $minuend_string, $subtrahend_string, $minuend_float, $subtrahend_float, $minuend_float + $subtrahend_float);
}

function differenz_in_min ( $term) {
  preg_match( "/([.\d]*)(-)([.\d]*)/", $term, $matches);
  # printf( "a %s b %s c%s<br />\n", $matches[1], $matches[2], $matches[3]);
  
  $minuend_string = $matches[1];
  $subtrahend_string = $matches[3];
  
  # printf( "%.f %.f<br />\n", $minuend_string, $subtrahend_string);
  
  $minuend_integer    = minute( $minuend_string   );
  $subtrahend_integer = minute( $subtrahend_string);
  
  # printf( "%s = %f<br />\n", $minuend_integer,    fliesz( $minuend_integer));
  # printf( "%s = %f<br />\n", $subtrahend_integer, fliesz( $subtrahend_integer));
  
  printf( "i %s - %s = %d - %d = %d = %f %d %d %d \n", $minuend_string, $subtrahend_string,
	  $minuend_integer,
	  $subtrahend_integer,
	  $minuend_integer - $subtrahend_integer,
	  round( ($minuend_integer - $subtrahend_integer) / 60.0, 2),
  $minuend_integer%3,$subtrahend_integer%3,($minuend_integer - $subtrahend_integer)%3
  );
# printf( "i %s + %s = %d + %d = %d<br />\n", $minuend_string, $subtrahend_string, $minuend_integer, $subtrahend_integer, $minuend_integer + $subtrahend_integer);
}

echo "<pre>\n";
$term =  $_SERVER['QUERY_STRING'];
# echo $_SERVER['QUERY_STRING'] . "<br />\n";
# echo "$term<br />\n";

differenz_in_std( $_SERVER['QUERY_STRING'], ""); printf( "<br />\n");

function erprobe( $arg, $soll) {
	printf( "Soll : %s\n",  $soll);
	differenz_in_min( $arg); 
	differenz_in_std( $arg, "auf", "auf");
	differenz_in_std( $arg, "auf", "abb");
	differenz_in_std( $arg, "abb", "auf");
	differenz_in_std( $arg, "abb", "abb");
	differenz_in_std( $arg, "din", "din");
	differenz_in_std( $arg, "",    "");
	printf( "\n");
}

erprobe( "16.23-13.45", "");
erprobe( "20.07-16.38", "");
erprobe( "20.00-16.38", "");
erprobe( "20.07-20.00", "");
erprobe( "13.55-11.45", "");
erprobe( "17.09-14.10", "");
erprobe( "20.19-17.24", "");
erprobe( "20.15-17.24", "");
erprobe( "17.40-14.30", "");
erprobe( "20.22-17.55", "");
erprobe( "14.25-11.45", "");
                                 
erprobe( "00.04-00.01", "");
erprobe( "00.05-00.01", "");
erprobe( "00.06-00.01", "");
                                 
erprobe( "00.04-00.02", "");
erprobe( "00.05-00.02", "");
erprobe( "00.06-00.02", "");
                                 
erprobe( "00.04-00.03", "");
erprobe( "00.05-00.03", "");
erprobe( "00.06-00.03", "");
                                 
erprobe( "00.06-00.03", "");
                                 
erprobe( "17.17-13.51", "3.44");
erprobe( "09.38-06.45", "2.89");
erprobe( "15.17-11.45", "3.54");
erprobe( "17.45-15.32", "2.21");
erprobe( "18.30-16.53", "1.61");

erprobe( "13.22-09.53", "3.48");

?>

<pre>
Beschluss : Uhrzeiten in Minuten wandeln, damit Differenzen errechnen und diese gerundet ausgeben.
Oder Uhrzeiten in Stunden mit einer Genauigkeit einer tausendstel Stunde wandeln und diese gerundet ausgeben.
Das Runden bei der Umrechnung von Minuten in Stunden.
Wir runden auf Hundertstel Stunden genau.
1 min = 0.02 h   0.0033333333 zu viel
2 min = 0.03 h   0.0033333333 zu wenig
3 min = 0.05 h   genau
4 min = 0.07 h   0.0033333333 zu viel
5 min = 0.08 h   0.0033333333 zu wenig
6 min = 0.10 h   genau

Differenzen

1 -1 =3   0.07 - 0.02 = 0.05      genau
2 -1 =4   0.08 - 0.02 = 0.06 -0.1 zu wenig 
3 -1 =5   0.10 - 0.02 = 0.08      genau

1 -2 =2   0.07 - 0.03 = 0.04 +0.1 zu viel
2 -2 =3   0.08 - 0.03 = 0.05      genau
3 -2 =4   0.10 - 0.03 = 0.07      genau

1 -3 =1   0.07 - 0.05 = 0.02      genau
2 -3 =2   0.08 - 0.05 = 0.03      genau
3 -3 =3   0.10 - 0.05 = 0.05      genau

Differenzen

147 -147 =0   0.07 - 0.02 = 0.05      genau
258 -147 =1   0.08 - 0.02 = 0.06 -0.1 zu wenig 
369 -147 =2   0.10 - 0.02 = 0.08      genau

147 -258 =2   0.07 - 0.03 = 0.04 +0.1 zu viel
258 -258 =0   0.08 - 0.03 = 0.05      genau
369 -258 =1   0.10 - 0.03 = 0.07      genau

147 -369 =1   0.07 - 0.05 = 0.02      genau
258 -369 =2   0.08 - 0.05 = 0.03      genau
369 -369 =0   0.10 - 0.05 = 0.05      genau
</pre>

