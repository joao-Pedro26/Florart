<?php 
session_start();
if (!isset($_SESSION['statusLogado']) || $_SESSION['statusLogado'] !== true) {
    header('Location: home.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/reset.css">
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/finaliza-compra.css">
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <title>Finalizar Compra</title>
</head>
<body>
     <!-- Cabeçalho -->
        <?php include '../components/cabecalho.php';?>
    <main>
      <a href="javascript:history.back()"><i class='bxr  bx-arrow-left-stroke icon-voltar'  style='color:#5a2ff4'></i> </a> 

        <section class="finaliza-compra">
            <div class="titulo-finaliza">
            <h2>Finalizar Compra</h2>
            </div>
            <section class="div">
            <section class="lista-produtos">
                <div class="produto">
                    <input type="checkbox" name="produto" id="produto">
                    <div class="imagem-produto">
                        <img src="../imagens/produtos/rosa.jpg" alt="Produto 1">
                    </div>
                    <div class="div-nome">
                        <h4>Rosa Vermelha</h4>
                    </div>
                    <div class="div-quantidade">
                        <input type="number" id="quantidade" name="quantidade" min="1" value="1">
                    </div>
                    <div class="div-preco">
                        <p>R$ 15,00</p>
                    </div>
        
                    </div>
                     <div class="produto">
                    <input type="checkbox" name="produto" id="produto">
                    <div class="imagem-produto">
                        <img src="../imagens/produtos/rosa.jpg" alt="Produto 1">
                    </div>
                    <div class="div-nome">
                        <h4>Rosa Vermelha</h4>
                    </div>
                    <div class="div-quantidade">
                        <input type="number" id="quantidade" name="quantidade" min="1" value="1">
                    </div>
                    <div class="div-preco">
                        <p>R$ 15,00</p>
                    </div>
                 </section>  
                     <section class="resumo-compra">
                    <h3>Resumo da Compra</h3>
                    <p>Total de Itens: 2</p>
                    <p>Valor Total: R$ 30,00</p>
                    <button class="botao-finalizar">Finalizar Compra</button>
                 </section>
                 </section>
                
                 </section>

    </main>
</body>
</html>