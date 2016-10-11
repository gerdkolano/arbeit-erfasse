<?php
function verbinde( $host) {
  $mysqli = new mysqli("zoe.xeo", "hanno", "geheim", "arbeit");
  if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
  }
  return $mysqli;
}

function lies( $mysqli) {
  $table = "verdienst";
  $query = "SELECT id, datum FROM $table ORDER BY id ASC";
  $query = "select datum, round(la300/144) as std1, round(la300/144*1.04) as std2, 0.01*round(la422/2/(round(la300/14400*1.04))) as abgegolten, zt305, sa305, la305, round( zt305 * sa305 / 100) as l305, zt307, sa307, la307, round( zt307 * sa307 / 100) as l307, zt357, sa357, la357, round( zt357 * sa357 / 500) as l357, zt770, sa770, la770, ceil( zt770 * sa770 / 200) as l770 from verdienst order by datum;";
  
  $res = $mysqli->query( $query);
  if ( !$res) {
      echo "Table $table opening failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }
  
  $erg = "";
  $res->data_seek(0);
  $finfo = $res->fetch_fields();
  foreach ($finfo as $val) {printf("%s\n",   $val->name);}
  echo "<br />\n";
  
  echo "std1 datum<br />\n";
  while ($row = $res->fetch_assoc()) {
      echo $row['datum'      ] . "\n";
      echo $row['std1'       ] . "\n";
      echo $row['abgegolten' ] . "\n";
      echo "<br />\n";
  }
}

lies( verbinde( "zoe.xeo"));

?>

<pre>

</pre>

<!--
mysql> show columns from verdienst;
+--------------+-----------+------+-----+-------------------+-----------------------------+
| Field        | Type      | Null | Key | Default           | Extra                       |
+--------------+-----------+------+-----+-------------------+-----------------------------+
| id           | int(11)   | NO   | PRI | NULL              | auto_increment              |
| datum        | date      | YES  |     | NULL              |                             |
| la001        | int(11)   | YES  |     | NULL              |                             |
| la300        | int(11)   | YES  |     | NULL              |                             |
| zt305        | int(11)   | YES  |     | NULL              |                             |
| sa305        | int(11)   | YES  |     | NULL              |                             |
| la305        | int(11)   | YES  |     | NULL              |                             |
| zt307        | int(11)   | YES  |     | NULL              |                             |
| sa307        | int(11)   | YES  |     | NULL              |                             |
| la307        | int(11)   | YES  |     | NULL              |                             |
| zt357        | int(11)   | YES  |     | NULL              |                             |
| sa357        | int(11)   | YES  |     | NULL              |                             |
| la357        | int(11)   | YES  |     | NULL              |                             |
| la422        | int(11)   | YES  |     | NULL              |                             |
| la444        | int(11)   | YES  |     | NULL              |                             |
| la531        | int(11)   | YES  |     | NULL              |                             |
| la541        | int(11)   | YES  |     | NULL              |                             |
| la549        | int(11)   | YES  |     | NULL              |                             |
| la550        | int(11)   | YES  |     | NULL              |                             |
| la570        | int(11)   | YES  |     | NULL              |                             |
| la613        | int(11)   | YES  |     | NULL              |                             |
| la639        | int(11)   | YES  |     | NULL              |                             |
| la671        | int(11)   | YES  |     | NULL              |                             |
| la677        | int(11)   | YES  |     | NULL              |                             |
| la692        | int(11)   | YES  |     | NULL              |                             |
| la700        | int(11)   | YES  |     | NULL              |                             |
| la707        | int(11)   | YES  |     | NULL              |                             |
| la753        | int(11)   | YES  |     | NULL              |                             |
| la756        | int(11)   | YES  |     | NULL              |                             |
| la760        | int(11)   | YES  |     | NULL              |                             |
| zt770        | int(11)   | YES  |     | NULL              |                             |
| sa770        | int(11)   | YES  |     | NULL              |                             |
| la770        | int(11)   | YES  |     | NULL              |                             |
| BRG          | int(11)   | YES  |     | NULL              |                             |
| BSL          | int(11)   | YES  |     | NULL              |                             |
| SZFz         | int(11)   | YES  |     | NULL              |                             |
| SZF          | int(11)   | YES  |     | NULL              |                             |
| BSE          | int(11)   | YES  |     | NULL              |                             |
| LSE          | int(11)   | YES  |     | NULL              |                             |
| LST          | int(11)   | YES  |     | NULL              |                             |
| LAG          | int(11)   | YES  |     | NULL              |                             |
| SOZ          | int(11)   | YES  |     | NULL              |                             |
| SAG          | int(11)   | YES  |     | NULL              |                             |
| BRK          | int(11)   | YES  |     | NULL              |                             |
| BEK          | int(11)   | YES  |     | NULL              |                             |
| KAN          | int(11)   | YES  |     | NULL              |                             |
| KZA          | int(11)   | YES  |     | NULL              |                             |
| KEN          | int(11)   | YES  |     | NULL              |                             |
| KZE          | int(11)   | YES  |     | NULL              |                             |
| BRR          | int(11)   | YES  |     | NULL              |                             |
| BER          | int(11)   | YES  |     | NULL              |                             |
| RAN          | int(11)   | YES  |     | NULL              |                             |
| PAN          | int(11)   | YES  |     | NULL              |                             |
| PEN          | int(11)   | YES  |     | NULL              |                             |
| AAN          | int(11)   | YES  |     | NULL              |                             |
| REN          | int(11)   | YES  |     | NULL              |                             |
| AEN          | int(11)   | YES  |     | NULL              |                             |
| ZVU          | int(11)   | YES  |     | NULL              |                             |
| GSN          | int(11)   | YES  |     | NULL              |                             |
| la990        | int(11)   | YES  |     | NULL              |                             |
| VLA          | int(11)   | YES  |     | NULL              |                             |
| GWS          | int(11)   | YES  |     | NULL              |                             |
| UVM          | int(11)   | YES  |     | NULL              |                             |
| UEZ          | int(11)   | YES  |     | NULL              |                             |
| AZB          | int(11)   | YES  |     | NULL              |                             |
| aktualisiert | timestamp | NO   |     | CURRENT_TIMESTAMP | on update CURRENT_TIMESTAMP |
+--------------+-----------+------+-----+-------------------+-----------------------------+
66 rows in set (0.00 sec)
-->

