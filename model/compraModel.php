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
                INSERT INTO compra (fk_usuario, status_compra, sessao)
                VALUES (:fk_usuario, 'reservado', :sessao)
                RETURNING id_compra
            ");

            $stmt->execute([
                ':fk_usuario' => $idUsuario,
                ':sessao' => session_id()
            ]);
            
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
                UPDATE compra 
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

    // não está sendo utilizado no momento
    public function buscarCompras()
    {
        $stmt = $this->conexao->query("
            SELECT 
                c.id_compra, 
                u.nome AS nome_usuario, 
                c.status_compra, 
                c.data_compra,
                v.valor_total
            FROM compra c
            JOIN usuario u ON c.fk_usuario = u.id_usuario
            LEFT JOIN Valor_Total_Compra v ON c.id_compra = v.id_compra
            ORDER BY c.data_compra DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarComprasComItens() {
        $sql = "SELECT c.id_compra, u.nome AS nome_usuario, c.data_compra, c.status_compra, vt.valor_total
                FROM Compra c
                JOIN Usuario u ON u.id_usuario = c.fk_usuario
                LEFT JOIN Valor_Total_Compra vt ON vt.id_compra = c.id_compra
                ORDER BY c.data_compra ASC";

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();
        $compras = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $ids = array_column($compras, 'id_compra');
        if (count($ids) > 0) {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));

            $sqlItens = "SELECT cp.fk_compra, p.nome, cp.quantidade
                         FROM compra_produto cp
                         JOIN Produto p ON p.id_produto = cp.fk_produto
                         WHERE cp.fk_compra IN ($placeholders)";

            $stmtItens = $this->conexao->prepare($sqlItens);
            $stmtItens->execute($ids);
            $itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);

            $itensPorCompra = [];
            foreach ($itens as $item) {
                $itensPorCompra[$item['fk_compra']][] = $item;
            }
        } else {
            $itensPorCompra = [];
        }

        foreach ($compras as &$compra) {
            $id = $compra['id_compra'];
            if (!empty($itensPorCompra[$id])) {
                $listaItens = array_map(function($i) {
                    return $i['nome'] . " (x" . $i['quantidade'] . ")";
                }, $itensPorCompra[$id]);
                $compra['itens_comprados'] = implode(', ', $listaItens);
            } else {
                $compra['itens_comprados'] = '';
            }
        }
        return $compras;
    }


    public function getComprasPorUsuario($idUsuario)
{
    try {
        // Busca todas as compras do usuário, com total e status
            $sql= "SELECT c.id_compra, c.data, c.status, SUM(cp.quantidade) AS total_quantidade, SUM(cp.quantidade * cp.valor_unitario) AS preco_total
                    FROM compra AS c
                    JOIN compra_produto AS cp ON c.id_compra = cp.fk_compra
                    WHERE c.fk_usuario = :id
                    GROUP BY c.id_compra
                    ORDER BY c.data DESC";
        $stmt = $this->conexao->prepare($sql_pedidos);
        $stmt->execute([':id' => $idUsuario]);
        $pedidosUsuario = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        $compras = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Se não houver compras, retorna um array vazio
        if (!$compras) {
            return [];
        }

        return $compras;

    } catch (Exception $e) {
        throw new Exception("Erro ao buscar compras do usuário: " . $e->getMessage());
    }
}



}