<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
// SAVE (update) A SINGLE CELL
//      AND SET THE RESPECTIVE VALID FIELD
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

if ($actividade != '') {

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
}

$query = '';


// *************************************
// SAVE CELL
// *************************************
if ($competencia != '') {
    $query .= '
    INSERT INTO `denuncias_ai`(
            `ID_DENUNCIA`, 
            `MODELO`, 
            `ID_COMPETENCIA`, 
            `VALID_COMPETENCIA`) 
    VALUES ('
        .$id_denuncia.', '
        .$model.','
        .$comp['ID_COMPETENCIA'].',
        1
    )
    ON DUPLICATE KEY UPDATE 
        ID_DENUNCIA = '.$id_denuncia.',
        MODELO =  '.$model.',
        ID_COMPETENCIA = '.$comp['ID_COMPETENCIA'].',
        VALID_COMPETENCIA = 1;
    ';
}

if ($competencia_simples != '') {
    $query .= '
    INSERT INTO `denuncias_ai`(
            `ID_DENUNCIA`, 
            `MODELO`, 
            `COMPETENCIA_SIMPLES`, 
            `VALID_COMPETENCIA_SIMPLES`) 
    VALUES ('
        .$id_denuncia.', '
        .$model.','
        .$competencia_simples.',
        1
    )
    ON DUPLICATE KEY UPDATE 
        ID_DENUNCIA = '.$id_denuncia.',
        MODELO =  '.$model.',
        COMPETENCIA_SIMPLES = '.$competencia_simples.',
        VALID_COMPETENCIA_SIMPLES = 1;
    ';
}

if ($actividade != '') {
    $query .= '
    INSERT INTO `denuncias_ai`(
            `ID_DENUNCIA`, 
            `MODELO`, 
            `ID_ACTIVIDADE`, 
            `VALID_ACTIVIDADE`) 
    VALUES ('
        .$id_denuncia.', '
        .$model.','
        .$act['ACTIVIDADE'].',
        1
    )
    ON DUPLICATE KEY UPDATE 
        ID_DENUNCIA = '.$id_denuncia.',
        MODELO =  '.$model.',
        ID_ACTIVIDADE = '.$act['ACTIVIDADE'].',
        VALID_ACTIVIDADE = 1;
    ';
}


if ($infraccao != '') {
    $query .= '
    INSERT INTO `denuncias_ai`(
            `ID_DENUNCIA`, 
            `MODELO`, 
            `ID_INFRACCAO`, 
            `VALID_INFRACCAO`) 
    VALUES ('
        .$id_denuncia.', '
        .$model.','
        .$inf['ID_INFRACCAO'].',
        1
    )
    ON DUPLICATE KEY UPDATE 
        ID_DENUNCIA = '.$id_denuncia.',
        MODELO =  '.$model.',
        ID_INFRACCAO = '.$inf['ID_INFRACCAO'].',
        VALID_INFRACCAO = 1;
    ';
}


try {
    $conn->beginTransaction();
    $conn->exec($query);
    $conn->commit();
} catch(PDOException $e) {
    $conn->rollBack();
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());    
}