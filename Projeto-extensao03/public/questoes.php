<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/conexao.php';

$usuario_id = $_SESSION['usuario_id'];

// 1. Busca XP Real
$res_user = mysqli_query($conn, "SELECT xp FROM usuarios WHERE id = $usuario_id");
$user_data = mysqli_fetch_assoc($res_user);
$xp_atual = $user_data['xp'] ?? 0;

// 2. Configuração da Missão
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'soma';
$dificuldade = isset($_GET['dif']) ? $_GET['dif'] : 'iniciante'; 
$_SESSION['missao_atual'] = $tipo;



// Define o nome correto para buscar na tabela 'tarefas'
$nome_categoria = 'Adição';
if ($tipo === 'subtracao') {
    $nome_categoria = 'Subtração';
} elseif ($tipo === 'multiplicacao') {
    $nome_categoria = 'Multiplicação';
}
// Monta o nome exato.
$nome_tarefa_exata = $nome_categoria . " " . ucfirst($dificuldade);

// Usa "=" no lugar do LIKE para garantir que é exatamente aquela tarefa
$sql_check = "SELECT status FROM tarefas WHERE usuario_id = $usuario_id AND titulo = '$nome_tarefa_exata'";
$res_check = mysqli_query($conn, $sql_check);

if ($res_check && mysqli_num_rows($res_check) > 0) {
    $tarefa = mysqli_fetch_assoc($res_check);
    if ($tarefa['status'] === 'concluida') {
        // Se a dificuldade específica que ele tentou acessar já estiver concluída, expulsa!
        header("Location: home.php?aviso=ja_concluido");
        exit;
    }
}
// ==========================================

// 4. Lógica de Progresso da Sessão
$objetivo = 5; 
$progresso_sessao = isset($_SESSION['progresso']) ? $_SESSION['progresso'] : 0;
$porcentagem = ($progresso_sessao / $objetivo) * 100;

// ==========================================
// 5. BUSCA A QUESTÃO NO BANCO
// ==========================================
$sql_busca_questao = "
    SELECT id, enunciado, opcao_a, opcao_b, opcao_c, opcao_d, resposta_correta, xp_recompensa, dificuldade 
    FROM banco_questoes 
    WHERE categoria = '$tipo' 
      AND dificuldade = '$dificuldade'
      AND id NOT IN (
          SELECT questao_id FROM historico_respostas 
          WHERE usuario_id = $usuario_id AND acertou = 1
      )
    ORDER BY RAND() 
    LIMIT 1
";

$resultado_questao = mysqli_query($conn, $sql_busca_questao);

// Se não achou questão (acabaram as perguntas)
if (!$resultado_questao || mysqli_num_rows($resultado_questao) == 0) {
    header("Location: home.php?aviso=sem_questoes");
    exit;
}

$questao = mysqli_fetch_assoc($resultado_questao);

// Salva na sessão para o validar_resposta.php saber depois
$_SESSION['resposta_correta_atual'] = $questao['resposta_correta'];
$_SESSION['id_questao_atual'] = $questao['id'];
$_SESSION['dificuldade_atual'] = $dificuldade; // Guarda a dificuldade atual!
$_SESSION['xp_da_questao'] = $questao['xp_recompensa'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Missão: <?= $nome_tarefa_exata ?></title>
    <link rel="icon" href="img/favicon.svg" type="image/svg+xml">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/questoes.css"> <!-- Mantendo seu CSS -->
</head>
<body class="bg-light d-flex align-items-center min-vh-100">

    <div class="container fade-in">
        
        <!-- HUD Superior -->
        <div class="row mb-4 align-items-center">
            <div class="col-4">
                <a href="home.php" class="btn btn-outline-secondary rounded-pill fw-bold">&larr; Sair</a>
            </div>
            <div class="col-4 text-center">
                <span class="badge bg-warning text-dark p-2 px-3 rounded-pill fs-6 shadow-sm">
                    ⭐ <?= $xp_atual ?> XP
                </span>
            </div>
            <div class="col-4 text-end">
                <span class="badge bg-info p-2 px-3 rounded-pill fs-6 shadow-sm">
                    🔥 Combo: <?= $progresso_sessao ?>
                </span>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8"> <!-- Aumentei a largura pra caber os botões -->
                
                

                <div class="text-center mb-2">
                    <small class="fw-bold text-muted text-uppercase">Progresso da Missão: <?= $progresso_sessao ?> / <?= $objetivo ?></small>
                </div>
                <div class="progress mb-4 shadow-sm" style="height: 15px; border-radius: 10px;">
                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" style="width: <?= $porcentagem ?>%;"></div>
                </div>

                <!-- NOVO CARD COM MÚLTIPLA ESCOLHA -->
                <div class="card card-desafio shadow shadow-lg">
                    <div class="card-body p-5">
                        
                        <!-- Mostra a Dificuldade e o Enunciado -->
                        <div class="d-flex justify-content-between mb-4">
                            <h5 class="text-muted text-uppercase fw-bold m-0">Desafio de <?= ucfirst($tipo) ?></h5>
                            <span class="badge bg-dark"><?= ucfirst($questao['dificuldade']) ?> (<?= $questao['xp_recompensa'] ?> XP)</span>
                        </div>
                        
                        <h3 class="fw-bold text-dark mb-5 text-center">
                            <?= $questao['enunciado'] ?>
                        </h3>

                        <!-- Formulário de Múltipla Escolha -->
                        <form action="validar_resposta.php" method="POST">
                            <div class="d-grid gap-3">
                                
                                <label class="btn btn-outline-primary text-start p-3 fs-5">
                                    <input type="radio" name="resposta_usuario" value="A" class="me-2" required> 
                                    <strong>A)</strong> <?= $questao['opcao_a'] ?>
                                </label>

                                <label class="btn btn-outline-primary text-start p-3 fs-5">
                                    <input type="radio" name="resposta_usuario" value="B" class="me-2"> 
                                    <strong>B)</strong> <?= $questao['opcao_b'] ?>
                                </label>

                                <?php if (!empty($questao['opcao_c'])): ?>
                                <label class="btn btn-outline-primary text-start p-3 fs-5">
                                    <input type="radio" name="resposta_usuario" value="C" class="me-2"> 
                                    <strong>C)</strong> <?= $questao['opcao_c'] ?>
                                </label>
                                <?php endif; ?>

                                <?php if (!empty($questao['opcao_d'])): ?>
                                <label class="btn btn-outline-primary text-start p-3 fs-5">
                                    <input type="radio" name="resposta_usuario" value="D" class="me-2"> 
                                    <strong>D)</strong> <?= $questao['opcao_d'] ?>
                                </label>
                                <?php endif; ?>

                            </div>
                            
                            <button type="submit" class="btn btn-success btn-lg w-100 fw-bold py-3 mt-4 rounded-pill shadow">
                                CONFIRMAR RESPOSTA
                            </button>
                        </form>

                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Configuração padrão do "Toast" (Aquele pop-up de canto de tela)
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end', // Aparece no canto superior direito
            showConfirmButton: false,
            timer: 2500, // Some em 2.5 segundos
            timerProgressBar: true, // Mostra uma barrinha de tempo diminuindo
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // O PHP "escreve" o gatilho da animação dependendo de como o aluno respondeu
        <?php if (isset($_GET['feedback'])): ?>
            
            <?php if ($_GET['feedback'] == 'correto'): ?>
                Toast.fire({
                    icon: 'success',
                    title: 'Mandou bem! Acertou!'
                });
            <?php else: ?>
                Toast.fire({
                    icon: 'error',
                    title: 'Ops! Tente novamente.'
                });
            <?php endif; ?>

        <?php endif; ?>
    </script>
</body>
</html>