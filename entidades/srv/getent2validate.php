<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
//  RETURN A DUPLICATED ENTITY STILL TO BE VALIDATED
//
//  AND ALSO SOME RELATED COUNTERS
//
// **********************************
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  header("HTTP/1.1 405 Method Not Allowed");
  exit();
}

$database = new Database();

$conn = $database->getConnection();
if (!$conn) die("error");

$query = "
  select 
    count(*) as total 
  from 
    entidade_duplicados";

try {  
  $result=$conn->query($query);  
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}

$row = $result->fetch(PDO::FETCH_ASSOC);
$total = $row['total'];


$query = "
  select 
    count(*) as sofar 
  from 
    entidade_duplicados 
  where 
    entidade_duplicados.valid is not null";

try {  
  $result=$conn->query($query);  
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}

$row = $result->fetch(PDO::FETCH_ASSOC);
$sofar = $row['sofar'];

$query = "
  select 
    count(*) as total_valid
  from 
    entidade_duplicados 
  where 
    entidade_duplicados.valid=1";

try {  
  $result=$conn->query($query);  
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}

$row = $result->fetch(PDO::FETCH_ASSOC);
$total_valid = $row['total_valid'];

$query = "
  select 
    count(*) as total_invalid
  from 
    entidade_duplicados 
  where 
    entidade_duplicados.valid=0";

try {  
  $result=$conn->query($query);  
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}

$row = $result->fetch(PDO::FETCH_ASSOC);
$total_invalid = $row['total_invalid'];


$query = "
  select 
    count(*) as total_ignored
  from 
    entidade_duplicados 
  where 
    entidade_duplicados.valid=2";

try {  
  $result=$conn->query($query);  
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}

$row = $result->fetch(PDO::FETCH_ASSOC);
$total_ignored = $row['total_ignored'];


$query = "
select 
    ent1.id_entidade as id_duplicada,
    ent1.nome as nome_duplicada,
    ent1.morada as morada_duplicada,
    ent1.is_pai as pai_duplicada,
    ent1.NIF_NIPC as nif_duplicada,
    ent1.localidade_cp as localidade_duplicada,
    ent2.id_entidade as id_unique,
    ent2.nome as nome_unique,
    ent2.morada as morada_unique,
    ent2.is_pai as pai_unique,
    ent2.NIF_NIPC as nif_unique,
    ent2.localidade_cp as localidade_unique
from 
  entidade_duplicados AS dups
left JOIN entidade AS ent1 on (dups.ID_ENTIDADE_DUPLICADA = ent1.id_entidade)
left join entidade AS ent2 on (dups.ID_ENTIDADE_UNIQUE = ent2.id_entidade)
WHERE 
  dups.VALID IS null
LIMIT 1
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
    $json[]= array(
      'id_duplicada' => $row['id_duplicada'],
      'nome_duplicada' => $row['nome_duplicada'],
      'morada_duplicada' => $row['morada_duplicada'],
      'pai_duplicada' => $row['pai_duplicada'],
      'nif_duplicada' => $row['nif_duplicada'],
      'localidade_duplicada' => $row['localidade_duplicada'],
      'id_unique' => $row['id_unique'],
      'nome_unique' => $row['nome_unique'],
      'morada_unique' => $row['morada_unique'],
      'pai_unique' => $row['pai_unique'],
      'nif_unique' => $row['nif_unique'],
      'localidade_unique' => $row['localidade_unique'],
      'total' => $total,
      'sofar' => $sofar,
      'total_valid' => $total_valid,
      'total_invalid' => $total_invalid,
      'total_ignored' => $total_ignored,
    );
}


$jsonstring = json_encode($json);

header('Content-Type: application/json');

echo ($jsonstring);