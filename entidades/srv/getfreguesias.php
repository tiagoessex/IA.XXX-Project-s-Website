<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
//  RETURN ALL FREGUESIAS FOR A GIVEN
//    DISTRITO AND CONCELHO
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
$concelho = trim($_POST["concelho"]);


$query = "
  SELECT 
    DISTINCT(freguesia) AS nuts, 
    DESIGNACAO_FREGUESIA AS freguesia
  FROM 
    nuts
  WHERE
    DESIGNACAO_FREGUESIA IS NOT NULL
  AND
     DESIGNACAO_DISTRITO='" . $distrito . "' 
  AND
     DESIGNACAO_CONCELHO='" . $concelho . "'";


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
      'freguesia' => $row['freguesia'],
    );
}

$jsonstring = json_encode($json);

header('Content-Type: application/json');

echo ($jsonstring);