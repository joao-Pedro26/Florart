<div id="usuarios" class="tabela">
  <h2>Gerenciar Usuários</h2>
  <a href="../../pages/cadastro.php" class="btn-add">+ Adicionar Usuário</a>

  <table>
    <tr>
      <th>ID</th>
      <th>Nome</th>
      <th>Email</th>
      <th>Telefone</th>
      <th>Admin</th>
      <th>Ações</th>
    </tr>

    <?php if (!empty($usuarios)): ?>
      <?php foreach ($usuarios as $u): ?>
        <tr>
          <td><?= $u['id_usuario'] ?></td>
          <td><?= htmlspecialchars($u['nome']) ?></td>
          <td><?= htmlspecialchars($u['email']) ?></td>
          <td><?= htmlspecialchars($u['telefone']) ?></td>
          <td><?= $u['admin'] ? 'Sim' : 'Não' ?></td>
          <td>

            <button
              class="btn btn-edit"
              data-id="<?= $u['id_usuario'] ?>"
              data-nome="<?= htmlspecialchars($u['nome'], ENT_QUOTES) ?>"
              data-email="<?= htmlspecialchars($u['email'], ENT_QUOTES) ?>"
              data-telefone="<?= htmlspecialchars($u['telefone'], ENT_QUOTES) ?>"
              onclick="abrirModalEditar(this)">
              Editar
            </button>

            <button
              class="btn btn-delete"
              onclick="abrirModalDeletar('usuario', <?= $u['id_usuario'] ?>, '<?= htmlspecialchars(addslashes($u['nome'])) ?>')">
              Deletar
            </button>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="6" style="text-align:center;">Nenhum usuário encontrado.</td></tr>
    <?php endif; ?>

  </table>
</div>

 