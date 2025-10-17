<div id="usuarios" class="tabela">
  <h2>Gerenciar Usuários</h2>
  <a href="../../pages/cadastro.php" class="btn-add">+ Adicionar Usuário</a>

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
              <button class="btn-editar-usuario" ><a href="formEditarUsuarios.php?id=<?= $u['id_usuario'] ?>" class="btn-editar">Editar</a></button>
              <button 
              onclick="abrirModalExcluir('Deseja realmente excluir o produto <?= htmlspecialchars($p['nome']) ?>?', 
              () => { window.location.href='admin.php?route=produtos/excluir&id=<?= $p['id_produto'] ?>'; })">
              Excluir
            </button>
            

            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="6" class="vazio">Nenhum usuário encontrado.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

