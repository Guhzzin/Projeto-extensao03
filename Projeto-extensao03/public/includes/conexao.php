<?php
$host = "db";          // nome do serviço docker
$user = "root";
$password = "root";
$database = "meu_banco";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

