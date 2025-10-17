<?php
require_once '../controller/produtosController.php';
$controller = new ProdutoController();

$id = $_GET['id'] ?? null;
if (!$id) {
    die('ID do produto não informado.');
}

$produto = $controller->buscarPorId($id);
if (!$produto) {
    die('Produto não encontrado.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $tipo = $_POST['tipo'];
    $valor = $_POST['valor_unitario'];
    $descricao = $_POST['descricao'];

    $imagem = $_FILES['imagem']['name'] ?? $produto['imagem'];
    if (!empty($_FILES['imagem']['tmp_name'])) {
        move_uploaded_file($_FILES['imagem']['tmp_name'], "../uploads/" . $imagem);
    }

    $controller->editarProduto($id, $nome, $tipo, $valor, $descricao, $imagem);
    header('Location: ../pages/admin.php?route=produtos/listarProdutos');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Produto</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/admin.css">
</head>
<body>
    <?php if (isset($_SESSION['erro'])): ?>
      <div class="erro" style="color:red; text-align:center; margin:10px 0; ; position: relative; ">
        <?php
        echo htmlspecialchars($_SESSION['erro']);
        unset($_SESSION['erro']);
        ?>
      </div>
    <?php endif; ?>
    <div class="container-editar">
        <h1>Editar Produto</h1>
        <form method="POST" enctype="multipart/form-data">
            <label>Nome:</label>
            <input type="text" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required>

            <label>Tipo:</label>
            <input type="text" name="tipo" value="<?= htmlspecialchars($produto['tipo']) ?>" required>

            <label>Preço (R$):</label>
            <input type="number" name="valor_unitario" value="<?= htmlspecialchars($produto['valor_unitario']) ?>" step="0.01" required>

            <label>Descrição:</label>
            <textarea name="descricao" required><?= htmlspecialchars($produto['descricao']) ?></textarea>

            <label>Imagem:</label>
            <input type="file" name="imagem">
            <?php if (!empty($produto['imagem'])): ?>
                <p>Imagem atual: <img src="../imagens/<?= htmlspecialchars($produto['imagem']) ?>" width="100"></p>
            <?php endif; ?>

            <button type="submit" class="btn-salvar">Salvar Alterações</button>
            <a href="admin.php?route=produtos/listarProdutos" class="btn-voltar">Cancelar</a>
        </form>
    </div>
</body>
</html>
