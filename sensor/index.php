<?php

    function curl($url, $method, $post_data, $headers) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        
        // Set HTTP Header for POST request 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload))
        );
    }

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


    $maquinas = [
        "SCS6033",
        "SCS5052",
        "SCS5104",
        "PE10",
        "SCS1104",
        "CAN3081",
        "CAN1081",
        "PE20",
        "CAN8042",
        "DG01",
        "JK10",
        "JK20"
    ];

    $sql = "SELECT * FROM tb_erros";
    $result = mysqli_query($conn, $sql);
    $erros = [];
    while($row = $result->fetch_assoc()) {
        $erros[] = $row;
    }

    $maquina = $maquinas[mt_rand(0, count($maquinas)-1)];
    $erro = $erros[mt_rand(0, count($erros)-1)];
    $codigo_erro = $erro["codigo_erro"];
    $nome_erro = utf8_encode($erro["nome"]);
    $tempo = mt_rand(0, 400);

    var_dump($tempo);