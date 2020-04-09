<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
/*
    nota:
        sum(DISTINCT dn.populacao) AS POPULACAO,
        sum(DISTINCT dn.area) AS AREA

        porque é estatisticamente improvavel que existam 2
        distritos/concelhos/freguesias com valores iguais.

*/
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  header("HTTP/1.1 405 Method Not Allowed");
  exit();
}

if (isset($_POST['id_nuts']))
    $id_nuts = trim($_POST['id_nuts']);
if (isset($_POST['level']))
    $level = trim($_POST['level']);
if (isset($_POST['densityofwhat']))
    $densityofwhat = trim($_POST['densityofwhat']);
if (isset($_POST['nuts_or_uo']))
  $nuts_or_uo = trim($_POST['nuts_or_uo']);
else
  $nuts_or_uo = "true";

$ano_start = trim($_POST['ano_start']);
$mes_start = trim($_POST['mes_start']);
$ano_end = trim($_POST['ano_end']);
$mes_end = trim($_POST['mes_end']);

$org = trim($_POST['org']);
$particulares = trim($_POST['particulares']);

$d_indeterminada = trim($_POST['d_indeterminada']);
$d_XXX = trim($_POST['d_XXX']);
$d_XXX_outra_ent = trim($_POST['d_XXX_outra_ent']);
$d_XXX_trib = trim($_POST['d_XXX_trib']);
$d_outra_ent = trim($_POST['d_outra_ent']);
$d_trib = trim($_POST['d_trib']);
$d_trib_outra = trim($_POST['d_trib_outra']);


$database = new Database();

$conn = $database->getConnection();
if (!$conn) die("error");


// ************************************************
// ************************************************
// TODO: SIMPLIFY
// ************************************************
// ************************************************

$column = '';
if ($densityofwhat == 'entidades') {
    $column = 'entidades';
} else if ($densityofwhat == 'denuncias') {
    $column = 'denuncias';
} else if ($densityofwhat == 'reclamacoes') {
    $column = 'reclamacoes';
} else if ($densityofwhat == 'informacoes') {
    $column = 'informacoes';
} else if ($densityofwhat == 'fiscalizacoes') {
    $column = 'fiscalizacoes';
} else if ($densityofwhat == 'processos') {
    $column = 'processos';
}

$entidade_column = 'entidades';
if ($org === 'true' && $particulares === 'true') {
    $entidade_column = 'entidades';
} else if ($org === 'true' && $particulares != 'true') {
    $entidade_column = 'entidades_org';
} else if ($particulares === 'true' && $org != 'true') {
    $entidade_column = 'entidades_individuo';
}

if ($column == 'entidades') {
    $column = $entidade_column;
}

//(SUM(dd.denuncias_c1)+SUM(dd.denuncias_c2)+SUM(dd.denuncias_c3)) AS total


$query_densidade = " sum(data_dados." . $column . ") as DENSITY, ";
// denuncias filters

if ($column == 'denuncias') {
    $total = 'sum(';
    if (
        $d_indeterminada  != 'true' or 
        $d_XXX  != 'true' or 
        $d_XXX_outra_ent  != 'true' or 
        $d_XXX_trib  != 'true' or 
        $d_outra_ent  != 'true' or 
        $d_trib  != 'true' or 
        $d_trib_outra  != 'true' 
    ) {

        if ($d_indeterminada  === 'true') {
            $total .= 'data_dados.denuncias_c1';
        }
        if ($d_XXX  === 'true') {
            $total .= ' data_dados.denuncias_c2';
        }
        if ($d_XXX_outra_ent === 'true') {
            $total .= ' data_dados.denuncias_c3';
        }
        if ($d_XXX_trib  === 'true') {
            $total .= ' data_dados.denuncias_c4';
        }
        if ($d_outra_ent  === 'true') {
            $total .= ' data_dados.denuncias_c5';
        }
        if ($d_trib === 'true') {
            $total .= ' data_dados.denuncias_c6';
        }
        if ($d_trib_outra  === 'true') {
            $total .= ' data_dados.denuncias_c7';
        }
        $total = str_replace(' ', '+',$total); // add '+'
        $query_densidade = ' ' . $total . ") as DENSITY, ";
    }
}





if ($nuts_or_uo === "true") {

  if ($level == 0) {
    $query = "
        select 
            substr(data_dados.id_nuts,1,2) as ID,
            " . $query_densidade . "
            sum(data_dados." . $entidade_column . ") as ENTIDADES,
            sum(DISTINCT dn.populacao) AS POPULACAO,
            sum(DISTINCT dn.area) AS AREA
        from 
            data_dados
        LEFT JOIN data_nuts dn ON (dn.id = data_dados.id_nuts)
        where
data_dados.ID_DATA >= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_start . " AND MES =   " . $mes_start . ") ,(SELECT MIN(ID) FROM DATA_DATA)))
AND
data_dados.ID_DATA <= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_end . " AND MES =   " . $mes_end . ") ,(SELECT MAX(ID) FROM DATA_DATA)))
        GROUP BY 
            substr(data_dados.id_nuts,1,2)
    ";
  } else if ($level == 1) {
    $query = "
        SELECT 
            substr(data_dados.id_nuts,1,4) as ID,
            " . $query_densidade . "
            sum(data_dados." . $entidade_column . ") as ENTIDADES,
            sum(DISTINCT dn.populacao) AS POPULACAO,
            sum(DISTINCT dn.area) AS AREA
        FROM 
            data_dados 
        LEFT JOIN data_nuts dn ON (dn.id = data_dados.id_nuts)
        where
data_dados.ID_DATA >= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_start . " AND MES =   " . $mes_start . ") ,(SELECT MIN(ID) FROM DATA_DATA)))
AND
data_dados.ID_DATA <= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_end . " AND MES =   " . $mes_end . ") ,(SELECT MAX(ID) FROM DATA_DATA)))
        and
            data_dados.id_nuts LIKE '". substr($id_nuts,0,2) . "%'
        GROUP BY 
            substr(data_dados.id_nuts,1,4)
      ";
  } else if ($level == 2) {
    $query = "
        SELECT 
            data_dados.id_nuts as ID,
            " . $query_densidade . "
            sum(data_dados." . $entidade_column . ") as ENTIDADES,
            sum(DISTINCT dn.populacao) AS POPULACAO,
            sum(DISTINCT dn.area) AS AREA
        FROM 
            data_dados
        LEFT JOIN data_nuts dn ON (dn.id = data_dados.id_nuts)
        where
data_dados.ID_DATA >= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_start . " AND MES =   " . $mes_start . ") ,(SELECT MIN(ID) FROM DATA_DATA)))
AND
data_dados.ID_DATA <= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_end . " AND MES =   " . $mes_end . ") ,(SELECT MAX(ID) FROM DATA_DATA)))
        and
            data_dados.id_nuts LIKE '". substr($id_nuts,0,4) . "%'
        GROUP BY 
            data_dados.id_nuts 
    ";
  } else {
    $query = "
        SELECT 
            data_dados.id_nuts as ID,
            " . $query_densidade . "
            sum(data_dados." . $entidade_column . ") as ENTIDADES,
            sum(DISTINCT dn.populacao) AS POPULACAO,
            sum(DISTINCT dn.area) AS AREA
        FROM 
            data_dados 
        LEFT JOIN data_nuts dn ON (dn.id = data_dados.id_nuts)
        where
data_dados.ID_DATA >= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_start . " AND MES =   " . $mes_start . ") ,(SELECT MIN(ID) FROM DATA_DATA)))
AND
data_dados.ID_DATA <= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_end . " AND MES =   " . $mes_end . ") ,(SELECT MAX(ID) FROM DATA_DATA)))
        and
            data_dados.id_nuts = '". $id_nuts . "'
        GROUP BY 
            data_dados.id_nuts  
    ";
  }

} else {

  if ($level == 0) {
    $query = "
        select 
            data_dados.ur as ID,
            " . $query_densidade . "
            sum(data_dados." . $entidade_column . ") as ENTIDADES,
            sum(DISTINCT dn.populacao) AS POPULACAO,
            sum(DISTINCT dn.area) AS AREA
        from 
            data_dados
        LEFT JOIN data_nuts dn ON (dn.id = data_dados.id_nuts)
        where
data_dados.ID_DATA >= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_start . " AND MES =   " . $mes_start . ") ,(SELECT MIN(ID) FROM DATA_DATA)))
AND
data_dados.ID_DATA <= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_end . " AND MES =   " . $mes_end . ") ,(SELECT MAX(ID) FROM DATA_DATA)))
        GROUP BY 
            data_dados.ur
    ";
  } else if ($level == 1) {
    $query = "
        SELECT 
            data_dados.uo as ID,
            " . $query_densidade . "
            sum(data_dados." . $entidade_column . ") as ENTIDADES,
            sum(DISTINCT dn.populacao) AS POPULACAO,
            sum(DISTINCT dn.area) AS AREA
        FROM 
            data_dados 
        LEFT JOIN data_nuts dn ON (dn.id = data_dados.id_nuts)
        where
data_dados.ID_DATA >= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_start . " AND MES =   " . $mes_start . ") ,(SELECT MIN(ID) FROM DATA_DATA)))
AND
data_dados.ID_DATA <= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_end . " AND MES =   " . $mes_end . ") ,(SELECT MAX(ID) FROM DATA_DATA)))
        and
            data_dados.UR = '". $id_nuts. "'
        GROUP BY 
            data_dados.uo
      ";
  } else if ($level == 2) {
    $query = "
        SELECT 
            data_dados.id_nuts as ID,
            " . $query_densidade . "
            sum(data_dados." . $entidade_column . ") as ENTIDADES,
            sum(DISTINCT dn.populacao) AS POPULACAO,
            sum(DISTINCT dn.area) AS AREA
        FROM 
            data_dados
        LEFT JOIN data_nuts dn ON (dn.id = data_dados.id_nuts)
        where
data_dados.ID_DATA >= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_start . " AND MES =   " . $mes_start . ") ,(SELECT MIN(ID) FROM DATA_DATA)))
AND
data_dados.ID_DATA <= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_end . " AND MES =   " . $mes_end . ") ,(SELECT MAX(ID) FROM DATA_DATA)))
         and
            data_dados.UO = '". $id_nuts. "'
        GROUP BY 
            data_dados.id_nuts 
    ";
  } else {
    $query = "
        SELECT 
            data_dados.id_nuts as ID,
            " . $query_densidade . "
            sum(data_dados." . $entidade_column . ") as ENTIDADES,
            sum(DISTINCT dn.populacao) AS POPULACAO,
            sum(DISTINCT dn.area) AS AREA
        FROM 
            data_dados
        LEFT JOIN data_nuts dn ON (dn.id = data_dados.id_nuts)
        where
data_dados.ID_DATA >= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_start . " AND MES =   " . $mes_start . ") ,(SELECT MIN(ID) FROM DATA_DATA)))
AND
data_dados.ID_DATA <= (SELECT COALESCE((SELECT ID FROM DATA_DATA WHERE ANO = " . $ano_end . " AND MES =   " . $mes_end . ") ,(SELECT MAX(ID) FROM DATA_DATA)))
        and
            data_dados.id_nuts LIKE '". substr($id_nuts,0,4) . "%'
        GROUP BY 
            data_dados.id_nuts 
    ";
  }
}



// ************************************************
// ************************************************


try {  
  $result=$conn->query($query);  
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    die($e->getMessage());
}


$json = array();
foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row)
{

    if (array_key_exists('ID',$row) && $row['ID'] == null)
        continue;

    if ($nuts_or_uo === "true") {
        // add trailing 0s to make it 6 chars long
        $id = null;
        if (array_key_exists('ID',$row)) {
          if ($level == 0) {
              $id = $row['ID'] . '0000';
          } else if ($level == 1) {
              $id = $row['ID'] . '00';
          } else {
            $id = $row['ID'];
          }
        } else {
          $id = '000000';
        }
    } else {
         $id = $row['ID'];
    }

    $json[]= array(
      //'id' => array_key_exists('ID',$row)?$row['ID']:'000000',
      'id' => $id,
      'density' => array_key_exists('DENSITY',$row)?$row['DENSITY']:0,
      'entidades' => array_key_exists('ENTIDADES',$row)?$row['ENTIDADES']:-1,
      'area' => array_key_exists('AREA',$row)?$row['AREA']:-1,
      'populacao' => array_key_exists('POPULACAO',$row)?$row['POPULACAO']:-1
    );
}

header('Content-Type: application/json');
//header("Content-Length: 5000");

echo (json_encode($json));