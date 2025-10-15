<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Redireciona se não estiver logado
if (!isset($_SESSION['statusLogado']) || $_SESSION['statusLogado'] !== true) {
    header('Location: home.php');
    exit;
}

// Pega o carrinho da sessão
$carrinho = $_SESSION['carrinho'] ?? [];
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
            <i class='bxr bx-arrow-left-stroke icon-voltar' style='color:#5a2ff4'></i>
        </a>

        <section class="finaliza-compra">
            <div class="titulo-finaliza">
                <h2>Finalizar Compra</h2>
            </div>

            <section class="div">
                <section class="lista-produtos">
                    <?php if (!empty($carrinho)): ?>
                        <?php
                        $totalItens = 0;
                        $totalValor = 0;
                        foreach ($carrinho as $produto):
                            $subtotal = $produto['preco'] * $produto['quantidade'];
                            $totalItens += $produto['quantidade'];
                            $totalValor += $subtotal;
                        ?>
                        <div class="produto">
                            <input type="checkbox" name="produto" checked>
                            <div class="imagem-produto">
                                <img src="<?= htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                            </div>
                            <div class="div-nome">
                                <h4><?= htmlspecialchars($produto['nome']) ?></h4>
                            </div>
                            <div class="div-quantidade">
                                <input type="number" name="quantidade" min="1" value="<?= (int)$produto['quantidade'] ?>" disabled>
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
                    <p>Total de Itens: <?= $totalItens ?? 0 ?></p>
                    <p>Valor Total: R$ <?= number_format($totalValor ?? 0, 2, ',', '.') ?></p>
                    <button class="botao-finalizar">Finalizar Compra</button>
                </section>
            </section>
        </section>
    </main>
</body>
</html>
