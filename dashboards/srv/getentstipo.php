<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
//  RETURNS THE NUMBER OF ENTITIES PER TYPE
//    IN A GIVEN TIME INTERVAL
//
//  TYPE: AGENTE ECONÓMICO, TRIBUNAL, ...
//
// **********************************
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  header("HTTP/1.1 405 Method Not Allowed");
  exit();
}


$database = new Database();

$conn = $database->getConnection();
if (!$conn) die("error");
//echo "DB: GUT";

$ano_start = trim($_POST['ano_start']);
$mes_start = trim($_POST['mes_start']);
$ano_end = trim($_POST['ano_end']);
$mes_end = trim($_POST['mes_end']);


if (substr(MIN_DATE,0,7) == $ano_start . '-' . $mes_start) {
     $data_start_query = "ENTIDADE.ID_DATA >= -1";
} else {
    $data_start_query = "ENTIDADE.ID_DATA >= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_start . " AND MES =   " . $mes_start . ") ,(SELECT MIN(ID) FROM DATA_DATA)))";
}



$query = "
    SELECT 
        T_E.DESC_TP_ENT AS TIPO_DESC,
        COUNT(*) AS COUNT_TIPOS
    FROM
        ENTIDADE
    LEFT JOIN TIPOS_ENTIDADE T_E ON (T_E.ID_TP_ENT = ENTIDADE.TIPO_ENT_ID_TP_ENT)
    WHERE
        " . $data_start_query . "
    AND
        ENTIDADE.ID_DATA <= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_end . " AND MES =   " . $mes_end . ") ,(SELECT MAX(ID) FROM DATA_DATA)))
    GROUP BY
       T_E.DESC_TP_ENT
    ORDER BY COUNT_TIPOS DESC
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
        'TIPO_DESC' => array_key_exists('TIPO_DESC',$row)?$row['TIPO_DESC']:"",
        'COUNT_TIPOS' => array_key_exists('COUNT_TIPOS',$row)?(int)$row['COUNT_TIPOS']:0
    );
}


header('Content-Type: application/json');

echo (json_encode($json));