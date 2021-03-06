<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
//  RETURNS THE NUMBER OF INSPECTIONS PER YEAR
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
    N.DESIGNACAO_DISTRITO AS DISTRITO,
   count(*) AS COUNT_DISTRITO
FROM 
    FISC_ENTIDADE D    
    left join DATA_DATA DD on (DD.ID = D.ID_DATA)
    LEFT JOIN NUTS N ON (N.ID = D.ID_NUTS)
    WHERE
        " . $data_start_query . "
AND
    D.ID_DATA <= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_end . " AND MES =   " . $mes_end . ") ,(SELECT MAX(ID) FROM DATA_DATA)))
AND
    N.DISTRITO = SUBSTR(D.ID_NUTS,1,2)
GROUP BY
   DISTRITO
ORDER BY 
    COUNT_DISTRITO DESC
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
        'DISTRITO' => array_key_exists('DISTRITO',$row)?$row['DISTRITO']:"",
        'COUNT_DISTRITO' => array_key_exists('COUNT_DISTRITO',$row)?(int)$row['COUNT_DISTRITO']:0
    );
}


header('Content-Type: application/json');

echo (json_encode($json));