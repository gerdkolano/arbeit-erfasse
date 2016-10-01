<?php
require_once( "../include/datum.php");

function brief( $quelle) {
  // A4 210 by 297 millimetres
  $konverter = "/usr/bin/wkhtmltopdf";
  $ziel   = "/daten/srv/www/htdocs/arbeit/pdf/brief.pdf";
  $kommando = "$konverter --margin-left 20 --margin-top 20 --page-size A4 --orientation Landscape \"$quelle\" $ziel";
  $kommando = "$konverter --margin-top  0 --margin-right 0 --margin-bottom 0 --margin-left 0 --page-height 600mm --page-width 400mm --orientation Portrait  \"$quelle\" $ziel";
  $kommando = "$konverter --margin-top  0 --margin-right 0 --margin-bottom 0 --margin-left 0 --page-size A4 --orientation Portrait  \"$quelle\" $ziel";
  $kommando = "$konverter --margin-top  0 --margin-right 0 --margin-bottom 0 --margin-left 0 --page-width  70mm --page-height  99mm --orientation Portrait  \"$quelle\" $ziel";
  $kommando = "$konverter --margin-top  0 --margin-right 0 --margin-bottom 0 --margin-left 0 --page-width 140mm --page-height 198mm --orientation Portrait  \"$quelle\" $ziel";
  $kommando = "$konverter --margin-top  0 --margin-right 0 --margin-bottom 0 --margin-left 0 --page-width 210mm --page-height 297mm --orientation Portrait  \"$quelle\" $ziel";
  echo "$kommando\n";
  system( $kommando);
}

$url = "http://zoe.xeo/arbeit/brief-din-5008/brief-din-5008.php"
  . "?gesendet-von=brief-schreiben.php"
  . "&anschrift%5B%5D="
  . "&anschrift%5B%5D="
  . "&anschrift%5B%5D="
  . "&anschrift%5B%5D=Sabine+Schallehn"
  . "&anschrift%5B%5D=L%C3%B6wenbrucher+Weg+24c"
  . "&anschrift%5B%5D=12307+Berlin"
  . "&anschrift%5B%5D="
  . "&anschrift%5B%5D="
  . "&anschrift%5B%5D="
  . "&info%5B%5D="
  . "&info%5B%5D="
  . "&info%5B%5D=grd"
  . "&info%5B%5D="
  . "&info%5B%5D=Sabine+Schallehn"
  . "&info%5B%5D=030+744+09+05"
  . "&info%5B%5D="
  . "&info%5B%5D="
  . "&info%5B%5D="
  . "&brieftext%5B%5D=Betrifft " . (new datum_objekt())->deutsch( "EEEE d.MMMM Y")
  . "&brieftext%5B%5D=Sehr+geehrte"
  . "&brieftext%5B%5D=%3Cp%3E%0D%0A"
    . "rem+ipsum+nulla+non+risus+nec+quisque+faucibus+ullamcorper+dapibus%2C+eleifend+pulvinar+torquent+amet+blandit+taciti+imperdiet+volutpat%2C+eleifend+feugiat+orci+turpis+quam+eget+curabitur+quam.+%0D%0A"
    . "adipiscing+neque+nulla+velit+varius+nec+aptent+quisque+felis+aliquam%2C+venenatis+risus+dictumst+vivamus+integer+eros+litora+vitae+senectus%2C+ante+amet+aliquam+gravida+feugiat+ultrices+dictum+in.+%0D%0A"
    . "duis+id+mollis+vel+quis+integer+morbi+est+morbi+imperdiet+etiam+luctus+adipiscing%2C+malesuada+tristique+curabitur+feugiat+sollicitudin+senectus+fusce+pretium+dui+sodales+cubilia.+%0D%0A"
    . "varius+donec+at+morbi+mauris+sodales+quam+urna+metus+ut%2C+sit+lacinia+duis+vivamus+inceptos+cras+diam+elementum%2C+leo+id+laoreet+primis+lorem+aliquam+enim+per.+%0D%0A"
    . "%3C%2Fp%3E%0D%0A"
    . "%3Cp%3E%0D%0A"
    . "lorem+lacinia+"
  ."pharetra+felis+torquent+nam+quisque+donec+mauris+rutrum%2C+aliquet+diam+malesuada+commodo+quam+consequat+pharetra+aptent%2C+curabitur+hac+porta+sodales+tortor+nostra+a+at.+%0D%0A"
    . "sollicitudin+felis+facilisis+malesuada+quisque+donec+integer+consequat+nisl%2C+non+sodales+rutrum+bibendum+senectus+aliquet+luctus+etiam+dictumst%2C+risus+vivamus+platea+odio+ut+aenean+neque.+%0D%0A"
    . "dapibus+morbi+ultricies+eget+bibendum+feugiat+justo+lectus+laoreet+urna+himenaeos%2C+integer+eros+fusce+commodo+ut+ipsum+cras+aliquet+egestas+torquent%2C+gravida+phasellus+in+suscipit+risus+velit+dictumst+arcu+adipiscing.+%0D%0A"
    . "erat+malesuada+orci+quisque+felis+diam+tincidunt+habitasse+lacus+accumsan%2C+netus+vehicula+orci+elementum+eget+turpis+arcu+potenti%2C+cras+rutrum+pharetra+curabitur+duis+adipiscing+euismod+placerat.+%0D%0A"
    . "%3C%2Fp%3E%0D%0A"
    . "%3Cp%3E%0D%0A"
    . "nulla+elementum+proin+faucibus+ornare+ligula+etiam+maecenas%2C+congue+lectus+mi+justo+nec+netus+semper+turpis%2C+cras+id+mollis+maecenas+sagittis+dapibus.+%0D%0A"
    . "imperdiet+arcu+sollicitudin+pharetra+porta+velit+sem+himenaeos+quisque+fusce%2C+etiam+justo+sodales+viverra+sollicitudin+euismod+blandit+massa+aenean+nec%2C+aenean+lacinia+tincidunt+mollis+viverra+neque+mattis+sed.+%0D%0A"
    . "placerat+nisl+luctus+nulla+nostra+amet+in+vivamus+etiam+est%2C+feugiat+gravida+mollis+turpis+euismod+volutpat+pretium+id%2C+sed+dictumst+rutrum+sodales+dictumst+purus+viverra+aliquam.+%0D%0A"
    . "tempor+etiam+sagittis+dictumst+lorem+a+congue+etiam+sapien%2C+arcu+laoreet+conubia+nostra+purus+blandit+egestas+potenti%2C+fusce+tortor+risus+tellus+ullamcorper+dapibus+id.+%0D%0A"
    . "%3C%2Fp%3E%0D%0A"
    . "%3Cp%3E%0D%0A"
    . "tellus+nullam+amet+pretium+vulputate+arcu+imperdiet+quisque+dapibus%2C+turpis+commodo+laoreet+ipsum+dapibus+hac+ante%2C+nec+tellus+rhoncus+sed+quis+curabitur+ultricies.+%0D%0A"
    . "lectus+himenaeos+dictum+dui+nulla+urna+lacinia+id+odio+curabitur%2C+adipiscing+interdum+condimentum+pharetra+auctor+sit+et+consequat.+%0D%0A"
    . "feugiat+quisque+nisl+vivamus+duis+convallis+tristique+volutpat+elit+eget%2C+orci+diam+lobortis+sociosqu+proin+et+pellentesque+iaculis+amet%2C+sem+nisl+feugiat+taciti+porta+duis+mi+eleifend.+%0D%0A"
    . "lacinia+pharetra+molestie+sollicitudin+sodales+justo+nisl+bibendum+eget+ornare+quisque+odio%2C+eget+consectetur+magna+eros+fringilla+potenti+ut+ornare+diam+turpis.+%0D%0A"
    . "%3C%2Fp%3E"
  . "&brieftext%5B%5D=Mit+freundlichen+Gr%C3%BC%C3%9Fen"
  . "&brieftext%5B%5D=Sabine+Schallehn";

if (true) brief( $url);
?>

