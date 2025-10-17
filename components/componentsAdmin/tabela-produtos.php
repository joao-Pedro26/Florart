<div id="produtos" class="tabela">

  <h2>Gerenciar Produtos</h2>
  <a href="cadastroProduto.php" class="btn-add">+ Adicionar Produto</a>

  <div class="tabela-scroll">

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nome</th>
          <th>Preço</th>
          <th>Tipo</th>
          <th>Descrição</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($produtos)): ?>
          <?php foreach ($produtos as $p): ?>
          <tr>
            <td><?= htmlspecialchars($p['id_produto']) ?></td>
            <td><?= htmlspecialchars($p['nome']) ?></td>
            <td>R$ <?= number_format($p['valor_unitario'], 2, ',', '.') ?></td>
            <td><?= htmlspecialchars($p['tipo']) ?></td>
            <td><?= htmlspecialchars($p['descricao'])?></td>
            <td class="acoes">
              <a href="form-editar-produtos.php?id=<?= $p['id_produto'] ?>" class="btn btn-edit">Editar</a>
              <!-- <a href="admin.php?route=produtos/excluir&id=<?= $p['id_produto']?>" class="btn btn-delete">Deletar</a> -->
              <a href="admin.php?route=produtos/excluir&id=<?= $p['id_produto']?>"  class="btn btn-delete"
                  onclick="return confirm('Tem certeza que deseja deletar este produto?');">
                  Deletar
                </a>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="5" class="vazio">Nenhum produto encontrado.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

  </div>

</div>