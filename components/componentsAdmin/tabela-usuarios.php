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
            <a href="#"
               class="btn btn-edit"
               onclick='abrirModalEditar(<?= json_encode([
                   "id" => $u["id_usuario"],
                   "nome" => $u["nome"],
                   "email" => $u["email"],
                   "telefone" => $u["telefone"]
               ]) ?>, "usuario"); return false;'>
               Editar
            </a>

            <a href="#"
               class="btn btn-delete"
               onclick="abrirModalExcluir(<?= $u['id_usuario'] ?>, '<?= addslashes($u['nome']) ?>', '<?= addslashes($u['telefone']) ?>', 'usuario'); return false;">
               Excluir
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="6" style="text-align:center;">Nenhum usuário encontrado.</td></tr>
    <?php endif; ?>
  </table>
</div>

 