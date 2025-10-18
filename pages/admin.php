<?php
  session_start();
  require_once "../public/routesUsuarios.php";
  require_once "../public/routesProdutos.php";
  require_once "../public/routesCompras.php";

  if (!isset($_SESSION['statusLogado']) || $_SESSION['statusLogado'] !== true) {
      header("Location: home.php");
      exit;
  }

  $route = $_GET['route'];
  $_GET['route'] = $route;

  $abaAtiva = '';


  $id = intval($_GET['id'] ?? 0);

  if (($route === 'consultas/deletar' || $route === 'produtos/excluir') || $route ==='compras/cancelar' && $id) {
      if ($route === 'consultas/deletar') handleRoute();
      if ($route === 'produtos/excluir') handleProdutoRoute();
      if ($route ==='compras/cancelar') handleCompraRoute();

      $redirect = ($route === 'consultas/deletar') ? 'consultas/listarUsuarios' : 'produtos/listarProdutos';
      header("Location: admin.php?route={$redirect}");
      exit;
  }

  if (($route === 'consultas/atualizar' || $route === 'produtos/editar') && $_SERVER['REQUEST_METHOD'] === 'POST') {
      if ($route === 'consultas/atualizar') handleRoute();
      if ($route === 'produtos/editar') handleProdutoRoute();

      $redirect = ($route === 'consultas/atualizar') ? 'consultas/listarUsuarios' : 'produtos/listarProdutos';
      header("Location: admin.php?route={$redirect}");
      exit;
  }

 

  if ($route === 'produtos/listarProdutos') {
      $produtos = handleProdutoRoute();
      $abaAtiva = 'produtos';
  } 
  else if ($route === 'consultas/listarUsuarios') {
      $usuarios = handleRoute();
      $abaAtiva = 'usuarios';
  }
  else if ($route === 'compras/listarCompras') {
      $compras = handleCompraRoute();
      $abaAtiva = 'compras';
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
  <link rel="stylesheet" href="../styles/modal.css">
  <link rel="stylesheet" href="../styles/header-footer.css">
</head>
<body>
  <?php include '../components/cabecalho.php'; ?>

  <h1>Painel Administrativo</h1>

  <div class="painel-botoes">
    <a class="tab-btn" href="admin.php?route=consultas/listarUsuarios">Usuários</a>
    <a class="tab-btn" href="admin.php?route=produtos/listarProdutos">Produtos</a>
    <a class="tab-btn" href="admin.php?route=compras/listarCompras">Compras</a>
  </div>

  <?php 
      if ($abaAtiva === 'usuarios') 
      {
          include '../components/componentsAdmin/tabela-usuarios.php';
      } 
      else if ($abaAtiva === 'produtos') 
      {
          include '../components/componentsAdmin/tabela-produtos.php';
      } 
      else if ($abaAtiva === 'compras') {
        include '../components/componentsAdmin/tabela-compras.php';
      } 
      else {
        echo "<p style='text-align:center; margin:20px;'>Selecione uma aba acima para gerenciar dados.</p>";
      }
  ?>

</body>
</html>
