<?php 
require_once(__DIR__ . "/includes/auth.php");
require_once(__DIR__ . "/includes/conexao.php");

// 1. CORREÇÃO: Usar usuario_id
$id_usuario = $_SESSION['usuario_id'];

// 2. Busca o nome do usuário
$query = "SELECT nome FROM usuarios WHERE id = $id_usuario";
$resultado = mysqli_query($conn, $query);
$nome_exibicao = "Estudante";
if ($resultado && $usuario = mysqli_fetch_assoc($resultado)) {
    $nome_exibicao = $usuario['nome'];
}

// 3. NOVO: Busca o status das tarefas desse usuário no banco
$sql_tarefas = "SELECT titulo, status FROM tarefas WHERE usuario_id = $id_usuario";
$res_tarefas = mysqli_query($conn, $sql_tarefas);

$status_missoes = [];
if ($res_tarefas) {
    while($row = mysqli_fetch_assoc($res_tarefas)) {
        // Guarda o status usando o título como chave (tudo em minúsculo para facilitar)
        $titulo_limpo = strtolower($row['titulo']);
        $status_missoes[$titulo_limpo] = $row['status'];
    }
}

// Verifica se a missão de soma já foi concluída
$status_soma = isset($status_missoes['adição']) ? $status_missoes['adição'] : 'pendente';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Home</title>
</head>
<body>
  <?php include_once(__DIR__ . "/includes/header.php"); ?>

<div class="container mt-3">
    <?php if (isset($_GET['status']) && $_GET['status'] == 'sucesso'): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <strong>🎉 Missão Cumprida!</strong> Você completou o desafio e ganhou XP extra!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['aviso']) && $_GET['aviso'] == 'ja_concluido'): ?>
        <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
            <strong>Atenção:</strong> Você já concluiu esta missão. Escolha outro desafio!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
</div>

<section class="py-5 mb-4 bg-light rounded-4 shadow-sm text-center container">
    <span class="badge rounded-pill bg-primary mb-3 px-3 py-2 text-uppercase">Nível 1: Iniciante</span>
    <h1 class="display-5 fw-bold text-dark">Pronto para o desafio de hoje?</h1>
    <p class="lead text-muted mb-0">Escolha uma operação abaixo, resolva os problemas e acumule <strong>pontos de XP</strong>!</p>
</section>

<div class="container">
  <div class="row mt-4">
    <div class="col-md-4 mb-4">
      <div class="card h-100 border-start border-4 border-success shadow-sm">
        <div class="card-body text-center">
          <div class="display-1 text-success mb-3">+</div>
          <h5 class="card-title">Adição</h5>
          <p class="card-text text-muted">Pratique somas básicas e suba de nível.</p>
          
          <?php if ($status_soma == 'concluida'): ?>
              <button class="btn btn-secondary w-100 fw-bold" disabled>
                  <i class="bi bi-check-circle-fill"></i> Concluído
              </button>
          <?php else: ?>
              <a href="questoes.php?tipo=soma" class="btn btn-success w-100 fw-bold">Começar</a>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once(__DIR__ . "/includes/footer.php"); ?>
</body>
</html>