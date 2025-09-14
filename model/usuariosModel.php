<?php
require_once 'Database.php';

class UsuarioModel extends Database 
{

    public function __construct($dsn, $username, $password) 
    {
        parent::__construct($dsn, $username, $password);
    }
    //metodo de usuarios
    public function criarUsuario($nome, $email, $senha, $telefone) 
    {
        $sql = "INSERT INTO Usuario (nome, email, senha, telefone) 
                VALUES (:nome, :email, :senha, :telefone)";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':senha', password_hash($senha, PASSWORD_BCRYPT),  PDO::PARAM_STR);
        $stmt->bindValue(':telefone', $telefone, PDO::PARAM_STR);

        return $stmt->execute(); // retorna true se inseriu, false se deu erro
    }

    //metodo de usuarios dependente
    public function getUsuarioPorEmail($email) 
    {
        $sql = "SELECT * FROM usuario WHERE email = :email LIMIT 1"; //metodo para logar automaticamente apos cadastro
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);  
    }

    //metodo de usuarios
    public function login($email, $senha) {
       
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
    

    //metodo de usuarios
    public function atualizarUsuario($id, $nome, $email, $senha = null , $telefone) 
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

    //metodo de usuarios
    public function deletarUsuario($id) 
    {
        $sql = "DELETE FROM usuario WHERE id_usuario = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    //metodo de adms
    public function listarUsuarios() 
    {
        $sql = "SELECT id_usuario, nome, email, telefone, admin FROM usuarios";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    

    //metodo de adms
    public function tornarAdmin($id) 
    {
        $sql = "UPDATE usuario SET admin = 1 WHERE id_usuario = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    //metodo de adms

    public function revogarAdmin($id) 
    {
        $sql = "UPDATE usuario SET admin = 0 WHERE id_usuario = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>

