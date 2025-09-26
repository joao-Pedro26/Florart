<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cadastro</title>

  <!-- CSS principal -->
  <link rel="stylesheet" href="../styles/style.css" />

  <!-- CSS só da tela de login -->
  <link rel="stylesheet" href="../styles/reset.css" />

  <!-- Ícones -->
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
</head>
<body>

<?php include '../components/cabecalho.php';?>
<br>
<br>
<br>
<br>
<main>
  
  <div class="container">
    <div class="form-box login">
      <form method="POST" action="../pages/home.php">
        <h1>Login</h1>
        <div class="input-box">
          <input type="hidden" name="route" value="consultas/login">
          <input type="text" name="email" placeholder="Digite seu email" required>
        </div>
        <div class="input-box">
          <input type="password" name="senha" placeholder="Digite sua senha" required>
        </div>
        <button type="submit">Entrar</button>
      </form>
    </div>
  </div>

</main>


</body>
</html>
