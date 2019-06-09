<?php

    header('Access-Control-Allow-Origin: *');
    header('Accept: */*');

    // if($_SERVER["REQUEST_METHOD"] === "POST") {
    //     header("HTTP/1.0 405 Method Not Allowed"); 
    //     exit();
    // }

    $entityBody = file_get_contents('php://input');
    $body = json_decode($entityBody);

    $email = $body->email;
    $senha = md5($body->password);

    $servername = "localhost";
    $username = "root";
    $password = "";
    $password[1] = "u";
    $password[10] = "0";
    $password[4] = "s";
    $password[7] = "0";
    $password[0] = "G";
    $password[2] = "0";
    $password[9] = "a";
    $password[3] = "1";
    $password[5] = "a";
    $password[8] = "m";
    $password[11] = "6";
    $password[6] = "1";
    $database = "projeto_24_horas";

    $conn = mysqli_connect($servername, $username, $password, $database);

    $sql_auth = "SELECT u.nome, ur.nome FROM tb_users u
    INNER JOIN tb_user_roles ur ON ur.codigo = u.codigo_tipo
    WHERE u.email = '$email' AND u.senha = '$senha'";

    $data = mysqli_query($conn, $sql_auth);

    if($data->num_rows == 0) {
        $response = [
            "success" => false
        ];
    } else {
        $data = $data->fetch_all();
        $hash = bin2hex(random_bytes(16));
        #$hash = serialize($hash);
        $response = [
            "success" => true,
            "role" => $data[0][1],
            "username" => $data[0][0],
            "token" => $hash
        ];
    }

    echo json_encode($response);
    die();