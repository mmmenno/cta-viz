<?php


function composeSparqlQuery(
        $street = '',
        $yearStart = 0,
        $yearEnd = 0,
        $term = '',
        $searchterms = ''
) {
    $spatialQuery = '';
    if ($street != '') {
        $spatialQuery = '?dossier dct:spatial <' . $street . '> .';
    }

    $stringQuery = '';
    if($searchterms != ""){
      $terms = explode(" ", urldecode($searchterms));
      foreach ($terms as $key => $value) {
        if(strlen($value)>3){
          $stringQuery .= "
          ?dossier dc:description ?description" . $key . " .
          ?description" . $key . " bif:contains \"'" . $value . "*'\" .\n";
        }
      }
    }

    $termQuery = '';
    if ($term != '') {
        $termQuery = '?dossier dc:subject <' . $term . '> .';
    }

    $yearQuery = '';
    if ($yearStart > 0 || $yearEnd > 0) {
        $yearStart -= 1;
        $yearEnd += 1;
        $yearQuery = '
            FILTER (
                ?jaar > "' . $yearStart .'"^^xsd:integer && ?jaar < "' . $yearEnd . '"^^xsd:integer
            )
        ';
    }

    $sparqlquery = '
PREFIX skos: <http://www.w3.org/2004/02/skos/core#>
PREFIX geo: <http://www.opengis.net/ont/geosparql#>
PREFIX dc: <http://purl.org/dc/elements/1.1/>
PREFIX dct: <http://purl.org/dc/terms/>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX hg: <http://rdf.histograph.io/>

SELECT * WHERE
{ 
  {
    SELECT ?dossier ?datum ?jaar ?description WHERE 
    {
      ' . $spatialQuery . '
      ?dossier dc:description ?description .
      ' . $termQuery . '
      ?dossier dc:date ?datum .
      BIND(IF(COALESCE(xsd:datetime(str(?datum)), "!") != "!",
      year(xsd:dateTime(str(?datum))),"1000"^^xsd:integer) AS ?jaar )
      ' . $yearQuery . '
      ' . $stringQuery . '
      #FILTER(?jaar>1500)
      #FILTER(?jaar<2970)
    }
  }
  UNION
  {
    SELECT ?dossier ?jaar ?description WHERE 
    {
      ' . $spatialQuery . '
      ?dossier dc:description ?description .
      ' . $termQuery . '
      ' . $yearQuery . '
      ' . $stringQuery . '
      FILTER NOT EXISTS{?dossier dc:date ?datum .}
      BIND("3000" AS ?jaar)
    }
  }
}
ORDER BY ASC (?jaar)
LIMIT 1000
';
    return $sparqlquery;
}

$sparqlquery = composeSparqlQuery(
        $_GET['street'] ?? '',
        $_GET['start'] ?? 0,
        $_GET['end'] ?? 0,
        $_GET['term'] ?? '',
        $_GET['searchterms'] ?? ''
    );
//echo $sparqlquery;

/*
$url = "https://api.druid.datalegend.net/datasets/saa/CTA/services/endpoint/sparql?query=" . urlencode($sparqlquery) . "";

$querylink = "https://druid.datalegend.net/saa/CTA/sparql/endpoint#query=" . urlencode($sparqlquery) . "&endpoint=https%3A%2F%2Fdruid.datalegend.net%2F_api%2Fdatasets%2FAdamNet%2Fall%2Fservices%2Fendpoint%2Fsparql&requestMethod=POST&outputFormat=table";
*/

$url = "https://api.data.netwerkdigitaalerfgoed.nl/datasets/stadsarchiefamsterdam/cta/services/cta/sparql?query=" . urlencode($sparqlquery) . "";

$querylink = "https://api.data.netwerkdigitaalerfgoed.nl/datasets/stadsarchiefamsterdam/cta/services/cta#query=" . urlencode($sparqlquery) . "&endpoint=https%3A%2F%2Fdruid.datalegend.net%2F_api%2Fdatasets%2FAdamNet%2Fall%2Fservices%2Fendpoint%2Fsparql&requestMethod=POST&outputFormat=table";


// Druid does not like url parameters, send accept header instead
$opts = [
    "http" => [
        "method" => "GET",
        "header" => "Accept: application/sparql-results+json\r\n"
    ]
];

$context = stream_context_create($opts);

// Open the file using the HTTP headers set above
$json = file_get_contents($url, false, $context);

$data = json_decode($json,true);

$i = 0;

echo "<h2>" . count($data['results']['bindings']) . " resultaten</h2>";
echo '<div class="row">';

foreach ($data['results']['bindings'] as $row) {
    $description = $row['description']['value'];
    if (! preg_match("/\.$/", $description)) {
        $description .= ".";
    }
    $i++;
    if ($i % 2 != 0 && $i > 1) {
        echo "</div>\n";
        echo '<div class="row">';
    }
    ?>
    <div>
        <a target="_blank" href="<?= $row['dossier']['value'] ?>">
            <?= str_replace('https://archief.amsterdam/archief/10057/', '',
                $row['dossier']['value']) ?>
        </a>
        <?= $description ?>
        <? if ($row['jaar']['value'] < 2500) { ?>
            <strong><?= $row['jaar']['value'] ?></strong>
        <? } ?>
    </div>

    <?php
}

if ($i % 2 != 0) {
    echo '<div class="col-md-6"></div></div>';
} else {
    echo '</div>';
}
?>
