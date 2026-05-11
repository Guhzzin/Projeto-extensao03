<?php
// Garante que a sessão e a conexão com o banco estejam ativas
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inicializa variáveis padrão
$nome_completo = "Estudante";
$email_user = "email@exemplo.com";
$xp_user = 0;
$nivel_user = 1;

// Busca os dados reais do usuário logado se a conexão existir
if (isset($_SESSION['usuario_id']) && isset($conn)) {
    $id_usuario = $_SESSION['usuario_id'];
    // Consulta os campos na tabela usuarios
    $query_perfil = "SELECT nome, email, xp FROM usuarios WHERE id = $id_usuario";
    $resultado_perfil = mysqli_query($conn, $query_perfil);
    
    if ($resultado_perfil && $usuario_data = mysqli_fetch_assoc($resultado_perfil)) {
        $nome_completo = $usuario_data['nome'];
        $email_user = $usuario_data['email'];
        $xp_user = $usuario_data['xp'];
        
        // Lógica de nível: ganha 1 nível a cada 50 de XP acumulado
        $nivel_user = floor($xp_user / 50) + 1; 
    }
}

// Descobre a página atual para marcar o link ativo no menu
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

        <div class="dropdown">
          <button class="btn btn-outline-light border-0 dropdown-toggle d-flex align-items-center p-2 rounded-pill shadow-sm" 
                  type="button" id="perfilDropdown" data-bs-toggle="dropdown" aria-expanded="false" 
                  style="background: rgba(255,255,255,0.1);">
            <i class="bi bi-person-circle fs-5 me-2"></i> 
            <strong><?php echo explode(' ', $nome_completo)[0]; ?></strong>
          </button>
          
          <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2" 
              aria-labelledby="perfilDropdown" 
              style="width: 280px; border-radius: 15px; overflow: hidden; padding: 0;">
            
            <div class="text-center p-4" style="background-color: #fdf5ef; border-bottom: 1px solid #eee;">
                <i class="bi bi-person-circle text-secondary" style="font-size: 3.5rem;"></i>
                <h6 class="mb-0 fw-bold text-dark mt-2"><?php echo $nome_completo; ?></h6>
                <small class="text-muted d-block"><?php echo $email_user; ?></small>
            </div>
            
            <div class="p-3 bg-white">
                <div class="d-flex justify-content-between mb-2 px-1">
                    <span class="text-secondary small fw-bold">Nível Atual:</span>
                    <span class="badge bg-primary rounded-pill"><?php echo $nivel_user; ?></span>
                </div>
                <div class="d-flex justify-content-between mb-3 px-1">
                    <span class="text-secondary small fw-bold">Experiência:</span>
                    <span class="badge bg-warning text-dark rounded-pill"><?php echo $xp_user; ?> XP</span>
                </div>
                
                <hr class="my-2">
                
                <a class="dropdown-item text-danger fw-bold d-flex align-items-center py-2" href="logout.php">
                    <i class="bi bi-box-arrow-right me-2"></i> Sair da Conta
                </a>
            </div>
          </ul>
        </div>
      </div>
    </div>
  </div>
</nav>