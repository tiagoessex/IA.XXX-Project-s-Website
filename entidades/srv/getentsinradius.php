<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
//  RETURN limite ENTITIES INSIDE A CIRCULAR AREA
//    CENTER (lat, lon) AND RADIUS radius
//
//
// **********************************
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  header("HTTP/1.1 405 Method Not Allowed");
  exit();
}

$database = new Database();

$conn = $database->getConnection();
if (!$conn) die("error");


$limite = trim($_POST["limite"]);
$lat = trim($_POST["lat"]);
$lon = trim($_POST["lon"]);
$radius = trim($_POST["radius"]);


$query = "
    SELECT
      id,
      UPPER(nome) AS nome,
      latitude,
      longitude,
      nif
    FROM
    _temp_sani
    WHERE
      CalcDistance(_temp_sani.LATITUDE, _temp_sani.LONGITUDE, ". $lat . ", " . $lon . ") < " . $radius;


if ($limite) {
  $query .= " limit " .$limite;
}


try {  
  $result=$conn->query($query);  
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}


$json = array();
foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row)
 {
    $json[]= array(
      'id' => $row['id'],
      'nome' => $row['nome'],
      'latitude' => $row['latitude'],
      'longitude' => $row['longitude'],
      'nif' => $row['nif']
    );
}

$jsonstring = json_encode($json);

header('Content-Type: application/json');

echo ($jsonstring);