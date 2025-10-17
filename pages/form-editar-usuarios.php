<?php
require_once '../controller/usuariosController.php';
$controller = new UsuarioController();

session_start(); // Garante que a sessão esteja ativa para exibir mensagens de erro

// Pega ID da URL
$id = $_GET['id'] ?? null;
if (!$id) die('ID do usuário não informado.');

// Busca o usuário
$usuario = $controller->getUsuarioPorID($id);
if (!$usuario) die('Usuário não encontrado.');

// Processa o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $senha = $_POST['senha'] ?? null;
    $admin = $_POST['admin'] ?? null;

    $resultado = $controller->editarConta($id, $nome, $email, $telefone, $senha, $admin);

    if ($resultado) {
        header('Location: ../pages/admin.php?route=consultas/listarUsuarios');
        exit;
    } else {
        $_SESSION['erro'] = "Erro ao atualizar usuário.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
    <link rel="stylesheet" href="../styles/admin-editar.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>

  <a href="javascript:history.back()" class="voltar-link">
    <i class='bx bx-chevron-left icon-voltar'></i>
  </a>

  <div class="container-editar">

    <h1>Editar Usuário</h1>

    <form method="POST" class="form-editar">
      <label for="nome">Nome:</label>
      <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required>

      <label for="email">E-mail:</label>
      <input type="email" name="email" id="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>

      <label for="telefone">Telefone:</label>
      <input type="text" name="telefone" id="telefone" value="<?= htmlspecialchars($usuario['telefone']) ?>">

      <label for="senha">Nova Senha (opcional):</label>
      <input type="password" name="senha" id="senha" placeholder="Digite nova senha (ou deixe em branco)">

      <label for="admin">
        <input type="checkbox" name="admin" id="admin" value="1" <?= $usuario['admin'] ? 'checked' : '' ?>>
        Tornar usuário administrador
      </label>

      <div class="form-botoes">
        <button type="submit" class="btn-salvar">Salvar</button>
      </div>
    </form>

  </div>

</body>
</html>