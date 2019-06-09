<?php

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


    $sql = "SELECT * FROM tb_dia WHERE maquina = 'SCS6033'";
    $result = mysqli_query($conn, $sql);
    $dados_dias = [];
    while($row = $result->fetch_assoc()) {
        $dados_dias[] = $row;
    }

    foreach($dados_dias as $key => &$data) {
        $sql = "SELECT de.tempo, e.codigo_erro, e.nome FROM tb_dia_erro de
        INNER JOIN tb_erros e ON e.codigo = de.codigo_erro
        WHERE de.codigo_dia = " . $data["codigo"];

        $result = mysqli_query($conn, $sql);
        while($erros_dia = $result->fetch_assoc()) {
            if($dados_dias["tempo"] != "0") {
                $data["erros"][] = [
                    "codigo_erro" => $erros_dia["codigo_erro"],
                    "nome" => utf8_encode($erros_dia["nome"]),
                    "tempo" => $erros_dia["tempo"]
                ];
            }
        }
    }

    $response = [];
    foreach ($dados_dias as $key => $dado) {
        $response[$dado["maquina"]]["tempo_disponivel"] += $dado["tempo_disponivel"];
        $response[$dado["maquina"]]["total_calendario"] += $dado["tempo_total_calendario"];
    }
    $response[$dado["maquina"]]["total_dias"] = count($dados_dias);
    $response[$dado["maquina"]]["nome_maquina"] = $dado["maquina"];

    echo json_encode($response);
    die();