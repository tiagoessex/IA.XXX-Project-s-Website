<?php include_once('../../settings/config.php'); ?>
<?php include('../../settings/database.php'); ?>
<?php
// **********************************
//
// SENDS AN EXCEL FILE (denuncias.xlxs)
//    TO A TARGET MACHINE, THROUGH A POST METHOD
//    THIS TARGET MACHINE SHOULD CONTAIN THE CLASSIFIER 2 TRAINER,
//
//  IF THE TIME INTERVAL IS LARGE, 
//  IT CAN TAKE QUITE A WHILE FOR THE FILE TO BE SEND
//
//  it should be called right after the file's creation
//  ATT:  it doesn't check if the file exists or not
//        it just assumes its existence
//
//  called by: denunciasexporttrain.php
//
// **********************************
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  header("HTTP/1.1 405 Method Not Allowed");
  exit();
}

$filename = trim($_POST['filename']);


// default is 128M
// but all complaint from 1999 to 2019 is more than that
ini_set('memory_limit', '1024M'); 
// the query +  excel build can take a lot longer than 30s
set_time_limit(600);



 $pathToDocument = './' . $filename;
  $docData = file_get_contents($pathToDocument);
  $base64Doc = base64_encode($docData);

  $postData = array(
    'number' => '12025550108',
    'document' => $base64Doc,
    'filename' => $filename
  );
  $headers = array(
    'Content-Type: application/json',
  );
  $url = CLASSIFICADOR_LUIS . 'getfile';
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
  $a = curl_exec($ch);
  curl_close($ch);

unlink($pathToDocument);

header('Content-Type: application/json');

if ($a)
  echo (json_encode(array('status' => 'OK')));
else
  echo (json_encode(array('status' => 'ERROR')));