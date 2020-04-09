<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
//  RETURNS THE TOTAL NUMBER OF ENTS, COMPLAINTS, ...
//    IN A GIVEN TIME INTERVAL
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
  SUM(DD.DENUNCIAS) AS denuncias,
  SUM(DD.FISCALIZACOES) AS fiscalizacoes,
  SUM(DD.INFORMACOES) AS informacoes,
  SUM(DD.PROCESSOS) AS processos,
  SUM(DD.RECLAMACOES) AS reclamacoes -- ,
--  SUM(DD.COIMAS) AS coimas,
--  SUM(DD.COIMAS_VALOR) AS coimas_valor,
--  SUM(DD.FUNCIONARIOS) AS funcionarios,
--  SUM(DD.BRIGADAS) AS brigadas,
--  SUM(DD.VEICULOS) AS veiculos,
FROM 
  data_dados DD
LEFT JOIN DATA_NUTS DN ON (DN.ID = DD.ID_NUTS)
WHERE
--  ID_NUTS != '000000'
-- AND
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
      $json['denuncias'] =  array_key_exists('denuncias',$row)?$row['denuncias']:0;
      $json['fiscalizacoes'] =  array_key_exists('fiscalizacoes',$row)?$row['fiscalizacoes']:0;
      $json['informacoes'] =  array_key_exists('informacoes',$row)?$row['informacoes']:0;
      $json['processos'] =  array_key_exists('processos',$row)?$row['processos']:0;
      $json['reclamacoes'] =  array_key_exists('reclamacoes',$row)?$row['reclamacoes']:0;
} else {
    header('HTTP/1.1 500 Internal Server Booboo');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode(array('status' => 'error', 'code' => 1337)));
}


$query = "
SELECT  
  viaturas,
  funcionarios,
  brigadas
FROM 
  data_single
";

try {  
  $result=$conn->query($query);  
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}

$row = $result->fetch(PDO::FETCH_ASSOC);

if ($row) {
    $json['viaturas'] =  array_key_exists('viaturas',$row)?$row['viaturas']:0;
    $json['funcionarios'] =  array_key_exists('funcionarios',$row)?$row['funcionarios']:0;
    $json['brigadas'] =  array_key_exists('brigadas',$row)?$row['brigadas']:0;
} else {
    header('HTTP/1.1 500 Internal Server Booboo');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode(array('status' => 'error', 'code' => 1337)));
}



header('Content-Type: application/json');

echo (json_encode($json));