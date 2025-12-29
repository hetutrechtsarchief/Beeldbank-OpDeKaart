<?php
header("Content-type: text/json");

$page = $_GET["page"];
$wikidataID = $_GET["wikidataID"];
if (!is_numeric($page)) die("404");
if (!preg_match('/^Q[0-9]+$/', $wikidataID)) die("404");

$json_path = __DIR__ . "/local-data/" . $wikidataID . ".json";
if (!file_exists($json_path)) {
  echo json_encode([]);
  die();
}

$response = file_get_contents($json_path);
$json = json_decode($response, true);
if (!isset($json["results"]["bindings"])) {
  echo json_encode([]);
  die();
}

$page = max(1, intval($page));
$offset = 10 * ($page - 1);
$bindings = array_slice($json["results"]["bindings"], $offset, 10);

$items = [];
foreach ($bindings as $row) { 
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
