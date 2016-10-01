<?php

echo "<a href='http://anyexample.com/programming/php/bar_chart_html_generator.xml' target='_blank'>http://anyexample.com/programming/php/bar_chart_html_generator.xml</a><br />\n";
require_once( "ae_bar_html.php");
// loading data
// setting values to associative array 
// day=>number of visitors 
$visitors = array('1.1.2007'=>450, '1.2.2007'=>420, 
                  '1.3.2007'=>440, '1.4.2007'=>430,
                  '1.5.2007'=>421, '1.6.2007'=>318,
                  '1.7.2007'=>234);
?> 
<table> 
<?php
echo ae_bar_html($visitors, '300', 'red', 100);
?> 
</table>
