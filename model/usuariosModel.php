<?php
require_once 'Database.php';

class UsuarioModel extends Database {

    public function __construct($dsn, $username, $password) {
        parent::__construct($dsn, $username, $password);
    }
    //metodo de usuarios
    public function criarUsuario($nome, $email, $senha, $telefone) {
        $sql = "INSERT INTO usuarios (nome, email, senha, telefone) 
                VALUES (:nome, :email, :senha, :telefone)";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':senha', password_hash($senha, PASSWORD_BCRYPT), PDO::PARAM_STR);
        $stmt->bindValue(':telefone', $telefone, PDO::PARAM_STR);

        return $stmt->execute();
    }

    //metodo de usuarios
    public function login($email, $senha) {
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        
        $usuario = $stmt->fetch();
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            return $usuario;
             // retorna array com dados do usuário
             //session start();
        }
        return false;
    }

    //metodo de adms

    public function listarUsuarios() {
        $sql = "SELECT id_usuario, nome, email, telefone, admin FROM usuarios";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    //metodo de usuarios este metodo deve ser alterado conforme o front end
    public function atualizarUsuario($id, $nome, $email, $senha = null) {
    $sql = "UPDATE usuarios SET nome = :nome, email = :email";
    if ($senha) $sql .= ", senha = :senha";
    $sql .= " WHERE id_usuario = :id";

    $stmt = $this->conexao->prepare($sql);
    $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    if ($senha) $stmt->bindValue(':senha', password_hash($senha, PASSWORD_BCRYPT), PDO::PARAM_STR);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    return $stmt->execute();
}


    //metodo de usuarios
    public function deletarUsuario($id) {
        $sql = "DELETE FROM usuarios WHERE id_usuario = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    //metodo de adms metodo tipo botao experimental
    public function tornarAdmin($id) {
        $sql = "UPDATE usuarios SET admin = 1 WHERE id_usuario = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    //metodo de adms metodo tipo botao experimental

    public function revogarAdmin($id) {
        $sql = "UPDATE usuarios SET admin = 0 WHERE id_usuario = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>

