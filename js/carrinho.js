const btnCarrinhoTopo = document.querySelector('header .icon-carrinho-perfil button');
const btnCarrinhoInferior = document.querySelector('.menu-inferior .icon-carrinho-perfil button')
const carrinho = document.querySelector('.carrinho');
const btnFechar = document.querySelector('.carrinho .fechar');
const overlay = document.querySelector('.overlay');

[btnCarrinhoTopo, btnCarrinhoInferior].forEach(btn => {
  if (btn) {
    btn.addEventListener('click', () => {
      carrinho.classList.add('ativo');
      overlay.classList.add('ativo');
    });
  }
});

btnFechar.addEventListener('click', () => {
    carrinho.classList.remove('ativo');
    overlay.classList.remove('ativo');
});

overlay.addEventListener('click', () => {
    carrinho.classList.remove('ativo');
    overlay.classList.remove('ativo');
});

document.addEventListener("DOMContentLoaded", () => {
    fetch('pegar_carrinho.php')
    .then(res => res.json())
    .then(data => {
        if (data.carrinho && data.carrinho.length > 0) {
            localStorage.setItem("carrinho", JSON.stringify(data.carrinho));
            atualizarCarrinho();
        }
    });
});
//metodos funcionais
document.querySelectorAll(".btn-add-carrinho").forEach(btn => {
    btn.addEventListener("click", () => {
        const produto = {
            id: btn.dataset.id,
            nome: btn.dataset.nome,
            preco: parseFloat(btn.dataset.preco),
            imagem: btn.dataset.imagem,
            quantidade: 1
        };

        let carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];
        const idx = carrinho.findIndex(p => p.id === produto.id);
        if (idx > -1) {
            carrinho[idx].quantidade += 1;
        } else {
            carrinho.push(produto);
        }

        localStorage.setItem("carrinho", JSON.stringify(carrinho));
        atualizarCarrinho();
        salvarCarrinhoNoServidor();
    });
});

function atualizarCarrinho() {
    const carrinhoDiv = document.querySelector(".carrinho-produtos");
    const totalSpan = document.querySelector(".valor-carrinho");
    let carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];

    carrinhoDiv.innerHTML = '';
    let total = 0;

    carrinho.forEach((p, index) => {
        const item = document.createElement("div");
        item.classList.add("item-carrinho");
        item.innerHTML = `
            <img src="${p.imagem}" alt="${p.nome}" class="img-carrinho">
            <div class="info-carrinho">
                <p class="nome-carrinho">${p.nome}</p>
                <p>
                    Qtd: 
                    <button class="quantidade-btn" data-acao="diminuir" data-index="${index}">-</button>
                    <span>${p.quantidade}</span>
                    <button class="quantidade-btn" data-acao="aumentar" data-index="${index}">+</button>
                </p>
                <p class="preco-carrinho">R$ ${(p.preco * p.quantidade).toFixed(2)}</p>
                <button class="remover-item" data-index="${index}">Remover</button>
            </div>
        `;
        carrinhoDiv.appendChild(item);
        total += p.preco * p.quantidade;
    });

    totalSpan.textContent = `R$ ${total.toFixed(2)}`;
    localStorage.setItem("carrinho", JSON.stringify(carrinho));

    // Adiciona eventos de remover e alterar quantidade
    document.querySelectorAll(".remover-item").forEach(btn => {
        btn.addEventListener("click", () => {
            const idx = parseInt(btn.dataset.index);
            carrinho.splice(idx, 1);
            localStorage.setItem("carrinho", JSON.stringify(carrinho));
            atualizarCarrinho();
            salvarCarrinhoNoServidor();
        });
    });

    document.querySelectorAll(".quantidade-btn").forEach(btn => {
        btn.addEventListener("click", () => {
            const idx = parseInt(btn.dataset.index);
            const acao = btn.dataset.acao;
            if (acao === "aumentar") carrinho[idx].quantidade += 1;
            if (acao === "diminuir" && carrinho[idx].quantidade > 1) carrinho[idx].quantidade -= 1;
            localStorage.setItem("carrinho", JSON.stringify(carrinho));
            atualizarCarrinho();
            salvarCarrinhoNoServidor();
        });
    });
}

function salvarCarrinhoNoServidor() {
    const carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];

    fetch('salvar_carrinho.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ carrinho: carrinho })
    })
    .then(res => res.json())
    .then(data => console.log(data.mensagem))
    .catch(err => console.error(err));
}

document.querySelectorAll(".quantidade-btn, .remover-item, .btn-add-carrinho button")
.forEach(el => el.addEventListener("click", salvarCarrinhoNoServidor));


