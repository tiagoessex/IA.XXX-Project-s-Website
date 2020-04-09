<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
// RETURN ALL COMPLAINTS THAT WERE ALREADY CLASSIFIED
//    BY THE MODELS 
//    IN A GIVEN TIME INTERVAL
//
//  returns both the real as well the predited
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


$model = trim($_POST['model']);
$ano_start = trim($_POST['ano_start']);
$mes_start = trim($_POST['mes_start']);
$ano_end = trim($_POST['ano_end']);
$mes_end = trim($_POST['mes_end']);

if (substr(MIN_DATE,0,7) == $ano_start . '-' . $mes_start) {
     $data_start_query = "DD.ID >= -1";
} else {
    $data_start_query = "DD.ID >= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_start . " AND MES =   " . $mes_start . ") ,(SELECT MIN(ID) FROM DATA_DATA)))";
}



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
    LEFT JOIN ACTIVIDADE ACT_AI ON (ACT_AI.ID_ACT = D_AI.ID_ACTIVIDADE)
    LEFT JOIN DATA_DATA DD ON (DD.ID = D.ID_DATA)
    WHERE 
      D_AI.MODELO = " . $model;
$query .= " AND " . $data_start_query . "
              AND
              DD.ID <= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_end . " AND MES =   " . $mes_end . ") ,(SELECT MAX(ID) FROM DATA_DATA)))
              ";

$query .= " GROUP BY D.ID_DENUNCIA";


try {  
  $result=$conn->query($query);  
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}


$json = array();
foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row)
 {

    if (isset($row['COMPETENCIA_SIMPLES'])) {
        $competencia = ($row['COMPETENCIA_SIMPLES']==1?'XXX':'OUTRO');
        $competencia .= ' (' . (isset($row['COMPETENCIA_AI'])?$row['COMPETENCIA_AI']:'') . ')';
    } else {
        $competencia = '';
    }

    $inf = (isset($row['CLASSE_INFRACCAO_AI'])?$row['CLASSE_INFRACCAO_AI']:'');

    $json[]= array(
      'id_denuncia' => $row['ID_DENUNCIA'],
      'competencia' => $row['COMPETENCIA'],
      'competencia_ai' => $competencia,
      'infraccao' => $row['CLASSE_INFRACCAO'],
      'infraccao_ai' => $inf,
      'actividade' => $row['ACTIVIDADE_COD'],
      'actividade_ai' => isset($row['ACTIVIDADE_COD_AI'])?$row['ACTIVIDADE_COD_AI']:'',
    );
}


header('Content-Type: application/json');

echo (json_encode($json));