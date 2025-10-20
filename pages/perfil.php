<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once "../controller/usuariosController.php";
require_once "../controller/compraController.php";


$controllerUsuario = new UsuarioController();

$usuario = $controllerUsuario->getUsuarioPorID($_SESSION['id'] ?? 0);

$controllerCompra = new CompraController();

$pedidos = $controllerCompra->getComprasPorUsuario($_SESSION['id'] ?? 0);

if (!isset($_SESSION['statusLogado']) || $_SESSION['statusLogado'] !== true) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/reset.css">
    <link rel="stylesheet" href="../styles/style.css">
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <title>Perfil</title>
</head>

<body class="body-perfil">
    <!-- Cabeçalho -->
    <?php include '../components/cabecalho.php'; ?>
    <main class="main-perfil">
        <a href="javascript:history.back()"><i class='bxr  bx-arrow-left-stroke icon-voltar' style='color:#5a2ff4'></i> </a>
        <img src="/images/logo.png" alt="logo" class="logo-log-cad logo-cad">

        <section class="perfil">
            <h2>Meu Perfil</h2>
            <div class="dados-usuario">
                <div class="icon-editar">
                    <a href="/pages/editar-perfil.php"><i class='bxr  bx-edit icon-editar' style='color:#000000'></i> </a>
                </div>
                <label>Email:</label>
                <div class="campo">
                    <p><?=$usuario['email']?></p>
                </div>
                <label>Nome:</label>
                <div class="campo">
                    <p><?=$usuario['nome']?></p>
                </div>
                <label>Telefone:</label>
                <div class="campo">
                    <p><?=$usuario['telefone']?></p>
                </div>

            </div>
        </section>
            <section class="perfil">
                <h2>Meus Pedidos</h2>
                <div class="produto">
                    <table>
                        <thead>
                            <tr>
                                <th>Pedido Nº</th>
                                <th>Data</th>
                                <th>Quantidade</th>
                                <th>Valor Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($pedidos)): ?>
                                <?php foreach ($pedidos as $pedido): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($pedido['id_compra']) ?></td>
                                        <td><?= htmlspecialchars($pedido['data_compra']) ?></td>
                                        <td><?= htmlspecialchars($pedido['total_quantidade'] ?? 0) ?></td>
                                        <td>R$ <?= number_format($pedido['preco_total'] ?? 0, 2, ',', '.') ?></td>
                                        <td><?= htmlspecialchars($pedido['status_compra']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5">Nenhum pedido encontrado.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>     
                </div>
            </section>

    </main>
    <?php include '../components/menu-mobile.php'; ?>

</body>

</html>