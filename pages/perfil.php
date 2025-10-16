<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['statusLogado']) || $_SESSION['statusLogado'] !== true) {
    header('Location: home.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/reset.css">
    <link rel="stylesheet" href="../styles/style.css">
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <title>Perfil</title>
</head>

<body class="body-perfil">
    <!-- Cabeçalho -->
    <?php include '../components/cabecalho.php'; ?>
    <main class="main-perfil">
    <a href="javascript:history.back()"><i class='bxr  bx-arrow-left-stroke icon-voltar' style='color:#5a2ff4'></i> </a>
    <a href="#"><img src="/images/logo.png" alt="logo" class="logo-log-cad logo-cad"></a>

        <section class="perfil">
                <h2>Meu Perfil</h2>
                <div class="dados-usuario">
                    <div class="icon-editar">
                <a href="/pages/editar-perfil.php"><i class='bxr  bx-edit icon-editar'  style='color:#000000'></i> </a>
                </div>
                    <label >Email:</label>
                    <div class="campo">
                        <p>joao@gmail.com</p>
                    </div>
                    <label >Nome:</label>
                    <div class="campo">
                        <p>Joao</p>
                    </div>
                     <label >Telefone:</label>
                    <div class="campo">
                        <p>14997973331  </p>
                    </div>

                </div>
        </section>
    </main>

</body>

</html>