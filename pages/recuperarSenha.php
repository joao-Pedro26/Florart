<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

require_once "../public/routesUsuarios.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Se o POST veio da requisição do e-mail para enviar link
  if (isset($_POST['email'])) {
    $_POST['route'] = 'consultas/enviarLinkRecuperacao';
    handleRoute();
    exit;
  }
  // Se o POST veio da redefinição de senha (com token)
  if (isset($_POST['token']) && isset($_POST['senha']) && isset($_POST['confirmaSenha'])) {
    // Pode validar senha e confirmar senha aqui (ou deixar no controller)
    if ($_POST['senha'] !== $_POST['confirmaSenha']) {
      $_SESSION['erro'] = "As senhas não conferem.";
      header("Location: ".$_SERVER['REQUEST_URI']);
      exit;
    }
    $_POST['route'] = 'consultas/redefinirSenha';
    handleRoute();
    exit;
  }
}

$token = $_GET['token'] ?? null;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Recuperar Senha</title>
  <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="../styles/reset.css" />
  <link rel="stylesheet" href="../styles/recuperar-senha.css" />
</head>

<body>
  <!-- Botão voltar no canto superior esquerdo -->
<a href="javascript:history.back()" class="voltar-link">
  <i class='bx bx-chevron-left icon-voltar'></i>
</a>

  <!-- Container centralizado -->
  <div class="container-recuperar" style="display:flex; align-items:center; justify-content:center; min-height:100vh;">
    <div class="box-log-cad">

      <div class="header-log-cad">
        <h1 class="titulo-log-cad">Recuperar Senha</h1>
      </div>

      <?php if (isset($_SESSION['erro'])): ?>
        <div class="erro" style="margin-bottom:1rem;">
          <?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?>
        </div>
      <?php endif; ?>

      <p style="text-align:justify; margin-bottom:1.2rem; color:#444;">
        Digite o e-mail cadastrado e enviaremos um link para redefinir sua senha.
      </p>

      <form method="POST" action="">
        <div class="input-group">
          <label for="email">E-mail</label>
          <input type="email" id="email" name="email" required autocomplete="username" />
        </div>

        <button type="submit">Enviar link de recuperação</button>
      </form>

    </div>
  </div>
</body>
</html>