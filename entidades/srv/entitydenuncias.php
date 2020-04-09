<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
// RETURN ALL COMPLAINS OF A GIVEN ENTITY
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
SELECT
  ID_DENUNCIA AS ID_DENUNCIA,
  NID,
  COMP.DESIGNACAO AS COMPETENCIA,
  ED.DESCRICAO AS ESTADO,
  EA.DESIGNACAO AS ESTADO_DE_AVERIGUACAO,
  DT_ARQUIVO AS DATA_ARQUIVO,
  GetOrganogramaPath(ID_UO_DESTINO) AS UO_DESTINO,
  DT_ENVIO AS DATA_ENVIO,
  DT_AVERIGUACAO,
  GetOrganogramaPath(ID_LOCAL_REGISTO) AS LOCAL_REGISTO,
  TD.DESC_TP_DOC AS TIPO,
  
  -- REMETENTE
  GetDescTipoEntidade(ENT_REM.TIPO_ENT_ID_TP_ENT) AS TIPO_REMETENTE,
  ENT_REM.NOME AS REMETENTE,
  ENT_REM.EMAIL AS EMAIL,
  CASE
    WHEN REMETIDA_DENUNCIANTE = 'T' THEN 'SIM'
    ELSE 'NÃO'
  END REMETIDA_POR_DENUNCIANTE,
  
  -- DENUNCIANTE
  GetDescTipoEntidade(ENT_DEN.TIPO_ENT_ID_TP_ENT) AS TIPO_DENUNCIANTE,
  ENT_DEN.NOME AS DENUNCIANTE,
  MNP_DEN.DESC_MOT_N_PREENCH AS MOTIVO_NAO_PREENCH_DEN,
  
  -- ENTIDADE VISADA
  ENT_VIS.NOME AS ENTIDADE_VISADA,
  MNP_VIS.DESC_MOT_N_PREENCH AS MOTIVO_NAO_PREENCH_ENT,
  ENT_VIS.MORADA AS MORADA,
  CONCAT (ACT.DESIGNACAO,' (', ACT.CODIGO,')') AS ACTIVIDADE,
    
  GetOrganogramaPath(ID_AREA_GEOGRAFICA_DR) AS AREA_GEOGRAFICA_DR,
  DESC_CONTEUDO AS CONTEUDO,
  GetClassConteudoDenunciaStr(ID_DENUNCIA) AS CLASSIFICACAO_CONTEUDO,
  NUMERO_OFICIOS_DENUNCIANTE AS N_OFICIOS_PARA_DENUNCIANTE,
  NUMERO_OFICIOS_AUTORIDADE AS N_AUTORIDADES_OFICIOS, 
  GetOficiosStr(ID_DENUNCIA,'R') AS OFICIOS_PARA_DENUNCIANTE, 
  GetProdutosStr(ID_DENUNCIA) AS PRODUTOS,
  
  GetInfraccoesDenunciasStr(ID_DENUNCIA) AS INFRACCOES,
  
  GetCorrespFiscStr(ID_DENUNCIA) AS FISCALIZACOES_RELACIONADAS,
  GetCorrespProcessosStr(ID_DENUNCIA) AS PROCESSOS_RELACIONADOS,
  
  DT_REGISTO AS DATA_REGISTO,
  DEN.DT_EDICAO AS DATA_EDICAO,
  A.DESC_ASSUNTO AS DESC_ASSUNTO,
  
 
  EMAIL_CONTENT AS EMAIL_CONTENT
  
  
FROM DENUNCIAS DEN
LEFT JOIN COMPETENCIA COMP ON (COMP.ID_COMP = DEN.ID_COMPETENCIA)
LEFT JOIN ESTADO_DENUNCIA ED ON (ED.ID = DEN.ID_ESTADO_DENUNCIA)
LEFT JOIN ESTADO_AVERIGUACAO EA ON (EA.ID_EST_AVG = DEN.ID_ESTADO_AVERIGUACAO)
LEFT JOIN TIPO_DOCUMENTO TD ON (TD.COD_TP_DOC = DEN.ID_TIPO_DOCUMENTO)
LEFT JOIN ENTIDADE ENT_REM ON (ENT_REM.ID_ENTIDADE = DEN.ID_REMETENTE)
LEFT JOIN ENTIDADE ENT_DEN ON (ENT_DEN.ID_ENTIDADE = DEN.ID_DENUNCIANTE)
LEFT JOIN ENTIDADE ENT_VIS ON (ENT_VIS.ID_ENTIDADE = DEN.ID_ENTIDADE_VISADA)
LEFT JOIN MOTIVOS_N_PREENCH MNP_DEN ON (MNP_DEN.ID_MOT_N_PREENCH = DEN.ID_MOTIVO_NAO_PREENCH_DEN)
LEFT JOIN MOTIVOS_N_PREENCH MNP_VIS ON (MNP_VIS.ID_MOT_N_PREENCH = DEN.ID_MOTIVO_NAO_PREENCH_DEN)
LEFT JOIN ASSUNTOS A ON (A.ID_ASSUNTO = DEN.ID_ASSUNTO)
LEFT JOIN CORRESP_ACTIVIDADES CA ON (CA.CORRESP_ID_CORRESP = DEN.ID_DENUNCIA)
LEFT JOIN ACTIVIDADE ACT ON (ACT.ID_ACT = CA.ACT_ID_ACT)
WHERE
   ENT_VIS.id_entidade = ". $id;


try {  
  $result=$conn->query($query);  
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}

$json = array();

foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row) {
 
    $json[]= array(
      'ID_DENUNCIA' => array_key_exists('ID_DENUNCIA',$row)?$row['ID_DENUNCIA']:"-",
      'NID' => array_key_exists('NID',$row)?$row['NID']:"-",
      'COMPETENCIA' => array_key_exists('COMPETENCIA',$row)?$row['COMPETENCIA']:"-",
      'ESTADO' => array_key_exists('ESTADO',$row)?$row['ESTADO']:"-",
      'ESTADO_DE_AVERIGUACAO' => array_key_exists('ESTADO_DE_AVERIGUACAO',$row)?$row['ESTADO_DE_AVERIGUACAO']:"-",
      'REMETIDA_POR_DENUNCIANTE' => array_key_exists('REMETIDA_POR_DENUNCIANTE',$row)?$row['REMETIDA_POR_DENUNCIANTE']:"-",
      'DATA_DE_ENVIO' => array_key_exists('DATA_ENVIO',$row)?$row['DATA_ENVIO']:"-",
      'TIPO' => array_key_exists('TIPO',$row)?$row['TIPO']:"-",
      'DENUNCIANTE' => array_key_exists('DENUNCIANTE',$row)?$row['DENUNCIANTE']:"-",
      'TIPO_DENUNCIANTE' => array_key_exists('TIPO_DENUNCIANTE',$row)?$row['TIPO_DENUNCIANTE']:"-",
      'CONTEUDO' => array_key_exists('CONTEUDO',$row)?$row['CONTEUDO']:"-",
      'ACTIVIDADE' => array_key_exists('ACTIVIDADE',$row)?$row['ACTIVIDADE']:"-",

      'INFRACCOES' => array_key_exists('INFRACCOES',$row)?$row['INFRACCOES']:"",
      'FISCALIZACOES_RELACIONADAS' => array_key_exists('FISCALIZACOES_RELACIONADAS',$row)?$row['FISCALIZACOES_RELACIONADAS']:"",
      'PROCESSOS_RELACIONADOS' => array_key_exists('PROCESSOS_RELACIONADOS',$row)?$row['PROCESSOS_RELACIONADOS']:"",
      'CLASSIFICACAO_CONTEUDO' => array_key_exists('CLASSIFICACAO_CONTEUDO',$row)?$row['CLASSIFICACAO_CONTEUDO']:"",
      'HAS_MESSAGE' => (strlen($row['EMAIL_CONTENT']) > 0?1:0)
    );//2709137
}


$jsonstring = json_encode($json);

header('Content-Type: application/json');

echo ($jsonstring);