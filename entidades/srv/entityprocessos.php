<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
// RETURN ALL PROCESSES OF A GIVEN ENTITY
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
    ep.processo_id_processo as ID_PROCESSO,
    papeis.desc_papel as PAPEL_NO_PROCESSO,
    procs.NUP as NUP,
    procs.descricao as DESCRICAO,
    procs.dt_inicio as DT_INICIO,
    procs.dt_fim as DT_FIM,
    procs.dt_situacao as DT_SITUACAO,
    procs.dt_envio_destino as DT_ENVIO,
    estadios.desc_estadio AS ESTADO,
    tipos_proc.desc_processo as TIPO_PROCESSO,
    TP.EPIGRAFE AS INFRACCAO
from 
    entidade_processo ep
left join PAPEIS_ENT papeis on (papeis.id_papel = ep.PAPEL_ENT_ID_PAPEL)
left join PROCESSOS procs on (procs.id_processo = ep.processo_id_processo)
left join ESTADIOS_PROC estadios on (estadios.id_estadio = procs.TIPO_PROC_ID_TP_PROCESSO)
left join TIPOS_PROC   on (tipos_proc.id_tp_processo = procs.TIPO_INFR_ID_INFRACCAO)
left join TIPO_INFRACCAO TP on (TP.ID_INFRACCAO = procs.TIPO_INFR_ID_INFRACCAO)
where
    ep.entidade_id_entidade = ". $id;


try {  
  $result=$conn->query($query);  
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}



$json = array();
foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row) {
  
    $json[]= array(
      'ID_PROCESSO' => array_key_exists('ID_PROCESSO',$row)?$row['ID_PROCESSO']:"-",
      'PAPEL_NO_PROCESSO' => array_key_exists('PAPEL_NO_PROCESSO',$row)?$row['PAPEL_NO_PROCESSO']:"-",
      'NUP' => array_key_exists('NUP',$row)?$row['NUP']:"-",
      'DESCRICAO' => array_key_exists('DESCRICAO',$row)?$row['DESCRICAO']:"-",
      'DT_INICIO' => array_key_exists('DT_INICIO',$row)?$row['DT_INICIO']:"-",
      'DT_FIM' => array_key_exists('DT_FIM',$row)?$row['DT_FIM']:"-",
      'DT_SITUACAO' => array_key_exists('DT_SITUACAO',$row)?$row['DT_SITUACAO']:"-",
      'DT_ENVIO' => array_key_exists('DT_ENVIO',$row)?$row['DT_ENVIO']:"-",
      'ESTADO' => array_key_exists('ESTADO',$row)?$row['ESTADO']:"-",
      'TIPO_PROCESSO' => array_key_exists('TIPO_PROCESSO',$row)?$row['TIPO_PROCESSO']:"-",
      'INFRACCAO' => array_key_exists('INFRACCAO',$row)?$row['INFRACCAO']:"-",
    );//2709137
}


$jsonstring = json_encode($json);

header('Content-Type: application/json');

echo ($jsonstring);