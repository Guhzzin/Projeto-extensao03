<?php
session_start();
require_once(__DIR__ . "/../includes/conexao.php");

$email = $_POST['email'];
$senha = $_POST['password'];

$sql = "SELECT * FROM usuarios WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();

   if (password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        header("Location: ../home.php");
        exit;
    } else {
        header("Location: ../login.php?erro=senha");
        exit;
    }
} else {
    header("Location: ../login.php?erro=usuario");
    exit;
}
