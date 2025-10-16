<?php
session_start();
require_once '../controller/compraController.php';

// Redireciona se não estiver logado
if (!isset($_SESSION['statusLogado']) || $_SESSION['statusLogado'] !== true) {
    header('Location: home.php');
    exit;
}

$carrinho = $_SESSION['carrinho'] ?? [];
$controller = new CompraController();
$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['finalizar'])) {
    if (!empty($carrinho)) {
        try {
            $idCompra = $controller->finalizarCompra(); // Cria compra e limpa carrinho
            $mensagem = "Compra finalizada com sucesso! ID da compra: $idCompra";
        } catch (Exception $e) {
            $mensagem = "Erro ao finalizar compra: " . $e->getMessage();
        }
    } else {
        $mensagem = "Seu carrinho está vazio!";
    }
}

// Calcula total de itens e valor
$totalItens = 0;
$totalValor = 0;
foreach ($carrinho as $produto) {
    $subtotal = $produto['preco'] * $produto['quantidade'];
    $totalItens += $produto['quantidade'];
    $totalValor += $subtotal;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/reset.css">
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/finaliza-compra.css">
    <link rel="stylesheet" href="../styles/header-footer.css">
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>

    <title>Finalizar Compra</title>
</head>
<body>
    <?php include '../components/cabecalho.php'; ?>
    <main>
        <a href="javascript:history.back()">
            <i class='bx bx-arrow-back icon-voltar' style='color:#5a2ff4'></i>
        </a>

        <section class="finaliza-compra">
            <div class="titulo-finaliza">
                <h2>Finalizar Compra</h2>
            </div>

            <?php if ($mensagem): ?>
                <p class="mensagem-compra"><?= htmlspecialchars($mensagem) ?></p>
            <?php endif; ?>

            <section class="div">
                <section class="lista-produtos">
                    <?php if (!empty($carrinho)): ?>
                        <?php foreach ($carrinho as $produto): 
                            $subtotal = $produto['preco'] * $produto['quantidade'];
                        ?>
                        <div class="produto">
                            <div class="imagem-produto">
                                <img src="<?= htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                            </div>
                            <div class="div-nome">
                                <h4><?= htmlspecialchars($produto['nome']) ?></h4>
                            </div>
                            <div class="div-quantidade">
                                <input type="number" min="1" value="<?= (int)$produto['quantidade'] ?>" disabled>
                            </div>
                            <div class="div-preco">
                                <p>R$ <?= number_format($subtotal, 2, ',', '.') ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Seu carrinho está vazio 😢</p>
                    <?php endif; ?>
                </section>

                <section class="resumo-compra">
                    <h3>Resumo da Compra</h3>
                    <p>Total de Itens: <?= $totalItens ?></p>
                    <p>Valor Total: R$ <?= number_format($totalValor, 2, ',', '.') ?></p>

                    <?php if (!empty($carrinho)): ?>
                        <form method="POST">
                            <button class="botao-finalizar" name="finalizar">Finalizar Compra</button>
                        </form>
                    <?php else: ?>
                        <a href="home.php">
                            <button class="botao-finalizar">Voltar à loja</button>
                        </a>
                    <?php endif; ?>
                </section>
            </section>
        </section>
    </main>
</body>
</html>
