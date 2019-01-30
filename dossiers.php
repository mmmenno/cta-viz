<?php


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
    SELECT ?dossier ?datum ?jaar ?description ?subject ?label ?scope WHERE 
    {
      ?dossier dct:spatial <' . $_GET['street'] . '> .
      ?dossier dc:description ?description .
      ?dossier dc:identifier ?scope .
      OPTIONAL{ 
        ?dossier dc:subject ?subject . 
        ?subject rdfs:label ?label .
      }
      ?dossier dc:date ?datum .
      BIND(IF(COALESCE(xsd:datetime(str(?datum)), "!") != "!",
      year(xsd:dateTime(str(?datum))),"1000"^^xsd:integer) AS ?jaar )
      FILTER(?jaar>1500)
      FILTER(?jaar<2970)
    }
  }
  UNION
  {
    SELECT ?dossier ?jaar ?description ?subject ?label ?scope WHERE 
    {
      ?dossier dct:spatial <' . $_GET['street'] . '> .
      ?dossier dc:description ?description .
      ?dossier dc:identifier ?scope .
      OPTIONAL{ 
        ?dossier dc:subject ?subject . 
        ?subject rdfs:label ?label .
      }
      FILTER NOT EXISTS{?dossier dc:date ?datum .}
      BIND("3000" AS ?jaar)
    }
  }
}
ORDER BY ASC (?jaar)
LIMIT 1000
';

//echo $sparqlquery;


$url = "https://api.data.adamlink.nl/datasets/saa/CTA/services/endpoint/sparql?default-graph-uri=&query=" . urlencode($sparqlquery) . "&format=application%2Fsparql-results%2Bjson&timeout=120000&debug=on";

$querylink = "https://api.data.adamlink.nl/datasets/saa/CTA/services/endpoint#query=" . urlencode($sparqlquery) . "&contentTypeConstruct=text%2Fturtle&contentTypeSelect=application%2Fsparql-results%2Bjson&endpoint=https%3A%2F%2Fdata.adamlink.nl%2F_api%2Fdatasets%2Fmenno%2Falles%2Fservices%2Falles%2Fsparql&requestMethod=POST&tabTitle=Query&headers=%7B%7D&outputFormat=table";


$json = file_get_contents($url);

$data = json_decode($json, true);

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
        <a href="<?= $row['dossier']['value'] ?>">
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

<a target="_blank" style="font-size:36px; margin: 40px 0 40px 0; display: block;" href="<?= $querylink ?>">SPARQL it
    yourself</a>


