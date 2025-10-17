<?php
require_once '../controller/usuariosController.php';
$controller = new UsuarioController();

// pega ID
$id = $_GET['id'] ?? null;
if (!$id) die('ID do usuário não informado.');

// pega usuário (usar model diretamente se o controller não tiver)
$usuario = $controller->getUsuarioPorId($id); 
if (!$usuario) die('Usuário não encontrado.');

// processa formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $senha = $_POST['senha'] ?? null;

    $resultado = $controller->editarConta($id, $nome, $email, $telefone, $senha);

    if ($resultado) {
        header('Location: ../pages/admin.php?route=consultas/listarUsuarios');
        exit;
    } else {
        // erro já vai estar em $_SESSION['erro']
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
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
        <h1>Editar Usuário</h1>
        <form method="POST">
            <label>Nome:</label>
            <input type="text" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required>

            <label>E-mail:</label>
            <input type="text" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>

            <label>Telefone:</label>
            <input type="text" name="telefone" value="<?= htmlspecialchars($usuario['telefone']) ?>">

            <label>Nova Senha (opcional):</label>
            <input type="password" name="senha">

            <button type="submit" class="btn-salvar">Salvar Alterações</button>
            <a href="admin.php?route=consultas/listarUsuarios" class="btn-voltar">Cancelar</a>
        </form>
    </div>
</body>
</html>
