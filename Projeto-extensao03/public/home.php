<?php 
require_once(__DIR__ . "/includes/auth.php");
require_once(__DIR__ . "/includes/conexao.php");

$id_usuario = $_SESSION['usuario_id'];

// Busca o nome do usuário
$query = "SELECT nome FROM usuarios WHERE id = $id_usuario";
$resultado = mysqli_query($conn, $query);
$nome_exibicao = "Estudante";
if ($resultado && $usuario = mysqli_fetch_assoc($resultado)) {
    $nome_exibicao = $usuario['nome'];
}

//
// Array matriz que guarda o status de TUDO!
$missoes = [
    'Adição' => ['iniciante' => 'pendente', 'intermediario' => 'nao_existe', 'veterano' => 'nao_existe'],
    'Subtração' => ['iniciante' => 'pendente', 'intermediario' => 'nao_existe', 'veterano' => 'nao_existe']
];

$sql_tarefas = "SELECT titulo, status FROM tarefas WHERE usuario_id = $id_usuario";
$res_tarefas = mysqli_query($conn, $sql_tarefas);

if ($res_tarefas) {
    while($row = mysqli_fetch_assoc($res_tarefas)) {
        // Separa o "Adição" do "Iniciante" (O banco guarda "Adição Iniciante")
        $partes = explode(' ', trim($row['titulo']));
        
        if (count($partes) >= 2) {
            $categoria = $partes[0]; // Adição ou Subtração
            $dificuldade = strtolower($partes[1]); // iniciante, intermediario, veterano
            $status = $row['status'];

            // Se existir no nosso array, atualiza o status real
            if (isset($missoes[$categoria][$dificuldade])) {
                $missoes[$categoria][$dificuldade] = $status;
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Home | MathQuest</title>
    <link rel="icon" href="img/favicon.svg" type="image/svg+xml">
</head>
<body class="bg-body-tertiary">
  <?php include_once(__DIR__ . "/includes/header.php"); ?>

<div class="container mt-3">
    <?php if (isset($_GET['status']) && $_GET['status'] == 'sucesso'): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <strong> Missão Cumprida!</strong> Você completou o desafio e ganhou XP extra!
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

<section class="py-5 mb-4 bg-body-tertiary rounded-4 shadow-sm text-center container border">
    <span class="badge rounded-pill bg-primary mb-3 px-3 py-2 text-uppercase">Área de Treinamento</span>
    <h1 class="display-5 fw-bold text-body">Pronto para o desafio de hoje?</h1>
    <p class="lead text-body-secondary mb-0">Escolha uma operação abaixo, resolva os problemas e acumule <strong>pontos de XP</strong>!</p>
</section>

<div class="container">
  <div class="row mt-4 justify-content-center">
    
    <div class="col-md-5 mb-4">
      <div class="card h-100 border-start border-4 border-success shadow-sm">
        <div class="card-body text-center p-4">
          <div class="display-1 text-success mb-3"><i class="bi bi-plus-circle-fill"></i></div>
          <h4 class="card-title fw-bold">Adição</h4>
          <p class="card-text text-body-secondary mb-4">Pratique somas básicas e suba de nível.</p>
          
          <?php 
          $soma = $missoes['Adição']; 
          if ($soma['veterano'] === 'concluida'): ?>
              <button class="btn btn-secondary w-100 fw-bold py-2" disabled><i class="bi bi-check-circle-fill"></i> Mestre da Adição</button>
          <?php elseif ($soma['intermediario'] === 'concluida' || $soma['veterano'] === 'pendente'): ?>
              <a href="questoes.php?tipo=soma&dif=veterano" class="btn btn-danger w-100 fw-bold shadow-sm py-2">Nível 3: Veterano</a>
          <?php elseif ($soma['iniciante'] === 'concluida' || $soma['intermediario'] === 'pendente'): ?>
              <a href="questoes.php?tipo=soma&dif=intermediario" class="btn btn-warning text-dark w-100 fw-bold shadow-sm py-2">Nível 2: Intermediário</a>
          <?php else: ?>
              <a href="questoes.php?tipo=soma&dif=iniciante" class="btn btn-success w-100 fw-bold shadow-sm py-2">Nível 1: Iniciante</a>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="col-md-5 mb-4">
      <div class="card h-100 border-start border-4 border-info shadow-sm">
        <div class="card-body text-center p-4">
          <div class="display-1 text-info mb-3"><i class="bi bi-dash-circle-fill"></i></div>
          <h4 class="card-title fw-bold">Subtração</h4>
          <p class="card-text text-body-secondary mb-4">Treine a lógica de diminuir e resolver diferenças.</p>
          
          <?php 
          $subtracao = $missoes['Subtração']; 
          if ($subtracao['veterano'] === 'concluida'): ?>
              <button class="btn btn-secondary w-100 fw-bold py-2" disabled><i class="bi bi-check-circle-fill"></i> Mestre da Subtração</button>
          <?php elseif ($subtracao['intermediario'] === 'concluida' || $subtracao['veterano'] === 'pendente'): ?>
              <a href="questoes.php?tipo=subtracao&dif=veterano" class="btn btn-danger w-100 fw-bold shadow-sm py-2">Nível 3: Veterano</a>
          <?php elseif ($subtracao['iniciante'] === 'concluida' || $subtracao['intermediario'] === 'pendente'): ?>
              <a href="questoes.php?tipo=subtracao&dif=intermediario" class="btn btn-warning text-dark w-100 fw-bold shadow-sm py-2">Nível 2: Intermediário</a>
          <?php else: ?>
              <a href="questoes.php?tipo=subtracao&dif=iniciante" class="btn btn-info text-white w-100 fw-bold shadow-sm py-2">Nível 1: Iniciante</a>
          <?php endif; ?>
        </div>
      </div>
    </div>

  </div>
</div>

<?php include_once(__DIR__ . "/includes/footer.php"); ?>
</body>
</html>