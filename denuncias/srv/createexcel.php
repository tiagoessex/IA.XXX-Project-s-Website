<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
// CREATES AN EXCEL FILE (denuncias.xlxs)
//    DESTINATED FOR CLASSIFIER 2 TRAINING,
//    AND SAVE IT IN THIS FILE'S DIRECTORY
//    THIS EXCEL CONSTAINS 5 COLUMNS:
//      - ID_DENUNCIA
//      - COMPETENCIA
//      - INFRACCAO
//      - ACTIVIDADE
//      - MESSAGE
//
//  IF THE TIME INTERVAL IS LARGE, 
//  IT CAN TAKE QUITE A WHILE FOR THE FILE TO BE CREATED
//
//  called by: denunciasexporttrain.php
//
// **********************************
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  header("HTTP/1.1 405 Method Not Allowed");
  exit();
}

// PhpSpreadsheet library ready to use downloaded from
// https://php-download.com/package/phpoffice/phpspreadsheet
require '../../external/PhpSpreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;




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
--  GetClassConteudoDenunciaStr(D.ID_DENUNCIA) AS CLASSE_INFRACCAO,
NJ.DESC_NAT_JUR AS CLASSE_INFRACCAO,
  ACT.CODIGO AS ACTIVIDADE_COD,
  D.EMAIL_CONTENT AS MENSAGEM
FROM
  DENUNCIAS D
LEFT JOIN COMPETENCIA C ON (C.ID_COMP = D.ID_COMPETENCIA)
LEFT JOIN CORRESP_ACTIVIDADES CA ON (CA.CORRESP_ID_CORRESP = D.ID_DENUNCIA)
LEFT JOIN ACTIVIDADE ACT ON (ACT.ID_ACT = CA.ACT_ID_ACT)
LEFT JOIN DATA_DATA DD ON (DD.ID = D.ID_DATA)
LEFT JOIN DENUNCIAS_NATUREZA_JURIDICA DNJ ON (DNJ.ID_DENUNCIA = D.ID_DENUNCIA)
LEFT JOIN NATUREZA_JURIDICA NJ on (NJ.ID_NAT_JUR = DNJ.ID_NATUREZA_JURIDICA)

WHERE
  " . $data_start_query . "
              AND
              DD.ID <= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_end . " AND MES =   " . $mes_end . ") ,(SELECT MAX(ID) FROM DATA_DATA)))
              ";
$query .= " AND 
D.HAS_MESSAGE = 1
AND
  C.DESIGNACAO IS NOT null
AND
  NJ.DESC_NAT_JUR IS NOT null
AND
  ACT.CODIGO IS NOT null
";

// default is 128M
// but all complaint from 1999 to 2019 is more than that
ini_set('memory_limit', '1024M'); 
// the query +  excel build can take a lot longer than 30s
set_time_limit(600);

try {  
  $result=$conn->query($query);  
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}


$timestamp = time();
//$filename = './denuncias_' . $timestamp . '.xlsx';
$filename = './denuncias.xlsx';

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
// HEADER
$sheet->setCellValue(('A').'1', 'id_denuncia');
$sheet->setCellValue(('B').'1', 'competencia');
$sheet->setCellValue(('C').'1', 'infraccao');
$sheet->setCellValue(('D').'1', 'actividade');
$sheet->setCellValue(('E').'1', 'mensagem');


// all into an array and then save it in one go
// TODO: test and check if split by blocks of 250 or more rows is necessary

$json = array();
foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row)
 {

    if ($row['COMPETENCIA']) {
      $comp = strtolower($row['COMPETENCIA']);
      if (strpos($comp, 'XXX') !== false) {
        $comp = 'True';
      } else {
        $comp = 'False';
      }
    } else {
      $comp = $row['COMPETENCIA'];
    }

    if ($row['ACTIVIDADE_COD']) {
        $act = explode(".", $row['ACTIVIDADE_COD'], 2);
    } else {
        $act = $row['ACTIVIDADE_COD'];
    }

    $inf = $row['CLASSE_INFRACCAO'];
    if ($row['CLASSE_INFRACCAO']) {
        if ($row['CLASSE_INFRACCAO'] == 'Conflito de Consumo') {
          $inf = "Indefinido";
        }
    }

    $line = array($row['ID_DENUNCIA'], $comp, $inf, $act[0],$row['MENSAGEM']);
    array_push($json, $line);
}

$sheet->fromArray(
    $json,
    NULL,       // NaN / null => empty
    'A2');


$writer = new Xlsx($spreadsheet);
$writer->save($filename);


header('Content-Type: application/json');

echo json_encode(array ('filename'=>$filename));