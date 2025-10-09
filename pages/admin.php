<?php
session_start();
require_once "../public/routesUsuarios.php";
require_once "../public/routesProdutos.php";

if (!isset($_SESSION['statusLogado']) || $_SESSION['statusLogado'] !== true) {
    header("Location: home.php");
    exit;
}

$route = $_GET['route'] ?? 'consultas/listarUsuarios';
$_GET['route'] = $route;

if ($route === 'consultas/deletar' && isset($_GET['id'])) 
{
    $id = intval($_GET['id']);
    handleRoute();
    header("Location: admin.php");
    exit;
}

if ($route === 'consultas/atualizar' && isset($_GET['id'])) 
{
    $id = intval($_GET['id']);
    handleRoute();
    header("Location: admin.php");
    exit;
}


// Carregar produtos se a rota for produtos/listarProdutos
if ($route === 'produtos/listarProdutos') 
{
    $produtos = handleProdutoRoute();
    $abaAtiva = 'produtos';
}
else if ($route === 'consultas/listarUsuarios') 
{
    $usuarios = handleRoute();
    $abaAtiva = 'usuarios';
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <title>Painel Administrativo</title>
  <link rel="stylesheet" href="../styles/style.css">
  <link rel="stylesheet" href="../styles/admin.css">
  <link rel="stylesheet" href="../styles/reset.css">
  <link rel="stylesheet" href="../styles/header-footer.css">
</head>

<body>
  <?php include '../components/cabecalho.php'; ?>

  <h1>Painel Administrativo</h1>

  <div class="painel-botoes">
    <a class="tab-btn" href="admin.php?route=consultas/listarUsuarios">Usuários</a>
    <a class="tab-btn" href="admin.php?route=produtos/listarProdutos">Produtos</a>
  </div>

    <?php 
      if ($abaAtiva === 'usuarios') {
          include '../components/componentsAdmin/tabela-usuarios.php';
      } 
      else if ($abaAtiva === 'produtos') {
          include '../components/componentsAdmin/tabela-produtos.php';
      }

      // Modal sempre presente na página
      include '../components/componentsAdmin/modalExcluir.php'; 
    ?>


  <?php include '../components/rodape.php'; ?>
  
</body>

</html>