<?php
//$servername = "localhost";
//$username = "root";
//$password = "";

$servername = "localhost";
$username = "tecnotr1_webmstr";
$password = "Oa?*&2#Bzuqt";

$dbname = "tecnotr1_website";

// Create connection
$conex = mysqli_connect($servername, $username, $password, $dbname);
if (mysqli_connect_errno($conex)) {
    echo "Fallo al contenctar a MySQL: " . mysqli_connect_error();
}

date_default_timezone_set('America/Mexico_City');

$conex->set_charset('utf8');
$variable = "4n1t4_14v4_14_t1n4";
?>
