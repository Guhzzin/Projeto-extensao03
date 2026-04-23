<?php require_once(__DIR__ . "/includes/auth.php");
require_once(__DIR__ . "/includes/conexao.php");
$id_usuario = $_SESSION['usuario'];

// 2. Busca o nome do usuário no banco de dados
$query = "SELECT nome FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

// 3. Guarda o nome em uma variável
if ($usuario = $resultado->fetch_assoc()) {
    $nome_exibicao = $usuario['nome'];
} else {
    // Caso ocorra algum erro raro de não achar o ID
    $nome_exibicao = "Estudante";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Home</title>
</head>
<body>
  <?php include_once(__DIR__ . "/includes/header.php"); ?>

<section class="py-5 mb-4 bg-light rounded-4 shadow-sm text-center">
    <div class="container">
        <span class="badge rounded-pill bg-primary mb-3 px-3 py-2 text-uppercase">Nível 1: Iniciante</span>
        
        <h1 class="display-5 fw-bold text-dark">Pronto para o desafio de hoje?</h1>
        
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <p class="lead text-muted mb-0">
                    Escolha uma operação abaixo, resolva os problemas e acumule 
                    <strong>pontos de XP</strong> para subir no ranking!
                </p>
            </div>
        </div>
    </div>
</section>

  <div class="row mt-4">
    <div class="col-md-4 mb-4">
      <div class="card h-100 border-start border-4 border-success shadow-sm">
        <div class="card-body text-center">
          <div class="display-1 text-success mb-3">+</div>
          <h5 class="card-title">Adição</h5>
          <p class="card-text text-muted">Pratique somas básicas e suba de nível.</p>
          <a href="exercicios.php?tipo=soma" class="btn btn-success w-100">Começar</a>
        </div>
      </div>
    </div>
    </div>
</div>



 <?php include_once(__DIR__ . "/includes/footer.php"); ?>
</body>
</html>
