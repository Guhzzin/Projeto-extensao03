<?php
// Descobre qual é o arquivo atual que o usuário está acessando (ex: 'home.php' ou 'ranking.php')
$pagina_atual = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="home.php">
      <i class="bi bi-dpad-fill"></i> MathQuest 
    </a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link <?= ($pagina_atual == 'home.php') ? 'active' : '' ?>" href="home.php">Início</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($pagina_atual == 'ranking.php') ? 'active' : '' ?>" href="ranking.php">Ranking</a>
        </li>
      </ul>
      
      <div class="d-flex align-items-center">
        <button id="btn-dark-mode" class="btn btn-outline-light btn-sm me-3 border-0">
            <i class="bi bi-moon-stars-fill"></i>
        </button>

        <span class="navbar-text text-white me-3 boas-vindas">
          <i class="bi bi-person-circle"></i> Olá, <strong><?php echo explode(' ', $nome_exibicao)[0]; ?></strong>!
        </span>
        <a href="logout.php" class="btn btn-outline-light btn-sm">Sair</a>
      </div>
    </div>
  </div>
</nav>