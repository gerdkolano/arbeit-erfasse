<?php
$programm = array (
"zeitkonto.php?start=2014-12&stop=2016-4"              ,
"zeitkonto.php?start=2014-12&stop=2016-4&gfos=ja"      ,
"mache_geltend.php?datum=2014-11-24&anzahl=77"         ,
"view-table.php"                                       ,
"../kalender/anal-to-html.php"                         ,
"README"                                               ,
"speichere.php"                                        ,
"erfasse.php"                                          ,
)
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<h3> Produktion </h3>
<ol>
<?php
foreach ($programm as $key=>$val) {
  printf( "<li><a href=\"http://gerd.dyndns.za.net/arbeit/erfasse/%s\" target=\"_blank\">%s</a>\n", $val, $val);
}
?>
</ol>



<h3> Produktion nur daheim </h3>
<ol>
<?php
foreach ($programm as $key=>$val) {
  printf( "<li><a href=\"http://zoe.xeo/arbeit/erfasse/%s\" target=\"_blank\">%s</a>\n", $val, $val);
}
?>
</ol>



</body>
</html>

