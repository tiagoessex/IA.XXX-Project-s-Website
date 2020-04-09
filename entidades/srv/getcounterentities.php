<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
//  RETURN THE NUMBER OF ENTITIES IN THE DB
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

$query = "
            select 
                count(*) as COUNTER
            from 
              entidade
            ";

try {  
  $result=$conn->query($query);  
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}

$row = $result->fetch(PDO::FETCH_ASSOC);

$jsonstring = json_encode($row);
header('Content-Type: application/json');
echo ($jsonstring);