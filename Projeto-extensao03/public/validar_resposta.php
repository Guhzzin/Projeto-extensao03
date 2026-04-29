<?php
require_once __DIR__ . '/includes/auth.php'; 
require_once __DIR__ . '/includes/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resposta_usuario = intval($_POST['resposta_usuario']);
    $resposta_correta = $_SESSION['resposta_correta_atual'];
    $tipo_missao = $_SESSION['missao_atual'];
    $usuario_id = $_SESSION['usuario_id'];

    // Inicializa o progresso se não existir
    if (!isset($_SESSION['progresso'])) {
        $_SESSION['progresso'] = 0;
    }

    if ($resposta_usuario === $resposta_correta) {
        $_SESSION['progresso']++;
        
        // Ganha 10 de XP por acerto (Update direto no banco)
        $sql_xp = "UPDATE usuarios SET xp = xp + 10 WHERE id = $usuario_id";
        mysqli_query($conn, $sql_xp);

        // Verifica se completou a missão (ex: 5 acertos)
        if ($_SESSION['progresso'] >= 5) {
            // Atualiza a tabela tarefas que você criou!
            $sql_tarefa = "UPDATE tarefas SET status = 'concluida' 
                           WHERE usuario_id = $usuario_id AND titulo LIKE '%$tipo_missao%'";
            mysqli_query($conn, $sql_tarefa);
            
            // Limpa o progresso e manda para uma tela de vitória ou para a home
            $_SESSION['progresso'] = 0;
            header("Location: home.php?status=sucesso");
            exit;
        }

        header("Location: questoes.php?tipo=$tipo_missao&feedback=correto");
    } else {
        // Se errar, podemos resetar o progresso ou apenas mandar de volta
        header("Location: questoes.php?tipo=$tipo_missao&feedback=erro");
    }
}