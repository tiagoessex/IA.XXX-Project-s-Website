<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
//  RETURNS THE NUMBER OF COMPLAINTS PER ACTIVITY (FIRST LEVEL)
//    IN A GIVEN TIME INTERVAL
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
     $data_start_query = "DEN.ID_DATA >= -1";
} else {
    $data_start_query = "DEN.ID_DATA >= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_start . " AND MES =   " . $mes_start . ") ,(SELECT MIN(ID) FROM DATA_DATA)))";
}



$query = "
SELECT
  LEFT(ACT.CODIGO,INSTR(ACT.CODIGO,'.')-1) AS CODIGO,
  COUNT(*) AS COUNT_ACTIVIDADES
FROM 
  DENUNCIAS DEN
LEFT JOIN CORRESP_ACTIVIDADES CA ON (CA.CORRESP_ID_CORRESP = DEN.ID_DENUNCIA)
LEFT JOIN ACTIVIDADE ACT ON (ACT.ID_ACT = CA.ACT_ID_ACT)
WHERE
" . $data_start_query . "
AND
DEN.ID_DATA <= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_end . " AND MES =   " . $mes_end . ") ,(SELECT MAX(ID) FROM DATA_DATA)))
GROUP BY
    LEFT(ACT.CODIGO,INSTR(ACT.CODIGO,'.')-1)
ORDER BY
    CODIGO
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
        'CODIGO' => array_key_exists('CODIGO',$row)?$row['CODIGO']:"",
        'COUNT_ACTIVIDADES' => array_key_exists('COUNT_ACTIVIDADES',$row)?(int)$row['COUNT_ACTIVIDADES']:0
    );
}



header('Content-Type: application/json');

echo (json_encode($json));