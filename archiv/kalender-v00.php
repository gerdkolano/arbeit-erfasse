<?php
function datumsobjekt( $datum) {
  try {
    $date = new DateTime( $datum);
  } catch (Exception $e) {
    echo $e->getMessage();
    exit(1);
  }
  //echo $date->format('Y-m-d  H:i:s N W');
  return $date;
}

function main () {
  if (php_sapi_name()==="cli") { // von der Kommandozeile gerufen
  #if (false) { // zum Testen
    // echo count($_SERVER['argv']) . "\n";
    $start = "";
    $stop  = "";
    // foreach geht nicht
    while ( $arg = next( $_SERVER['argv'])) {
      switch ($arg) {
      case "-a": $start = next ($_SERVER['argv']); break;
      case "-e": $stop  = next ($_SERVER['argv']); break;
      default : echo "arg $arg\n"; break;
      }
    }
  } else {
    printf( "<!DOCTYPE html>\n");
    printf( "<html>\n");
    printf( "<head>\n");
    printf( "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n");
    printf( "<pre>\n");
    #php_sapi_name()==="cli";
    #printf( "%s\n", php_sapi_name());
    # echo count($_GET) . "<br />\n";
    # echo "<pre>"; print_r( $_GET); echo "</pre>";
    $start = "";
    $stop  = "";
    #while ( $arg = next( $_GET)) {
    foreach ( $_GET as $key=>$arg) {
      # echo "<pre>"; print_r( $arg); echo "</pre>";
      switch ($key) {
      case "start": $start = $arg; break;
      case "stop" : $stop  = $arg; break;
      default : echo "arg $arg\n"; break;
      }
    }
  }
  arbeite( $start, $stop);
}

function arbeite( $start, $stop) {
  $startobjekt   = datumsobjekt( $start);  printf( "Start %s\n", $startobjekt->format('Y-m-d - D'));
  $stopobjekt    = datumsobjekt( $stop);   printf( "Stop  %s\n", $stopobjekt->format('Y-m-d - D'));
  $montagsobjekt = datumsobjekt( $start);  printf( "Start %s\n", $montagsobjekt->format('Y-m-d - D'));
  $montagsobjekt->modify( 'Monday');       printf( "Nachmontag %s\n", $montagsobjekt->format('Y-m-d - D'));
  $montagsobjekt->modify( 'last Monday');  printf( "Vormontag  %s\n", $montagsobjekt->format('Y-m-d - D'));

  $ein_tag = new DateInterval( 'P1D'); // Period 1 Day
  for ($i=0; $i<7; $i++) {
    $montagsobjekt->add( $ein_tag);
    printf( "tt  %s\n", $montagsobjekt->format('Y-m-d - D'));
  }
  $laufobject = clone( $startobjekt);
  while ( $laufobject < $stopobjekt) {
    $laufobject->add( $ein_tag);
    printf( "uu  %s\n", $laufobject->format('Y-m-d - D'));
  }
}

main();
?>

