<?php include_once('settings/config.php'); ?>
<?php include_once('settings/database.php'); ?>
<?php
// only for dev
// replace this file with a proper one, with forms and sh*t
$username = 'XXX';
$password = password_hash("admin999666333+", PASSWORD_DEFAULT);
$nome = 'Utilizador Teste2';
$is_operacional = 0;
$ID_UO = 'UO1';

$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}
$sql = "INSERT INTO users (username, password, nome, is_operacional, ID_UO)
VALUES ('" . $username ."', '" . $password . "', '" . $nome . "', '" . $is_operacional . "', '" . $ID_UO . "')";
if ($conn->query($sql) === TRUE) {
    echo "New user created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
