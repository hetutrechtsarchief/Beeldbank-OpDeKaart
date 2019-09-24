<?php
header("Content-type: text/json");

$page = $_GET["page"];
$wikidataID = $_GET["wikidataID"];
if (!is_numeric($page)) die("404");

$sparqlquery = 'PREFIX edm: <http://www.europeana.eu/schemas/edm/>
PREFIX dct: <http://purl.org/dc/terms/>
PREFIX dc: <http://purl.org/dc/elements/1.1/>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX sem: <http://semanticweb.cs.vu.nl/2009/11/sem/>
SELECT ?uuid, ?catalogusnummer, ?description, ?beginTimeStamp, ?endTimeStamp, ?rights WHERE {
  ?bbitem dct:spatial <http://www.wikidata.org/entity/'.$wikidataID.'> .
  ?bbitem edm:isShownBy ?img .
  ?bbitem edm:isShownAt ?rec .
  ?bbitem dc:identifier ?catalogusnummer .
  ?bbitem dc:description ?description .
  ?bbitem sem:hasBeginTimeStamp ?beginTimeStamp .
  ?bbitem sem:hasEndTimeStamp ?endTimeStamp .
  ?bbitem dc:rights ?rights .
  BIND(REPLACE(str(?bbitem), "https://hetutrechtsarchief.nl/id/", "") AS ?uuid)
} OFFSET '.(10*($page-1)).' LIMIT 10';

// echo $sparqlquery;
// die();

$url = "https://api.druid.datalegend.net/datasets/hetutrechtsarchief/beeldbank/services/beeldbank/sparql?query=" . urlencode($sparqlquery) . "";

// echo $url;
// die();

// Druid does not like url parameters, send accept header instead
$opts = [
    "http" => [
        "method" => "GET",
        "header" => "Accept: application/sparql-results+json\r\n"
        // "header" => "Accept: text/csv\r\n"
    ]
];

$context = stream_context_create($opts);

// Open the file using the HTTP headers set above
$response = file_get_contents($url, false, $context);

// echo $response;
// die();

$json = json_decode($response, true);

$items = [];
foreach ($json['results']['bindings'] as $row) { 
  $item = [];
  $item["catalogusnummer"] = $row['catalogusnummer']['value'];
  $item["guid"] = $row['uuid']['value'];
  $item["description"] = $row['description']['value'];
  $item["beginTimeStamp"] = $row['beginTimeStamp']['value'];
  $item["endTimeStamp"] = $row['endTimeStamp']['value'];
  $item["rights"] = $row['rights']['value'];
  $items[] = $item;
}

echo json_encode($items);
// var_dump($json);
// echo $json;

die();

// $csv = explode("\n", $csv);
// array_shift($csv);
// $csv = str_replace("\"","", $csv);
// foreach ($csv as $row) {
//   echo $row . "\n";
// }

?>
