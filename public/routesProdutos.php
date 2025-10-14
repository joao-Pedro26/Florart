<?php
require_once "../controller/produtosController.php";

function handleProdutoRoute()
{
    $method = $_SERVER['REQUEST_METHOD'];
    $route = $_POST['route'] ?? $_GET['route'] ?? '';
    $controller = new ProdutoController();

    $postData = fn($key) => $_POST[$key] ?? '';

    switch ($route) {
        case 'produtos/listarProdutos':
            $produtos = $controller->listarProdutos();
            return is_array($produtos) ? $produtos : [];

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
            if ($method === 'POST') {
                // Recebe dados do POST corretamente
                $id = $postData('id');
                $nome = $postData('nome');
                $descricao = $postData('descricao');
                $preco = $postData('preco');
                $imagem = $_FILES['imagem']['name'] ?? null;

                return $controller->editarProduto($id, $nome, $descricao, $preco, $imagem);
            }
            break;

        case 'produtos/excluir':
            if ($method === 'GET' || $method === 'POST') {
                $id = $_GET['id'] ?? $_POST['id'] ?? null;

                if (!$id) {
                    echo "Erro: ID não recebido.";
                    return false;
                }

                return $controller->deletarProduto($id);
            }
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