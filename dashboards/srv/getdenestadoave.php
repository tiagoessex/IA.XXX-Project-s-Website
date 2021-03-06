<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
//  RETURNS THE NUMBER OF COMPLAINTS PER STATE
//    IN A GIVEN TIME INTERVAL
//
//  STATE: Por averiguar, Em averiguação, ...
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
     $data_start_query = "D.ID_DATA >= -1";
} else {
    $data_start_query = "D.ID_DATA >= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_start . " AND MES =   " . $mes_start . ") ,(SELECT MIN(ID) FROM DATA_DATA)))";
}


$query = "
SELECT
  EA.DESIGNACAO AS DESIGNACAO,
  COUNT(*) AS COUNT_ESTADOS
FROM 
  DENUNCIAS D
LEFT JOIN ESTADO_AVERIGUACAO EA ON (EA.ID_EST_AVG = D.ID_ESTADO_AVERIGUACAO)
WHERE
" . $data_start_query . "
AND
D.ID_DATA <= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_end . " AND MES =   " . $mes_end . ") ,(SELECT MAX(ID) FROM DATA_DATA)))
GROUP BY
    EA.DESIGNACAO
ORDER BY
    COUNT_ESTADOS
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
        'DESIGNACAO' => array_key_exists('DESIGNACAO',$row)?$row['DESIGNACAO']:"",
        'COUNT_ESTADOS' => array_key_exists('COUNT_ESTADOS',$row)?(int)$row['COUNT_ESTADOS']:0
    );
}



header('Content-Type: application/json');

echo (json_encode($json));