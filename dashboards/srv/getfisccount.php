<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
//  RETURNS THE NUMBER OF INSPECTIONS TOTAL
//    IN A GIVEN TIME INTERVAL
//
//  total, number of infractions, number of ents. with insp., ...
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
  count(*) as fiscalizacoes_total
FROM 
  fiscalizacoes DD
-- LEFT JOIN DATA_NUTS DN ON (DN.ID = DD.ID_NUTS)
WHERE
" . $data_start_query . "
AND
DD.ID_DATA <= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_end . " AND MES =   " . $mes_end . ") ,(SELECT MAX(ID) FROM DATA_DATA)))
";

//die($query);

try {  
  $result=$conn->query($query);
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}


$row = $result->fetch(PDO::FETCH_ASSOC);

$json = array();


if ($row) {
      $json['fiscalizacoes_total'] =  array_key_exists('fiscalizacoes_total',$row)?$row['fiscalizacoes_total']:0;;
} else {
    header('HTTP/1.1 500 Internal Server Booboo');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode(array('status' => 'error', 'code' => 1337)));
}


$query = "
SELECT  
  count(DISTINCT DD.ENTIDADE_ID_ENTIDADE) as fiscalizacoes_entidades
FROM 
  fisc_entidade DD
-- LEFT JOIN DATA_NUTS DN ON (DN.ID = DD.ID_NUTS)
WHERE
" . $data_start_query . "
AND
DD.ID_DATA <= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_end . " AND MES =   " . $mes_end . ") ,(SELECT MAX(ID) FROM DATA_DATA)))
";


$result=$conn->query($query);
$row = $result->fetch(PDO::FETCH_ASSOC);

if ($row) {
      $json['fiscalizacoes_entidades'] =  array_key_exists('fiscalizacoes_entidades',$row)?$row['fiscalizacoes_entidades']:0;;
} else {
    header('HTTP/1.1 500 Internal Server Booboo');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode(array('status' => 'error', 'code' => 1337)));
}



$query = "
SELECT  
  sum(DD.NUM_INFRAC_CO) as fiscalizacoes_num_infraccoes
FROM 
  fisc_entidade DD
WHERE
" . $data_start_query . "
AND
DD.ID_DATA <= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_end . " AND MES =   " . $mes_end . ") ,(SELECT MAX(ID) FROM DATA_DATA)))
";


$result=$conn->query($query);
$row = $result->fetch(PDO::FETCH_ASSOC);

if ($row) {
      $json['fiscalizacoes_num_infraccoes'] =  array_key_exists('fiscalizacoes_num_infraccoes',$row)?$row['fiscalizacoes_num_infraccoes']:0;;
} else {
    header('HTTP/1.1 500 Internal Server Booboo');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode(array('status' => 'error', 'code' => 1337)));
}





header('Content-Type: application/json');

echo (json_encode($json));