<div id="produtos" class="tabela">
  <h2>Gerenciar Produtos</h2>
  <a href="cadastroProduto.php" class="btn-add">+ Adicionar Produto</a>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Preço</th>
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
          <td>
            <button class="btn-editar-produto">
              <a href="formEditarProdutos.php?id=<?= $p['id_produto'] ?>" class="btn-editar">Editar</a>
            </button>

            <button 
              onclick="abrirModalExcluir('Deseja realmente excluir o produto <?= htmlspecialchars($p['nome']) ?>?', 
              () => { window.location.href='admin.php?route=produtos/excluir&id=<?= $p['id_produto'] ?>'; })">
              Excluir
            </button>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="5" class="vazio">Nenhum produto encontrado.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

