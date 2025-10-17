<div id="compras" class="tabela">
  <h2>Lista de Compras</h2>

  <div class="tabela-scroll">

      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Usuário</th>
            <th>Data</th>
            <th>Status</th>
            <th>Valor Total</th>
            <th>Itens Comprados</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($compras)): ?>
            <?php foreach ($compras as $c): ?>
              <tr>
                <td><?= $c['id_compra'] ?></td>
                <td><?= htmlspecialchars($c['nome_usuario']) ?></td>
                <td><?= date("d/m/Y H:i", strtotime($c['data_compra'])) ?></td>
                <td><?= ucfirst($c['status_compra']) ?></td>
                <td>R$ <?= number_format($c['valor_total'], 2, ',', '.') ?></td>
                <td><?= htmlspecialchars($c['itens_comprados']) ?></td>
                <td class="acoes">
                    <?php if ($c['status_compra'] !== 'cancelado'): ?>
                        <a href="admin.php?route=compras/cancelar&id=<?= $c['id_compra'] ?>" 
                          class="btn btn-delete"
                          onclick="return confirm('Tem certeza que deseja cancelar a compra ID <?= $c['id_compra'] ?>?');">
                          Cancelar
                        </a>
                    <?php else: ?>
                        <em>Cancelada</em>
                    <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="7" class="vazio">Nenhuma compra encontrada.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>

  </div>
  
</div>