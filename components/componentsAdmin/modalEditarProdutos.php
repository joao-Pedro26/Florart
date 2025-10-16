<div id="modalEditarProdutos" class="modal">
  <div class="modal-conteudo">
    <span id="fecharEditar" class="fechar">&times;</span>
    <h2>Editar Produto</h2>

    <form id="formEditarProdutos" method="POST" action="admin.php?route=produtos/editar" enctype="multipart/form-data">
      <input type="hidden" name="id" id="editarIdProduto">

      <label for="editarNomeProduto">Nome:</label>
      <input type="text" name="nome" id="editarNomeProduto" required>

      <label for="editarTipoProduto">Tipo:</label>
      <select name="tipo" id="editarTipoProduto" required>
        <option value="">Selecione o tipo</option>
        <option value="boho">Boho</option>
        <option value="minimalista">Minimalista</option>
        <option value="gotico">Gótico</option>
        <option value="romantico">Romântico</option>
      </select>

      <label for="editarPrecoProduto">Preço:</label>
      <input type="number" name="valor_unitario" id="editarPrecoProduto" step="0.01" min="0" required>

      <label for="editarImagemProduto">Imagem:</label>
      <input type="file" name="imagem" id="editarImagemProduto" accept="image/*">

      <button type="submit">Salvar Alterações</button>
      <button type="button" onclick="document.getElementById('modalEditarProdutos').style.display='none'">Cancelar</button>
    </form>
  </div>
</div>
