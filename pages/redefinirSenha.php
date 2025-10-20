<?php
session_start();
require_once "../public/routesUsuarios.php";

$token = $_GET['token'] ?? null;

if (!$token) {
    echo "Token inválido ou não informado.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha = $_POST['senha'] ?? '';
    $confirmaSenha = $_POST['confirmaSenha'] ?? '';

    if ($senha !== $confirmaSenha) {
        $_SESSION['erro'] = "As senhas não conferem.";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    $_POST['token'] = $token;
    $_POST['senha'] = $senha;
    $_POST['confirmaSenha'] = $confirmaSenha;
    $_POST['route'] = 'consultas/redefinirSenha';

    handleRoute();
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Redefinir Senha</title>
  <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="../styles/reset.css" />
  <link rel="stylesheet" href="../styles/recuperar-senha.css" />
</head>

<body>
    <a href="javascript:history.back()"><i class='bxr  bx-arrow-left-stroke icon-voltar' style='color:#5a2ff4'></i> </a>


<div class="container-recuperar" style="display:flex; align-items:center; justify-content:center; min-height:100vh;">
  <div class="box-log-cad">
    <div class="header-log-cad">
      <h1 class="titulo-log-cad">Redefinir Senha</h1>
    </div>

    <?php if (isset($_SESSION['erro'])): ?>
      <div class="erro" style="margin-bottom:1rem;">
        <?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="input-group">
        <label for="senha">Nova senha</label>
        <input type="password" id="senha" name="senha" required minlength="8" autocomplete="new-password" />
      </div>

      <div class="input-group">
        <label for="confirmaSenha">Confirmar nova senha</label>
        <input type="password" id="confirmaSenha" name="confirmaSenha" required minlength="8" autocomplete="new-password" />
      </div>

      <button type="submit">Redefinir senha</button>
    </form>
  </div>
</div>
</body>
</html>