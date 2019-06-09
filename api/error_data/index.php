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


    $get_request = $_GET;

    $sql = "SELECT * FROM tb_dia" . (isset($_GET["maquina"])? " WHERE maquina = '" . $_GET['maquina'] . "'": "");
    $result = mysqli_query($conn, $sql);
    $dados_dias = [];
    while($row = $result->fetch_assoc()) {
        $dados_dias[] = $row;
    }

    $array_erros = [];
    foreach($dados_dias as $key => &$data) {
        $sql = "SELECT de.tempo, e.codigo_erro, e.nome FROM tb_dia_erro de
        INNER JOIN tb_erros e ON e.codigo = de.codigo_erro
        WHERE de.codigo_dia = " . $data["codigo"];

        $result = mysqli_query($conn, $sql);
        while($erros_dia = $result->fetch_assoc()) {
            if($erros_dia["tempo"] != "0") {
                $array_erros[] = [
                    "codigo_erro" => $erros_dia["codigo_erro"],
                    "nome" => utf8_encode($erros_dia["nome"]),
                    "tempo" => intval($erros_dia["tempo"])
                ];
            }
        }
    }

    function getDateWiseScore($data) {
        $groups = array();
        foreach ($data as $item) {
            $key = $item['codigo_erro'];
            if (!array_key_exists($key, $groups)) {
                $groups[$key] = array(
                    'codigo_erro' => $item['codigo_erro'],
                    'tempo' => $item['tempo'],
                    'nome' => $item['nome'],
                );
            } else {
                $groups[$key]['tempo'] = $groups[$key]['tempo'] + $item['tempo'];
            }
        }
        return $groups;
    }

    $array_erros = getDateWiseScore($array_erros);

    $new_array = [];
    foreach ($array_erros as $key => $value) {
        $new_array[] = $value;
    }

    usort($new_array, function($a, $b) {
        return intval($a['codigo_erro']) - intval($b['codigo_erro']);
    });
    usort($new_array, function($a, $b) {
        return intval($b['tempo']) - intval($a['tempo']);
    });

    echo json_encode($new_array);
    die();