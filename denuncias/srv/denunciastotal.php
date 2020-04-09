<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  header("HTTP/1.1 405 Method Not Allowed");
  exit();
}

$database = new Database();

$conn = $database->getConnection();
if (!$conn) die("error");



$query = "
SELECT  
  count(*) AS denuncias
FROM 
  denuncias";


try {  
  $result=$conn->query($query);  
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}

$row = $result->fetch(PDO::FETCH_ASSOC);

$json = array();


if ($row) {
      $json['denuncias'] =  array_key_exists('denuncias',$row)?$row['denuncias']:0;
} else {
    header('HTTP/1.1 500 Internal Server Booboo');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode(array('status' => 'error', 'code' => 1337)));
}


$query = "
SELECT  
  COUNT(*) AS denuncias_with_message
FROM 
  DENUNCIAS D
WHERE
  D.HAS_MESSAGE = 1";
try {  
  $result=$conn->query($query);  
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}
$row = $result->fetch(PDO::FETCH_ASSOC);
if ($row) {
      $json['denuncias_with_message'] =  array_key_exists('denuncias_with_message',$row)?$row['denuncias_with_message']:0;
} else {
    header('HTTP/1.1 500 Internal Server Booboo');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode(array('status' => 'error', 'code' => 1337)));
}




$query = "
SELECT  
  count(distinct id_denuncia) AS den_total_class
FROM 
  denuncias_ai";
try {  
  $result=$conn->query($query);  
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}
$row = $result->fetch(PDO::FETCH_ASSOC);
if ($row) {
      $json['den_total_class'] =  array_key_exists('den_total_class',$row)?$row['den_total_class']:0;
} else {
    header('HTTP/1.1 500 Internal Server Booboo');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode(array('status' => 'error', 'code' => 1337)));
}




$query = "
SELECT  
  count(*) AS den_total_class1
FROM 
  denuncias_ai
WHERE
  modelo = 1";
try {  
  $result=$conn->query($query);  
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}
$row = $result->fetch(PDO::FETCH_ASSOC);
if ($row) {
      $json['den_total_class1'] =  array_key_exists('den_total_class1',$row)?$row['den_total_class1']:0;
} else {
    header('HTTP/1.1 500 Internal Server Booboo');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode(array('status' => 'error', 'code' => 1337)));
}


$query = "
SELECT  
  count(DISTINCT (ID_DENUNCIA)) AS den_total_class2
FROM 
  denuncias_ai
WHERE
  modelo = 2";
try {  
  $result=$conn->query($query);  
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}
$row = $result->fetch(PDO::FETCH_ASSOC);
if ($row) {
      $json['den_total_class2'] =  array_key_exists('den_total_class2',$row)?$row['den_total_class2']:0;
} else {
    header('HTTP/1.1 500 Internal Server Booboo');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode(array('status' => 'error', 'code' => 1337)));
}



$jsonstring = json_encode($json);

header('Content-Type: application/json');

echo ($jsonstring);