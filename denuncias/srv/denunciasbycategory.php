<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
// RETURN COMPLAINT STATISTICS
//    IN A GIVEN TIME INTERVAL
//
//  called by: denunciasclassficadas.php
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


$id_act = trim($_POST['id_act']);
$exact = trim($_POST['exact']);
$model = trim($_POST['model']);


$query = "
SELECT 
  CODIGO
FROM 
  ACTIVIDADE 
WHERE 
  ID_ACT = '" . $id_act . "' LIMIT 1";


try {  
  $result=$conn->query($query);  
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}



$ano_start = trim($_POST['ano_start']);
$mes_start = trim($_POST['mes_start']);
$ano_end = trim($_POST['ano_end']);
$mes_end = trim($_POST['mes_end']);

if (substr(MIN_DATE,0,7) == $ano_start . '-' . $mes_start) {
     $data_start_query = "DD.ID >= -1";
} else {
    $data_start_query = "DD.ID >= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_start . " AND MES =   " . $mes_start . ") ,(SELECT MIN(ID) FROM DATA_DATA)))";
}



$row = $result->fetch(PDO::FETCH_ASSOC);

$codigo = $row['CODIGO'];

if ($model == 1) {
  $query = "
    SELECT 
      D.ID_DENUNCIA AS ID_DENUNCIA,
      C.DESIGNACAO AS COMPETENCIA,
      C_AI.DESIGNACAO AS COMPETENCIA_AI,
      D_AI.COMPETENCIA_SIMPLES AS COMPETENCIA_SIMPLES,
      GetClassConteudoDenunciaStr(D.ID_DENUNCIA) AS CLASSE_INFRACCAO,
      NJ.DESC_NAT_JUR AS CLASSE_INFRACCAO_AI,
      ACT.CODIGO AS ACTIVIDADE_COD,
      ACT_AI.CODIGO AS ACTIVIDADE_COD_AI
    FROM
      DENUNCIAS D
    LEFT JOIN DENUNCIAS_AI D_AI ON (D_AI.ID_DENUNCIA = D.ID_DENUNCIA)
    LEFT JOIN COMPETENCIA C_AI ON (C_AI.ID_COMP = D_AI.ID_COMPETENCIA)
    LEFT JOIN COMPETENCIA C ON (C.ID_COMP = D.ID_COMPETENCIA)
    LEFT JOIN CORRESP_ACTIVIDADES CA ON (CA.CORRESP_ID_CORRESP = D.ID_DENUNCIA)
    LEFT JOIN ACTIVIDADE ACT ON (ACT.ID_ACT = CA.ACT_ID_ACT)
    LEFT JOIN NATUREZA_JURIDICA NJ ON (NJ.ID_NAT_JUR = D_AI.ID_INFRACCAO)
    LEFT JOIN DENUNCIAS_AI_ACTIVIDADE D_AI_ACT ON (D_AI_ACT.ID_DENUNCIA = D_AI.ID_DENUNCIA)
    LEFT JOIN ACTIVIDADE ACT_AI ON (ACT_AI.ID_ACT = D_AI_ACT.ID_ACTIVIDADE)
    LEFT JOIN DATA_DATA DD ON (DD.ID = D.ID_DATA)
    WHERE 
      ACT.CODIGO = '" . $codigo . "'";
    if ($exact != 1) {
      $query .= " OR ACT.CODIGO LIKE '" . $codigo . ".%' ";
    };
    $query .= " AND D_AI_ACT.MODELO = 1 ";
    $query .= " AND " . $data_start_query . "
              AND
              DD.ID <= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_end . " AND MES =   " . $mes_end . ") ,(SELECT MAX(ID) FROM DATA_DATA)))
              ";
} else {
  $query = "
  SELECT 
    D.ID_DENUNCIA AS ID_DENUNCIA,
    C.DESIGNACAO AS COMPETENCIA,
    C_AI.DESIGNACAO AS COMPETENCIA_AI,
    D_AI.COMPETENCIA_SIMPLES AS COMPETENCIA_SIMPLES,
    GetClassConteudoDenunciaStr(D.ID_DENUNCIA) AS CLASSE_INFRACCAO,
    NJ.DESC_NAT_JUR AS CLASSE_INFRACCAO_AI,
    ACT.CODIGO AS ACTIVIDADE_COD,
    GetActividadesAIStr(D.ID_DENUNCIA) AS ACTIVIDADE_COD_AI
  FROM
    DENUNCIAS D
  LEFT JOIN DENUNCIAS_AI D_AI ON (D_AI.ID_DENUNCIA = D.ID_DENUNCIA)
  LEFT JOIN COMPETENCIA C_AI ON (C_AI.ID_COMP = D_AI.ID_COMPETENCIA)
  LEFT JOIN COMPETENCIA C ON (C.ID_COMP = D.ID_COMPETENCIA)
  LEFT JOIN CORRESP_ACTIVIDADES CA ON (CA.CORRESP_ID_CORRESP = D.ID_DENUNCIA)
  LEFT JOIN ACTIVIDADE ACT ON (ACT.ID_ACT = CA.ACT_ID_ACT)
  LEFT JOIN NATUREZA_JURIDICA NJ ON (NJ.ID_NAT_JUR = D_AI.ID_INFRACCAO)
  LEFT JOIN DENUNCIAS_AI_ACTIVIDADE D_AI_ACT ON (D_AI_ACT.ID_DENUNCIA = D_AI.ID_DENUNCIA)
  LEFT JOIN DATA_DATA DD ON (DD.ID = D.ID_DATA)
  WHERE 
    ACT.CODIGO = '" . $codigo . "'";
  if ($exact != 1) {
    $query .= " OR ACT.CODIGO LIKE '" . $codigo . ".%' ";
  };
  $query .= " AND D_AI_ACT.MODELO = 2 ";
  $query .= " AND " . $data_start_query . "
              AND
              DD.ID <= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_end . " AND MES =   " . $mes_end . ") ,(SELECT MAX(ID) FROM DATA_DATA)))
              ";
  $query .= " GROUP BY D_AI_ACT.ID_DENUNCIA";
}



try {  
  $result=$conn->query($query);  
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}


$json = array();
foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row)
 {
    $competencia = ($row['COMPETENCIA_SIMPLES']==1?'XXX':'OUTRO');
    $competencia .= ' (' . $row['COMPETENCIA_AI'] . ')';

    $json[]= array(
      'id_denuncia' => $row['ID_DENUNCIA'],
      'competencia' => $row['COMPETENCIA'],
      'competencia_ai' => $competencia, //$row['COMPETENCIA_AI'],
      'infraccao' => $row['CLASSE_INFRACCAO'],
      'infraccao_ai' => $row['CLASSE_INFRACCAO_AI'],
      'actividade' => $row['ACTIVIDADE_COD'],
      'actividade_ai' => $row['ACTIVIDADE_COD_AI'],
    );
}


header('Content-Type: application/json');

echo (json_encode($json));