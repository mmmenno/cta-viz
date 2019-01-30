<?php

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

while($row = $result->fetch_assoc()){
	//print_r($row);
	//echo "================\n";
	echo "\n" . $row['Straatnaam'] . "\n";

	$sql2 = "SELECT * FROM CTA_index WHERE 
				MATCH(BESCHRIJVING) AGAINST ('\"" . preg_replace('/[^\p{L}\p{N}_]+/u', ' ', $row['Straatnaam']) . "\"' IN BOOLEAN MODE )
				AND BESCHRIJVING like '%" . $mysqli->real_escape_string($row['Straatnaam']) . "%'";
	$ctaresult = $mysqli->query($sql2);

	while($rij = $ctaresult->fetch_assoc()){
		//print_r($rij);
		//echo $rij['BESCHRIJVING'] . "\n";
		echo ". ";
		$sql3 = "insert into cta_x_straat (scope_id,uri,name_found) values ( 
					'" . $rij['SCOPEID'] . "',
					'" . $row['URI'] . "',
					'" . $mysqli->real_escape_string($row['Straatnaam']) . "')";
		$do = $mysqli->query($sql3);
	}
}




?>