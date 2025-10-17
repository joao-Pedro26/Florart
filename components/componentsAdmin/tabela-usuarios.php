<div id="usuarios" class="tabela">

  <h2>Gerenciar Usuários</h2>
  <a href="../../pages/cadastro.php" class="btn-add">+ Adicionar Usuário</a>

  <div class="tabela-scroll">

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nome</th>
          <th>Email</th>
          <th>Telefone</th>
          <th>Admin</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($usuarios)): ?>
          <?php foreach ($usuarios as $u): ?>
            <tr>
              <td><?= $u['id_usuario'] ?></td>
              <td><?= htmlspecialchars($u['nome']) ?></td>
              <td><?= htmlspecialchars($u['email']) ?></td>
              <td><?= htmlspecialchars($u['telefone']) ?></td>
              <td><?= $u['admin'] ? 'Sim' : 'Não' ?></td>
              <td class="acoes">
                <a href="form-editar-usuarios.php?id=<?= $u['id_usuario'] ?>" class="btn btn-edit">Editar</a>
                <a
                  href="#"
                  class="btn btn-delete"
                  onclick="abrirModalExcluir('Deseja realmente excluir o usuário <?= htmlspecialchars($u['nome']) ?>?',
                  () => { window.location.href='admin.php?route=usuarios/excluir&id=<?= $u['id_usuario'] ?>'; })"
                >
                  Excluir
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="6" class="vazio">Nenhum usuário encontrado.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

  </div>

</div>