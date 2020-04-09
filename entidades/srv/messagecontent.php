<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
//  RETURN THE COMPLAINT'S MESSAGE
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
if (!$conn) {
    header('HTTP/1.1 500 Internal Server Booboo');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode(array('status' => 'error', 'code' => 500)));
}



$id_correspondencia = trim($_POST["id_correspondencia"]);
$den_or_rec = trim($_POST["den_or_rec"]);


if ($den_or_rec == 'D') {
	$query = "
			SELECT 
			    C.EMAIL_CONTENT AS email_content    
			FROM 
				DENUNCIAS C
			WHERE 
			   C.ID_DENUNCIA = ". $id_correspondencia . " limit 1";
} else {
	$query = "
			SELECT 
			    C.EMAIL_CONTENT AS email_content    
			FROM 
				RECLAMACOES C
			WHERE 
			   C.ID_RECLAMACAO = ". $id_correspondencia . " limit 1";
}


try {  
  $result=$conn->query($query);  
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}

$row = $result->fetch(PDO::FETCH_ASSOC);
$data = $row['email_content'];//->load();


header('Content-Type: application/text');

echo ($data);