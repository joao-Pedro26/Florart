<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
    if (session_status() !== PHP_SESSION_ACTIVE) 
    {
        session_start();
    }
    if (!isset($_SESSION['statusLogado']) || $_SESSION['statusLogado'] !== true || $_SESSION['admin'] !== true) 
    {
        header('Location: home.php');
        exit;
    }

    require_once "../public/routesUsuarios.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
        $tipo = $_POST['tipo'] ?? '';
        $id = intval($_POST['id'] ?? 0);

        if ($tipo === 'usuario') 
            {
            $nome = trim($_POST['nome'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $telefone = preg_replace('/\D/', '', $_POST['telefone'] ?? '');
            $senha = $_POST['senha'] ?? null;
            $admin = $_POST['admin'] ?? 0;

            // Define a rota
            $_POST['route'] = 'consultas/atualizar';

            // Executa a rota
            $resultado = handleRoute();

            if ($resultado) 
            {
                header("Location: admin.php?aba=usuarios&msg=atualizado");
                exit;
            } 
            else 
            {
                $erro = $_SESSION['erro'] ?? "Erro ao atualizar usuário.";
                unset($_SESSION['erro']);
                echo "<p style='color:red'>$erro</p>";
            }
        }
    }
    elseif ($tipo === 'produto') 
    {
        $nome = trim($_POST['nome'] ?? '');
        $tipo_produto = trim($_POST['tipo_produto'] ?? '');
        $valor_unitario = $_POST['valor_unitario'] ?? 0;
        $estoque = $_POST['estoque'] ?? 0;
        $descricao = trim($_POST['descricao'] ?? '');
        $imagem = $_FILES['imagem']['name'] ?? null;

        $resultado = $produtoController->editarProduto($id, $nome, $descricao, $tipo_produto, $valor_unitario, $estoque, $imagem);
        if ($resultado) 
        {
            header("Location: admin.php?aba=produtos"); // aqui passa a aba correta
            exit;
        } 
        else 
        {
            $erro = $_SESSION['erro'] ?? "Erro ao atualizar produto.";
            unset($_SESSION['erro']);
        }
    }


// ========================== CARREGAR DADOS PARA O FORMULÁRIO ==========================
$tipo = $_GET['tipo'] ?? '';
$id = intval($_GET['id'] ?? 0);
$registro = null;

if ($tipo === 'usuario' && $id) 
{
    $usuarios = $usuarioController->listarUsuarios();
    foreach ($usuarios as $u) 
    {
        if ($u['id_usuario'] === $id) 
        {
            $registro = $u;
            break;
        }
    }
} 
elseif ($tipo === 'produto' && $id) 
{
    $registro = $produtoController->buscarProduto($id);
}

if (!$registro) 
{
    header("Location: admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Editar <?= ucfirst($tipo) ?></title>
    <link rel="stylesheet" href="../styles/editar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <div class="container">

        <?php if ($tipo === 'usuario'): ?>
            <h1>Editar Usuário</h1>
            <?php if (isset($erro)): ?>
                <div class="mensagem-erro"><?= $erro ?></div>
            <?php endif; ?>

            <form class="form-editar" action="editar.php" method="POST">
                <input type="hidden" name="tipo" value="usuario">
                <input type="hidden" name="id" value="<?= $registro['id_usuario'] ?>">

                <label>Nome:</label>
                <input type="text" name="nome" value="<?= htmlspecialchars($registro['nome']) ?>" required>

                <label>Email:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($registro['email']) ?>" required>

                <label>Telefone:</label>
                <input type="text" name="telefone" value="<?= htmlspecialchars($registro['telefone']) ?>" required>

                <div class="label-select">
                    <label for="admin">Admin:</label>
                    <select name="admin" id="admin">
                        <option value="1" <?= $registro['admin'] ? 'selected' : '' ?>>Sim</option>
                        <option value="0" <?= !$registro['admin'] ? 'selected' : '' ?>>Não</option>
                    </select>
                </div>

                <button type="submit" class="btn-salvar">Salvar Alterações</button>

                <a href="admin.php?aba=<?= $tipo === 'usuario' ? 'usuarios' : 'produtos' ?>" class="btn-voltar"><i class="fas fa-arrow-left"></i> Voltar</a>
            </form>

        <?php elseif ($tipo === 'produto'): ?>
            <h1>Editar Produto</h1>
            <?php if (isset($erro)): ?>
                <div class="mensagem-erro"><?= $erro ?></div>
            <?php endif; ?>


            <form class="form-editar" action="editar.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="tipo" value="produto">
                <input type="hidden" name="id" value="<?= $registro['id_produto'] ?>">

                <label>Nome:</label>
                <input type="text" name="nome" value="<?= htmlspecialchars($registro['nome']) ?>" required>

                <label>Tipo:</label>
                <input type="text" name="tipo_produto" value="<?= htmlspecialchars($registro['tipo']) ?>" required>

                <label>Valor Unitário:</label>
                <input type="number" name="valor_unitario" step="0.01" value="<?= htmlspecialchars($registro['valor_unitario']) ?>" required>

                <label>Estoque:</label>
                <input type="number" name="estoque" value="<?= htmlspecialchars($registro['estoque']) ?>" required>

                <label>Descrição:</label>
                <textarea name="descricao"><?= htmlspecialchars($registro['descricao']) ?></textarea>

                <label>Imagem (opcional):</label>
                <input type="file" name="imagem">

                <button type="submit" class="btn-salvar">Salvar Alterações</button>

                <a href="admin.php?aba=<?= $tipo === 'usuario' ? 'usuarios' : 'produtos' ?>" class="btn-voltar"><i class="fas fa-arrow-left"></i> Voltar</a>
            </form>
        <?php endif; ?>

    </div>

    <!-- ===================== MODAL DE CONFIRMAÇÃO DE SALVAMENTO ===================== -->
    <div id="modalSalvar" class="modal">
        <div class="modal-content">
            <button class="modal-close" onclick="fecharModal()">✕</button>

            <h3 id="tituloModalSalvar">Confirmar Salvamento</h3>
            <p><strong>ID:</strong> <span id="itemId"></span></p>
            <p><strong>Nome:</strong> <span id="itemNome"></span></p>
            <p id="extra1"></p>
            <p id="extra2"></p>
            <p id="extra3"></p>

            <div class="modal-botoes">
                <a id="confirmarSalvar" class="btn btn-add">Sim, salvar</a>
                <button type="button" onclick="fecharModal()" class="btn btn-delete no-border">Cancelar</button>
            </div>
        </div>
    </div>

    <script src="../js/pag-editar/editar.js"></script>

</body>

</html>