
<?php
require_once '../model/usuariosModel.php';

class UsuarioController 
{

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
        try 
        {
            $nome     = trim($nome);
            $email    = strtolower(trim($email));
            $telefone = preg_replace('/\D/', '', $telefone);

            if (empty($nome) || empty($email) || empty($senha) || empty($telefone)) 
            {
                throw new Exception("Todos os campos são obrigatórios.");
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
            {
                throw new Exception("E-mail inválido.");
            }

            if (strlen($senha) < 8) 
            {
                throw new Exception("A senha deve ter no mínimo 8 caracteres.");
            }

            if ($this->model->getUsuarioPorEmail($email)) 
            {
                throw new Exception("E-mail já cadastrado.");
            }

            
            $sucesso = $this->model->criarUsuario($nome, $email, $senha, $telefone);
            if (!$sucesso) 
            {
                throw new Exception("Erro ao cadastrar usuário.");
            }

            
            $usuario = $this->model->getUsuarioPorEmail($email);
            if ($usuario) 
            {
                session_regenerate_id(true); // reforço de segurança
                $_SESSION['statusLogado'] = true;
                $_SESSION['usuario']      = $usuario['nome'];
                $_SESSION['email']        = $usuario['email'];
                $_SESSION['telefone']     = $usuario['telefone'];
                $_SESSION['admin']        = $usuario['admin'];
            }

            return true;

        } 
        catch (Exception $e) 
        {
            $_SESSION['erro'] = $e->getMessage(); 

            
            unset($_SESSION['statusLogado'], $_SESSION['usuario'], $_SESSION['email'], $_SESSION['telefone'], $_SESSION['admin']);

            return false;
        }
    }



    

    // Login
    public function loginConta($email, $senha) 
    {
        try { 
            if (empty($email) || empty($senha)) 
            {
                throw new Exception("E-mail ou senha vazios."); 
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
            {
                throw new Exception("Formato de e-mail inválido.");
            }

            if (($this->model->getUsuarioPorEmail($email)) === false) 
            {
                throw new Exception("E-mail não cadastrado.");
            }

            $login = $this->model->login($email, $senha);

            if (!$login) 
            {
                throw new Exception("Senha incorreta.");
            }

            session_regenerate_id(true);

            $_SESSION['statusLogado'] = true;
            $_SESSION['usuario']      = $login['nome'];
            $_SESSION['email']        = $login['email'];
            $_SESSION['telefone']     = $login['telefone'];
            $_SESSION['admin']        = $login['admin'];

            return true;

        } 
        catch (Exception $e) 
        {
            error_log("Erro no login: " . $e->getMessage());
            $_SESSION['erro'] = $e->getMessage();
            unset($_SESSION['statusLogado'], $_SESSION['usuario'], $_SESSION['email'], $_SESSION['telefone'], $_SESSION['admin']);

            return false;
        }
    }

    public function editarConta($id, $nome, $email, $senha, $telefone) 
    {
        try {
            if (empty($id) || !is_numeric($id)) 
            {
                throw new Exception("ID inválido.");
            }

            if (empty($nome)) 
            {
                throw new Exception("Nome não pode estar vazio.");
            }

            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) 
            {
                throw new Exception("E-mail inválido.");
            }

            if (empty($telefone) || !preg_match('/^\d{10,15}$/', $telefone)) 
            {
                throw new Exception("Telefone inválido.");
            }

            if (!empty($senha)) {
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            } else {
                $senhaHash = null;
            }

            return $this->model->atualizarUsuario($id, $nome, $email, $telefone, $senhaHash);

        } 
        catch (Exception $e) 
        {
            error_log("Erro ao editar conta: " . $e->getMessage());
            $_SESSION['erro'] = $e->getMessage();
            return false;
        }
    }

    public function deletarConta($id) 
    {
        return $this->model->deletarUsuario($id);
    }

    public function lougout() 
    {
        session_unset();
        session_destroy();
        return true;
    }

}
?>
