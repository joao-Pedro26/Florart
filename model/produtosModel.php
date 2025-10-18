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

    public function criarProduto($nome, $descricao, $tipo, $preco, $imagem = null)
{
    try {
        // 1️⃣ Inserir o produto primeiro (sem imagem)
        $sql = "INSERT INTO produto (nome, descricao, tipo, valor_unitario, imagem)
                VALUES (:nome, :descricao, :tipo, :preco, :imagem)";
        $insert = $this->conexao->prepare($sql);

        $caminhoImagem = null;
        $insert->bindParam(':nome', $nome);
        $insert->bindParam(':descricao', $descricao);
        $insert->bindParam(':tipo', $tipo);
        $insert->bindParam(':preco', $preco);
        $insert->bindParam(':imagem', $caminhoImagem);

        if ($insert->execute()) {
            // 2️⃣ Pega o ID recém-criado para gerar o nome da imagem
            $id = $this->conexao->lastInsertId("produto_id_produto_seq");

            // 3️⃣ Verifica se foi enviada uma imagem
            if ($_FILES && isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                $nomeOriginal = basename($_FILES['imagem']['name']);
                $extensao = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));
                $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array($extensao, $extensoesPermitidas)) {
                    // 5️⃣ Gera nome seguro e único
                    $nomeBase = pathinfo($nomeOriginal, PATHINFO_FILENAME);
                    $nomeBase = preg_replace('/[^a-zA-Z0-9_-]/', '_', $nomeBase); 
                    $novoNome = "image{$id}.{$extensao}";

                    // Caminho final (pasta imagens)
                    $pastaDestino = "../images/";
                    if (!file_exists($pastaDestino)) {
                        mkdir($pastaDestino, 0777, true);
                    }

                    $caminhoCompleto = $pastaDestino . $novoNome;

                    // 6️⃣ Move o arquivo temporário para a pasta final
                    if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoCompleto)) {
                        // Caminho que será salvo no banco (relativo)
                        $caminhoRelativo = "images/" . $novoNome;

                        // 7️⃣ Atualiza o produto com o caminho da imagem
                        $update = $this->conexao->prepare("UPDATE produto SET imagem = :imagem WHERE id_produto = :id");
                        $update->bindParam(':imagem', $caminhoRelativo);
                        $update->bindParam(':id', $id, PDO::PARAM_INT);
                        $update->execute();
                    } else {
                        throw new Exception("Erro ao mover o arquivo de imagem.");
                    }
                } else {
                    throw new Exception("Formato de imagem inválido. Use JPG, PNG ou GIF.");
                }
            }

            return true; // tudo certo!
        } else {
            throw new Exception("Erro ao cadastrar o produto.");
        }

    } catch (Exception $e) {
        $_SESSION['erro'] = $e->getMessage();
        return false;
    }
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