<?php

require "conexaoMysql.php";
$pdo = mysqlConnect();

$nome = $email = $tele = $cep =  "";
$logra = $bairro = $cidade = "";
$estado = "";
if (isset($_POST["nome"])) $nome = $_POST["nome"];
if (isset($_POST["email"])) $email = $_POST["email"];
if (isset($_POST["tele"])) $tele = $_POST["tele"];
if (isset($_POST["cep"])) $cep = $_POST["cep"];
if (isset($_POST["logra"])) $logra = $_POST["logra"];
if (isset($_POST["bairro"])) $bairro = $_POST["bairro"];
if (isset($_POST["cidade"])) $cidade = $_POST["cidade"];
if (isset($_POST["estado"])) $estado = $_POST["estado"];

$inicio = $salario = $senha = "";
if (isset($_POST["inicio"])) $inicio = $_POST["inicio"];
if (isset($_POST["salario"])) $salario = $_POST["salario"];
if (isset($_POST["senha"])) $senha = $_POST["senha"];

// calcula um hash de senha seguro para armazenar no BD
$hashsenha = password_hash($senha, PASSWORD_DEFAULT);

  $sql1 = <<<SQL
  -- Repare que a coluna Id foi omitida por ser auto_increment
  INSERT INTO pessoa (nome, email, telefone, cep, 
  logradouro, bairro, cidade, estado)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?)
  SQL;

  $sql2 = <<<SQL
  INSERT INTO funcionario 
    (data_contrato, salario, senha_hash, codigo)
  VALUES (?, ?, ?, ?)
  SQL;

 
  try {
    $pdo->beginTransaction();
  
    
    $stmt1 = $pdo->prepare($sql1);
    if (!$stmt1->execute([
        $nome, $email, $tele,
        $cep, $logra, $bairro, $cidade, $estado
    ])) throw new Exception('Falha na primeira inserção');
  
  
    $codigo = $pdo->lastInsertId();
    $stmt2 = $pdo->prepare($sql2);
    if (!$stmt2->execute([
      $inicio, $salario, $hashsenha, $codigo
    ])) throw new Exception('Falha na segunda inserção');
  
    // Efetiva as operações
    $pdo->commit();
  
    header("location: ../home/index.html");
    exit();
  } 
  catch (Exception $e) {
    $pdo->rollBack();
    if ($e->errorInfo[1] === 1062)
      exit('Dados duplicados: ' . $e->getMessage());
    else
      exit('Falha ao cadastrar os dados: ' . $e->getMessage());
  }
