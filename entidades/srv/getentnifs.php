<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
//  RETURN ALL ENTITIES WITH A SPECIFIC NIFs
//
//  used to detect duplicated entities
//
// **********************************
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  header("HTTP/1.1 405 Method Not Allowed");
  exit();
}

$database = new Database();

$conn = $database->getConnection();
if (!$conn) die("error");

$nif = trim($_POST["nif"]);


$query = "
  SELECT 
    ID_ENTIDADE as id,
    nome as nome,
    NIF_NIPC as nif,
    morada as morada,
    IS_PAI as is_pai,
    TIPO_ENT_ID_TP_ENT as type 
  FROM 
    ENTIDADE
  WHERE
     NIF_NIPC='" . $nif . "'";


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
      'id' => $row['id'],
      'nome' => $row['nome'],
      'nif' => $row['nif'],
      'is_pai' => $row['is_pai'],
      'type' => $row['type'],
    );
}

$jsonstring = json_encode($json);

header('Content-Type: application/json');

echo ($jsonstring);