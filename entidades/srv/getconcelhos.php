<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
//  RETURN ALL CONCELHOS FOR A GIVEN
//    DISTRICT
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

$distrito = trim($_POST["distrito"]);



$query = "
  SELECT 
    DISTINCT(concelho) AS nuts, 
    DESIGNACAO_CONCELHO AS concelho
  FROM 
    nuts
  WHERE
    DESIGNACAO_CONCELHO IS NOT NULL
  AND
     DESIGNACAO_DISTRITO='" . $distrito . "'";


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
      'concelho' => $row['concelho'],
    );
}

$jsonstring = json_encode($json);

header('Content-Type: application/json');

echo ($jsonstring);