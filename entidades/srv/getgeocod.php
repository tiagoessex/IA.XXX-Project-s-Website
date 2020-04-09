<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
//  RETURN GEOCODE DATA OF AN ENTITY
//    INCLUDING ITS VALIDATION
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

$id = trim($_POST['id_entidade']);


$query = "
select 
  ST_X(geocod.coordenadas) as latitude,
  ST_Y(geocod.coordenadas) as longitude,
  geocod.is_in_distrito,
  geocod.is_in_concelho,
  geocod.is_in_freguesia,
  geocod.is_in_local,
  geocod.is_in_cp,
  geocod.is_in_rua,
  geocod.is_manually_valid,
  geocod.is_valid
from 
  geocod
where id_entidade = " .  $id . " limit 1";



try {  
  $result=$conn->query($query);  
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}

$row = $result->fetch(PDO::FETCH_ASSOC);

$json = array();

if ($row) {
    $json['latitude'] =  $row['latitude'];
    $json['longitude'] =  $row['longitude'];
    $json['is_in_distrito'] =  $row['is_in_distrito'];
    $json['is_in_concelho'] =  $row['is_in_concelho'];
    $json['is_in_freguesia'] =  $row['is_in_freguesia'];
    $json['is_in_local'] =  $row['is_in_local'];
    $json['is_in_cp'] =  $row['is_in_cp'];
    $json['is_in_rua'] =  $row['is_in_rua'] ;
    $json['is_manually_valid'] =  $row['is_manually_valid'];
    $json['is_valid'] =  $row['is_valid'];
}


$jsonstring = json_encode($json);

header('Content-Type: application/json');

echo ($jsonstring);