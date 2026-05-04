<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Pega a letra que o usuário marcou (A, B, C ou D)
    $resposta_usuario = isset($_POST['resposta_usuario']) ? $_POST['resposta_usuario'] : '';
    
    // 2. Pega os dados da questão que o questoes.php salvou na sessão
    $resposta_correta = $_SESSION['resposta_correta_atual'];
    $questao_id = $_SESSION['id_questao_atual'];
    $xp_questao = $_SESSION['xp_da_questao']; // Pega o XP dinâmico da questão!
    $tipo_missao = $_SESSION['missao_atual'];
    $usuario_id = $_SESSION['usuario_id'];

    // Inicializa o progresso da sessão se não existir
    if (!isset($_SESSION['progresso'])) {
        $_SESSION['progresso'] = 0;
    }

    // 3. LÓGICA DE VALIDAÇÃO (Compara as Letras)
    if ($resposta_usuario === $resposta_correta) {
        $acertou = 1; // 1 = true (acertou) no banco
    } else {
        $acertou = 0; // 0 = false (errou) no banco
    }

    // ==========================================
    // 4. SALVA O HISTÓRICO NO BANCO DE DADOS
    // ==========================================
    // Guarda se ele acertou ou errou para não repetir a pergunta depois
    $sql_historico = "INSERT INTO historico_respostas (usuario_id, questao_id, acertou) 
                      VALUES ($usuario_id, $questao_id, $acertou)";
    mysqli_query($conn, $sql_historico);

    // ==========================================
    // 5. FLUXO DE ACERTO OU ERRO
    // ==========================================
    if ($acertou === 1) {
        
        // Aumenta o Combo/Progresso
        $_SESSION['progresso']++;
        
        // Dá o XP da questão direto no banco
        $sql_xp = "UPDATE usuarios SET xp = xp + $xp_questao WHERE id = $usuario_id";
        mysqli_query($conn, $sql_xp);

        // Verifica se completou a missão inteira (ex: 2 acertos)
        if ($_SESSION['progresso'] >= 5) { 
            
            // GARANTIA: Se a sessão falhar, assume que é iniciante
            $dif_atual = isset($_SESSION['dificuldade_atual']) ? $_SESSION['dificuldade_atual'] : 'iniciante';
            
            $busca_titulo = "Adição " . ucfirst($dif_atual); // Ex: "Adição Iniciante"
            
            // 1. Marca o nível atual como 'concluida'
            $sql_tarefa = "UPDATE tarefas SET status = 'concluida' 
                           WHERE usuario_id = $usuario_id 
                           AND titulo LIKE '%$busca_titulo%'";
            mysqli_query($conn, $sql_tarefa);
            
            // 2. Cria o próximo nível no banco
            $proxima_dif = '';
            if ($dif_atual == 'iniciante') {
                $proxima_dif = 'Intermediario';
            } elseif ($dif_atual == 'intermediario') {
                $proxima_dif = 'Veterano';
            }

            if ($proxima_dif !== '') {
                $titulo_novo = "Adição " . $proxima_dif;
                // Insere a nova tarefa "pendente" para ele
                $sql_novo = "INSERT INTO tarefas (usuario_id, titulo, descricao, status) 
                             VALUES ($usuario_id, '$titulo_novo', 'Missão destravada!', 'pendente')";
                mysqli_query($conn, $sql_novo);
            }
            
            // Limpa a barra de progresso e manda pra Home
            $_SESSION['progresso'] = 0;
            header("Location: home.php?status=sucesso");
            exit;
        }

        // Se ainda não fechou 5/5, recarrega a página de questões com aviso verde
        header("Location: questoes.php?tipo=$tipo_missao&feedback=correto");
        exit;

    } else {
        // Se errou (acertou = 0), recarrega a página com aviso vermelho
        // O progresso não zera, ele só precisa tentar outra questão
        header("Location: questoes.php?tipo=$tipo_missao&feedback=erro");
        exit;
    }
}