<?php
require_once(__DIR__ . "/../includes/conexao.php");
$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = password_hash($_POST['password'], PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha')";

header("Location: ../login.php?sucesso=1");
exit;