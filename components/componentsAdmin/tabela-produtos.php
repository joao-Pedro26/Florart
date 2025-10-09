
<div id="produtos" class="tabela">
  <h2>Gerenciar Produtos</h2>
  <a href="cadastroProduto.php" class="btn-add">+ Adicionar Produto</a>
  <table>
    <tr>
      <th>ID</th>
      <th>Nome</th>
      <th>Preço</th>
      <th>descrição</th>
      <th>Ações</th>
    </tr>
    <?php if (!empty($produtos)): ?>
      <?php foreach ($produtos as $p): ?>
        <tr>
          <td><?= $p['id_produto'] ?></td>
          <td><?= htmlspecialchars($p['nome']) ?></td>
          <td>R$ <?= number_format($p['valor_unitario'], 2, ',', '.') ?></td>
          <td><?= htmlspecialchars($p['descricao']) ?></td>
          <td>
            <a href="admin.php?route=produtos/atualizar&id=<?= $p['id_produto'] ?>" class="btn btn-edit">Editar</a>
            <a href="admin.php?route=produtos/deletar&id=<?= $p['id_produto'] ?>" class="btn btn-delete">Excluir</a>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="5">Nenhum produto encontrado.</td></tr>
    <?php endif; ?>
  </table>
</div>