<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
//  RETURNS THE NUMBER OF INSPECTIONS PER TARGET TYPE
//    IN A GIVEN TIME INTERVAL
//
//  TARGET TYPE: Produtor Primário, Importador/Distribuidor, ...
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
     $data_start_query = "D.ID_DATA >= -1";
} else {
    $data_start_query = "D.ID_DATA >= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_start . " AND MES =   " . $mes_start . ") ,(SELECT MIN(ID) FROM DATA_DATA)))";
}



$query = "
  SELECT
  TA.DESIGNACAO AS TIPO,
   count(*) AS COUNT_TIPO
  FROM 
    FISC_ENTIDADE D   
  LEFT JOIN TIPOS_ALVOS TA ON (TA.ID_TIPO_ALVO = D.TIPO_ALVO_ID_TIPO_ALVO)
  left join DATA_DATA DD on (DD.ID = D.ID_DATA)
    WHERE
        " . $data_start_query . "
    AND
        D.ID_DATA <= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_end . " AND MES =   " . $mes_end . ") ,(SELECT MAX(ID) FROM DATA_DATA)))
  GROUP BY
   TA.DESIGNACAO
  ORDER BY 
    COUNT_TIPO DESC
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
        'TIPO' => array_key_exists('TIPO',$row)?$row['TIPO']:"",
        'COUNT_TIPO' => array_key_exists('COUNT_TIPO',$row)?(int)$row['COUNT_TIPO']:0
    );
}


header('Content-Type: application/json');

echo (json_encode($json));