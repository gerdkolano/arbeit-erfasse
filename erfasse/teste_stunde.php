<?php
require_once( "stunde.php");

$std_1 = new stunde( 1234);
$std_2 = new stunde( 2345);

printf( "%s\n", $std_1);

echo "<pre> std "; print_r( $std_1); echo "</pre>\n";

$zugewiesen = $std_1;

echo "<pre> zugewiesen "; print_r( $zugewiesen); echo "</pre>\n";

$zugewiesen = $std_1->add( $std_2);

printf( " %s kleiner %s  %s\n", $std_1, 0          , $std_1->lt0(      )      ? "ja "   : "nein ");
printf( " %s größer  %s  %s\n", $std_2, 0          , $std_2->lt0(      )      ? "nein " : "ja "  );

printf( " %s kleiner %s  %s\n", $std_1, $std_2     , $std_1->lt( $std_2)      ? "ja "   : "nein ");
printf( " %s größer  %s  %s\n", $std_2, $std_1     , $std_2->lt( $std_1)      ? "nein " : "ja "  );
printf( " %s größer  %s  %s\n", $std_2, $zugewiesen, $std_2->lt( $zugewiesen) ? "nein " : "ja "  );

echo "<pre> zugewiesen "; print_r( $zugewiesen); echo "</pre>\n";
