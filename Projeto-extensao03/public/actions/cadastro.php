<?php
require_once(__DIR__ . "/../includes/conexao.php");
$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = password_hash($_POST['password'], PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha')";

if ($conn->query($sql)){
  // Pega o ID do usuário que acabou de ser criado
    $novo_usuario_id = $conn->insert_id;
    
    // cria as primeiras missões para ele automaticamente
    $sql_tarefa1 = "INSERT INTO tarefas (usuario_id, titulo, descricao, status) VALUES ($novo_usuario_id, 'Adição Iniciante', 'Missão básica de soma', 'pendente')";
    $sql_tarefa2 = "INSERT INTO tarefas (usuario_id, titulo, descricao, status) VALUES ($novo_usuario_id, 'Subtração Iniciante', 'Missão básica de subtração', 'pendente')";
    
    $conn->query($sql_tarefa1);
    $conn->query($sql_tarefa2);

    //  bem-sucedido
    header("Location: ../login.php?sucesso=1");
    exit;
} else {
    // Erro 
    echo "Erro: " . $sql . "<br>" . $conn->error;
}
