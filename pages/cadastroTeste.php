

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cadastro</title>
  <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="../styles/style.css" />
  <link rel="stylesheet" href="../styles/reset.css" />
</head>
<body>
  
<main>
    <a href="javascript:history.back()"><i class='bxr  bx-arrow-left-stroke icon-voltar'  style='color:#5a2ff4'></i> </a> 
    <?php
      session_start();
      if (isset($_SESSION['erro'])): ?>
          <div class="erro" style="color:red; text-align:center; margin:10px 0; z-index: 1000;">
              <?php 
                  echo htmlspecialchars($_SESSION['erro']); 
                  unset($_SESSION['erro']); 
              ?>
          </div>
  <?php endif; ?>
    <a href="/"><img src="/images/logo.png" alt="logo" class="logo-log-cad logo-cad"></a>

    <div class="container-cad container-log-cad ">
      <form class="form-login" method="POST" action="../pages/home.php">
        <input type="hidden" name="route" value="consultas/cadastrar">
        <div class="input-group">
          <label for="nome">Nome completo</label>
          <input type="text" id="nome" name="nome" required autocomplete="name">
        </div>
        <div class="input-group">
          <label for="email">E-mail</label>
          <input type="email" id="email" name="email" required autocomplete="username">
        </div>
        <div class="input-group">
          <label for="telefone">Telefone</label>
          <input type="tel" id="telefone" name="telefone" required autocomplete="tel">
        </div>
        <div class="input-group">
          <label for="senha">Senha</label>
          <input type="password" id="senha" name="senha" required autocomplete="new-password">
        </div>
        <button type="submit" class="btn-login">Cadastrar</button>
        <div class="link-cadastro">
          <span>Já tem uma conta?</span>
          <a href="../pages/loginTeste.php">Entrar</a>
        </div>
      </form>
    </div>
</main>
</body>
</html>
