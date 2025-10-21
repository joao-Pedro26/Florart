<?php
session_start();
require_once '../controller/compraController.php';
$compraFinalizada = $_SESSION['compra_finalizada'] ?? null;
if ($compraFinalizada) unset($_SESSION['compra_finalizada']);

date_default_timezone_set('America/Sao_Paulo');

if (!isset($_SESSION['statusLogado']) || $_SESSION['statusLogado'] !== true) {
    header('Location: login.php');
    exit;
}

$carrinho = $_SESSION['carrinho'] ?? [];
$controller = new CompraController();
$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['finalizar'])) {
    if (!empty($carrinho)) {
        try {
            // 🔹 Captura o acréscimo enviado pelo formulário
            $acrescimoTotal = floatval($_POST['acrescimo_total'] ?? 0);
            $_SESSION["acrescimo_total"] = $_POST['acrescimo_total'] ?? 0;

            // 🔹 Envia o valor como parâmetro para o controller
            $idCompra = $controller->finalizarCompra($acrescimoTotal);

            $_SESSION['compra_finalizada'] = [
                'id' => $idCompra,
                'data' => date('d/m/Y H:i')
            ];
            header("Location: finaliza-compra.php");
            exit;

        } catch (Exception $e) {
            $mensagem = "Erro ao finalizar compra: " . $e->getMessage();
        }
    } else {
        $mensagem = "Seu carrinho está vazio!";
    }
}

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
    <link rel="stylesheet" href="../styles/modal.css">
    <link rel="stylesheet" href="../styles/header-footer.css">
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <title>Finalizar Compra</title>
</head>
<body>
    <?php include '../components/cabecalho.php'; ?>
    <main>
        <a href="javascript:history.back()"><i class='bxr  bx-arrow-left-stroke icon-voltar' style='color:#5a2ff4'></i> </a>


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
                    <p>Valor Total: <span id="valorTotal">R$ <?= number_format($totalValor, 2, ',', '.') ?></span></p>

                    <!-- 🔹 Novo campo para acréscimo/desconto -->
            

                    <p><strong>Total Final:</strong> <span id="valorFinal">R$ <?= number_format($totalValor, 2, ',', '.') ?></span></p>

                    <?php if (!empty($carrinho)): ?>
                        <form method="POST" id="formFinalizar">
                            <input type="hidden" name="acrescimo_total" id="inputHiddenAcrescimo" value="0">
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

    <?php if ($compraFinalizada): ?>
        <div class="modal-compra" id="modalCompra">
            <div class="modal-conteudo">
                <h2>🎉 Compra Finalizada!</h2>
                <p><strong>ID da compra:</strong> <?= htmlspecialchars($compraFinalizada['id']) ?></p>
                <p><strong>Data:</strong> <?= htmlspecialchars($compraFinalizada['data']) ?></p>
                <button id="btnFecharModal">Fechar</button>
            </div>
        </div>
    <?php endif; ?>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('modalCompra');
        const btnFechar = document.getElementById('btnFecharModal');
        if (modal && btnFechar) {
            modal.style.display = 'flex';
            btnFechar.addEventListener('click', () => {
                modal.style.opacity = '0';
                setTimeout(() => modal.remove(), 300);
            });
        }

        // 🔹 Atualiza total dinamicamente
        const acrescimoInput = document.getElementById('acrescimo_total');
        const valorTotalEl = document.getElementById('valorTotal');
        const valorFinalEl = document.getElementById('valorFinal');
        const inputHidden = document.getElementById('inputHiddenAcrescimo');

        const valorBase = <?= $totalValor ?>;

        acrescimoInput.addEventListener('input', () => {
            const acrescimo = parseFloat(acrescimoInput.value) || 0;
            const totalFinal = valorBase + acrescimo;
            valorFinalEl.textContent = "R$ " + totalFinal.toFixed(2).replace('.', ',');
            inputHidden.value = acrescimo; // envia o valor para o PHP
        });
    });
    </script>
        <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('modalCompra');
            const btnFechar = document.getElementById('btnFecharModal');
            if (modal && btnFechar) {
                modal.style.display = 'flex';
                btnFechar.addEventListener('click', () => {
                    modal.style.opacity = '0';
                    setTimeout(() => modal.remove(), 300);
                    window.location.href = 'home.php';

                });
            }
        });
        </script>

</body>
</html>
