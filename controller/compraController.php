<?php
require_once '../model/compraModel.php';

class CompraController
{
    private $model;

    public function __construct()
    {
        $dsn = "pgsql:host=projetoscti.com.br;port=54432;dbname=eq5.inf2;";
        $username = "eq5.inf2";
        $password = "eq52335";
        $this->model = new CompraModel($dsn, $username, $password);
    }

    public function finalizarCompra()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();

        if (!isset($_SESSION['statusLogado']) || $_SESSION['statusLogado'] !== true || !isset($_SESSION['id'])) {
            throw new Exception("Usuário não está logado ou ID não encontrado na sessão.");
        }

        $idUsuario = $_SESSION['id'];
        $carrinho = $_SESSION['carrinho'] ?? [];

        if (empty($carrinho)) {
            throw new Exception("Carrinho vazio.");
        }

        try {
            $idCompra = $this->model->criarCompra($idUsuario, $carrinho);

            // Limpa o carrinho da sessão
            unset($_SESSION['carrinho']);

           

            return $idCompra;

        } catch (Exception $e) {
            throw new Exception("Erro ao finalizar compra: " . $e->getMessage());
        }
    }

    public function listarCompras()
    {
        return $this->model->buscarComprasComItens();
    }

    public function cancelarCompra($idCompra)
    {
        if (!$idCompra || !is_numeric($idCompra)) {
            throw new Exception("ID da compra inválido.");
        }

        try {
            $affected = $this->model->atualizarStatus($idCompra, 'cancelado');
            if ($affected === 0) {
                throw new Exception("Nenhuma compra foi atualizada, verifique o ID.");
            }
            return true;
        } catch (Exception $e) {
            throw new Exception("Erro ao cancelar a compra: " . $e->getMessage());
        }
    }


       public function getComprasPorUsuario() 
    {
        $idUsuario = $_SESSION['id'];
        return $this->model->getComprasPorUsuario($idUsuario);
    }

}