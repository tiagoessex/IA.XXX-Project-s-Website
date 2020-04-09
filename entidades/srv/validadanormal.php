<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
//  VALIDATE AN ABNORMAL ENTITY
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

$isvalid = trim($_POST["isvalid"]);
$id_duplicada = trim($_POST["id_duplicada"]);
$id_unique = trim($_POST["id_unique"]);

$query = "
	update 
		entidade_anomalias
	set 
		entidade_anomalias.valid = ?
	where
		entidade_anomalias.ID_ENTIDADE_1 = ?
	and 
		entidade_anomalias.ID_ENTIDADE_2 = ?
";

try {
	$stmt= $conn->prepare($query);
	$stmt->execute([$isvalid, $id_duplicada, $id_unique]);
} catch (PDOException $e) {
	echo 'Connection failed: ' . $e->getMessage();
	die();
}	

echo "OK";