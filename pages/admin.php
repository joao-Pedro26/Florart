<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Painel Administrativo</title>
  <link rel="stylesheet" href="../styles/style.css">
  <link rel="stylesheet" href="../styles/admin.css">
  <link rel="stylesheet" href="../styles/reset.css">
  <link rel="stylesheet" href="../styles/header-footer.css">
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
  <div class="painel-botoes">
    <button class="tab-btn" onclick="mostrarTabela('usuarios')">Usuários</button>
    <button class="tab-btn" onclick="mostrarTabela('produtos')">Produtos</button>
  </div>

  <!-- Tabela Usuários -->
  <div id="usuarios" class="tabela">
    <h2>Gerenciar Usuários</h2>
    <a href="cadastroTeste.php" class="btn-add">+ Adicionar Usuário</a>
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
            <a href="editar.php?tipo&id=<?= $u['id_usuario'] ?>" class="btn btn-edit">Editar</a>
            <a href="#" 
              class="btn btn-delete"
              onclick="abrirModalExcluir(<?= $u['id_usuario'] ?>, '<?= addslashes($u['nome']) ?>', '<?= $u['telefone'] ?>'); return false;">
              Excluir
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>

  <!-- Tabela Produtos -->
  <div id="produtos" class="tabela hidden">
    <h2>Gerenciar Produtos</h2>
    <p>Ainda não há produtos cadastrados.</p>
  </div>


  <script>
    let tabelaVisivel = 'usuarios'; // deixa a tabela de usuários visível por padrão

    function mostrarTabela(tipo) {
      const tabelas = ['usuarios', 'produtos'];

      tabelas.forEach(t => {
        const el = document.getElementById(t);
        if (t === tipo) {
          el.classList.toggle('hidden'); // alterna visibilidade
          tabelaVisivel = el.classList.contains('hidden') ? null : tipo;
        } else {
          el.classList.add('hidden');
        }
      });
    }

    function abrirModalExcluir(id, nome, telefone) {
      const modal = document.getElementById('modalConfirmacao');
      const texto = document.getElementById('modalTexto');
      const btnConfirmar = document.getElementById('btnConfirmarExcluir');

      texto.innerHTML = `
        <strong>ID:</strong> ${id}<br>
        <strong>Nome:</strong> ${nome}<br>
        <strong>Telefone:</strong> ${telefone}
      `;
      
      btnConfirmar.href = `admin.php?deletar=${id}`;
      modal.style.display = 'flex';
    }

    function fecharModal() {
      document.getElementById('modalConfirmacao').style.display = 'none';
    }

    // Fecha o modal ao clicar fora do conteúdo
    window.onclick = function(event) {
      const modal = document.getElementById('modalConfirmacao');
      if (event.target === modal) {
        fecharModal();
      }
    }

  </script>

  <!-- Modal de confirmação -->
  <div id="modalConfirmacao" class="modal">
    <div class="modal-content">
      <button class="modal-close" onclick="fecharModal()">✕</button>
      <h3>Tem certeza que deseja excluir este usuário?</h3>
      <p id="modalTexto"></p>

      <div class="modal-botoes">
        <a id="btnConfirmarExcluir" class="btn btn-add">Sim</a>
        <button onclick="fecharModal()" class="btn btn-delete no-border">Não</button>
      </div>
    </div>
  </div>


  <?php include '../components/rodape.php';?>

</body>
</html>
