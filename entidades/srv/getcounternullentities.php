<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
//  RETURN THE NUMBER OF ENTITIES IN THE DB
//    THAT HAVEN'T BEING USED ANYWHERE
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
                count(*) as COUNTER_TOTAL
            from 
              entidade_ultimo_update
            ";

    $json = array();

try {  
  $result=$conn->query($query);  
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}

    $row = $result->fetch(PDO::FETCH_ASSOC);
    $json['COUNTER_TOTAL'] = $row['COUNTER_TOTAL'];

	$query = "
            select 
                count(*) as COUNTER_NULL
            from 
              entidade_ultimo_update
            where
            	outra = 'F'
            ";
            
try {  
  $result=$conn->query($query);  
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}

    $row = $result->fetch(PDO::FETCH_ASSOC);	
	$json['COUNTER_NULL'] = $row['COUNTER_NULL'];
	

	$jsonstring = json_encode($json);
	header('Content-Type: application/json');
	echo ($jsonstring);