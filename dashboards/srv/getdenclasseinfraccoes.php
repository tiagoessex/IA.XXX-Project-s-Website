<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
//  RETURNS THE NUMBER OF COMPLAINTS PER TYPE
//    IN A GIVEN TIME INTERVAL
//
//  TYPE: crime, contraordenaccao, ...
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
    NJ.DESC_NAT_JUR as TIPO,
    count(*) AS COUNT_TIPO
  FROM 
    DENUNCIAS_NATUREZA_JURIDICA DNJ    
  left join NATUREZA_JURIDICA NJ on (NJ.ID_NAT_JUR = DNJ.ID_NATUREZA_JURIDICA)
  LEFT JOIN DENUNCIAS D ON (D.ID_DENUNCIA = DNJ.ID_DENUNCIA)
    WHERE
        " . $data_start_query . "
    AND
        D.ID_DATA <= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_end . " AND MES =   " . $mes_end . ") ,(SELECT MAX(ID) FROM DATA_DATA)))
  GROUP BY
      NJ.DESC_NAT_JUR
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