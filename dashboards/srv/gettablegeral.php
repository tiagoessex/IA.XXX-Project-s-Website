<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
//  RETURNS AN ARRAY OF ARRAYS (TABLE) WITH
//    THE NUMBER OF ENTS, COMPLAINS, ..., IN A GIVEN AREA
//    PER AREA, POPULATION, ENTS, ... (densidade)
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
     $data_start_query = "DD.ID_DATA >= -1";
} else {
    $data_start_query = "DD.ID_DATA >= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_start . " AND MES =   " . $mes_start . ") ,(SELECT MIN(ID) FROM DATA_DATA)))";
}



$query = "
SELECT 
  DD.ID_NUTS AS id,
--  GetPath(DD.ID_NUTS,'T') AS nome, -- TOO SLOW
  (select 
     CONCAT(CONCAT(CONCAT(CONCAT(COALESCE(NUTS.DESIGNACAO_DISTRITO,''),'/'),COALESCE(NUTS.DESIGNACAO_CONCELHO,'')),'/'),COALESCE(NUTS.DESIGNACAO_FREGUESIA,''))
    FROM NUTS
    WHERE NUTS.ID = DD.ID_NUTS
    LIMIT 1
  ) as nome,
  SUM(DD.ENTIDADES) AS entidades,
  SUM(DD.DENUNCIAS) AS denuncias,
  SUM(DD.FISCALIZACOES) AS fiscalizacoes,
  SUM(DD.INFORMACOES) AS informacoes,
  SUM(DD.PROCESSOS) AS processos,
  SUM(DD.RECLAMACOES) AS reclamacoes,
  DN.POPULACAO AS populacao,
  DN.AREA AS area
FROM 
  data_dados DD
LEFT JOIN DATA_NUTS DN ON (DN.ID = DD.ID_NUTS)
WHERE
--   ID_NUTS != '000000'
--  AND
" . $data_start_query . "
AND
DD.ID_DATA <= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_end . " AND MES =   " . $mes_end . ") ,(SELECT MAX(ID) FROM DATA_DATA)))
GROUP BY 
  DD.ID_NUTS
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
    $nome = array_key_exists('nome',$row)?$row['nome']:"";
    if ($row['id'] == '000000') {
      $nome = "NÃO ESPECIFICADA";
    }
    $json[] = array(
        'id' => array_key_exists('id',$row)?$row['id']:"",
        'nome' => $nome,
        'entidades' => array_key_exists('entidades',$row)?(int)$row['entidades']:0,
        'denuncias' => array_key_exists('denuncias',$row)?(int)$row['denuncias']:0,
        'fiscalizacoes' => '-',//array_key_exists('fiscalizacoes',$row)?(int)$row['fiscalizacoes']:0,
        'informacoes' => array_key_exists('informacoes',$row)?(int)$row['informacoes']:0,
        'processos' => '-',//array_key_exists('processos',$row)?(int)$row['processos']:0,
        'reclamacoes' => array_key_exists('reclamacoes',$row)?(int)$row['reclamacoes']:0,
        'populacao' => array_key_exists('populacao',$row)?(int)$row['populacao']:0,
        'area' => array_key_exists('area',$row)?(float)$row['area']:0,
    );

}

header('Content-Type: application/json');
//header("Content-Length: 5000");

echo (json_encode($json));