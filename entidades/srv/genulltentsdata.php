<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  header("HTTP/1.1 405 Method Not Allowed");
  exit();
}

  $last_id = trim($_POST["last_id"]);
  $block_size = trim($_POST["block_size"]);

	$database = new Database();
	$conn = $database->getConnection();
	if (!$conn) die("error");

	$query = "
          select 
              ent.id_entidade as id,
              ent.nome as nome,
              ent.NIF_NIPC as nif,
              euu.data_primeira as data_primeira,
              euu.data_recente as date_ultima -- ,
              -- euu.outra as in_ops
          from 
            entidade AS ent
          left JOIN entidade_ultimo_update AS euu on (euu.id_entidade = ent.id_entidade)
          where
            ent.id_entidade > " . $last_id . " 
          and
            euu.outra = 'F'
          LIMIT " . $block_size . "
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
        'data_primeira' => $row['data_primeira'],
        'date_ultima' => $row['date_ultima'],
     //   'in_ops' => $row['in_ops']
       );
    }

	$jsonstring = json_encode($json);
	header('Content-Type: application/json');
	echo ($jsonstring);