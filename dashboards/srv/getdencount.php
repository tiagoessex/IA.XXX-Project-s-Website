<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
//  RETURNS THE NUMBER OF COMPLAINTS TOTAL
//    IN A GIVEN TIME INTERVAL
//
//  total, cumpridas, pendentes, ...
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
  SUM(DD.denuncias) AS denuncias_total,
  SUM(DD.entidades_denuncias) AS denuncias_entidades,
  SUM(DD.denuncias_cumpridas) AS denuncias_cumpridas,
  SUM(DD.denuncias_pendentes) AS denuncias_pendentes,
  SUM(DD.denuncias_infraccoes) AS denuncias_infraccoes
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
      $json['denuncias_total'] =  array_key_exists('denuncias_total',$row)?$row['denuncias_total']:0;
      $json['denuncias_entidades'] =  array_key_exists('denuncias_entidades',$row)?$row['denuncias_entidades']:0;
      $json['denuncias_cumpridas'] =  array_key_exists('denuncias_cumpridas',$row)?$row['denuncias_cumpridas']:0;
      $json['denuncias_pendentes'] =  array_key_exists('denuncias_pendentes',$row)?$row['denuncias_pendentes']:0;
      $json['denuncias_infraccoes'] =  array_key_exists('denuncias_infraccoes',$row)?$row['denuncias_infraccoes']:0;
} else {
    header('HTTP/1.1 500 Internal Server Booboo');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode(array('status' => 'error', 'code' => 1337)));
}



header('Content-Type: application/json');

echo (json_encode($json));