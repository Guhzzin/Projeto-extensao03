<?php
require_once(__DIR__ . "/includes/auth.php");
require_once(__DIR__ . "/includes/conexao.php");

//  Busca os 10 melhores alunos ordenados por xp desc
$sql_ranking = "SELECT nome, xp FROM usuarios ORDER BY xp DESC LIMIT 10";
$resultado = mysqli_query($conn, $sql_ranking);

// Guarda esses alunos em um Array
$top_alunos = [];
if ($resultado) {
    while ($row = mysqli_fetch_assoc($resultado)) {
        $top_alunos[] = $row;
    }
}

//  busca o nome do usuário
$id_usuario = $_SESSION['usuario_id'];
$query_user = "SELECT nome FROM usuarios WHERE id = $id_usuario";
$res_user = mysqli_query($conn, $query_user);
$nome_exibicao = "Estudante";
if ($res_user && $usuario = mysqli_fetch_assoc($res_user)) {
    $nome_exibicao = $usuario['nome'];
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranking | MathQuest</title>
    <link rel="icon" href="img/favicon.svg" type="image/svg+xml">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Um pequeno charme CSS para a linha do Top 1 brilhar */
        .top-1 { background-color: #fff3cd !important; border-left: 5px solid #ffc107; font-weight: bold;}
        .top-2 { background-color: #f8f9fa !important; border-left: 5px solid #adb5bd; }
        .top-3 { background-color: #fdf5eb !important; border-left: 5px solid #d97a46; }

        .card-estatico:hover {
            transform: none !important;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important; /* Mantém a sombra padrão do Bootstrap */
            animation: none !important;
        }
    </style>
</head>
<body class="bg-light">

    <?php include_once(__DIR__ . "/includes/header.php"); ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <div class="text-center mb-5">
                    <h1 class="display-4 fw-bold text-primary"><i class="bi bi-trophy-fill text-warning"></i> Ranking</h1>
                    <p class="lead text-muted">Os maiores mestres do MathQuest</p>
                </div>

                <div class="card shadow card-estatico border-0 rounded-4">
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0 fs-5">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th class="py-3 rounded-top-left">Posição</th>
                                    <th class="text-start py-3">Estudante</th>
                                    <th class="py-3 rounded-top-right">XP Total</th>
                                </tr>
                            </thead>
                            <tbody class="text-center align-middle">
                                
                                <?php if (count($top_alunos) > 0): ?>
                                    
                                    <?php foreach ($top_alunos as $index => $aluno): ?>
                                        <?php 
                                            // Lógica das Medalhas
                                            $posicao = $index + 1; 
                                            $icone_posicao = "<strong>{$posicao}º</strong>"; // Padrão (4º, 5º, 6º...)
                                            $classe_linha = "";

                                            if ($posicao == 1) {
                                                $icone_posicao = "<span class='fs-3'>🥇</span>";
                                                $classe_linha = "top-1";
                                            } elseif ($posicao == 2) {
                                                $icone_posicao = "<span class='fs-4'>🥈</span>";
                                                $classe_linha = "top-2";
                                            } elseif ($posicao == 3) {
                                                $icone_posicao = "<span class='fs-4'>🥉</span>";
                                                $classe_linha = "top-3";
                                            }
                                        ?>
                                        
                                        <tr class="<?= $classe_linha ?>">
                                            <td class="py-3"><?= $icone_posicao ?></td>
                                            <td class="text-start py-3 fw-semibold">
                                                <i class="bi bi-person-circle text-secondary me-2"></i> 
                                                <?= $aluno['nome'] ?>
                                            </td>
                                            <td class="py-3 text-success fw-bold">
                                                <?= $aluno['xp'] ?> <span class="small text-muted fw-normal">XP</span>
                                            </td>
                                        </tr>

                                    <?php endforeach; ?>

                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-5 text-muted">
                                            Ainda não há jogadores no ranking. Seja o primeiro!
                                        </td>
                                    </tr>
                                <?php endif; ?>

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php include_once(__DIR__ . "/includes/footer.php"); ?>

</body>
</html>