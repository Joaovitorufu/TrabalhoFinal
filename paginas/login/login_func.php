<?php

function checkLogin($pdo, $email, $senha)
{
  $sql = <<<SQL
    SELECT senha_hash
    FROM pessoa INNER JOIN funcionario ON pessoa.codigo = funcionario.codigo
    WHERE email = ?
    SQL;

  try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $row = $stmt->fetch();
    if (!$row)
      return false;
    else
      $hash = password_verify($senha, $row['senha_hash']);
      return $hash;
  } 
  catch (Exception $e) {
    exit('Falha inesperada: ' . $e->getMessage());
  }
}

$errorMsg = "";
$sucessoMsg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  require "conexaoMysql.php";
  $pdo = mysqlConnect();

  $email = $senha = "";

  if (isset($_POST["email"]))
    $email = $_POST["email"];
  if (isset($_POST["senha"]))
    $senha = $_POST["senha"];

  if (checkLogin($pdo, $email, $senha))  {
    $sucessoMsg = "Dados corretos";
    echo $sucessoMsg;
  } else
    $errorMsg = "Dados incorretos";
    echo $errorMsg;
}
