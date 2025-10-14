<?php
require_once 'database.php';

class UsuarioModel extends Database 
{
    public function __construct($dsn, $username, $password) 
    {
        parent::__construct($dsn, $username, $password);
    }

    public function listarUsuarios() 
    {
        $sql = "SELECT id_usuario, nome, email, telefone, admin FROM usuario";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // ====================== USUÁRIOS ======================
    public function listarUsuariosTeste() 
    {
        // ======= INÍCIO: MODO TESTE (remover depois) =======
        if ($this->conexao === null) {
            return [
                [
                    'id_usuario' => 1,
                    'nome'       => 'Teste Dev',
                    'email'      => 'dev@teste.com',
                    'telefone'   => '0000-0000',
                    'admin'      => 1
                ],
                [
                    'id_usuario' => 2,
                    'nome'       => 'Usuário Fake',
                    'email'      => 'fake@teste.com',
                    'telefone'   => '1111-1111',
                    'admin'      => 0
                ]
            ];
        }
        // ======= FIM: MODO TESTE =======

        $sql = "SELECT id_usuario, nome, email, telefone, admin FROM usuario";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function criarUsuario($nome, $email, $senha, $telefone) 
    {
        if ($this->conexao === null) {
            // ======= MODO TESTE =======
            return true; // apenas simula que funcionou
        }

        $sql = "INSERT INTO usuario (nome, email, senha, telefone) 
                VALUES (:nome, :email, :senha, :telefone)";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':senha', password_hash($senha, PASSWORD_BCRYPT), PDO::PARAM_STR);
        $stmt->bindValue(':telefone', $telefone, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function getUsuarioPorEmail($email) 
    {
        $sql = "SELECT * FROM usuario WHERE email = :email LIMIT 1"; //metodo para logar automaticamente apos cadastro
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);  
    }

    public function getUsuarioPorId($id)
    {
        $sql = "SELECT * FROM usuario WHERE id_usuario = :id LIMIT 1";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUsuarioPorEmailTeste($email) 
    {
        if ($this->conexao === null) {
            // ======= MODO TESTE =======
            // Simula um usuário retornado no modo teste
            if ($email === 'teste@gmail.com') {
                return [
                    'id_usuario' => 1,
                    'nome'       => 'Teste Dev',
                    'email'      => 'teste@gmail.com',
                    'telefone'   => '5514111111111',
                    'admin'      => false
                ];
            }
            return false;
        }

        $sql = "SELECT id_usuario, nome, email, telefone, admin FROM usuario WHERE email = :email LIMIT 1";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deletarUsuario($id) 
{
    if (empty($id) || !is_numeric($id)) {
        throw new Exception("ID inválido recebido: " . var_export($id, true));
    }

    $sql = "DELETE FROM usuario WHERE id_usuario = :id";
    $stmt = $this->conexao->prepare($sql);
    $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
    return $stmt->execute();
}

    public function deletarUsuarioTeste($id) 
    {
        if ($this->conexao === null) {
            // ======= MODO TESTE =======
            return true; // só finge que deletou
        }

        $sql = "DELETE FROM usuario WHERE id_usuario = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function atualizarUsuario($id, $nome, $email, $telefone, $senha = null)
{
    $sql = "UPDATE usuario SET nome = :nome, email = :email, telefone = :telefone";
    if ($senha) $sql .= ", senha = :senha";
    $sql .= " WHERE id_usuario = :id";

    $stmt = $this->conexao->prepare($sql);
    $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->bindValue(':telefone', $telefone, PDO::PARAM_STR);
    if ($senha) $stmt->bindValue(':senha', password_hash($senha, PASSWORD_BCRYPT), PDO::PARAM_STR);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    return $stmt->execute();
}


    public function tornarAdmin($id) 
    {
        $sql = "UPDATE usuario SET admin = 1 WHERE id_usuario = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function revogarAdmin($id) 
    {
        $sql = "UPDATE usuario SET admin = 0 WHERE id_usuario = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // ====================== RECUPERAR SENHA ======================
    public function criarTokenRecuperacao($email) 
    {
        $usuario = $this->getUsuarioPorEmail($email);
        if (!$usuario) return false;

        $token = bin2hex(random_bytes(16));
        $sql = "INSERT INTO recuperacao_senha (fk_usuario, token) VALUES (:fk_usuario, :token)";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':fk_usuario', $usuario['id_usuario'], PDO::PARAM_INT);
        $stmt->bindValue(':token', $token, PDO::PARAM_STR);

        return $stmt->execute() ? $token : false;
    }

    public function validarToken($token) 
    {
        $sql = "SELECT r.id_recuperacao, r.fk_usuario, r.usado, u.email 
                FROM recuperacao_senha r
                JOIN usuario u ON r.fk_usuario = u.id_usuario
                WHERE r.token = :token LIMIT 1";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':token', $token, PDO::PARAM_STR);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        return ($res && !$res['usado']) ? $res : false;
    }

    public function atualizarSenha($id_usuario, $novaSenha) 
    {
        $senhaHash = password_hash($novaSenha, PASSWORD_BCRYPT);
        $sql = "UPDATE usuario SET senha = :senha WHERE id_usuario = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':senha', $senhaHash, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id_usuario, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function criarCodigoRecuperacao($email) 
    {
        $usuario = $this->getUsuarioPorEmail($email);
        if (!$usuario) return false;

        $codigo = random_int(100000, 999999);
        $_SESSION['recuperacao_email'] = $email;
        $_SESSION['recuperacao_codigo'] = $codigo;
        $_SESSION['recuperacao_usuario_id'] = $usuario['id_usuario'];
        $_SESSION['recuperacao_data'] = time();

        return $codigo;
    }

    public function login($email, $senha) 
    {
        $sql = "SELECT * FROM usuario WHERE email = :email LIMIT 1";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) 
        {
            return $usuario;
        }

        return false;
    }

    public function loginTeste($email, $senha) 
    {
    if ($this->conexao === null) {
        // ======= MODO TESTE =======
        if ($email === 'teste@gmail.com' && $senha === '11111111') {
            return [
                'id_usuario' => 1,
                'nome'       => 'Teste Dev',
                'email'      => 'teste@gmail.com',
                'telefone'   => '5514111111111',
                'admin'      => false
            ];
        }
        return false;
    }

    $sql = "SELECT id_usuario, nome, email, senha, telefone, admin 
                FROM usuario 
                WHERE email = :email LIMIT 1";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            // Remove a senha do array antes de retornar
            unset($usuario['senha']);
            return $usuario;
        }

        return false;
    }

}
?>