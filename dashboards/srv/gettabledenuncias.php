<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
//  RETURNS AN ARRAY OF ARRAYS (TABLE) WITH
//    THE NUMBER OF COMPLAINS IN A GIVEN AREA
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
  (select 
     CONCAT(CONCAT(CONCAT(CONCAT(COALESCE(NUTS.DESIGNACAO_DISTRITO,''),'/'),COALESCE(NUTS.DESIGNACAO_CONCELHO,'')),'/'),COALESCE(NUTS.DESIGNACAO_FREGUESIA,''))
    FROM NUTS
    WHERE NUTS.ID = DD.ID_NUTS
    LIMIT 1
  ) as nome,
  SUM(DD.ENTIDADES) AS entidades,
  SUM(DD.DENUNCIAS) AS denuncias,
  SUM(DD.RECLAMACOES) AS reclamacoes,
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
    $denuncias_area = 0;
    if (array_key_exists('area',$row) && array_key_exists('denuncias',$row)) {
        if ($row['area'] > 0)
            $denuncias_area = $row['denuncias']/$row['area'];
        else
            $denuncias_area = 0;
    };
    
    $denuncias_populacao = 0;
    
    if (array_key_exists('populacao',$row) && array_key_exists('denuncias',$row)) {
        if ($row['populacao'] > 0)
            $denuncias_populacao = $row['denuncias']/$row['populacao'];
        else
            $denuncias_populacao = 0;
    };

    $denuncias_entidades = 0;
    
    if (array_key_exists('entidades',$row) && array_key_exists('denuncias',$row)) {
        if ($row['entidades'] > 0)
            $denuncias_entidades = $row['denuncias']/$row['entidades'];
        else
            $denuncias_entidades = 0;
    };


    $reclamacoes_area = 0;
    if (array_key_exists('area',$row) && array_key_exists('reclamacoes',$row)) {
        if ($row['area'] > 0)
            $reclamacoes_area = $row['reclamacoes']/$row['area'];
        else
            $reclamacoes_area = 0;
    };
    
    $reclamacoes_populacao = 0;
    
    if (array_key_exists('populacao',$row) && array_key_exists('reclamacoes',$row)) {
        if ($row['populacao'] > 0)
            $reclamacoes_populacao = $row['reclamacoes']/$row['populacao'];
        else
            $reclamacoes_populacao = 0;
    };

    $reclamacoes_entidades = 0;
    
    if (array_key_exists('entidades',$row) && array_key_exists('reclamacoes',$row)) {
        if ($row['entidades'] > 0)
            $reclamacoes_entidades = $row['reclamacoes']/$row['entidades'];
        else
            $reclamacoes_entidades = 0;
    };


    $nome = array_key_exists('nome',$row)?$row['nome']:"";
    if ($row['id'] == '000000') {
      $nome = "NÃO ESPECIFICADA";
    }
    
    $json[] = array(
      'id' => array_key_exists('id',$row)?$row['id']:"",
      'nome' => $nome,
      'denuncias' => array_key_exists('denuncias',$row)?(int)$row['denuncias']:0,
      'denuncias_area' => round($denuncias_area,2),
      'denuncias_populacao' => round($denuncias_populacao,2),
      'denuncias_entidades' => round($denuncias_entidades,2),
      'reclamacoes' => array_key_exists('reclamacoes',$row)?(int)$row['reclamacoes']:0,
      'reclamacoes_area' => round($reclamacoes_area,2),
      'reclamacoes_populacao' => round($reclamacoes_populacao,2),
      'reclamacoes_entidades' => round($reclamacoes_entidades,2),
    );

}

header('Content-Type: application/json');

echo (json_encode($json));