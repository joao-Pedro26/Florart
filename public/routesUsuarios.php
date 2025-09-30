<?php

require_once "../controller/usuariosController.php";

function handleRoute() 
{
    
    $method = $_SERVER['REQUEST_METHOD'];
    
    // Pegamos a rota do POST ou GET
    $route = $_POST['route'] ?? $_GET['route'] ?? '';

    switch ($route) 
    {
        case 'consultas/cadastrar':
            if ($method === 'POST' && !empty($_POST)) 
            {
                $nome = $_POST["nome"] ?? '';
                $email = $_POST['email'] ?? '';
                $senha = $_POST['senha'] ?? '';
                $telefone = $_POST['telefone'] ?? '';
                $controller = new UsuarioController();
                return $controller->cadastrarConta($nome, $email, $senha, $telefone);
            } 
            else
            {
                return false;
            }
            
        case 'consultas/login':
            if ($method === 'POST' && !empty($_POST)) 
            {
                $email = $_POST['email'] ?? '';
                $senha = $_POST['senha'] ?? '';
                $controller = new UsuarioController();
                
                return $controller->loginConta($email, $senha);
            }   
            else 
            {
                return false;
            }
        case 'acoes/sair':
            if ($method === 'GET' && !empty($_GET))
            {
                $controller = new UsuarioController();
                return $controller->lougout();
            }   
            else 
            {
                return false;
            }
        
        
        
        case 'consultas/atualizar':
            if ($method === 'POST' && !empty($_POST)) 
            {
                $id = $_POST['id'] ?? '';
                $nome = $_POST["nome"] ?? '';
                $email = $_POST['email'] ?? '';
                $senha = $_POST['senha'] ?? '';
                $telefone = $_POST['telefone'] ?? '';
                $controller = new UsuarioController();
                return $controller->editarConta($id, $nome, $email, $senha, $telefone);
            } 
            else
            {
                return false;
            }
        
        case 'consultas/excluir':
            if ($method === 'POST' && !empty($_POST)) 
            {
                $id = $_POST['id'] ?? '';
                $controller = new UsuarioController();
                return $controller->deletarConta($id);
            } 
            else
            {
                return false;
            }
        
            


        default:
            return false;
            
    }
}

?>







