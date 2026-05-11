<?php
$conn = new mysqli("db", "root", "root", "meu_banco");

if ($conn->connect_error) {
    die("Erro: " . $conn->connect_error);
}

echo "Banco conectado!";