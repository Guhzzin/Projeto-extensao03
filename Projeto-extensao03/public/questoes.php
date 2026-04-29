<?php

include_once(__DIR__ . "/includes/conexao.php");
include_once(__DIR__ . "/includes/auth.php");

$usuario_id = $_SESSION['usuario_id'];
$xp_atual = 0; // Valor padrão caso seja a primeira vez

// 2. Busca o XP atual do usuário logado no banco de dados
$sql_xp = "SELECT xp FROM usuarios WHERE id = $usuario_id";
$resultado_xp = mysqli_query($conn, $sql_xp);

if ($resultado_xp && mysqli_num_rows($resultado_xp) > 0) {
    $linha = mysqli_fetch_assoc($resultado_xp);
    $xp_atual = $linha['xp'];
}

$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'soma';
$_SESSION['missao_atual'] = $tipo; 

// --- TRAVA DE MISSÃO CONCLUÍDA ---
$busca_titulo = ($tipo == 'soma') ? 'adição' : $tipo;
$sql_check = "SELECT status FROM tarefas WHERE usuario_id = $usuario_id AND (titulo LIKE '%$busca_titulo%' OR titulo LIKE '%soma%')";
$res_check = mysqli_query($conn, $sql_check);

if ($res_check && mysqli_num_rows($res_check) > 0) {
    $tarefa = mysqli_fetch_assoc($res_check);
    if ($tarefa['status'] === 'concluida') {
        // Se já está concluída no banco, expulsa de volta pra home com aviso!
        header("Location: home.php?aviso=ja_concluido");
        exit;
    }
}

// --- Lógica do Progresso Real ---
$objetivo = 5; 

// Se a sessão de progresso não existir, ela começa em 0
$progresso_atual = isset($_SESSION['progresso']) ? $_SESSION['progresso'] : 0;

// Cálculo da porcentagem para o CSS da barra
$porcentagem = ($progresso_atual / $objetivo) * 100;

// Pega o tipo de operação da URL
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'soma';

$num1 = 0;
$num2 = 0;
$sinal = '';
$nome_missao = '';
$resposta_correta = 0;

// Define a lógica com base no tipo da URL
switch ($tipo) {
    case 'subtracao':
        $nome_missao = 'Subtração';
        $sinal = '-';
        $num1 = rand(10, 50); // Número maior
        $num2 = rand(1, $num1); // Número menor ou igual ao primeiro
        $resposta_correta = $num1 - $num2;
        break;

    case 'multiplicacao':
        $nome_missao = 'Multiplicação';
        $sinal = 'x';
        $num1 = rand(2, 10);
        $num2 = rand(2, 10);
        $resposta_correta = $num1 * $num2;
        break;

    case 'divisao':
        $nome_missao = 'Divisão';
        $sinal = '÷';
        $num2 = rand(2, 10); // Divisor
        $resposta_correta = rand(2, 10); // O resultado (quociente)
        $num1 = $num2 * $resposta_correta; // Garante que a divisão seja sempre exata
        break;

    case 'soma':
    default:
        $nome_missao = 'Adição';
        $sinal = '+';
        $num1 = rand(1, 50);
        $num2 = rand(1, 50);
        $resposta_correta = $num1 + $num2;
        break;
}

// segurança do inspecionar
$_SESSION['resposta_correta_atual'] = $resposta_correta;
$_SESSION['missao_atual'] = $tipo; 
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Missão: <?= $nome_missao ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/questoes.css">
</head>

</head>
<body class="bg-light d-flex align-items-center min-vh-100">
    <div class="container fade-in">
        
        <div class="row mb-4 justify-content-between align-items-center">
            
            <div class="col-4 text-start">
                <a href="home.php" class="btn btn-outline-danger btn-sm fw-bold btn-sair rounded-pill px-3">
                    &larr; Abandonar Missão
                </a>
            </div>

            <div class="col-4 text-center">
                <span class="badge bg-warning text-dark p-2 fs-6 rounded-pill shadow-sm">
                    ⭐ XP: <?= $xp_atual ?>
                </span>
            </div>

            <div class="col-4 text-end">
                <span class="badge bg-info p-2 fs-6 rounded-pill shadow-sm">
                    Nível 1: Iniciante
                </span>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-6">
                
               <p class="text-muted mb-1 small fw-bold text-center">
    Progresso (<?= $progresso_atual ?>/<?= $objetivo ?>)
</p>

<div class="progress mb-4" style="height: 15px; border-radius: 10px;">
    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" 
         role="progressbar" 
         style="width: <?= $porcentagem ?>%;" 
         aria-valuenow="<?= $porcentagem ?>" 
         aria-valuemin="0" 
         aria-valuemax="100">
    </div>
</div>

                <div class="card card-desafio">
                    <div class="card-body p-5 text-center">
                        <h4 class="text-uppercase fw-bold text-secondary mb-4">Desafio de <?= $nome_missao ?></h4>
                        
                        <div class="bg-light rounded p-4 mb-4 border">
                            <h1 class="display-1 fw-bold text-primary mb-0">
                                <?= $num1 ?> <?= $sinal ?> <?= $num2 ?>
                            </h1>
                        </div>
                        
                        <form action="validar_resposta.php" method="POST">
                            <div class="mb-4">
                                <input type="number" name="resposta_usuario" class="form-control form-control-lg text-center fw-bold fs-3" placeholder="Sua resposta" required autofocus style="border-width: 3px;">
                            </div>
                            <button type="submit" class="btn btn-success btn-lg w-100 fw-bold fs-5 rounded-pill shadow-sm">
                                ENVIAR RESPOSTA
                            </button>
                        </form>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

</body>
</html>