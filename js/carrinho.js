const btnCarrinho = document.querySelector('.icon-carrinho-perfil button');
const carrinho = document.querySelector('.carrinho');
const btnFechar = document.querySelector('.carrinho .fechar');
const overlay = document.querySelector('.overlay');

btnCarrinho.addEventListener('click', () => {
    carrinho.classList.add('ativo');
    overlay.classList.add('ativo')
});

btnFechar.addEventListener('click', () => {
    carrinho.classList.remove('ativo');
    overlay.classList.remove('ativo')

});

overlay.addEventListener('click', () => {
    carrinho.classList.remove('ativo');
    overlay.classList.remove('ativo');
});