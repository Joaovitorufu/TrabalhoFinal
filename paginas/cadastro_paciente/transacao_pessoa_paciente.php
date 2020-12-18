<?php

require "conexaoMysql.php";
$pdo = mysqlConnect();

$nome = $email = $tel = $cep = "";
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

$peso = $alt = $tiposangue = "";
if (isset($_POST["peso"])) $peso = $_POST["peso"];
if (isset($_POST["alt"])) $alt = $_POST["alt"];
if (isset($_POST["tiposangue"])) $tiposangue = $_POST["tiposangue"];

$sql1 = <<<SQL
  INSERT INTO pessoa (nome, email, telefone, 
  cep, logradouro, bairro, cidade, estado)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?)
  SQL;

$sql2 = <<<SQL
  INSERT INTO paciente 
    (peso, altura, tipo_sanguineo, codigo)
  VALUES (?, ?, ?, ?)
  SQL;

try {
  $pdo->beginTransaction();

  $stmt1 = $pdo->prepare($sql1);
  if (!$stmt1->execute([
    $nome, $email, $tele,
    $cep, $logra, $bairro, $cidade, $estado
  ])) throw new Exception('Falha na primeira inserção');

  $codNovaPessoa = $pdo->lastInsertId();
  $stmt2 = $pdo->prepare($sql2);
  if (!$stmt2->execute([
    $peso, $alt, $tiposangue, $codNovaPessoa
  ])) throw new Exception('Falha na segunda inserção');

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
