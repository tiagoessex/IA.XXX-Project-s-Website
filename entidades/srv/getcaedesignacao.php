<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
//  GIVEN A CAE CODE RETURN ITS DESIGNATION
//
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

$cae_cod = trim($_POST['cae_cod']);


$query = "
    SELECT 
      DESC_CAE 
    FROM 
      CAE_REV 
    where 
      CAE = '" . $cae_cod . "'  
    limit 1";



try {  
  $result=$conn->query($query);  
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}

$row = $result->fetch(PDO::FETCH_ASSOC);



$json = array();

if ($row) {
    $json['cae_designacao'] =  $row['DESC_CAE'];
}


$jsonstring = json_encode($json);

header('Content-Type: application/json');

echo ($jsonstring);