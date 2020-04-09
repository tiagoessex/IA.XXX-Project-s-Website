<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
// RETURNS COMPLAINTS THAT HAVE BEEN CLASSIFIED
//    BY THE MODELS IN A GIVEN TIME INTERVAL
//    => THEY MUST EXIST IN DENUNCIAS_AI
//
//
//  called by: denunciasconsultaralterar.php
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
  D.COMPETENCIA_SIMPLES AS COMPETENCIA_SIMPLES,
  N_J.DESC_NAT_JUR AS CLASSE_INFRACCAO,
  A.CODIGO AS ACTIVIDADE_COD
FROM
  DENUNCIAS_AI D
LEFT JOIN DENUNCIAS DEN ON (DEN.ID_DENUNCIA = D.ID_DENUNCIA)
LEFT JOIN COMPETENCIA C ON (C.ID_COMP = D.ID_COMPETENCIA)
LEFT JOIN NATUREZA_JURIDICA N_J ON (N_J.ID_NAT_JUR = D.ID_INFRACCAO)
LEFT JOIN ACTIVIDADE A ON (A.ID_ACT = D.ID_ACTIVIDADE)
LEFT JOIN DATA_DATA DD ON (DD.ID = DEN.ID_DATA)

WHERE
      D.MODELO = " . $model;

$query .= " AND " . $data_start_query . "
              AND
              DD.ID <= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_end . " AND MES =   " . $mes_end . ") ,(SELECT MAX(ID) FROM DATA_DATA)))
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
    if (isset($row['COMPETENCIA_SIMPLES'])) {
        $competencia = ($row['COMPETENCIA_SIMPLES']==1?'XXX':'OUTRO');
        $competencia .= ' (' . (isset($row['COMPETENCIA'])?$row['COMPETENCIA']:'') . ')';
    } else {
        $competencia = '';
    }

    $json[]= array(
      'id_denuncia' => $row['ID_DENUNCIA'],
      'competencia_ai' => $competencia,
      'infraccao_ai' => isset($row['CLASSE_INFRACCAO'])?$row['CLASSE_INFRACCAO']:'',
      'actividade_ai' => isset($row['ACTIVIDADE_COD'])?$row['ACTIVIDADE_COD']:'',
      'operacoes' => '',
    );
}


header('Content-Type: application/json');

echo (json_encode($json));