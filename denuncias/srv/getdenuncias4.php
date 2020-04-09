<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
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
     $data_start_query = "DD.ID >= -1";
} else {
    $data_start_query = "DD.ID >= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_start . " AND MES =   " . $mes_start . ") ,(SELECT MIN(ID) FROM DATA_DATA)))";
}




  $query = "
SELECT
  D.ID_DENUNCIA AS ID_DENUNCIA,
  C.DESIGNACAO AS COMPETENCIA,
  GetClassConteudoDenunciaStr(D.ID_DENUNCIA) AS CLASSE_INFRACCAO,
  ACT.CODIGO AS ACTIVIDADE_COD
FROM
  DENUNCIAS D
LEFT JOIN COMPETENCIA C ON (C.ID_COMP = D.ID_COMPETENCIA)
LEFT JOIN CORRESP_ACTIVIDADES CA ON (CA.CORRESP_ID_CORRESP = D.ID_DENUNCIA)
LEFT JOIN ACTIVIDADE ACT ON (ACT.ID_ACT = CA.ACT_ID_ACT)
LEFT JOIN DATA_DATA DD ON (DD.ID = D.ID_DATA)

WHERE
  " . $data_start_query . "
              AND
              DD.ID <= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_end . " AND MES =   " . $mes_end . ") ,(SELECT MAX(ID) FROM DATA_DATA)))
              ";
$query .= " AND D.HAS_MESSAGE = 1 order by id_denuncia";


try {  
  $result=$conn->query($query);  
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}


$json = array();
foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row)
 {
    $comp = strtolower($row['COMPETENCIA']);
    if (strpos($comp, 'XXX') !== false) {
      $comp = True;
    } else {
      $comp = False;
    }
    $act = explode(".", $row['ACTIVIDADE_COD'], 2);

    $inf = '';
    if ($row['CLASSE_INFRACCAO'] == 'Conflito de Consumo' || $row['CLASSE_INFRACCAO'] == 'Indefinido') {
      $inf = "Indefinido";
    }

    $json[]= array(
      'id_denuncia' => $row['ID_DENUNCIA'],
      'competencia' => $comp,
      'infraccao' => $inf != ''?$inf:$row['CLASSE_INFRACCAO'],
      'actividade' => $act[0],
    );
}


header('Content-Type: application/json');

echo (json_encode($json));