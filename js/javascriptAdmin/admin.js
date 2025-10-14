document.addEventListener("DOMContentLoaded", () => {

  // ------------------------
  // Modal Edição
  // ------------------------
  const modalEd = document.getElementById("modalEditar");
  const formEditar = document.getElementById("formEditar");
  const fecharEditar = document.getElementById("fecharEditar");
  const fecharEditarBtn = document.getElementById("fecharEditarBtn");

  function abrirModalEditar(btn) {
  const modal = document.getElementById("modalEditar");
  modal.style.display = "flex";

  document.getElementById("editarIdUsuario").value = btn.dataset.id;
  document.getElementById("editarNomeUsuario").value = btn.dataset.nome;
  document.getElementById("editarEmailUsuario").value = btn.dataset.email;
  document.getElementById("editarTelefoneUsuario").value = btn.dataset.telefone;
  document.getElementById("editarSenhaUsuario").value = '';
}

  const fecharModal = () => {
    modalEd.style.display = "none";
  };

  if (fecharEditar) fecharEditar.addEventListener("click", fecharModal);
  if (fecharEditarBtn) fecharEditarBtn.addEventListener("click", fecharModal);

  window.addEventListener("click", (e) => {
    if (e.target === modalEd) fecharModal();
  });

  // ------------------------
  // Modal Exclusão
  // ------------------------
  let idExcluir = null;
  let tipoExcluir = null;
  const modalEx = document.getElementById("modalExcluir");
  const btnCancelarEx = document.getElementById("cancelarExclusao");
  const btnConfirmarEx = document.getElementById("confirmarExclusao");
  const mensagemModal = document.getElementById("mensagemModal");

  window.abrirModalExcluir = (id, nome, telefone, tipo) => {
    idExcluir = id;
    tipoExcluir = tipo;
    if (!modalEx || !mensagemModal) return;

    if (tipo === 'usuario') {
      mensagemModal.textContent = `Tem certeza que deseja excluir o usuário "${nome}" (Tel: ${telefone})?`;
    } else {
      mensagemModal.textContent = `Tem certeza que deseja excluir o produto "${nome}"?`;
    }

    modalEx.style.display = "flex";
  };

  if (btnCancelarEx) {
    btnCancelarEx.addEventListener("click", () => {
      modalEx.style.display = "none";
      idExcluir = null;
      tipoExcluir = null;
    });
  }

  if (btnConfirmarEx) {
    btnConfirmarEx.addEventListener("click", () => {
      if (idExcluir && tipoExcluir) {
        const rota = tipoExcluir === 'usuario' ? 'consultas/deletar' : 'produtos/excluir';
        window.location.href = `admin.php?route=${rota}&id=${idExcluir}`;
      }
    });
  }

  window.addEventListener("click", (e) => {
    if (e.target === modalEx) {
      modalEx.style.display = "none";
      idExcluir = null;
      tipoExcluir = null;
    }
  });

});
