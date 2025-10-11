document.addEventListener("DOMContentLoaded", () => {

  // ------------------------
  // Modal Exclusão
  // ------------------------
  let idExcluir = null;
  let tipoExcluir = null;

  const modalEx = document.getElementById("modalExcluir");
  const btnCancelarEx = document.getElementById("cancelarExclusao");
  const btnConfirmarEx = document.getElementById("confirmarExclusao");
  const mensagemModal = document.getElementById("mensagemModal");

  // Função global para abrir modal de exclusão
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

  // ------------------------
  // Modal Edição
  // ------------------------
  const modalEd = document.getElementById("modalEditar");
  const formEditar = document.getElementById("formEditar");
  const fecharEditar = document.getElementById("fecharEditar");

  const camposUsuario = document.getElementById("camposUsuario");
  const camposProduto = document.getElementById("camposProduto");

  window.abrirModalEditar = (data, tipo) => {
    if (!modalEd || !formEditar) return;

    modalEd.style.display = "flex";

    if (tipo === 'usuario') {
      camposUsuario.style.display = 'block';
      camposProduto.style.display = 'none';

      // Preenche campos usuário
      document.getElementById("editarIdUsuario").value = data.id;
      document.getElementById("editarNomeUsuario").value = data.nome;
      document.getElementById("editarEmailUsuario").value = data.email;
      document.getElementById("editarTelefoneUsuario").value = data.telefone;
      document.getElementById("editarSenhaUsuario").value = '';

      // required apenas nos campos visíveis
      document.getElementById("editarNomeUsuario").required = true;
      document.getElementById("editarEmailUsuario").required = true;
      document.getElementById("editarTelefoneUsuario").required = true;

      document.getElementById("editarNomeProduto").required = false;
      document.getElementById("editarDescricaoProduto").required = false;
      document.getElementById("editarPrecoProduto").required = false;

      formEditar.action = "admin.php?route=consultas/atualizar";

    } else {
      camposUsuario.style.display = 'none';
      camposProduto.style.display = 'block';

      // Preenche campos produto
      document.getElementById("editarIdProduto").value = data.id;
      document.getElementById("editarNomeProduto").value = data.nome;
      document.getElementById("editarDescricaoProduto").value = data.descricao;
      document.getElementById("editarPrecoProduto").value = data.preco;
      document.getElementById("editarImagemProduto").value = '';

      // required apenas nos campos visíveis
      document.getElementById("editarNomeUsuario").required = false;
      document.getElementById("editarEmailUsuario").required = false;
      document.getElementById("editarTelefoneUsuario").required = false;

      document.getElementById("editarNomeProduto").required = true;
      document.getElementById("editarDescricaoProduto").required = true;
      document.getElementById("editarPrecoProduto").required = true;

      formEditar.action = "admin.php?route=produtos/editar";
    }
  };

  if (fecharEditar) {
    fecharEditar.addEventListener("click", () => {
      modalEd.style.display = "none";
    });
  }

  window.addEventListener("click", (e) => {
    if (e.target === modalEd) modalEd.style.display = "none";
  });

  // =========================
  // Submissão Form
  // =========================
  if (formEditar) {
    formEditar.addEventListener("submit", (e) => {
      e.preventDefault();
      const dados = new FormData(formEditar);

      // Se quiser apenas log para teste:
      // for (let pair of dados.entries()) console.log(pair[0]+": "+pair[1]);

      // Envia via fetch
      fetch(formEditar.action, {
        method: "POST",
        body: dados
      })
      .then(res => res.text())
      .then(resp => {
        console.log(resp);
        modalEd.style.display = "none"; // fecha modal
        location.reload(); // atualiza a tabela
      })
      .catch(err => console.error(err));
    });
  }

});
