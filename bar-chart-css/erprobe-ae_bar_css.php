<!DOCTYPE html><html><head>
<title>strange bar chart example</title>
<style type="text/css">
table.chart { width: 100%; }
table.chart td { 
    font-size: 8pt;
    font-family: Arial,serif; 
}
table.chart tr.barvrow td 
{ 
    width: 5%;
    height: 300px; 
    vertical-align: bottom;
    border-bottom-color: darkblue;
    border-bottom-style: solid;
    text-align: center; 
}
table.chart tr.bartrow td 
{
    text-align: center;
    width: 100px;
}
</style>
</head>
<body>
<h3>
<a href="http://anyexample.com/programming/php/bar_chart_html_generator.xml" target="_blank">http://anyexample.com/programming/php/bar_chart_html_generator.xml</a>
</h3>

<?php 
require_once( "ae_bar_css.php");
$sinus = array();

// filling array with values of 
// expression round(1 + abs(sin($x)*10), 1);
for ($x = -pi(); $x < pi(); $x += pi() / 10)
    $sinus[strval(round($x, 1))] = round(1 + abs(sin($x)*10), 1);
// array key for float numbers should be string 

echo '<table class="chart">';
echo ae_bar_css($sinus, 300);
echo '</table>';
?>
</body>
</html>
