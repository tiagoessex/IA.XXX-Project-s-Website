<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
//  VALIDATE A DUPLICATED ENTITY
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
		entidade_duplicados
	set 
		entidade_duplicados.valid = ?
	where
		entidade_duplicados.ID_ENTIDADE_DUPLICADA = ?
	and 
		entidade_duplicados.ID_ENTIDADE_UNIQUE = ?
";

try {
	$stmt= $conn->prepare($query);
	$stmt->execute([$isvalid, $id_duplicada, $id_unique]);
} catch (PDOException $e) {
	echo 'Connection failed: ' . $e->getMessage();
	die();
}	

echo "OK";