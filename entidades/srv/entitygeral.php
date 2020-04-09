<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
//        ENTITY GENERAL INFORMATION
//
//  name, contacts, ...
//
// **********************************
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  header("HTTP/1.1 405 Method Not Allowed");
  exit();
}


$database = new Database();

$conn = $database->getConnection();
if (!$conn) {
    header('HTTP/1.1 500 Internal Server Booboo');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode(array('status' => 'error', 'code' => 500)));
}



$condition = ' true ';
if(isset($_POST['id_entidade']) && trim($_POST['id_entidade']) !== "") {
    $id = trim($_POST['id_entidade']);
    $condition = " entidade.id_entidade = " . $id;
}
else if(isset($_POST['nome_entidade']) && trim($_POST['nome_entidade']) !== "") {
    $name = trim($_POST['nome_entidade']);
    $condition = " entidade.nome = '" . $name . "'";
}


$query = "
select 
  entidade.id_entidade AS ID_ENTIDADE,
  entidade.nome AS NOME,
  entidade.freguesia_nuts_id as NUTS_CODE,
  GetPath(entidade.freguesia_nuts_id,'T') as PATH,
  entidade.morada AS MORADA,
  entidade.codigo_postal AS CODIGO_POSTAL,
  entidade.localidade_cp AS LOCALIDADE_CP,
  entidade.telefone AS TELEFONE,
  entidade.activa AS ACTIVA,
  entidade.is_pai AS IS_PAI,
  rnpc_natureza_juridica.designacao as NATUREZA_JURIDICA,
  entidade.NIF_NIPC,
  tipos_entidade.DESC_TP_ENT as TIPO_ENTIDADE, 
  entidade.tp_actividade as TIPO_ACTIVIDADE,
  GetActividadesStr(entidade.id_entidade) AS ACTIVIDADE
from 
  entidade
left join rnpc_natureza_juridica on (entidade.RNPC_NATJURIDICA = rnpc_natureza_juridica.ID_NAT_JUR)
left join tipos_entidade on (tipos_entidade.ID_TP_ENT = entidade.TIPO_ENT_ID_TP_ENT)
where " .  $condition . " limit 1";


try {  
  $result=$conn->query($query);  
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}

$row = $result->fetch(PDO::FETCH_ASSOC);



$json = array();

if ($row) {
      $json['id'] =  array_key_exists('ID_ENTIDADE',$row)?$row['ID_ENTIDADE']:'-';
      $json['nome'] =  array_key_exists('NOME',$row)?$row['NOME']:'-';
      $json['nuts_code'] =  array_key_exists('NUTS_CODE',$row)?$row['NUTS_CODE']:'-';
      $json['path'] =  array_key_exists('PATH',$row)?$row['PATH']:'-';
      $json['morada'] =  array_key_exists('MORADA',$row)?$row['MORADA']:'-';
      $json['codigo_postal'] =  array_key_exists('CODIGO_POSTAL',$row)?$row['CODIGO_POSTAL']:'-';
      $json['localidade_cp'] =  array_key_exists('LOCALIDADE_CP',$row)?$row['LOCALIDADE_CP']:'-';
      $json['telefone'] =  array_key_exists('TELEFONE',$row)?$row['TELEFONE']:'-';
      $json['activa'] =  array_key_exists('ACTIVA',$row)?$row['ACTIVA']:'-';
      $json['nif_nipc'] =  array_key_exists('NIF_NIPC',$row)?$row['NIF_NIPC']:'-';
      $json['natureza_juridica'] =  array_key_exists('NATUREZA_JURIDICA',$row)?$row['NATUREZA_JURIDICA']:'-';
      $json['tipo_entidade'] =  array_key_exists('TIPO_ENTIDADE',$row)?$row['TIPO_ENTIDADE']:'-';
      $json['is_pai'] =  array_key_exists('IS_PAI',$row)?$row['IS_PAI']:'-';
      $json['tipo_actividade'] =  array_key_exists('TIPO_ACTIVIDADE',$row)?$row['TIPO_ACTIVIDADE']:'-';
      $json['actividade'] = array_key_exists('ACTIVIDADE',$row)?$row['ACTIVIDADE']:"-";
} else {
    header('HTTP/1.1 500 Internal Server Booboo');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode(array('status' => 'error', 'code' => 1337)));
}


$jsonstring = json_encode($json);

header('Content-Type: application/json');

echo ($jsonstring);