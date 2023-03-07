<?php


include("functions.php");



$sparqlquery = '
PREFIX bif: <http://www.openlinksw.com/schemas/bif#>
PREFIX skos: <http://www.w3.org/2004/02/skos/core#>
PREFIX geo: <http://www.opengis.net/ont/geosparql#>
PREFIX dc: <http://purl.org/dc/elements/1.1/>
PREFIX dct: <http://purl.org/dc/terms/>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX hg: <http://rdf.histograph.io/>
SELECT DISTINCT ?street ?label ?wkt (COUNT(?dossier) AS ?count) WHERE {
	?street a hg:Street ;
		geo:hasGeometry/geo:asWKT ?wkt ;
		skos:prefLabel ?label .
	?dossier dct:spatial ?street .
	?dossier dc:date ?datum .
	BIND(year(?datum) AS ?year) .
	FILTER(?year >= ' . $_GET['start'] . ') .
	FILTER(?year <= ' . $_GET['end'] . ') .
	';

if(isset($_GET['term']) && $_GET['term'] != ""){
	$sparqlquery .= '
	?dossier dc:subject <' . $_GET['term'] . '> .
	';
}

if(isset($_GET['searchterms']) && $_GET['searchterms'] != ""){
	$terms = explode(" ", urldecode($_GET['searchterms']));
	foreach ($terms as $key => $value) {
		if(strlen($value)>3){
			$sparqlquery .= "
			?dossier dc:description ?description" . $key . " .
			# OLD: ?description" . $key . " bif:contains \"'" . $value . "*'\" 
			FILTER(REGEX(?description" . $key . ",\"" . $value . "\")) .\n";
		}
	}
}

$sparqlquery .= '
	bind (bif:st_geomfromtext(?wkt) as ?streetgeom) .
	bind (bif:st_geomfromtext("POLYGON((' . $_GET['bbox'] . '))") as ?bbox) .
	FILTER (bif:st_intersects(?streetgeom, ?bbox))
} 
GROUP BY ?street ?wkt ?label
ORDER BY DESC (?count)
LIMIT 50
';



$url = "https://api.data.netwerkdigitaalerfgoed.nl/datasets/stadsarchiefamsterdam/cta/services/cta/sparql?query=" . urlencode($sparqlquery) . "";

//echo $url;

$querylink = "https://api.data.netwerkdigitaalerfgoed.nl/datasets/stadsarchiefamsterdam/cta/services/cta#query=" . urlencode($sparqlquery) . "&endpoint=https%3A%2F%2Fdruid.datalegend.net%2F_api%2Fdatasets%2FAdamNet%2Fall%2Fservices%2Fendpoint%2Fsparql&requestMethod=POST&outputFormat=table";

//echo "\n\n" . $sparqlquery . "\n\n";

$endpoint = "https://api.data.netwerkdigitaalerfgoed.nl/datasets/stadsarchiefamsterdam/cta/services/cta/sparql";
$sparql = $sparqlquery;

$json = getSparqlResults($endpoint,$sparql);

$data = json_decode($json,true);


$fc = array("type"=>"FeatureCollection","query" => $querylink, "features"=>array());


foreach ($data['results']['bindings'] as $row) {
	$line = array("type"=>"Feature");
	$props = array(
		"count" => $row['count']['value'],
		"name" => $row['label']['value'],
		"street" => $row['street']['value']
	);
	$line['geometry'] = wkt2geojson($row['wkt']['value']);
	$line['properties'] = $props;
	$fc['features'][] = $line;
}


$json = json_encode($fc);

//file_put_contents('buildings.geojson', $json);
header('Content-Type: application/json');
die($json);


function wkt2geojson($wkt){
	$coordsstart = strpos($wkt,"(");
	$type = trim(substr($wkt,0,$coordsstart));
	$coordstring = substr($wkt, $coordsstart);

	switch ($type) {
	    case "LINESTRING":
	    	$geom = array("type"=>"LineString","coordinates"=>array());
			$coordstring = str_replace(array("(",")"), "", $coordstring);
	    	$pairs = explode(",", $coordstring);
	    	foreach ($pairs as $k => $v) {
	    		$coords = explode(" ", trim($v));
	    		$geom['coordinates'][] = array((double)$coords[0],(double)$coords[1]);
	    	}
	    	return $geom;
	    	break;
	    case "POLYGON":
	    	$geom = array("type"=>"Polygon","coordinates"=>array());
			preg_match_all("/\([0-9. ,]+\)/",$coordstring,$matches);
	    	//print_r($matches);
	    	foreach ($matches[0] as $linestring) {
	    		$linestring = str_replace(array("(",")"), "", $linestring);
		    	$pairs = explode(",", $linestring);
		    	$line = array();
		    	foreach ($pairs as $k => $v) {
		    		$coords = explode(" ", trim($v));
		    		$line[] = array((double)$coords[0],(double)$coords[1]);
		    	}
		    	$geom['coordinates'][] = $line;
	    	}
	    	return $geom;
	    	break;
	    case "MULTILINESTRING":
	    	$geom = array("type"=>"MultiLineString","coordinates"=>array());
	    	preg_match_all("/\([0-9. ,]+\)/",$coordstring,$matches);
	    	//print_r($matches);
	    	foreach ($matches[0] as $linestring) {
	    		$linestring = str_replace(array("(",")"), "", $linestring);
		    	$pairs = explode(",", $linestring);
		    	$line = array();
		    	foreach ($pairs as $k => $v) {
		    		$coords = explode(" ", trim($v));
		    		$line[] = array((double)$coords[0],(double)$coords[1]);
		    	}
		    	$geom['coordinates'][] = $line;
	    	}
	    	return $geom;
	    	break;
	    case "POINT":
			$coordstring = str_replace(array("(",")"), "", $coordstring);
	    	$coords = explode(" ", $coordstring);
	    	//print_r($coords);
	    	$geom = array("type"=>"Point","coordinates"=>array((double)$coords[0],(double)$coords[1]));
	    	return $geom;
	        break;
	}
}
