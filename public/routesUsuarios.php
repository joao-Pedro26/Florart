<?php
require_once "../controller/usuariosController.php";

function handleRoute()
{
    $method = $_SERVER['REQUEST_METHOD'];
    $route = $_POST['route'] ?? $_GET['route'] ?? '';
    $controller = new UsuarioController();

    $postData = fn($key) => $_POST[$key] ?? '';

    switch ($route) {
        case 'consultas/cadastrar':
            if ($method === 'POST')
                return $controller->cadastrarConta($postData('nome'), $postData('email'), $postData('senha'), $postData('telefone'));
            break;

        case 'consultas/login':
            if ($method === 'POST')
                return $controller->loginConta($postData('email'), $postData('senha'));
            break;

        case 'acoes/sair':
            if ($method === 'GET')
                return $controller->logout();
            break;

        case 'consultas/atualizar':
            if ($method === 'POST') {
                return $controller->editarConta(
                    $postData('id'),
                    $postData('nome'),
                    $postData('email'),
                    $postData('senha'),
                    $postData('telefone')
                );
            }
            break;

        case 'consultas/deletar':
            if ($method === 'GET' || $method === 'POST') {
                $id = $_GET['id'] ?? $_POST['id'] ?? null;

                if (!$id) {
                    echo "Erro: ID não recebido.";
                    return false;
                }

                return $controller->deletarConta($id);
            }
            break;

        case 'consultas/listarUsuarios':
            if ($method === 'GET') return $controller->listarUsuarios();

        case 'consultas/enviarLinkRecuperacao':
            $controller->enviarLinkRecuperacao($_POST['email']);
            break;

        case 'consultas/redefinirSenha':
            $controller->redefinirSenha($_POST['token'], $_POST['senha']);
            break;

        default:
            return false;
    }
}