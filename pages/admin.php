<?php
session_start();
require_once "../public/routesUsuarios.php";
require_once "../public/routesProdutos.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo '<pre>';
    print_r($_POST);
    print_r($_FILES);
    echo '</pre>';
}

// ---------------------
// Validação de login
// ---------------------
if (!isset($_SESSION['statusLogado']) || $_SESSION['statusLogado'] !== true) {
    header("Location: home.php");
    exit;
}

// ---------------------
// Rota e aba ativa
// ---------------------
$route = $_GET['route'] ?? 'consultas/listarUsuarios';
$_GET['route'] = $route;

// Inicializa para evitar warnings
$abaAtiva = '';

// Pegar ID se existir
$id = intval($_GET['id'] ?? 0);

// ---------------------
// Tratar exclusão de usuário ou produto
// ---------------------
if (($route === 'consultas/deletar' || $route === 'produtos/excluir') && $id) {
    if ($route === 'consultas/deletar') handleRoute();
    if ($route === 'produtos/excluir') handleProdutoRoute();

    $redirect = ($route === 'consultas/deletar') ? 'consultas/listarUsuarios' : 'produtos/listarProdutos';
    header("Location: admin.php?route={$redirect}");
    exit;
}

// ---------------------
// Tratar atualização de usuário ou produto
// ---------------------
if (($route === 'consultas/atualizar' || $route === 'produtos/editar') && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($route === 'consultas/atualizar') handleRoute();
    if ($route === 'produtos/editar') handleProdutoRoute();

    // Redireciona para a lista correspondente
    $redirect = ($route === 'consultas/atualizar') ? 'consultas/listarUsuarios' : 'produtos/listarProdutos';
    header("Location: admin.php?route={$redirect}");
    exit;
}

// ---------------------
// Carregar dados para a aba correta
// ---------------------
if ($route === 'produtos/listarProdutos') {
    $produtos = handleProdutoRoute();
    $abaAtiva = 'produtos';
} 
else if ($route === 'consultas/listarUsuarios') {
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
  <link rel="stylesheet" href="../styles/modal.css">
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
      else {
          echo "<p style='text-align:center; margin:20px;'>Selecione uma aba acima para gerenciar dados.</p>";
      }

      // Modal de exclusão
      include '../components/componentsAdmin/modalExcluir.php'; 

      // Modal de edição
      include '../components/componentsAdmin/modalEditar.php';
  ?>

  <?php include '../components/rodape.php'; ?>
  <script src="../js/javascriptAdmin/admin.js"></script>
</body>
</html>
