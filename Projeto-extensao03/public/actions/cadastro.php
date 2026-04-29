<?php
require_once(__DIR__ . "/../includes/conexao.php");
$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = password_hash($_POST['password'], PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha')";

if ($conn->query($sql)){
  // Pega o ID do usuário que acabou de ser criado
    $novo_usuario_id = $conn->insert_id;
    
    // Cria a primeira missão de Adição para ele automaticamente!
    $sql_tarefa = "INSERT INTO tarefas (usuario_id, titulo, descricao, status) 
                   VALUES ($novo_usuario_id, 'Adição', 'Missão básica de soma', 'pendente')";
    $conn->query($sql_tarefa);

    // Cadastro bem-sucedido
    header("Location: ../login.php?sucesso=1");
    exit;
} else {
    // Erro ao cadastrar
    echo "Erro: " . $sql . "<br>" . $conn->error;
}
