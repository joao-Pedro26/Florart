<?php
session_start();

require_once '../controller/produtosController.php';

$controller = new ProdutoController();

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    die("ID do produto inválido.");
}

// Buscar produto
$produto = $controller->buscarProduto($id);
if (!$produto) {
    die("Produto não encontrado.");
}

$mensagem = '';
$tipoMensagem = ''; // sucesso ou erro

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $preco = $_POST['preco'] ?? '';
    $imagem = $_FILES['imagem']['name'] ?? null;

    $resultado = $controller->editarProduto($id, $nome, $descricao, $tipo, $preco, $imagem);

    if ($resultado) {
        $mensagem = "Produto atualizado com sucesso!";
        $tipoMensagem = "sucesso";
        // Atualiza os dados para refletir no form
        $produto = $controller->buscarProduto($id);
    } else {
        // A mensagem de erro está em $_SESSION['erro'], se preferir pode capturar aqui
        $mensagem = $_SESSION['erro'] ?? "Erro ao atualizar o produto.";
        $tipoMensagem = "erro";
        unset($_SESSION['erro']);
    }
}

// Função para selected no select
function selected($value, $selected)
{
    return $value === $selected ? 'selected' : '';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Editar Produto</title>
    <link rel="stylesheet" href="../styles/admin-editar.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>

    <a href="javascript:history.back()" class="voltar-link">
        <i class='bx bx-chevron-left icon-voltar'></i>
    </a>

    <div class="container-editar">
        <h1>Editar Produto</h1>
        
        <form method="post" enctype="multipart/form-data">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required maxlength="100" value="<?= htmlspecialchars($produto['nome']) ?>" />
        
            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao" maxlength="255"><?= htmlspecialchars($produto['descricao']) ?></textarea>
        
            <label for="tipo">Tipo:</label>
            <select id="tipo" name="tipo" required>
                <option value="">-- Selecione --</option>
                <option value="gotico" <?= selected('gotico', $produto['tipo']) ?>>Gótico</option>
                <option value="romantico" <?= selected('romantico', $produto['tipo']) ?>>Romântico</option>
                <option value="boho" <?= selected('boho', $produto['tipo']) ?>>Boho</option>
                <option value="minimalista" <?= selected('minimalista', $produto['tipo']) ?>>Minimalista</option>
            </select>
        
            <label for="preco">Preço (R$):</label>
            <input type="number" id="preco" name="preco" step="0.01" min="0" required value="<?= htmlspecialchars($produto['valor_unitario']) ?>" />
        
            <label for="imagem">Imagem (deixe vazio para manter a atual)</label>
            <input type="file" id="imagem" name="imagem" accept="image/*" />
        
            <div class="form-botoes">
                <button class="btn-salvar" type="submit">Salvar alterações</button>
            </div>
        </form>
    </div>

</body>
</html>