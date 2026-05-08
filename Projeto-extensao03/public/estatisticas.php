<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/conexao.php';

$id_usuario = $_SESSION['usuario_id'];

// (para o header)
$query_user = "SELECT nome FROM usuarios WHERE id = $id_usuario";
$res_user = mysqli_query($conn, $query_user);
$nome_exibicao = "Estudante";
if ($res_user && $usuario = mysqli_fetch_assoc($res_user)) {
    $nome_exibicao = $usuario['nome'];
}


// Aqui nós cruzamos (JOIN) o histórico com o banco de questões para saber qual era a categoria.
// Depois, agrupamos (GROUP BY) para o banco de dados nos dar uma linha de totais para cada categoria!
$sql_estatisticas = "
    SELECT 
        b.categoria,
        SUM(CASE WHEN h.acertou = 1 THEN 1 ELSE 0 END) as total_acertos,
        SUM(CASE WHEN h.acertou = 0 THEN 1 ELSE 0 END) as total_erros
    FROM historico_respostas h
    JOIN banco_questoes b ON h.questao_id = b.id
    WHERE h.usuario_id = $id_usuario
    GROUP BY b.categoria
";
$res_estatisticas = mysqli_query($conn, $sql_estatisticas);

// Guarda tudo em um Array Dinâmico. Ex: ['soma' => ['acertos'=>3, 'erros'=>1], 'subtracao' => [...]]
$dados_categorias = [];
$tem_dados = false;

if ($res_estatisticas && mysqli_num_rows($res_estatisticas) > 0) {
    while ($row = mysqli_fetch_assoc($res_estatisticas)) {
        $dados_categorias[$row['categoria']] = [
            'acertos' => (int)$row['total_acertos'],
            'erros'   => (int)$row['total_erros']
        ];
        $tem_dados = true;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estatísticas | MathQuest</title>
    <link rel="icon" href="img/favicon.svg" type="image/svg+xml">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .card-estatico:hover {
            transform: none !important;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
            animation: none !important;
        }
    </style>
</head>
<body class="bg-body-tertiary">

    <?php include_once(__DIR__ . "/includes/header.php"); ?>

    <div class="container py-5">
        
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold text-primary"><i class="bi bi-pie-chart-fill text-info"></i> Desempenho</h1>
            <p class="lead text-body-secondary">Acompanhe sua precisão em cada operação matemática</p>
        </div>

        <?php if ($tem_dados): ?>
            
            <div class="row justify-content-center">
                
                <?php foreach ($dados_categorias as $categoria => $valores): ?>
                    <div class="col-md-5 col-lg-4 mb-4">
                        <div class="card card-estatico shadow border-0 rounded-4 h-100">
                            <div class="card-body p-4 text-center">
                                <h4 class="text-capitalize mb-4 fw-bold text-secondary">
                                    <i class="bi bi-calculator"></i> <?= $categoria ?>
                                </h4>
                                
                                <canvas id="grafico_<?= $categoria ?>"></canvas>
                                
                                <div class="mt-4 pt-3 border-top small text-body-secondary">
                                    <strong>Total respondido:</strong> <?= $valores['acertos'] + $valores['erros'] ?> questões
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>

        <?php else: ?>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card card-estatico shadow border-0 rounded-4 text-center p-5 text-muted">
                        <i class="bi bi-clipboard-x display-1 d-block mb-3"></i>
                        <h4>Nenhum dado encontrado</h4>
                        <p>Você ainda não respondeu nenhuma questão.<br>Jogue um pouco para gerar suas estatísticas!</p>
                        <a href="home.php" class="btn btn-outline-primary mt-2 fw-bold">Ir para Missões</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <?php include_once(__DIR__ . "/includes/footer.php"); ?>

    <?php if ($tem_dados): ?>
    <script>
        // Transfere o Array completo de Categorias do PHP para o JavaScript
        const dadosGraficos = <?= json_encode($dados_categorias) ?>;
        
        // Faz um "Loop" para desenhar um gráfico para cada categoria que existe no Array
        for (const [categoria, valores] of Object.entries(dadosGraficos)) {
            
            // Pega o Canvas específico dessa categoria
            const ctx = document.getElementById('grafico_' + categoria).getContext('2d');
            
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Acertos', 'Erros'],
                    datasets: [{
                        data: [valores.acertos, valores.erros],
                        backgroundColor: [
                            '#198754', // Verde Acerto
                            '#dc3545'  // Vermelho Erro
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 15
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { padding: 20, font: { size: 14, family: "'Poppins', sans-serif" } }
                        }
                    }
                }
            });
        }
    </script>
    <?php endif; ?>

</body>
</html>