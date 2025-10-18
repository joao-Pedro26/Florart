<?php
session_start();
require_once '../controller/produtosController.php';

$controller = new ProdutoController();

$mensagem = '';
$tipoMensagem = ''; // sucesso ou erro

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $preco = $_POST['preco'] ?? '';
    $imagem = $_FILES['imagem']['name'] ?? null;

    // Chama o método do controller para cadastrar
    $resultado = $controller->cadastrarProduto($nome, $descricao, $tipo, $preco, $imagem);

    if ($resultado) {
        // Redireciona para a listagem de produtos
        header('Location: admin.php?route=produtos/listarProdutos');
        exit;
    } else {
        $mensagem = $_SESSION['erro'] ?? "Erro ao cadastrar o produto.";
        $tipoMensagem = "erro";
        unset($_SESSION['erro']);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Cadastrar Produto</title>
    <link rel="stylesheet" href="../styles/admin-editar.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>

    <a href="javascript:history.back()" class="voltar-link">
        <i class='bx bx-chevron-left icon-voltar'></i>
    </a>

    <div class="container-editar">
        <h1>Cadastrar Produto</h1>

        <?php if ($mensagem): ?>
            <p class="mensagem <?= $tipoMensagem ?>"><?= htmlspecialchars($mensagem) ?></p>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required maxlength="100" />

            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao" maxlength="255"></textarea>

            <label for="tipo">Tipo:</label>
            <select id="tipo" name="tipo" required>
                <option value="">-- Selecione --</option>
                <option value="gotico">Gótico</option>
                <option value="romantico">Romântico</option>
                <option value="boho">Boho</option>
                <option value="minimalista">Minimalista</option>
            </select>

            <label for="preco">Preço (R$):</label>
            <input type="number" id="preco" name="preco" step="0.01" min="0" required />

            <label for="imagem">Imagem:</label>
            <input type="file" id="imagem" name="imagem" accept="image/*" />

            <div class="form-botoes">
                <button class="btn-salvar" type="submit">Cadastrar produto</button>
            </div>
        </form>
    </div>

</body>
</html>
