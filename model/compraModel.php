<?php
require_once 'database.php';

class CompraModel extends Database
{
    public function __construct($dsn, $username, $password)
    {
        parent::__construct($dsn, $username, $password);
    }

    public function criarCompra($idUsuario, $carrinho)
    {
        try {
            $this->conexao->beginTransaction();

            // 1️⃣ Cria a compra
            $stmt = $this->conexao->prepare("
                INSERT INTO compra (fk_usuario, status_compra)
                VALUES (:fk_usuario, 'pendente')
                RETURNING id_compra
            ");
            $stmt->execute([':fk_usuario' => $idUsuario]);
            $idCompra = $stmt->fetchColumn();

            if (!$idCompra) {
                throw new Exception("Não foi possível criar a compra.");
            }

            // 2️⃣ Insere os produtos da compra
            $stmtProd = $this->conexao->prepare("
                INSERT INTO compra_produto (fk_produto, fk_compra, valor_unitario, quantidade)
                VALUES (:fk_produto, :fk_compra, :valor_unitario, :quantidade)
            ");

            foreach ($carrinho as $item) {
                $stmtProd->execute([
                    ':fk_produto' => $item['id'],
                    ':fk_compra' => $idCompra,
                    ':valor_unitario' => $item['preco'],
                    ':quantidade' => $item['quantidade']
                ]);
            }

            $this->conexao->commit();

            return $idCompra;

        } catch (Exception $e) {
            $this->conexao->rollBack();
            throw new Exception("Erro ao criar compra: " . $e->getMessage());
        }
    }

    public function atualizarStatus($idCompra, $novoStatus)
    {
        try {
            $this->conexao->beginTransaction();

            // Atualiza o status da compra diretamente
            $update = $this->conexao->prepare("
                UPDATE Compra 
                SET status_compra = :novo 
                WHERE id_compra = :id
            ");
            $update->execute([
                ':novo' => $novoStatus,
                ':id' => $idCompra
            ]);

            $this->conexao->commit();

        } catch (Exception $e) {
            $this->conexao->rollBack();
            throw new Exception("Erro ao atualizar status: " . $e->getMessage());
        }
    }

}
