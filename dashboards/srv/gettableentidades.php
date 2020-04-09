<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
//  RETURNS AN ARRAY OF ARRAYS (TABLE) WITH
//    THE NUMBER OF ENTITIES IN A GIVEN AREA
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
  DN.POPULACAO AS populacao,
  DN.AREA AS area
FROM 
  data_dados DD
LEFT JOIN DATA_NUTS DN ON (DN.ID = DD.ID_NUTS)
WHERE
--   ID_NUTS != '000000'
-- AND
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
    $entidades_area = 0;
    if (array_key_exists('area',$row) && array_key_exists('entidades',$row)) {
        if ($row['area'] > 0)
            $entidades_area = $row['entidades']/$row['area'];
        else
            $entidades_area = 0;
    };
    
    $entidades_populacao = 0;
    
    if (array_key_exists('populacao',$row) && array_key_exists('entidades',$row)) {
        if ($row['populacao'] > 0)
            $entidades_populacao = $row['entidades']/$row['populacao'];
        else
            $entidades_populacao = 0;
    };

    $nome = array_key_exists('nome',$row)?$row['nome']:"";
    if ($row['id'] == '000000') {
      $nome = "NÃO ESPECIFICADA";
    }
    $json[] = array(
        'id' => array_key_exists('id',$row)?$row['id']:"",
        'nome' => $nome,
      'entidades' => array_key_exists('entidades',$row)?(float)$row['entidades']:"",
      'entidades_area' => round($entidades_area,2),
      'entidades_populacao' => round($entidades_populacao,2),
    );

}

header('Content-Type: application/json');
//header("Content-Length: 5000");

echo (json_encode($json));