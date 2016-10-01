<?php
$date = DateTime::createFromFormat("d.m.y", "18.3.15");    echo $date->format("Y-m-d<br />\n");
$date = DateTime::createFromFormat("Hi", "0915");          echo $date->format("H:i<br />\n");
?>


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


