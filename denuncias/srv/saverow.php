<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
// SAVE AN ENTIRE ROW IN DENUNCIAS_AI
//      WITH ALL VALID
//
//
//  called by: denunciasanalisar.php
//
//  the validation fields are really not necessary
//
// **********************************
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  header("HTTP/1.1 405 Method Not Allowed");
  exit();
}

$database = new Database();

$conn = $database->getConnection();
if (!$conn) die("error");



$model = $_POST['model'];
$competencia = trim($_POST['competencia']);
$actividade = trim($_POST['actividade']);
$infraccao = trim($_POST['infraccao']);
$id_denuncia = trim($_POST['id_denuncia']);
$competencia_simples = $_POST['competencia_simples'];


// *************************************
// BUSCA CODIGO DA INFRACCAO
// *************************************
if ($infraccao != '') {
    
    $query_infraccao = '
    SELECT
        ID_NAT_JUR AS ID_INFRACCAO
    FROM 
        NATUREZA_JURIDICA
    WHERE
        DESC_NAT_JUR LIKE "%'.$infraccao.'%"';


    try {  
      $result=$conn->query($query_infraccao);  
    } catch (PDOException $e) {
        header("HTTP/1.1 500 Internal Server Error");
        die($e->getMessage());
    }

    $inf = $result->fetch(PDO::FETCH_ASSOC);
} 


// *************************************
// BUSCA CODIGO DA COMPETENCIA
// *************************************

if ($competencia != '') {

    $query_competencia = '
    SELECT
        ID_COMP AS ID_COMPETENCIA
    FROM 
        COMPETENCIA
    WHERE
        DESIGNACAO LIKE "%'.$competencia.'%"';


    try {  
      $result=$conn->query($query_competencia);  
    } catch (PDOException $e) {
        header("HTTP/1.1 500 Internal Server Error");
        die($e->getMessage());
    }

    $comp = $result->fetch(PDO::FETCH_ASSOC);
}

// *************************************
// BUSCA CODIGO DA ACTIVIDADE
// *************************************

$query_actividade = '
        SELECT
            ID_ACT AS ACTIVIDADE
        FROM
            ACTIVIDADE
        WHERE
            CODIGO = "'.$actividade.'"
        LIMIT 1';

try {  
    $result=$conn->query($query_actividade);  
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}

$act = $result->fetch(PDO::FETCH_ASSOC);



// *************************************
// SAVE ROW
// *************************************
$query = '
INSERT INTO `denuncias_ai`(`ID_DENUNCIA`, `ID_COMPETENCIA`, `COMPETENCIA_SIMPLES`, `ID_INFRACCAO`, `ID_ACTIVIDADE`, `MODELO`, `VALID_COMPETENCIA`, `VALID_COMPETENCIA_SIMPLES`, `VALID_INFRACCAO`, `VALID_ACTIVIDADE`) VALUES ('.$id_denuncia.', '.($competencia == ''?'NULL':$comp['ID_COMPETENCIA']).', '.$competencia_simples.' ,'.($infraccao == ''?'NULL':$inf['ID_INFRACCAO']).' ,'. $act['ACTIVIDADE'] .' ,'.$model.','.($competencia == ''?'0':'1').',1,'.($infraccao == ''?'0':'1').',1)';

$query .= " ON DUPLICATE KEY UPDATE 
ID_DENUNCIA = ".$id_denuncia.",
ID_COMPETENCIA = ".($competencia == ''?'NULL':$comp['ID_COMPETENCIA']).",
COMPETENCIA_SIMPLES = ".$competencia_simples.",
ID_INFRACCAO =  ".($infraccao == ''?'NULL':$inf['ID_INFRACCAO']).",
ID_ACTIVIDADE =  ".$act['ACTIVIDADE'].",
MODELO =  ".$model.",
VALID_COMPETENCIA =  ".($competencia == ''?'0':'1').",
VALID_COMPETENCIA_SIMPLES =  1,
VALID_INFRACCAO =  ".($infraccao == ''?'0':'1').",
VALID_ACTIVIDADE =  1
";


try {
    $conn->beginTransaction();
    $conn->exec($query);
    $conn->commit();
} catch(PDOException $e) {
    $conn->rollBack();
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());    
}