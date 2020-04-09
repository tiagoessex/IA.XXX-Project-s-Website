<?php include_once('../settings/config.php'); ?>
<?php include('../settings/database.php'); ?>
<?php
// **********************************
//  GIVEN A NUTS ID OR WHETHER IT'S A UO OR UE
//  RETURNS THE MULTIPOLYGON CORRESPONDING TO ITS BORDERS
//
//  INPUTS:
//    - ID_NUTS (self explanatory)
//    - NUTS_OR_UO: nuts code or XXX's operational regions
//    - LEVEL : 
//          if NUTS_OR_UO=true: 0 => distritos | 1 => concelhos | 2 => freguesias
//          if NUTS_OR_UO=false: 0 => unidades regionais | 1 => unidades operacionais | 2 => freguesias
// **********************************
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    die("ONLY POST REQUESTS ARE AUTHORIZED");
}
function processPoly($poly) 
{
  $temp = substr_replace($poly,"",0,7);
  $temp = str_replace('),(', ']],[[', $temp);
  $temp = str_replace('((', '[[[', $temp);
  $temp = str_replace('))', ']]]', $temp);
  $temp = str_replace('],[', ']#[', $temp);
  $temp = str_replace(',', '],[', $temp);
  $temp = str_replace(']#[', '],[', $temp);
  $temp = str_replace(' ', ',', $temp);

  return $temp;
}

if (isset($_POST['id_nuts']))
  $id_nuts = trim($_POST['id_nuts']);
if (isset($_POST['level']))
  $level = trim($_POST['level']);
if (isset($_POST['nuts_or_uo']))
  $nuts_or_uo = trim($_POST['nuts_or_uo']);
else
  $nuts_or_uo = "true";




$database = new Database();

$conn = $database->getConnection();
if (!$conn) die("error");

// if distrito/concelho/freguesias
if ($nuts_or_uo === "true") {
    $query = "
    select 
      nuts_fronteiras.ID as id,
      astext(nuts_fronteiras.POLYGON_REV) as poly,
     GetPath(id,1) as nuts
    from 
      nuts_fronteiras 
    where ";

    if ($level == 0) {
      $query .= " substr(nuts_fronteiras.ID,3,4)='0000' ";
    } else if ($level == 1) {
      $query .= " nuts_fronteiras.ID like '" . substr($id_nuts,0,2) . "%00'
                  and substr(nuts_fronteiras.ID,3,2) <> '00'";
    } else if ($level == 2) {
      $query .= " substr(nuts_fronteiras.ID,1,4) = '" . substr($id_nuts,0,4) . "' and substr(nuts_fronteiras.ID,5,2) <> '00'";
    } else {
       $query .= " nuts_fronteiras.ID ='" . $id_nuts . "' ";
    }
} else {
  // if unidades regionais/operacionais/concelhos
    if ($level == 0) {
      $query = "
        select 
          unidades_regionais_fronteiras.ID_UR as id,
          astext(unidades_regionais_fronteiras.POLYGON_REV) as poly
        from 
          unidades_regionais_fronteiras 
        where 
          1";
    } else if ($level == 1) {
      $query = "
        select 
          UOF.ID_UO as id,
          astext(UOF.POLYGON_REV) as poly
        from 
          unidades_operacionais_fronteiras UOF
        LEFT JOIN unidades_ur_uo UUU ON (UOF.ID_UO = UUU.UO)
        WHERE
          UUU.UR = '" . $id_nuts . "' ";
    } else if ($level == 2) {
      $query = "
        SELECT 
          nuts.ID as id,
          astext(nuts.POLYGON_REV) as poly,
          GetPath(id,'T') as nuts
        from 
          nuts_fronteiras nuts
        LEFT JOIN unidades_uo_nuts uuc ON (uuc.ID_NUTS = nuts.ID)
        WHERE
          uuc.ID_UO ='" . $id_nuts . "' ";
    } else {
      $query = "
        select 
          nuts_fronteiras.ID as id,
          astext(nuts_fronteiras.POLYGON_REV) as poly,
          GetPath(id,'T') as nuts
        from 
          nuts_fronteiras 
        where substr(nuts_fronteiras.ID,1,4) = '" . substr($id_nuts,0,4) . "' and substr(nuts_fronteiras.ID,5,2) <> '00'";
       // die($query);
    }

}



$result=$conn->query($query);


$json = array();

if ($nuts_or_uo === "true" || $level > 1) {
    foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row)
     {
        $name = explode("\\", $row['nuts']);
        $json[]= array(
          'id' => $row['id'],
          'poly' => processPoly($row['poly']),
          'nuts' => end($name)
        );
    }
} else {
    foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row)
    {
        $json[]= array(
          'id' => $row['id'],
          'poly' => processPoly($row['poly'])
        );
    }
}

header('Content-Type: application/json');

echo (json_encode($json));