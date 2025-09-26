<?php
class Database {
    protected $conexao;

    public function __construct($dsn, $username, $password) {

        //define o atributo de conexao como objeto da classe PDO
        try { 
            //o options define o modo de erro e o modo de busca padrao
            $this->conexao = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            die("Erro ao conectar ao banco: " . $e->getMessage());
        }
    }
}
?>