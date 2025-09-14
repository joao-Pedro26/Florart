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

      <div class="teste"><h1>Cadastro</h1></div>
        

        <div class="input-box">
          <input type="hidden" name="route" value="consultas/cadastrar">
          <input type="text" name="nome" placeholder="Nome completo" required />
          <i class="bx bxs-user"></i>
        </div>

        <div class="input-box">
          <input type="email" name="email" placeholder="Email" required />
          <i class="bx bxs-envelope"></i>
        </div>

        <div class="input-box">
          <input type="password" name="senha" placeholder="Senha" required />
          <i class="bx bxs-lock-alt"></i>
        </div>

        <div class="input-box">
          <input type="telefone" name="telefone" placeholder="telefone" required />
          <i class="bx bxs-lock-alt"></i>
        </div>

        <button type="submit" class="btn">Cadastrar</button>
      </form>
    </div>
  </div>

</main>


</body>
</html>
