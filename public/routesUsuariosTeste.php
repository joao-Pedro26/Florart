<?php
require_once "../controller/usuariosController.php";

function handleRoute() 
{
    $method = $_SERVER['REQUEST_METHOD'];
    $route = $_POST['route'] ?? $_GET['route'] ?? '';
    $controller = new UsuarioController();

    $postData = fn($key) => $_POST[$key] ?? '';

    switch ($route) 
    {
        case 'consultas/cadastrar':
            if ($method === 'POST') return $controller->cadastrarConta($postData('nome'), $postData('email'), $postData('senha'), $postData('telefone'));
            break;

        case 'consultas/login':
            if ($method === 'POST') {

                $email = $postData('email');
                $senha = $postData('senha');

                /* ========================================================
                 * DEV BYPASS DE LOGIN (APENAS PARA TESTES LOCAIS)
                 * >>>> REMOVER ESTE TRECHO NA VERSÃO FINAL <<<<<
                 ======================================================== */
                $DEV_BYPASS = true; // habilita/desabilita o bypass
                if ($DEV_BYPASS && in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1','::1'])) {
                    if ($email === 'dev@local' && $senha === 'devpass') {
                        session_start();
                        session_regenerate_id(true);
                        $_SESSION['statusLogado'] = true;
                        $_SESSION['usuario'] = 'Admin Dev';
                        $_SESSION['email'] = $email;
                        $_SESSION['telefone'] = '';
                        $_SESSION['admin'] = true;
                        return true; // bypass feito, não precisa banco
                    }
                }
                /* ========================================================
                 * FIM DO DEV BYPASS
                 ======================================================== */

                // fluxo normal de login
                return $controller->loginConta($email, $senha);
            }
            break;

        case 'acoes/sair':
            if ($method === 'GET') return $controller->logout();
            break;

        case 'consultas/atualizar':
            if ($method === 'POST') return $controller->editarConta($postData('id'), $postData('nome'), $postData('email'), $postData('senha'), $postData('telefone'));
            break;

        case 'consultas/excluir':
            if ($method === 'POST') return $controller->deletarConta($postData('id'));
            break;

        case 'consultas/solicitarRecuperacao':
            if ($method === 'POST') return $controller->solicitarRecuperacao($postData('email'));
            break;

        case 'consultas/redefinirSenha':
            if ($method === 'POST') return $controller->redefinirSenhaConta($postData('token'), $postData('senha'));
            break;

        case 'consultas/validarCodigo':
            if ($method === 'POST') {
                $codigo = $postData('codigo');
                if ($controller->validarCodigo($codigo)) {
                    $_SESSION['codigo_valido'] = true;
                    return true;
                }
                $_SESSION['erro'] = "Código incorreto";
                return false;
            }
            break;

        case 'consultas/redefinirSenhaCodigo':
            if ($method === 'POST') return $controller->redefinirSenhaPorCodigo($postData('senha'));
            break;

        default:
            return false;
    }
}
?>
?>