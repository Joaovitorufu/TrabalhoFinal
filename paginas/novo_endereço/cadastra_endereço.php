<?php

require "conexaoMysql.php";
$pdo = mysqlConnect();

$cep = "";
$logra = $bairro = $cidade = "";
$estado = "";
if (isset($_POST["cep"])) $cep = $_POST["cep"];
if (isset($_POST["logra"])) $logra = $_POST["logra"];
if (isset($_POST["bairro"])) $bairro = $_POST["bairro"];
if (isset($_POST["cidade"])) $cidade = $_POST["cidade"];
if (isset($_POST["estado"])) $estado = $_POST["estado"];


try {

    $sql = <<<SQL
    -- Repare que a coluna Id foi omitida por ser auto_increment
    INSERT INTO base_enderecos_ajax (cep, logradouro, bairro, cidade, 
    estado)
    VALUES (?, ?, ?, ?, ?)
    SQL;
  
    // Neste caso utilize prepared statements para prevenir
    // ataques do tipo SQL Injection, pois precisamos
    // cadastrar dados fornecidos pelo usuĂ¡rio 
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      $cep, $logra, $cidade, $bairro,
      $estado
    ]);
  
    header("location: ../home/index.html");
    exit();
  } 
  catch (Exception $e) {  
    //error_log($e->getMessage(), 3, 'log.php');
    if ($e->errorInfo[1] === 1062)
      exit('Dados duplicados: ' . $e->getMessage());
    else
      exit('Falha ao cadastrar os dados: ' . $e->getMessage());
  }
