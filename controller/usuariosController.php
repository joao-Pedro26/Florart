<?php
require_once '../model/usuariosModel.php';

class UsuarioController {

    protected $model;

    public function __construct() {
        
        $dsn = "pgsql:host=localhost;port=5432;dbname=florart;";
        $username = "postgres";
        $password = "postgres";

        $this->model = new UsuarioModel($dsn, $username, $password);
    }

    // Cadastrar novo usuário
    public function cadastrarConta($nome, $email, $senha, $telefone) 
    {
    
        if (empty($nome) || empty($email) || empty($senha) || empty($telefone) ) {
            return "Todos os campos são obrigatórios!";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Por favor, insira um email válido!";
        }

        // Chama a model para criar usuário
        $sucesso = $this->model->criarUsuario($nome, $email, $senha, $telefone);

        if ($sucesso) 
        {
            // Busca o usuário recém-criado para preencher a sessão
            $usuario = $this->model->getUsuarioPorEmail($email); 
            if ($usuario) 
            {
                $_SESSION['statusLogado'] = true;
                $_SESSION['usuario']      = $usuario['nome'];
                $_SESSION['email']        = $usuario['email'];
                $_SESSION['telefone']     = $usuario['telefone'];
                $_SESSION['admin']        = false; // default
            }
            return true;
        }
        else 
        {
            return "Erro ao cadastrar usuário.";
        }
    }


    

    // Login
    public function loginConta($email, $senha) 
    {
        if (empty($email) || empty($senha)) {
            return false; // falha
        }

        if ($login = $this->model->login($email, $senha)) {
            $_SESSION['statusLogado'] = true;
            $_SESSION['usuario']      = $login['nome'];
            $_SESSION['email']        = $login['email'];
            $_SESSION['telefone']     = $login['telefone'];
            $_SESSION['admin']        = $login['admin'];

            return true;
        }
        return false;
    }


    // Editar usuário
    public function editarConta($id, $nome, $email, $senha, $telefone) 
    {
        if (empty($nome) || empty($email) || empty($telefone) || empty($senha)) {
            return "Todos os campos são obrigatórios!";
        }

        return $this->model->atualizarUsuario($id, $nome, $email, $telefone, $senha);
    }

    // Excluir usuário
    public function deletarConta($id) {
        return $this->model->deletarUsuario($id);
    }

}
?>
