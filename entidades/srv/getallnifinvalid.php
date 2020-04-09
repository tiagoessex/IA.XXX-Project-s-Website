<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
//  RETURN ENTITIES WITH INVALID NIFs
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
              eni.id_entidade as id,
              ent.nome as nome,
              ent.NIF_NIPC as nif,
              p.DESC_PAIS as pais
          from 
            entidade_nif_invalid eni
          left JOIN entidade ent on (eni.id_entidade = ent.id_entidade)
          left join paises p on (p.id_pais = ent.pais_id_pais)
          ";


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
        'pais' => $row['pais'],
	     );
    }

	$jsonstring = json_encode($json);
	header('Content-Type: application/json');
	echo ($jsonstring);
