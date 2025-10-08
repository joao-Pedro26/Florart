<?php
require_once "../controller/produtosController.php";

function handleProdutoRoute()
{
    $method = $_SERVER['REQUEST_METHOD'];
    $route = $_POST['route'] ?? $_GET['route'] ?? '';
    $controller = new ProdutoController();

    $postData = fn($key) => $_POST[$key] ?? '';

    switch ($route) {
        case 'produtos/listar':
            return $controller->listarProdutos();

        case 'produtos/cadastrar':
            if ($method === 'POST')
                return $controller->cadastrarProduto(
                    $postData('nome'),
                    $postData('descricao'),
                    $postData('preco'),
                    $_FILES['imagem']['name'] ?? null
                );
            break;

        case 'produtos/editar':
            if ($method === 'POST')
                return $controller->editarProduto(
                    $postData('id'),
                    $postData('nome'),
                    $postData('descricao'),
                    $postData('preco'),
                    $_FILES['imagem']['name'] ?? null
                );
            break;

        case 'produtos/excluir':
            if ($method === 'POST')
                return $controller->deletarProduto($postData('id'));
            break;

        case 'produtos/buscar':
            if ($method === 'GET')
                return $controller->buscarProduto($_GET['id'] ?? 0);
            break;

        default:
            return false;
    }
}
?>