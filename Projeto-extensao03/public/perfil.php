<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/conexao.php';

$id_usuario = $_SESSION['usuario_id'];

// 1. BUSCA OS DADOS GERAIS DO ALUNO
$query_user = "SELECT nome, email, xp, criado_em FROM usuarios WHERE id = $id_usuario";
$res_user = mysqli_query($conn, $query_user);
$usuario = mysqli_fetch_assoc($res_user);

// 2. LÓGICA DAS CONQUISTAS (Fazendo as contas no histórico)
// Conta total de Acertos
$res_acertos = mysqli_query($conn, "SELECT COUNT(id) as total FROM historico_respostas WHERE usuario_id = $id_usuario AND acertou = 1");
$total_acertos = mysqli_fetch_assoc($res_acertos)['total'] ?? 0;

// Conta total de Erros
$res_erros = mysqli_query($conn, "SELECT COUNT(id) as total FROM historico_respostas WHERE usuario_id = $id_usuario AND acertou = 0");
$total_erros = mysqli_fetch_assoc($res_erros)['total'] ?? 0;

// Conta acertos de Adição no nível Veterano
$res_vet_soma = mysqli_query($conn, "
    SELECT COUNT(h.id) as total 
    FROM historico_respostas h
    JOIN banco_questoes b ON h.questao_id = b.id
    WHERE h.usuario_id = $id_usuario AND h.acertou = 1 
    AND b.categoria = 'soma' AND b.dificuldade = 'veterano'
");
$vet_soma = mysqli_fetch_assoc($res_vet_soma)['total'] ?? 0;

// 3. DEFININDO QUEM GANHOU O QUE (True ou False)
$conquista_primeiro_passo = ($total_acertos >= 1);
$conquista_imparavel      = ($total_acertos >= 10);
$conquista_mestre_soma    = ($vet_soma >= 3); // Ganha se acertar 3 de veterano na soma
$conquista_resiliencia    = ($total_erros >= 3); // Ganha se errar 3 vezes no total
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil | MathQuest</title>
    <link rel="icon" href="img/favicon.svg" type="image/svg+xml">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* CSS para as Medalhas Bloqueadas (Cinzas e Transparentes) */
        .badge-bloqueado {
            filter: grayscale(100%);
            opacity: 0.4;
            transition: all 0.3s ease;
        }
        /* Efeito de brilho para medalhas desbloqueadas */
        .badge-desbloqueado {
            animation: pulse-glow 2s infinite;
        }
        @keyframes pulse-glow {
            0% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.4); }
            70% { box-shadow: 0 0 15px 10px rgba(255, 193, 7, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); }
        }
    </style>
</head>
<body class="bg-body-tertiary">

    <?php include_once(__DIR__ . "/includes/header.php"); ?>

    <div class="container py-5">
        <div class="row">
            
            <div class="col-md-4 mb-4">
                <div class="card shadow border-0 rounded-4 text-center p-4 h-100">
                    <div class="display-1 text-primary mb-3">
                        <i class="bi bi-person-bounding-box"></i>
                    </div>
                    <h3 class="fw-bold"><?= $usuario['nome'] ?></h3>
                    <p class="text-muted mb-4"><?= $usuario['email'] ?></p>
                    
                    <div class="bg-light rounded-3 p-3 mb-3 border">
                        <h2 class="text-success fw-bold m-0"><i class="bi bi-star-fill text-warning"></i> <?= $usuario['xp'] ?> XP</h2>
                        <small class="text-muted text-uppercase">Experiência Total</small>
                    </div>

                    <p class="small text-muted mt-auto mb-0">
                        Membro desde: <?= date('d/m/Y', strtotime($usuario['criado_em'])) ?>
                    </p>
                </div>
            </div>

            <div class="col-md-8 mb-4">
                <div class="card shadow border-0 rounded-4 p-4 h-100">
                    <h4 class="fw-bold mb-4 text-secondary"><i class="bi bi-award-fill"></i> Minhas Conquistas</h4>
                    
                    <div class="row g-4 text-center">
                        
                        <div class="col-6 col-md-3">
                            <div class="<?= $conquista_primeiro_passo ? 'badge-desbloqueado' : 'badge-bloqueado' ?>">
                                <div class="display-3 text-primary mb-2"><i class="bi bi-1-circle-fill"></i></div>
                                <h6 class="fw-bold">Primeiro Passo</h6>
                                <small class="text-muted">Acerte 1 questão.</small>
                            </div>
                        </div>

                        <div class="col-6 col-md-3">
                            <div class="<?= $conquista_imparavel ? 'badge-desbloqueado' : 'badge-bloqueado' ?>">
                                <div class="display-3 text-danger mb-2"><i class="bi bi-fire"></i></div>
                                <h6 class="fw-bold">Imparável</h6>
                                <small class="text-muted">Acerte 10 questões no total.</small>
                            </div>
                        </div>

                        <div class="col-6 col-md-3">
                            <div class="<?= $conquista_mestre_soma ? 'badge-desbloqueado' : 'badge-bloqueado' ?>">
                                <div class="display-3 text-success mb-2"><i class="bi bi-patch-check-fill"></i></div>
                                <h6 class="fw-bold">Mestre da Adição</h6>
                                <small class="text-muted">Acerte 3 questões veteranas de +. </small>
                            </div>
                        </div>

                        <div class="col-6 col-md-3">
                            <div class="<?= $conquista_resiliencia ? 'badge-desbloqueado' : 'badge-bloqueado' ?>">
                                <div class="display-3 text-secondary mb-2"><i class="bi bi-bandaid-fill"></i></div>
                                <h6 class="fw-bold">Resiliência</h6>
                                <small class="text-muted">Erre 3 vezes e não desista.</small>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php include_once(__DIR__ . "/includes/footer.php"); ?>
</body>
</html>