<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
// DELETE AN ENTIRE ROW IN DENUNCIAS_AI
//  AND SET ALL VALID TO 0
//
//
//  called by: denunciasanalisar.php
//
// **********************************
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  header("HTTP/1.1 405 Method Not Allowed");
  exit();
}

$database = new Database();

$conn = $database->getConnection();
if (!$conn) die("error");



$model = $_POST['model'];
$id_denuncia = trim($_POST['id_denuncia']);




// *************************************
// DELETE ROW
// *************************************
$query = '
DELETE FROM denuncias_ai
WHERE 
    ID_DENUNCIA = '.$id_denuncia.'
AND 
    MODELO = '.$model;



try {
    $conn->beginTransaction();
    $conn->exec($query);
    $conn->commit();
} catch(PDOException $e) {
    $conn->rollBack();
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());    
}