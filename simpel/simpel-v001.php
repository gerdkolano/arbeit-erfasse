
<?php
$mysqli = new mysqli("zoe.xeo", "hanno", "geheim", "arbeit");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

$query  = "";
$query .= "INSERT INTO test(";
$query .= "id,datum";
$query .= ") VALUES (";
$query .= "1,10";
$query .= "), (";
$query .= "2,11";
$query .= "), (";
$query .= "3,12";
$query .= "), (";
$query .= "4,13";
$query .= "), (";
$query .= "5,14";
$query .= ")";

if (!$mysqli->query( "DROP TABLE IF EXISTS test") ||
    !$mysqli->query( "CREATE TABLE test(id INT, datum INT)") ||
    !$mysqli->query( $query)) {
    echo "Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

$res = $mysqli->query("SELECT id, datum FROM test ORDER BY id ASC");

echo "Reverse order...\n";
for ($row_no = $res->num_rows - 1; $row_no >= 0; $row_no--) {
    $res->data_seek($row_no);
    $row = $res->fetch_assoc();
    echo " id = " . $row['id'] . "\n";
    echo " datum = " . $row['datum'] . "\n";
}

echo "<br>\n";

echo "Result set order...\n";
$res->data_seek(0);
while ($row = $res->fetch_assoc()) {
    echo " id = " . $row['id'] . "\n";
    echo " datum = " . $row['datum'] . "\n";
}
?>
