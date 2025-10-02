<?php
  session_start();

  // Recupera valor antigo do email se existir
  $emailValue = $_SESSION['old_inputs']['email'] ?? '';
  unset($_SESSION['old_inputs']); // limpa para a próxima requisição
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login</title>
  <!-- Ícones -->
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <!-- Reset + css login/cadastro -->
  <link rel="stylesheet" href="../styles/reset.css" />
  <link rel="stylesheet" href="../styles/login-cadastro.css" />
</head>
<body>

  <!-- Vídeo de fundo -->
  <div class="divVideo" aria-hidden="true">
    <video autoplay muted loop playsinline class="video">
      <source src="../video/videoFlor.mp4" type="video/mp4">
    </video>
  </div>

  <main>
    
    <!-- Mensagem de erro -->
    <?php if (isset($_SESSION['erro'])): ?>
      <div class="erro"><?= htmlspecialchars($_SESSION['erro']); ?></div>
      <?php unset($_SESSION['erro']); ?>
    <?php endif; ?>

    <!-- Caixa branca centralizada -->
    <div class="box-log-cad" role="region" aria-label="Formulário de login">
      <div class="header-log-cad">
        <a href="../pages/home.php">
          <img src="../images/logo.png" alt="Logo" class="logo-log-cad">
        </a>
        <span class="titulo-log-cad">Login</span>
      </div>

      <div class="container-log-cad">
        <form class="form-login" method="POST" action="../pages/home.php" novalidate>
          <input type="hidden" name="route" value="consultas/login">

          <!-- LOGIN -->
          <div class="input-group">
            <label for="email">E-mail</label>
            <input
              type="email"
              id="email"
              name="email"
              required
              autocomplete="username"
              value="<?= htmlspecialchars($emailValue) ?>"
            />
          </div>

          <div class="input-group">
            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" required autocomplete="current-password" />
          </div>

          <button type="submit" class="btn-login">Entrar</button>

          <div class="link-cadastro">
            <span>Não tem uma conta?</span>
            <a href="cadastroTeste.php">Cadastre-se</a>
          </div>
          <div class="link-recuperarSenha">
            <span>Esqueceu sua senha?</span>
            <a href="recuperarSenha.php">Clique aqui</a>
          </div>
        </form>
      </div>
    </div>

    <!-- Botão de voltar -->
    <a href="home.php" class="voltar-link" aria-label="Voltar">
      <i class="bx bxs-home icon-voltar" aria-hidden="true"></i>
    </a>

  </main>
</body>
</html>