<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
// 
//  devolve uma lista de n entidades com nome
//  like 's%' não ordenado
//
//  used in duplication detection
//
// **********************************
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  header("HTTP/1.1 405 Method Not Allowed");
  exit();
}


$database = new Database();

$conn = $database->getConnection();
if (!$conn) die("error");


$name_so_far = "A";
if(isset($_POST['name_so_far']) && trim($_POST['name_so_far']) !== "") {
    $name_so_far = trim($_POST['name_so_far']);
}

$n = 10;
if(isset($_POST['number_of_entities']) && trim($_POST['number_of_entities']) !== "") {
    $number_of_entities = trim($_POST['number_of_entities']);
}

$query = "
select 
  entidade.id_entidade as id,
  entidade.nome as nome, 
  entidade.localidade_cp as localidade,
  entidade.NIF_NIPC as nif 
from 
  entidade
where entidade.nome like '" . $name_so_far ."%' limit " . $number_of_entities;


try {  
  $result=$conn->query($query);  
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}


$row = $result->fetch(PDO::FETCH_ASSOC);

$json = array();
foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row)
 {
    $json[]= array(
      'id' => $row['id'],
      'nome' => $row['nome'],
      'localidade' => $row['localidade'],
      'nif' => $row['nif'],
    );
}

$jsonstring = json_encode($json);

header('Content-Type: application/json');

echo ($jsonstring);