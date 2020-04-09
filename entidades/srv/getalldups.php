<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
//  RETURN ALL DUPLICATED ENTITIES
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
              ent1.id_entidade as id_duplicada,
              ent1.nome as nome_duplicada,
              ent1.morada as morada_duplicada,
              ent1.NIF_NIPC as nif_duplicada,
              ent1.localidade_cp as localidade_duplicada,
              ent2.id_entidade as id_unique,
              ent2.nome as nome_unique,
              ent2.morada as morada_unique,
              ent2.NIF_NIPC as nif_unique,
              ent2.localidade_cp as localidade_unique
          from 
            entidade_duplicados AS dups
          left JOIN entidade AS ent1 on (dups.ID_ENTIDADE_DUPLICADA = ent1.id_entidade)
          left join entidade AS ent2 on (dups.ID_ENTIDADE_UNIQUE = ent2.id_entidade)
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
	      'id' => $row['id_duplicada'],
	      'nome' => $row['nome_duplicada'],
	      'morada' => $row['morada_duplicada'],
	      'localidade' => $row['localidade_duplicada'],
	      'nif' => $row['nif_duplicada'],
	     );

	    $json[]= array (
	    	'id' => $row['id_unique'],
	      	'nome' => $row['nome_unique'],
	      	'morada' => $row['morada_unique'],
	      	'localidade' => $row['localidade_unique'],
	      	'nif' => $row['nif_unique'],
    	);
    }

	$jsonstring = json_encode($json);
	header('Content-Type: application/json');
	echo ($jsonstring);