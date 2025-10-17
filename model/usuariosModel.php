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
        $sql = "SELECT id_usuario, nome, email, telefone, admin FROM usuario ORDER BY id_usuario ASC";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function listarUsuariosRedefinir() 
    {
        $sql = "SELECT id_usuario, nome, email, telefone, admin, senha FROM usuario";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // ====================== USUÁRIOS ======================

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

    public function atualizarUsuario($id, $nome, $email, $telefone, $senha, $admin)
    {
        $sql = "UPDATE usuario SET nome = :nome, email = :email, telefone = :telefone, admin = :admin";
        if ($senha) $sql .= ", senha = :senha";
        $sql .= " WHERE id_usuario = :id";

        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':telefone', $telefone, PDO::PARAM_STR);
        $stmt->bindValue(':admin', $admin, PDO::PARAM_BOOL);
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

    // ====================== RECUPERAR SENHA ======================

    public function atualizarSenha($id, $novaSenha)
    {
        // usa a mesma propriedade de conexão que o restante do model
        $sql = "UPDATE usuario SET senha = :senha WHERE id_usuario = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':senha', $novaSenha, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute(); // retorna true/false para o chamador saber se atualizou
    }

}
?>