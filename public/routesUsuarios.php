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
            if ($method === 'POST') 
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
        case 'metodos/sair':
            if ($method === 'GET') 
            {
                $controller = new UsuarioController();
                return $controller->lougout();
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



