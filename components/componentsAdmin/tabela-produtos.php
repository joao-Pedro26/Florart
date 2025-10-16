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
            <td><?= $p['id_produto'] ?></td>
            <td><?= htmlspecialchars($p['nome']) ?></td>
            <td>R$ <?= number_format($p['valor_unitario'], 2, ',', '.') ?></td>
            <td><?= htmlspecialchars($p['descricao']) ?></td>
            <td class="acoes">
              <button class="btn btn-edit"
                      onclick='abrirModalEditar(<?= json_encode([
                          "id" => $p["id_produto"],
                          "nome" => $p["nome"],
                          "descricao" => $p["descricao"],
                          "preco" => $p["valor_unitario"]
                      ]) ?>, "produto")'>
                Editar
              </button>

              <button class="btn btn-delete"
                      onclick="abrirModalDeletar('produto', <?= $p['id_produto'] ?>, '<?= addslashes($p['nome']) ?>')">
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

