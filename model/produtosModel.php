<?php
require_once 'database.php';

class ProdutoModel extends Database
{
    public function __construct($dsn, $username, $password)
    {
        parent::__construct($dsn, $username, $password);
    }

    public function listarProdutos()
    {
        $sql = "SELECT id_produto, nome, descricao, imagem, tipo, valor_unitario, excluido, data_criacao 
                FROM produto 
                WHERE excluido = false OR excluido IS NULL
                ORDER BY id_produto ASC";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function criarProduto($nome, $descricao, $tipo, $valor_unitario, $estoque, $imagem = null)
    {
        $sql = "INSERT INTO produto (nome, descricao, tipo, valor_unitario, estoque, imagem, data_criacao)
                VALUES (:nome, :descricao, :tipo, :valor_unitario, :estoque, :imagem, NOW())";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':descricao', $descricao);
        $stmt->bindValue(':tipo', $tipo);
        $stmt->bindValue(':valor_unitario', $valor_unitario);
        $stmt->bindValue(':estoque', $estoque);
        $stmt->bindValue(':imagem', $imagem);
        return $stmt->execute();
    }

    public function atualizarProduto($id, $nome, $descricao, $tipo, $valor_unitario, $imagem = null)
    {
        $sql = "UPDATE produto 
                SET nome = :nome, descricao = :descricao, tipo = :tipo, valor_unitario = :valor_unitario";
        if ($imagem) $sql .= ", imagem = :imagem";
        $sql .= " WHERE id_produto = :id";

        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':descricao', $descricao);
        $stmt->bindValue(':tipo', $tipo);
        $stmt->bindValue(':valor_unitario', $valor_unitario);
        if ($imagem) $stmt->bindValue(':imagem', $imagem);

        return $stmt->execute();
    }

    public function deletarProduto($id)
    {
        // Exclusão lógica (mantém registro)
        $sql = "UPDATE produto SET excluido = true, data_exclusao = NOW() WHERE id_produto = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    public function getProdutoPorId($id)
    {
        $sql = "SELECT * FROM produto WHERE id_produto = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>