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
          <a class="nav-link active" href="home.php">Início</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="ranking.php">Ranking</a>
        </li>
      </ul>
      
      <div class="d-flex align-items-center">
       <span class="navbar-text text-white me-3 boas-vindas">
    Olá, <strong><?php echo explode(' ', $nome_exibicao)[0]; ?></strong>!
        </span>
        <a href="logout.php" class="btn btn-outline-light btn-sm">Sair</a>
      </div>
    </div>
  </div>
</nav>