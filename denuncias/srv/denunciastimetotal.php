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
  count(*) AS den_time
FROM 
  DENUNCIAS D
LEFT JOIN DATA_DATA DD ON (DD.ID = D.ID_DATA)
WHERE
  D.HAS_MESSAGE = 1 ";

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


$row = $result->fetch(PDO::FETCH_ASSOC);

$json = array();


if ($row) {
      $json['den_time'] =  array_key_exists('den_time',$row)?$row['den_time']:0;
} else {
    header('HTTP/1.1 500 Internal Server Booboo');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode(array('status' => 'error', 'code' => 1337)));
}







$query = "
SELECT  
  count(distinct D_AI.id_denuncia) AS den_time_class
FROM 
  denuncias_ai D_AI
LEFT JOIN DENUNCIAS D ON (D.ID_DENUNCIA = D_AI.ID_DENUNCIA)
LEFT JOIN DATA_DATA DD ON (DD.ID = D.ID_DATA)";
$query .= " WHERE " . $data_start_query . "
              AND
              DD.ID <= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_end . " AND MES =   " . $mes_end . ") ,(SELECT MAX(ID) FROM DATA_DATA)))
              ";

try {  
  $result=$conn->query($query);  
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}
$row = $result->fetch(PDO::FETCH_ASSOC);
if ($row) {
      $json['den_time_class'] =  array_key_exists('den_time_class',$row)?$row['den_time_class']:0;
} else {
    header('HTTP/1.1 500 Internal Server Booboo');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode(array('status' => 'error', 'code' => 1337)));
}




$query = "
SELECT  
  count(*) AS den_time_class1
FROM 
  denuncias_ai D_AI_ACT
LEFT JOIN DENUNCIAS D ON (D.ID_DENUNCIA = D_AI_ACT.ID_DENUNCIA)
LEFT JOIN DATA_DATA DD ON (DD.ID = D.ID_DATA)
WHERE
  D_AI_ACT.modelo = 1";
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
$row = $result->fetch(PDO::FETCH_ASSOC);
if ($row) {
      $json['den_time_class1'] =  array_key_exists('den_time_class1',$row)?$row['den_time_class1']:0;
} else {
    header('HTTP/1.1 500 Internal Server Booboo');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode(array('status' => 'error', 'code' => 1337)));
}


$query = "
SELECT  
  count(DISTINCT (D_AI_ACT.ID_DENUNCIA)) AS den_time_class2
FROM 
  denuncias_ai D_AI_ACT
LEFT JOIN DENUNCIAS D ON (D.ID_DENUNCIA = D_AI_ACT.ID_DENUNCIA)
LEFT JOIN DATA_DATA DD ON (DD.ID = D.ID_DATA)
WHERE
  D_AI_ACT.modelo = 2";
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
$row = $result->fetch(PDO::FETCH_ASSOC);
if ($row) {
      $json['den_time_class2'] =  array_key_exists('den_time_class2',$row)?$row['den_time_class2']:0;
} else {
    header('HTTP/1.1 500 Internal Server Booboo');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode(array('status' => 'error', 'code' => 1337)));
}



$jsonstring = json_encode($json);

header('Content-Type: application/json');

echo ($jsonstring);