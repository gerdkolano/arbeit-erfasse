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
  monat( datumsobjekt( $start));
}

function monat( $tag) {
  $dieser_monat = $tag->format('n');
  $erster = clone( $tag); $erster->modify( "first day of"); printf( "erster  %s\n", $erster->format('Y-m-d - D'));
  $ultimo = clone( $tag); $ultimo->modify( "last day of");  printf( "ultimo  %s\n", $ultimo->format('Y-m-d - D'));
  $vormontag  = clone( $erster); $vormontag->modify( "last Monday"); printf( "Vormontag  %s\n" , $vormontag->format('Y-m-d - D'));
  $nachmontag = clone( $ultimo); $nachmontag->modify( "Monday");     printf( "Nachmontag  %s\n", $nachmontag->format('Y-m-d - D'));
  $laufobject = clone( $vormontag);
  $ein_tag = new DateInterval( 'P1D'); // Period 1 Day
  while ( $laufobject < $nachmontag) {
    if ( $laufobject->format('n') == $dieser_monat) {
      $zeile .= sprintf( "%s ", $laufobject->format('d'));
    } else {
      $zeile .= sprintf( "   ");
    }
    if ($laufobject->format('l') == "Sunday") {
      printf( "%s \n", $zeile);
      $zeile = "";
    }
    $laufobject->add( $ein_tag);
  }
  
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
    printf( "uu  %s\n", $laufobject->format('Y-m-d - D'));
    $laufobject->add( $ein_tag);
  }
}

main();
?>

