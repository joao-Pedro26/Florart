<?php
require_once '../model/produtosModel.php';

class ProdutoController
{
    protected $model;

    public function __construct()
    {
        
        $dsn = "pgsql:host=projetoscti.com.br;port=54432;dbname=eq5.inf2;";
        $username = "eq5.inf2";
        $password = "eq52335";
        $this->model = new ProdutoModel($dsn, $username, $password);
    }

    public function listarProdutos()
    {
        return $this->model->listarProdutos();
    }

    public function cadastrarProduto($nome, $descricao, $tipo, $preco, $imagem)
    {
        try {
            if (!$nome || !$descricao || !$preco)
                throw new Exception("Todos os campos obrigatórios devem ser preenchidos.");

            if (!is_numeric($preco) || $preco <= 0)
                throw new Exception("Preço inválido.");

            return $this->model->criarProduto($nome, $descricao, $tipo, $preco, $imagem);

        } catch (Exception $e) {
            $_SESSION['erro'] = $e->getMessage();
            return false;
        }
    }

    public function editarProduto($id, $nome, $descricao, $tipo, $valor_unitario, $imagem = null)
    {
        try {
            if (!$id || !is_numeric($id))
                throw new Exception("ID inválido.");

            // Passa todos os parâmetros para o método do model que atualiza o produto
            return $this->model->atualizarProduto($id, $nome, $descricao, $tipo, $valor_unitario, $imagem);
        } catch (Exception $e) {
            $_SESSION['erro'] = $e->getMessage();
            return false;
        }
    }

    public function deletarProduto($id)
    {
        try {
            if (!$id || !is_numeric($id))
                throw new Exception("ID inválido.");
            return $this->model->deletarProduto($id);
        } catch (Exception $e) {
            $_SESSION['erro'] = $e->getMessage();
            return false;
        }
    }

    public function buscarProduto($id)
    {
        return $this->model->getProdutoPorId($id);
    }
}
?>