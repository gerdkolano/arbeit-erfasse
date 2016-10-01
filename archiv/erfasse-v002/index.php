<?php
$programm = array (
"zeitkonto.php"                                        ,
"zeitkonto.php?start=2014-12"                          ,
"zeitkonto.php?start=2014-12&stop=2016-4"              ,
"zeitkonto.php?start=2014-12&stop=2016-4&gfos=ja"      ,
"mache_geltend.php?datum=2014-11-24&anzahl=77"         ,
"../kalender/anal-to-html.php"                         ,
"README"                                               ,
);
$editiere = array (
"speichere.php"                                        ,
"erfasse.php"                                          ,
);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<h3> Für Fred. Wenn's unverständlich ist, bitte fragen. </h3>
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
foreach ($editiere as $key=>$val) {
  printf( "<li><a href=\"http://zoe.xeo/arbeit/erfasse/%s\" target=\"_blank\">%s</a>\n", $val, $val);
}
?>
</ol>



</body>
</html>

