<?php
require_once '../model/produtosModel.php';

class ProdutoController
{
    protected $model;

    public function __construct()
    {
        $dsn = "pgsql:host=localhost;port=5432;dbname=florart;";
        $username = "postgres";
        $password = "postgres";
        $this->model = new ProdutoModel($dsn, $username, $password);
    }

    public function listarProdutos()
    {
        return $this->model->listarProdutos();
    }

    public function cadastrarProduto($nome, $descricao, $preco, $imagem = null)
    {
        try {
            if (!$nome || !$descricao || !$preco)
                throw new Exception("Todos os campos obrigatórios devem ser preenchidos.");

            if (!is_numeric($preco) || $preco <= 0)
                throw new Exception("Preço inválido.");


            return $this->model->criarProduto($nome, $descricao, $preco, $imagem);

        } catch (Exception $e) {
            $_SESSION['erro'] = $e->getMessage();
            return false;
        }
    }

    public function editarProduto($id, $nome, $descricao, $preco, $imagem = null)
    {
        try {
            if (!$id || !is_numeric($id))
                throw new Exception("ID inválido.");

            return $this->model->atualizarProduto($id, $nome, $descricao, $preco, $imagem);
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