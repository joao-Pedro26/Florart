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
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cadastro</title>
  <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="../styles/style.css" />
  <link rel="stylesheet" href="../styles/reset.css" />

</head>

<body>
  <main>
    <a href="javascript:history.back()"><i class='bxr  bx-arrow-left-stroke icon-voltar' style='color:#5a2ff4'></i> </a>
    <?php
    if (isset($_SESSION['erro'])): ?>
      <div class="erro" style="color:red; text-align:center; margin:10px 0; ; position: relative; ">
        <?php
        echo htmlspecialchars($_SESSION['erro']);
        unset($_SESSION['erro']);
        ?>
      </div>
    <?php endif; ?>
    <a href=""><img src="/images/logo.png" alt="logo" class="logo-log-cad"></a>

    <div class="container-log-cad">
      <form class="form-login" method="POST" action="../pages/home.php">
        <input type="hidden" name="route" value="consultas/login">
        <div class="input-group">
          <label for="email">E-mail</label>
          <input type="email" id="email"
            name="email"
            required autocomplete="email" ]
            value="<?= htmlspecialchars($emailValue) ?>">
        </div>
        <div class="input-group">
          <label for="senha">Senha</label>
          <input type="password" id="senha" name="senha" required autocomplete="current-password">
        </div>
        <button type="submit" class="btn-login">Entrar</button>
        <div class="link-cadastro">
          <span>Não tem uma conta?</span>
          <a href="../pages/cadastroTeste.php">Cadastre-se</a>
        </div>
        <div class="link-cadastro " style="margin-top: 0px;">
          <span>Esqueceu sua senha?</span>
          <a href="recuperarSenha.php">Clique aqui</a>
        </div>
      </form>
    </div>

  </main>
</body>

</html>