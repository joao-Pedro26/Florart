<?php
session_start();
require_once "../public/routesUsuarios.php";

$resultado = handleRoute();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($resultado === true && isset($_SESSION['statusLogado']) && $_SESSION['statusLogado'] === true) {
        header('Location: home.php');
        exit;
    }
    $origem = $_POST['origem'] ?? '';


    // Passa a mensagem de erro para a página correta
    switch ($origem) {
        case 'loginTeste.php':
            header('Location: loginTeste.php');
            exit;
        case 'cadastroTeste.php':
            header('cadastroTeste.php');// inclui a própria página de cadastro
            exit;
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/reset.css">
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="icon" href="../images/logo.png" type="">
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <title>Florart</title>
</head>
<body>
    <!-- Cabeçalho -->
        <?php include '../components/cabecalho.php';?>
    <main>
        <!-- Carrinho -->
        <?php include '../components/carrinho.php';?>
            
        <!-- Apresentação -->
            
        <section class="apresentacao">
            <div class="texto-apresentacao">
            <div>
            <h1 id="titulo-apresentacao"></h1>
            </div>
            <h4>Descubra a beleza das flores com nossas artes exclusivas.</h4>
            </div> 
            <div class="divBotoes">
                <div class="botao-catalogo">
                     <a href="#catalogo">Catálogo</a>
                </div>
                <div class="botao-entrega">
                    <a href="#diferenciais">Entrega Expressa</a>
                </div>
            </div>
            <div class="divVideo">
                <video class="video" autoplay muted loop>
                    <source src="../video/videoFlor.mp4" type="video/mp4">
                </video>
            </div>
        </section>
        <!-- Diferenciais  -->

        <section class="diferenciais" id="diferenciais">
            <div>
                <div class="icon-diferenciais icon1" >
                    <i class='bxr  bx-truck' id="icon-diferenciais" style="color:#004500" ></i> 
                </div>
                <div class="texto-diferenciais">
                    <h1>Entrega</h1>
                    <p>Receba suas flores imediatamente.</p>
                </div>
            </div>
            <div>
                <div class="icon-diferenciais icon2">
                    <i class='bxr  bx-shield' id="icon-diferenciais" style='color:#d95d73'></i> 
                </div>
                <div class="texto-diferenciais ">
                    <h1>Segurança</h1>
                    <p>Flores saudáveis com vasos resistentes.</p>
                </div>
            </div>
            <div>
                <div class="icon-diferenciais icon3">
                    <i class='bxr  bx-gift' id="icon-diferenciais" style='color:#9f6700'></i>  
                </div>
                <div class="texto-diferenciais">
                    <h1>Deslumbrar</h1>
                    <p>A forma mais sublime de encantar quem se ama.</p>
                </div>
            </div>
            <div>
                <div class="icon-diferenciais icon4">
                    <i class='bxr  bx-clock-1' id="icon-diferenciais" style='color:#b338b3'></i> 
                </div>
                <div class="texto-diferenciais">
                    <h1>Operação</h1>
                    <p>Durante toda a celebração da semana do colégio.</p>
                </div>
            </div>
        </section>

        <!-- Carrossel -->

        <section class="carrosel">
            <div class="slides">
                <div class="slide"><img src="https://img.freepik.com/free-photo/cosmos-flowers_1373-83.jpg" alt=""></div>
                <div class="slide"><img src="https://img.freepik.com/premium-photo/cosmos-flower-cosmos-bipinnatus-use-background_42082-300.jpg" alt=""></div>
                <div class="slide"><img src="https://img.freepik.com/free-photo/gerbera-pink-petals-black-background_23-2148268322.jpg" alt=""></div>
                <div class="slide"><img src="https://img.freepik.com/free-photo/top-view-beautifully-colored-flowers_23-2149005613.jpg" alt=""></div>
                <div class="slide"><img src="https://img.freepik.com/free-photo/silhouette-pink-cosmos-flowers-garden_1357-51.jpg" alt=""></div>
            </div>  
             <div class="controls">
      <button class="btn prev" aria-label="Anterior">◀</button>
      <button class="btn next" aria-label="Próximo">▶</button>
    </div>                            
        </section>

         <!-- Catálogo -->

        <section class="catalogo" id="catalogo" >
            <div class="titulo-catalogo" >
                <h2>Nossos Produtos</h2>
                <p>Seleção especial das nossas criações mais amadas</p>
            </div>
            <div class="produtos">
                 <?php include '../components/produto.php';?>
                 <?php include '../components/produto.php';?>
                 <?php include '../components/produto.php';?>
                 <?php include '../components/produto.php';?>
            </div>

        </section>
    </main>
    <script src="../js/tituloApresentacao.js"></script>
    <script src="../js/carrosel.js"></script>
    <script src="../js/carrinho.js"></script>
    <script src="../js/menu.js"></script>
    <script src="../js/cabecalho.js"></script>

    <!-- Rodapé -->
        <?php include '../components/rodape.php';?>
</body>
</html>