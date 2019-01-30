<?php
error_reporting(E_ALL);

$db = "CTA";
$name = "root";
$pass = "root";
$host = "127.0.0.1";
$port = "8889";

$mysqli = new mysqli($host, $name, $pass, $db, $port);
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
}

/** 
Script dat alle potentieel gevaarlijke matches van dubbel straatnamen gaat opslaan
Lombardtseeg en Enge Lombardsteeg
 */
//printf("Initial character set: %s\n", $mysqli->character_set_name());

/* change character set to utf8 */
if (!$mysqli->set_charset("utf8")) {
    printf("Error loading character set utf8: %s\n", $mysqli->error);
    exit();
} else {
    //printf("Current character set: %s\n", $mysqli->character_set_name());
}

$sql = "select * from Straatnamen";

$result = $mysqli->query($sql);
$i =1;

while($row = $result->fetch_assoc()){

	//print_r($row);
	//echo "================\n";
	//echo "\n" . $row['Straatnaam'] . "\n";

	$sql2 = "SELECT * FROM Straatnamen 
	WHERE (Straatnaam like '% " . $mysqli->real_escape_string($row['Straatnaam']) . "%')
	AND NOT Straatnaam = '". $mysqli->real_escape_string($row['Straatnaam']) . "' 
	";
	$ctaresult = $mysqli->query($sql2);

	while($rij = $ctaresult->fetch_assoc()){

		if ($row['URI'] != $rij['URI']) {

			echo "Straatnaam " . $row['Straatnaam'] . ' zit in: ' .  $rij['Straatnaam'] . "\n";
			//echo ". ";
			$sql3 = "insert into dubbele_matches (uri, andere_uri, Straatnaam, Straatnaam_volledig) values ( 
						'" . $row['URI'] . "',
						'" . $rij['URI'] . "',
						'" . $mysqli->real_escape_string($row['Straatnaam']) . "',
						'" . $mysqli->real_escape_string($rij['Straatnaam']) . "')";
		$do = $mysqli->query($sql3);
		}
	}


}