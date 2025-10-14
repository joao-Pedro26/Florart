<?php
require_once '../model/usuariosModel.php';
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class UsuarioController 
{
    protected $model;

    public function __construct() 
    {
        $dsn = "pgsql:host=projetoscti.com.br;port=54432;dbname=eq5.inf2;";
        $username = "eq5.inf2";
        $password = "eq52335";
        $this->model = new UsuarioModel($dsn, $username, $password);
    }

    public function cadastrarConta($nome, $email, $senha, $telefone) 
    {
        try {
            $nome = trim($nome);
            $email = strtolower(trim($email));
            $telefone = preg_replace('/\D/', '', $telefone);

            if (!$nome || !$email || !$senha || !$telefone) 
                throw new Exception("Todos os campos são obrigatórios.");

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
                throw new Exception("E-mail inválido.");

            if (strlen($senha) < 8) 
                throw new Exception("A senha deve ter no mínimo 8 caracteres.");

            if ($this->model->getUsuarioPorEmail($email)) 
                throw new Exception("E-mail já cadastrado.");

            if (!$this->model->criarUsuario($nome, $email, $senha, $telefone)) 
                throw new Exception("Erro ao cadastrar usuário.");

            $usuario = $this->model->getUsuarioPorEmail($email);
            session_regenerate_id(true);
            $_SESSION = [
                'statusLogado' => true,
                'usuario' => $usuario['nome'],
                'email' => $usuario['email'],
                'telefone' => $usuario['telefone'],
                'admin' => $usuario['admin']
            ];

            return true;

        } catch (Exception $e) {
            error_log("Erro no cadastro: " . $e->getMessage());
            $_SESSION['erro'] = $e->getMessage(); 
            $_SESSION['old_inputs'] = [
                    'nome' => $nome,
                    'email' => $email,
                    'telefone' => $telefone,
                    // NÃO salva senha
                ];
            header('Location: ../pages/cadastro.php');
            unset($_SESSION['statusLogado'], $_SESSION['usuario'], $_SESSION['email'], $_SESSION['telefone'], $_SESSION['admin']);
            return false;
        }
    }

    

    public function loginConta($email, $senha) 
    {
        try {
            if (!$email || !$senha) throw new Exception("E-mail ou senha vazios.");
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) throw new Exception("Formato de e-mail inválido.");

            $login = $this->model->login($email, $senha);
            if (!$login) throw new Exception("E-mail ou senha incorretos.");

            session_regenerate_id(true);
            $_SESSION = [
                'statusLogado' => true,
                'usuario' => $login['nome'],
                'email' => $login['email'],
                'telefone' => $login['telefone'],
                'admin' => $login['admin']
            ];

            return true;

        } catch (Exception $e) {
            error_log("Erro no cadastro: " . $e->getMessage());
            $_SESSION['erro'] = $e->getMessage();
            $_SESSION['old_inputs'] = [
                    'email' => $email,
                    // não salvar a senha!
                ];
            header('Location: ../pages/login.php');
            unset($_SESSION['statusLogado'], $_SESSION['usuario'], $_SESSION['email'], $_SESSION['telefone'], $_SESSION['admin']);
            return false;
        }
    }

    public function editarConta($id, $nome = null, $email = null, $senha = null, $telefone = null)
    {
        try {
            if (!$id || !is_numeric($id)) {
                throw new Exception("ID inválido.");
            }

            // Busca o usuário atual no banco para manter valores originais
            $usuarioAtual = $this->model->getUsuarioPorId($id);
            if (!$usuarioAtual) {
                throw new Exception("Usuário não encontrado.");
            }

            // Se o campo vier vazio, mantém o valor atual
            $nome = $nome ?: $usuarioAtual['nome'];
            $email = $email ?: $usuarioAtual['email'];
            $telefone = $telefone ?: $usuarioAtual['telefone'];
            $senha = $senha ?: null; // senha só atualiza se vier algo

            // Validações básicas apenas dos campos enviados
            if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("E-mail inválido.");
            }
            if ($telefone && !preg_match('/^\d{10,15}$/', $telefone)) {
                throw new Exception("Telefone inválido.");
            }

            return $this->model->atualizarUsuario($id, $nome, $email, $telefone, $senha);
            } 
            catch (Exception $e) 
            {
                $_SESSION['erro'] = $e->getMessage();
                return false;
            }
        }


    public function deletarConta($id) 
    {
        return $this->model->deletarUsuario($id);
    }

    public function logout() 
    {
        session_unset();
        session_destroy();
        return true;
    }


    public function listarUsuarios() 
    {
        return $this->model->listarUsuarios();
    }

    public function solicitarRecuperacao($email) 
    {
        try {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) throw new Exception("E-mail inválido.");

            $token = $this->model->criarTokenRecuperacao($email);
            if (!$token) throw new Exception("E-mail não encontrado.");

            $_SESSION['sucesso'] = "Um link de recuperação foi enviado para $email.";
            return true;

        } catch (Exception $e) {
            $_SESSION['erro'] = $e->getMessage();
            return false;
        }
    }

    public function redefinirSenhaConta($token, $novaSenha) 
    {
        try {
            if (strlen($novaSenha) < 8) throw new Exception("A senha deve ter no mínimo 8 caracteres.");

            $dados = $this->model->validarToken($token);
            if (!$dados) throw new Exception("Token inválido ou expirado.");

            return $this->model->atualizarSenha($dados['fk_usuario'], $novaSenha);

        } catch (Exception $e) {
            $_SESSION['erro'] = $e->getMessage();
            return false;
        }
    }

    public function criarCodigoRecuperacao($email) 
    {
        return $this->model->criarCodigoRecuperacao($email);
    }

    public function validarCodigo($codigo) 
    {
        return isset($_SESSION['recuperacao_codigo']) && $_SESSION['recuperacao_codigo'] == $codigo;
    }

    public function redefinirSenhaPorCodigo($novaSenha) 
    {
        if (!isset($_SESSION['recuperacao_usuario_id'])) return false;

        $id = $_SESSION['recuperacao_usuario_id'];
        $res = $this->model->atualizarSenha($id, $novaSenha);

        unset($_SESSION['recuperacao_codigo'], $_SESSION['recuperacao_usuario_id'], $_SESSION['recuperacao_email'], $_SESSION['recuperacao_data']);
        return $res;
    }
}
?>