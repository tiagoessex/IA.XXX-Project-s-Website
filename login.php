<?php include_once('settings/config.php'); ?>
<?php include_once('settings/database.php'); ?>
<?php
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $password = "";
    $username_err = $password_err = "";
    // Check if username is empty   
    if(empty(trim($_POST["username"]))) {
         $username_err = 'Please enter username.';
    } else{
        $username = trim(htmlentities($_POST["username"]));
    }
    // Check if password is empty
    if(empty(trim($_POST['password']))) {
        $password_err = 'Please enter your password.';
    } else{
        $password = trim(htmlentities($_POST['password']));
    }    
    // Validate credentials
    if(empty($username_err) && empty($password_err)) {
        // Create connection
        $conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $query = "SELECT username, password, " . DB_TABLE_USERS . ".nome, is_operacional, unidades_operacionais.ID_UO, unidades_operacionais.NOME, X(`unidades_operacionais`.`COORDENADAS`), Y(`unidades_operacionais`.`COORDENADAS`) 
        FROM " . DB_TABLE_USERS . " 
        LEFT JOIN unidades_operacionais ON " . DB_TABLE_USERS . ".ID_UO = unidades_operacionais.ID_UO 
        WHERE username = ? limit 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $param_username);
        $param_username = $username;
       // $param_password = $password;
        //$param_password = password_hash($password, PASSWORD_DEFAULT);
        //die($param_password); 
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows > 0) {
            //$row = $stmt->fetch_assoc();
            mysqli_stmt_bind_result($stmt, $username, $hashed_password, $nome, $is_op, $uo_id, $uo_nome, $uo_lat, $uo_lng);
            if (mysqli_stmt_fetch($stmt)) {
                if (password_verify($password, $hashed_password))
                {
                    #$stmt->bind_result($user,$pass, $nome, $is_op, $uo_id, $uo_nome, $uo_lat, $uo_lng);
                    #$stmt->fetch();
                    //echo $user;
                    //echo $pass;
                    if(!isset($_SESSION)) {
                        session_start();
                    }
                    $_SESSION['username'] = $username;
                    $user = [
                        'username' => $username,
                        'unidade_id' => $uo_id,
                        'nome' => $nome,
                        'is_operacional' => boolval($is_op),
                        'unidade' => [
                            'nome' => $uo_nome,
                            'lat' => $uo_lat,
                            'lng' => $uo_lng
                            ]
                        ];

                    $_SESSION['user'] = $user;
                    
                    $jwt = generateJWT($user);

                    $_SESSION['jwt'] = $jwt;


                    $isLogIn = 1;
                } else {
                    $isLogIn = 0;
                }
            } else {
                $isLogIn = 0;
            }
        } else {
            $isLogIn = 0;
           // die("Invalid credentials!");
        }
        $stmt->close();
        $conn->close();
    }
}

function generateJWT($user){
    // Create token header as a JSON string
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
    // Create token payload as a JSON string
    $payload = json_encode(['identity' => $user['username'],
    'user_claims' => $user
    ]);
    // Encode Header to Base64Url String
    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
    // Encode Payload to Base64Url String
    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
    // Create Signature Hash
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, JWT_SECRET_KEY, true);
    // Encode Signature to Base64Url String
    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
    // Create JWT
    $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    return $jwt;
}