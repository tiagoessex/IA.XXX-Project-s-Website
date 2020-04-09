<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
//  RETURNS THE NUMBER OF ENTITIES
//    IN A GIVEN TIME INTERVAL
//
//  with cae, with complaints, ...
//
// **********************************
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  header("HTTP/1.1 405 Method Not Allowed");
  exit();
}

$database = new Database();

$conn = $database->getConnection();
if (!$conn) die("error");


$ano_start = trim($_POST['ano_start']);
$mes_start = trim($_POST['mes_start']);
$ano_end = trim($_POST['ano_end']);
$mes_end = trim($_POST['mes_end']);


if (substr(MIN_DATE,0,7) == $ano_start . '-' . $mes_start) {
     $data_start_query = "DD.ID_DATA >= -1";
} else {
    $data_start_query = "DD.ID_DATA >= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_start . " AND MES =   " . $mes_start . ") ,(SELECT MIN(ID) FROM DATA_DATA)))";
}



$query = "
SELECT  
  SUM(DD.ENTIDADES) AS entidades,
  SUM(DD.entidades_cae) AS ent_cae,
  SUM(DD.entidades_denuncias) AS ent_den,
  SUM(DD.entidades_actividade) AS ent_act,
  SUM(DD.entidades_fiscalizacoes) AS ent_fisc,
  SUM(DD.entidades_processos) AS ent_proc
FROM 
  data_dados DD
LEFT JOIN DATA_NUTS DN ON (DN.ID = DD.ID_NUTS)
WHERE
" . $data_start_query . "
AND
DD.ID_DATA <= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_end . " AND MES =   " . $mes_end . ") ,(SELECT MAX(ID) FROM DATA_DATA)))
";



try {  
  $result=$conn->query($query);
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}

$row = $result->fetch(PDO::FETCH_ASSOC);


$json = array();


if ($row) {
      $json['entidades'] =  array_key_exists('entidades',$row)?$row['entidades']:0;
      $json['ent_cae'] =  array_key_exists('ent_cae',$row)?$row['ent_cae']:0;
      $json['ent_den'] =  array_key_exists('ent_den',$row)?$row['ent_den']:0;
      $json['ent_act'] =  array_key_exists('ent_act',$row)?$row['ent_act']:0;
      $json['ent_fisc'] =  array_key_exists('ent_fisc',$row)?$row['ent_fisc']:0;
      $json['ent_proc'] =  array_key_exists('ent_proc',$row)?$row['ent_proc']:0;
} else {
    header('HTTP/1.1 500 Internal Server Booboo');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode(array('status' => 'error', 'code' => 1337)));
}



header('Content-Type: application/json');

echo (json_encode($json));