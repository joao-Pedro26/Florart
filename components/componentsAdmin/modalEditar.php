<div id="modalEditar" class="modal">
  <div class="modal-conteudo">
    <span id="fecharEditar" class="fechar">&times;</span>
    <h2>Editar Usuário</h2>

    <form id="formEditar" method="POST" action="admin.php?route=consultas/atualizar">
      <input type="hidden" name="tipo" value="usuario">
      <input type="hidden" name="id" id="editarIdUsuario">

      <label>Nome:</label>
      <input type="text" name="nome" id="editarNomeUsuario" required>

      <label>Email:</label>
      <input type="email" name="email" id="editarEmailUsuario" required>

      <label>Telefone:</label>
      <input type="text" name="telefone" id="editarTelefoneUsuario" required>

      <label>Senha:</label>
      <input type="password" name="senha" id="editarSenhaUsuario" placeholder="Deixe vazio para manter">

      <button type="submit">Salvar Alterações</button>
      <button type="button" onclick="document.getElementById('modalEditar').style.display='none'">Cancelar</button>
    </form>

  </div>
</div>


