<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
// RETURN ALL INSPECTIONS OF A GIVEN ENTITY
//
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


$id = trim($_POST["id_entidade"]);

$query = "
select 
    fe.fisc_id_fiscalizacao as ID_FISCALIZACAO,
    fisc.nuf as NUF,
    fe.id_alvo as ID_ALVO,
    t_alvos.designacao as TIPO_ALVO,
    t_fisc.designacao as TIPO_FISCALIZACAO,
    a_ops.desc_area_oper as AREA_OPERACIONAL,
    fe.dt_averig as DT_AVERIGUACAO,    
    fe.MOTIV_FISC_ID_MOTIV_FISC as MOTIVO_FISC,
    fe.observacoes as OBSERVACOES,    
    fisc.DT_ENTRADA_QUEIXA as DT_ENTRADA_QUEIXA,
    fisc.ESTADO as ESTADO,
    fe.id_brigade as ID_BRIGADA,
    fisc.num_brig as NUMERO_BRIGADA
from
    fisc_entidade fe
left join TIPOS_ALVOS t_alvos on (t_alvos.id_tipo_alvo = fe.tipo_alvo_id_tipo_alvo)
left join FISCALIZACOES fisc on (fisc.id_fiscalizacao = fe.fisc_id_fiscalizacao)
left join TIPOS_FISCALIZACOES t_fisc on (t_fisc.id_designacao = fisc.ID_DESIGNACAO)
left join AREAS_OPERACIONAIS a_ops  on (a_ops.id_area_oper = fisc.AREA_OP_ID_AREA_OP)

WHERE FE.ENTIDADE_ID_ENTIDADE = ". $id;


try {  
  $result=$conn->query($query);  
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}

$json = array();


$json = array();
foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row) {  
    $json[]= array(
      'ID_FISCALIZACAO' => array_key_exists('ID_FISCALIZACAO',$row)?$row['ID_FISCALIZACAO']:"-",
      'NUF' => array_key_exists('NUF',$row)?$row['NUF']:"-",
      'ID_ALVO' => array_key_exists('ID_ALVO',$row)?$row['ID_ALVO']:"-",
      'TIPO_ALVO' => array_key_exists('TIPO_ALVO',$row)?$row['TIPO_ALVO']:"-",
      'TIPO_FISCALIZACAO' => array_key_exists('TIPO_FISCALIZACAO',$row)?$row['TIPO_FISCALIZACAO']:"-",
      'AREA_OPERACIONAL' => array_key_exists('AREA_OPERACIONAL',$row)?$row['AREA_OPERACIONAL']:"-",
      'DT_AVERIGUACAO' => array_key_exists('DT_AVERIGUACAO',$row)?$row['DT_AVERIGUACAO']:"-",
      'MOTIVO_FISC' => array_key_exists('MOTIVO_FISC',$row)?$row['MOTIVO_FISC']:"-",
      'OBSERVACOES' => array_key_exists('OBSERVACOES',$row)?$row['OBSERVACOES']:"-",
      'DT_ENTRADA_QUEIXA' => array_key_exists('DT_ENTRADA_QUEIXA',$row)?$row['DT_ENTRADA_QUEIXA']:"-",
      'ESTADO' => array_key_exists('ESTADO',$row)?$row['ESTADO']:"-",
      'ID_BRIGADA' => array_key_exists('ID_BRIGADA',$row)?$row['ID_BRIGADA']:"-",
      'NUMERO_BRIGADA' => array_key_exists('NUMERO_BRIGADA',$row)?$row['NUMERO_BRIGADA']:"-",
    );//2709137
}

$jsonstring = json_encode($json);

header('Content-Type: application/json');

echo ($jsonstring);