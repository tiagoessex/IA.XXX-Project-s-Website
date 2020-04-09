<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
// RETURN ALL ENTITIES OF A GIVEN ACTIVITY
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



$id_act = trim($_POST['id_act']);
$exact = trim($_POST['exact']);


$query = "
SELECT 
  ENTIDADE_ACTIVIDADE.CODIGO_ACTIVIDADE
FROM 
  ENTIDADE_ACTIVIDADE 
WHERE 
  ID_ACTIVIDADE = '" . $id_act . "' LIMIT 1";


try {  
  $result=$conn->query($query);  
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}

$row = $result->fetch(PDO::FETCH_ASSOC);

$codigo = $row['CODIGO_ACTIVIDADE'];



$query = "
SELECT 
  ENTIDADE_ACTIVIDADE.ID_ENTIDADE,
  ENT.NOME,
  ENT.NIF_NIPC AS NIF,
  ENT.MORADA,
  ENT.LOCALIDADE_CP AS LOCALIDADE
FROM 
  ENTIDADE_ACTIVIDADE 
LEFT JOIN ENTIDADE ENT ON (ENT.ID_ENTIDADE = ENTIDADE_ACTIVIDADE.ID_ENTIDADE)
WHERE 
CODIGO_ACTIVIDADE = '" . $codigo . "'";
if ($exact != 1) {
  $query .= " OR CODIGO_ACTIVIDADE LIKE '" . $codigo . ".%' ";
};


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
      'id' => $row['ID_ENTIDADE'],
      'nome' => $row['NOME'],
      'nif' => $row['NIF'],
      'morada' => $row['MORADA'],
      'localidade' => $row['LOCALIDADE'],
    );
}


header('Content-Type: application/json');

echo (json_encode($json));