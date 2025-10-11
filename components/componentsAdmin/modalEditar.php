<div id="modalEditar" class="modal">
  <div class="modal-content">
    <span class="close" id="fecharEditar">&times;</span>
    <h2 id="tituloEditar">Editar</h2>

    <form id="formEditar" method="POST" enctype="multipart/form-data">
      <!-- Campos Usuário -->
      <div id="camposUsuario" style="display:none;">
        <input type="hidden" name="idUsuario" id="editarIdUsuario">
        <label>Nome:</label>
        <input type="text" name="nome" id="editarNomeUsuario" required>
        <label>Email:</label>
        <input type="email" name="email" id="editarEmailUsuario" required>
        <label>Telefone:</label>
        <input type="text" name="telefone" id="editarTelefoneUsuario" required>
        <label>Senha (opcional):</label>
        <input type="password" name="senha" id="editarSenhaUsuario">
      </div>

      <!-- Campos Produto -->
      <div id="camposProduto" style="display:none;">
        <input type="hidden" name="idProduto" id="editarIdProduto">
        <label>Nome:</label>
        <input type="text" name="nomeProduto" id="editarNomeProduto" required>
        <label>Descrição:</label>
        <textarea name="descricao" id="editarDescricaoProduto" required></textarea>
        <label>Preço:</label>
        <input type="number" step="0.01" name="preco" id="editarPrecoProduto" required>
        <label>Imagem (opcional):</label>
        <input type="file" name="imagem" id="editarImagemProduto">
      </div>

      <button type="submit">Salvar</button>
    </form>
  </div>
</div>


