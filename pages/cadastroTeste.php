<?php 
  session_start();

  // Recupera valores antigos para reaproveitar nos inputs
  $nomeValue = $_SESSION['old_inputs']['nome'] ?? '';
  $emailValue = $_SESSION['old_inputs']['email'] ?? '';
  $telefoneValue = $_SESSION['old_inputs']['telefone'] ?? '';
  unset($_SESSION['old_inputs']); // limpa para a próxima requisição
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cadastro</title>
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="../styles/reset.css" />
  <link rel="stylesheet" href="../styles/login-cadastro.css" />
</head>
<body>
  
  <div class="divVideo">
    <video autoplay muted loop class="video">
      <source src="../video/videoFlor.mp4" type="video/mp4">
    </video>
  </div>

  <main>

    <?php if (isset($_SESSION['erro'])): ?>
      <div class="erro">
        <?= htmlspecialchars($_SESSION['erro']); ?>
        <?php unset($_SESSION['erro']); ?>
      </div>
    <?php endif; ?>

    <div class="box-log-cad">
      <div class="header-log-cad">
        <a href="../pages/home.php">
          <img src="../images/logo.png" alt="Logo" class="logo-log-cad">
        </a>
        <span class="titulo-log-cad">Cadastro</span>
      </div>

      <div class="container-log-cad">
        <form class="form-login" method="POST" action="../pages/home.php">
          <input type="hidden" name="route" value="consultas/cadastrar">

          <!-- CADASTRO -->
          <div class="input-group">
            <label for="nome">Nome completo</label>
            <input
              type="text"
              id="nome"
              name="nome"
              required
              autocomplete="name"
              value="<?= htmlspecialchars($nomeValue) ?>"
            >
          </div>
          <div class="input-group">
            <label for="email">E-mail</label>
            <input
              type="email"
              id="email"
              name="email"
              required
              autocomplete="username"
              value="<?= htmlspecialchars($emailValue) ?>"
            >
          </div>
          <div class="input-group">
            <label for="telefone">Telefone</label>
            <input
              type="tel"
              id="telefone"
              name="telefone"
              required
              autocomplete="tel"
              value="<?= htmlspecialchars($telefoneValue) ?>"
            >
          </div>
          <div class="input-group">
            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" required autocomplete="new-password">
          </div>

          <button type="submit" class="btn-login">Cadastrar</button>

          <div class="link-cadastro">
            <span>Já tem uma conta?</span>
            <a href="loginTeste.php">Entrar</a>
          </div>
        </form>
      </div>
    </div>

    <!-- Botão de voltar -->
    <a href="home.php" class="voltar-link" aria-label="Voltar">
      <i class="bx bxs-home icon-voltar" aria-hidden="true"></i>
    </a>
  </main>

  <script src="../js/telefoneCadastroLogica.js"></script>

</body>
</html>