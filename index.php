<?php

    require_once 'vendor/autoload.php';

    die();

    use Aspera\Spreadsheet\XLSX\Reader;
    use Aspera\Spreadsheet\XLSX\SharedStringsConfiguration;
    
    $data_file = "Dados.xlsx";
    
    $options = array(
        'TempDir'                    => '/tmp',
        'SkipEmptyCells'             => false,
        'ReturnDateTimeObjects'      => true,
        'SharedStringsConfiguration' => new SharedStringsConfiguration(),
        'CustomFormats'              => array(20 => 'hh:mm')
    );
    
    $reader = new Reader($options);
    $reader->open($data_file);

    $dados = [];
    foreach ($reader as $key => $row) {
        if($key > 1) {
            $dados[] = $row;
        }
    }
    $reader->close();


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

    foreach($dados as $key => $dado_dia) {
        $maquina = $dado_dia[0];
        $dia = $dado_dia[1]->format("Y-m-d");
        $velocidade_colagem = $dado_dia[2] != ""? $dado_dia[2]: 0;
        $obs = $dado_dia[20];
        $metros_bons = $dado_dia[21];
        $metros_refugados = $dado_dia[22];
        $total_metros = $dado_dia[23];
        $tempo_paradas_nao_programadas = $dado_dia[24];
        $tempo_paradas_programadas = $dado_dia[25];
        $tempo_real_operacao = $dado_dia[26];
        $tempo_disponivel = $dado_dia[27];
        $tempo_ciclo = $dado_dia[28];
        $tempo_total_calendario = $dado_dia[29];

        // Insere no banco
        $sql = "INSERT INTO `tb_dia`(`maquina`, `data`, `velocidade_colagem`, `obs`, `metros_bons`, `metros_refugados`, `total_metros`, `tempo_paradas_nao_programadas`, `tempo_paradas_programadas`, `tempo_real_operacao`, `tempo_disponivel`, `tempo_ciclo`, `tempo_total_calendario`) VALUES ('$maquina', '$dia', $velocidade_colagem, '$obs',$metros_bons,$metros_refugados,$total_metros,$tempo_paradas_nao_programadas,$tempo_paradas_programadas,$tempo_real_operacao,$tempo_disponivel,$tempo_ciclo,$tempo_total_calendario)";

        if(mysqli_query($conn, $sql)) {
            $id_dia = mysqli_insert_id($conn);

            for($i = 3; $i <= 19; $i++) {
                $tempo_erro = floatval($dado_dia[$i]);
                $id_erro = $i - 2;
                
                $sql = "INSERT INTO `tb_dia_erro`(`codigo_dia`, `codigo_erro`, `tempo`) VALUES ($id_dia, $id_erro, $tempo_erro)";
                mysqli_query($conn, $sql);
            }
        } else {
            echo mysqli_error($conn);
        }
    }
