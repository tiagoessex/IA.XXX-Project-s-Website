<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
// RETURN N ENTITIES IN A GIVEN AREA
//    THIS AREA IS GIVEN BY A NUTS CODE
//    OR BY ITS UR/UO
//
// **********************************
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  header("HTTP/1.1 405 Method Not Allowed");
  exit();
}

$database = new Database();

$conn = $database->getConnection();
if (!$conn) die("error");

$limite = trim($_POST["limite"]);
$codigo = trim($_POST["codigo"]);
$level = trim($_POST["level"]);
$nuts_vs_unidades = trim($_POST["nuts_vs_unidades"]);


if ($nuts_vs_unidades === "true" || $level > 2) {
    if ($level == 0)  {
      $code_query = " 1 ";
    } else if ($level == 1)  {
      $cod = substr($codigo,0,2);
      $code_query = " substring(r1.freguesia_nuts_id,1,2) = '" . $cod . "' ";
    } else if ($level == 2)  {
      $cod = substr($codigo,0,4);
      $code_query = " substring(r1.freguesia_nuts_id,1,4) = '" . $cod . "' ";
    } else {
      $code_query = " r1.freguesia_nuts_id = '" . $codigo . "' ";
    }


    $query = "
    select 
      r1.id_entidade,
      r1.nome as nome,
      ST_X(geocod.coordenadas) as latitude,
      ST_Y(geocod.coordenadas) as longitude,
      r1.freguesia_nuts_id as code
    from 
      entidade AS r1
    inner join geocod on geocod.id_entidade = r1.id_entidade 
     JOIN
           (SELECT CEIL(RAND() *
                         (SELECT MAX(entidade.id_entidade)
                            FROM entidade)) AS id)
            AS r2";

    $query .= " where " . $code_query;
    $query .= ' and r1.id_entidade >= r2.id ';

} else {
  $query = "
select 
  r1.id_entidade,
  r1.nome as nome,
  ST_X(geocod.coordenadas) as latitude,
  ST_Y(geocod.coordenadas) as longitude,
  r1.freguesia_nuts_id as code
  FROM 
    (SELECT FLOOR (RAND() * (SELECT count(*) FROM entidade)) num ,@num:=@num+1 from (SELECT @num:=0) a , entidade LIMIT 100) b ,
    entidade r1
INNER join geocod ON (geocod.id_entidade = r1.id_entidade)
LEFT JOIN unidades_uo_nuts uoc ON (uoc.id_nuts = r1.freguesia_nuts_id)
LEFT JOIN unidades_ur_uo uuu ON (uuu.UO = uoc.ID_UO) 
";

  if ($level == 0) {
      $query .= " where 1 ";
  } else if ($level == 1) {
      $query .= " where uuu.UR='" . $codigo . "' ";
  } else  {
      $query .= " where uuu.UO='" . $codigo . "' ";
  }

   $query .= ' and b.num=r1.id_entidade ';
   
}



if (!(isset($_POST["org"]) && isset($_POST["individuo"]))) {
  if (isset($_POST["org"])) {
    $query .= ' and r1.entidade_type = "ORG" ';
  }
  if (isset($_POST["individuo"])) {
    $query .= ' and r1.entidade_type = "INDIVIDUO" ';
  }
}

if (!(isset($_POST["activas"]) && isset($_POST["naoactivas"]))) {
  if (isset($_POST["activas"])) {
    $query .= ' and r1.activa = "S" ';
  }
  if (isset($_POST["naoactivas"])) {
    $query .= ' and r1.activa = "N" ';
  }
}


$query .= " limit " . $limite . ";";


try {  
  $result=$conn->query($query);  
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}


$json = array();
foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row)
 {
    $json[]= array(
      'id' => $row['id_entidade'],
      'nome' => $row['nome'],
      'latitude' => $row['latitude'],
      'longitude' => $row['longitude'],
      'code' => $row['code'],
    );
}

$jsonstring = json_encode($json);

header('Content-Type: application/json');

echo ($jsonstring);