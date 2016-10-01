<pre>
<?php
require_once("datum.php");

$formate = array (
  "d",
  "d.",
  "d.m",
  "d.m.",
  "d.m.y",
  "Y-m",
  "Y-m-d",
);

foreach ($formate as $eine_form) {
  $date = DateTime::createFromFormat( $eine_form, "18.3.15"   ); if ($date)   echo $date->format("Y-m-d") . "\n";
  $date = DateTime::createFromFormat( $eine_form, "2015-08-16"); if ($date)   echo $date->format("Y-m-d") . "\n";
}

echo "Und nun <br />\n";

$proben = array (
  "0",
  "1-",
  "2.",
  "18.3",
  "18.4.",
  "18.5.15",
  "18.6.15trailing garbage",
  "18.6.15-trailing garbage",
  "2015-07",
  "2015-08-16",
);

foreach ($proben as $probe) {
  printf( "%s %s\n", datum_create( $probe)->format( "Y-m-d"), $probe);
  echo "\n";
}

foreach ($proben as $probe) {
  foreach ($formate as $eine_form) {
    $date = DateTime::createFromFormat( $eine_form, $probe   );
    printf( "%s %s %s\n", $date ? $date->format("Y-m-d") : "....-..-..", $eine_form, $probe);
  }
  echo "\n";
}

function datum_create( $wort) {
  $formate = array (
    "d",
    "d.",
    "d.m",
    "d.m.",
    "d.m.y",
    "Y-m",
    "Y-m-d",
  );
  foreach ($formate as $eine_form) {
    if ($date = datum_objekt::createFromFormat( $eine_form, $wort)) return $date;
  }
  return new datum_objekt();
}

echo "Und nun <br />\n";
exit( 0);

  $date = DateTime::createFromFormat("d.m.y", "18.3.15");    echo $date->format("Y-m-d") . " Y-m-d<br />\n";
  $date = DateTime::createFromFormat("Hi", "0915");          echo $date->format("H:i") . " H:i<br />\n";

  for ($ii=1; $ii<=31; $ii++) {
    $Ymd = sprintf( "2016-10-%02d", $ii);
    $date = new datum_objekt( $Ymd);
    printf( "%s %s",                     $Ymd, $date->format("N D Y-m-d"));
    $neu  = $date->erster_werktag_der_woche();
    printf( " erster Werkag der Woche %s | ",     $neu->format("D Y-m-d"));
    $neu  = $date->letzter_werktag_der_woche();
    printf( " letzter Werkag der Woche %s | ",    $neu->format("D Y-m-d"));
    $neu  = $date->donnerstag_der_woche();
    printf( " Donnerstag der Woche %s | ",        $neu->format("D Y-m-d"));
    $neu  = $date->monatsnummer_der_woche();
    printf( " Monat der Woche %s | ",             $neu);
    echo "\n";
  }

  echo "\n";
  for ($ii=1; $ii<=14; $ii++) {
    $Ymd = sprintf( "2016-02-%02d", $ii);

    $date = DateTime::createFromFormat("Y-m-d", $Ymd);
    printf( "%s %s", $Ymd, $date->format("N D Y-m-d"));
    $mod = sprintf( "%+d day", 1 - $date->format("N"));
    $date->modify( $mod);
    printf( " erster Tag der Woche %s %s | ", $date->format("D Y-m-d"), $mod);

    $date = DateTime::createFromFormat("Y-m-d", $Ymd);
  # printf( "%s %s", $Ymd, $date->format("D Y-m-d"));
    $mod = sprintf( "%+d day", 6 - $date->format("N"));
    $date->modify( $mod);
    printf( " letzter Werktag der Woche  %s %s | ", $date->format("D Y-m-d"), $mod);

    $date = DateTime::createFromFormat("Y-m-d", $Ymd);
  # printf( "%s %s", $Ymd, $date->format("D Y-m-d"));
    $mod = sprintf( "%+d day", 4 - $date->format("N"));
    $date->modify( $mod);
    printf( " Donnerstag der Woche %s %s | ", $date->format("D Y-m-d"), $mod);
    echo "<br />\n";
  }

for ($jj=0; $jj<7; $jj++) {               // $jj == 1 ist gut
  for ($ii=0; $ii<14; $ii++) {
  $monat = DateTime::createFromFormat("d.m.y", sprintf( "%s.4.16", $ii));
  $kriterium = $monat->format( 'w');
    printf( "%02d < %02d %s ", $kriterium, $jj, $monat->format("Y-m-d w D "));
    $monat->modify( ($kriterium < $jj) ? 'monday last week' : 'monday this week');
    echo $monat->format("d") . "\n";
  }
  echo "\n";
}

?>
</pre>

<?php

try {

    $date = new DateTime('asdfasdf');

} catch (Exception $e) {
    // Nur zu Demonstrationszwecken...
    echo "<pre>\n";    
    print_r( DateTime::getLastErrors());
    print_r( $e);

    $err = DateTime::getLastErrors();

    printf( "errors %d <br />\n", $err["error_count"]);


    echo "</pre>\n";    
    echo "<br />\n";    

    // Die wahre objektorientierte Weise, um dies zu tun ist
    echo $e->getMessage();

    echo "<br />\n";    

}
?>


