

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Painel Administrativo</title>
  <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
  <?php
session_start();
if (!isset($_SESSION['statusLogado']) || $_SESSION['statusLogado'] !== true) {
    header("Location: home.php");
    exit;
}

require_once "../controller/usuariosController.php";
$controller = new UsuarioController;

// Se veio um pedido de exclusão
if (isset($_GET['deletar'])) {
    $id = intval($_GET['deletar']); // protege contra injeção
    $controller->deletarConta($id);
    header("Location: admin.php"); // recarrega a página sem a query
    exit;
}

$usuarios = $controller->listarUsuarios();
?>
  <?php include '../components/cabecalho.php';?>

  <h1>Painel Administrativo</h1>

  <!-- Botões para trocar de tabela -->
  <div>
    <button onclick="mostrarTabela('usuarios')">Usuários</button>
    <!--<button onclick="mostrarTabela('produtos')">Produtos</button>-->
  </div>

  <!-- Tabela Usuários -->
  <div id="usuarios" class="tabela">
    <h2>Gerenciar Usuários</h2>
    <a href="cadastroTeste.php" class="btn">Adicionar Usuário</a>
    <table>
      <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Telefone</th>
        <th>Admin</th>
        <th>Ações</th>
      </tr>
      <?php foreach ($usuarios as $u): ?>
        <tr>
          <td><?= $u['id_usuario'] ?></td>
          <td><?= $u['nome'] ?></td>
          <td><?= $u['email'] ?></td>
          <td><?= $u['telefone'] ?></td>
          <td><?= $u['admin'] ? 'Sim' : 'Não' ?></td>
          <td>
            <a href="editarUsuario.php?id=<?= $u['id_usuario'] ?>" class="btn">Editar</a>
            <a href="admin.php?deletar=<?= $u['id_usuario'] ?>" class="btn" 
            onclick="return confirm('Tem certeza que deseja excluir este usuário?');">Excluir</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>

  

  <script>
    let tabelaVisivel = null;

    function mostrarTabela(tipo) {
      const usuarios = document.getElementById('usuarios');
      const produtos = document.getElementById('produtos');

      if (tabelaVisivel === tipo) {
        // Se já está visível, esconda
        document.getElementById(tipo).classList.add('hidden');
        tabelaVisivel = null;
      } else {
        // Esconde as duas
        usuarios.classList.add('hidden');
        produtos.classList.add('hidden');

        // Mostra a desejada
        document.getElementById(tipo).classList.remove('hidden');
        tabelaVisivel = tipo;
      }
    }
  </script>

  <?php include '../components/rodape.php';?>

</body>
</html>
