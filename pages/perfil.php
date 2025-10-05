<?php 
session_start();
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

<body>
    <!-- Cabeçalho -->
    <?php include '../components/cabecalho.php'; ?>
    <main class="main-perfil">
        <section class="perfil">
        

        </section>
    </main>
</body>

</html>