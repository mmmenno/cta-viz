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


// haal alle dubbele op
$sql1 = "SELECT * from dubbele_matches";

$result = $mysqli->query($sql1);

$i=0;
while($row = $result->fetch_assoc()){
	// zoek scopeIds waaraan zowel uri als andere uri zijn gekoppeld
	//echo "Zoek naar {$row['Straatnaam']} - {$row['uri']} en  {$row['Straatnaam_volledig']} {$row['andere_uri']}" . PHP_EOL;

	$sql2 = "SELECT c1.scope_id, c1.uri 
	FROM cta_x_straat_ontdubbeld c1
	JOIN cta_x_straat_ontdubbeld c2 ON c1.scope_id = c2.scope_id
	WHERE c1.uri = '{$row['uri']}'  AND c2.uri = '{$row['andere_uri']}'  
	GROUP BY scope_id
	";
	//print $sql2;
	$result2 = $mysqli->query($sql2);

	
	while($row2 = $result2->fetch_assoc()){
		$i++;
		print "Gevonden: scopeId: {$row2['scope_id']}: URI {$row2['uri']} kan verwijderd ten gunste van: {$row['andere_uri']}" . PHP_EOL;

		$sql3 = "DELETE FROM cta_x_straat_ontdubbeld WHERE
			scope_id = {$row2['scope_id']} AND uri = '{$row2['uri']}'
		";
		//print $sql3;
		$result3 = $mysqli->query($sql3);
	}
	
}
print "${$i} uris verwijderd.";




die;
// delet query die de dubbelen weghaalt
$sql = "DELETE c
    FROM cta_x_straat c JOIN
         (SELECT c.scope_id, c.uri, MIN(id) as min_id
          FROM cta_x_straat c 
          GROUP BY scope_id, uri
          HAVING COUNT(id) > 1
         ) cc
         ON c.scope_id = cc.scope_id
          AND c.uri = cc.uri AND c.id > cc.min_id";




?>