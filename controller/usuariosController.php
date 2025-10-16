<?php
// Inclui PHPMailer manualmente
require_once '../PHPMailer/src/Exception.php';
require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception; // Alias para evitar conflito

// Inclui o modelo do usuário
require_once '../model/usuariosModel.php';

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
                throw new \Exception("Todos os campos são obrigatórios.");

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
                throw new \Exception("E-mail inválido.");

            if (strlen($senha) < 8) 
                throw new \Exception("A senha deve ter no mínimo 8 caracteres.");

            if ($this->model->getUsuarioPorEmail($email)) 
                throw new \Exception("E-mail já cadastrado.");

            if (!$this->model->criarUsuario($nome, $email, $senha, $telefone)) 
                throw new \Exception("Erro ao cadastrar usuário.");

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

        } catch (\Exception $e) {
            error_log("Erro no cadastro: " . $e->getMessage());
            $_SESSION['erro'] = $e->getMessage(); 
            $_SESSION['old_inputs'] = [
                'nome' => $nome,
                'email' => $email,
                'telefone' => $telefone
            ];
            header('Location: ../pages/cadastro.php');
            unset($_SESSION['statusLogado'], $_SESSION['usuario'], $_SESSION['email'], $_SESSION['telefone'], $_SESSION['admin']);
            return false;
        }
    } 

    public function loginConta($email, $senha) 
    {
        try {
            if (!$email || !$senha) throw new \Exception("E-mail ou senha vazios.");
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) throw new \Exception("Formato de e-mail inválido.");

            $login = $this->model->login($email, $senha);
            if (!$login) throw new \Exception("E-mail ou senha incorretos.");

            session_regenerate_id(true);
            $_SESSION = [
                'statusLogado' => true,
                'usuario' => $login['nome'],
                'email' => $login['email'],
                'telefone' => $login['telefone'],
                'admin' => $login['admin']
            ];

            return true;

        } catch (\Exception $e) {
            error_log("Erro no login: " . $e->getMessage());
            $_SESSION['erro'] = $e->getMessage();
            $_SESSION['old_inputs'] = [
                'email' => $email
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
                throw new \Exception("ID inválido.");
            }

            $usuarioAtual = $this->model->getUsuarioPorId($id);
            if (!$usuarioAtual) {
                throw new \Exception("Usuário não encontrado.");
            }

            $nome = $nome ?: $usuarioAtual['nome'];
            $email = $email ?: $usuarioAtual['email'];
            $telefone = $telefone ?: $usuarioAtual['telefone'];
            $senha = $senha ?: null;

            if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \Exception("E-mail inválido.");
            }
            if ($telefone && !preg_match('/^\d{10,15}$/', $telefone)) {
                throw new \Exception("Telefone inválido.");
            }

            return $this->model->atualizarUsuario($id, $nome, $email, $telefone, $senha);
        } catch (\Exception $e) {
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

    // ====================== RECUPERAR SENHA ======================

    function EnviaEmail($destinatario, $assunto, $htmlBody, 
                    $usuario = "ecommerce@efesonet.com", 
                    $senha = "u!G8mDRr6PBXkH6", 
                    $smtp = "smtp.efesonet.com") 
    {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = $smtp;
            $mail->SMTPAuth   = true;
            $mail->Username   = $usuario;
            $mail->Password   = $senha;
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            // Ignora validação SSL (somente se necessário)
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ];

            $mail->setFrom($usuario, 'Recuperar Senha - Florart');
            $mail->addAddress($destinatario);
            $mail->isHTML(true);
            $mail->Subject = $assunto;
            $mail->Body    = $htmlBody;
            $mail->AltBody = strip_tags($htmlBody);

            return $mail->send();
        } catch (Exception $e) {
            echo "<script>alert('Erro ao enviar e-mail: " . $e->getMessage() . "');history.back();</script>";
            return false;
        }
    }

    public function enviarLinkRecuperacao($email)
    {
        $usuario = $this->model->getUsuarioPorEmail($email);

        if (!$usuario) {
            echo "<script>alert('E-mail não encontrado!');history.back();</script>";
            exit;
        }

        $token = $usuario['senha']; // ⚠️ Aqui você está usando a hash da senha como token, mas vamos manter por simplicidade.
        $nome = $usuario['nome'];

        $link = "http://localhost:3000/pages/redefinirSenha.php?token=$token";
        // $link = "http://eq5.inf2.projetoscti.com.br/redefinir.php?token=$token";
        // link correto é o que está comentado

        $assunto = 'Recuperação de Senha - Florart';
        $mensagem = "
            <h4>Redefinir sua senha</h4>
            <p><strong>Olá, {$nome}</strong>,</p>
            <p>Clique no link abaixo para redefinir sua senha:</p>
            <p><a href='{$link}'>{$link}</a></p>
            <p>Se você não solicitou esta recuperação, apenas ignore este e-mail.</p>
        ";

        $enviado = $this->EnviaEmail($email, $assunto, $mensagem);

        if ($enviado) {
            echo "<script>alert('E-mail de recuperação enviado com sucesso!');window.location='../pages/login.php';</script>";
        } else {
            echo "<script>alert('Erro ao enviar e-mail de recuperação.');history.back();</script>";
        }
    }

    // === REDEFINIR SENHA ===
    public function redefinirSenha($token, $novaSenha)
    {
        $usuarios = $this->model->listarUsuariosRedefinir();

        foreach ($usuarios as $usuario) {
            // file_put_contents("log_token.txt", "TOKEN GET: [$token]\nHASH BANCO: [{$usuario['senha']}]\n\n", FILE_APPEND);

            if ($usuario['senha'] === $token) {
                $novaHash = password_hash($novaSenha, PASSWORD_BCRYPT);
                $this->model->atualizarSenha($usuario['id_usuario'], $novaHash);

                echo "<script>alert('Senha redefinida com sucesso!');window.location='home.php';</script>";
                exit;
            }
        }

        echo "<script>alert('Token inválido!');history.back();</script>";
    }
    
}
?>