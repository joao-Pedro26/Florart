    const titulo = document.getElementById("titulo-apresentacao");
const mensagens = [
    "Bem-vindo a Florart.",
    "Flores com vasos exclusivos."
];
let i = 0;
let j = 0;
let apagando = false;

function maquinaEscrever() {
    if (!apagando) {
        titulo.textContent += mensagens[i].charAt(j);
        j++;
        if (j === mensagens[i].length) {
            setTimeout(() => { apagando = true; maquinaEscrever(); }, 1500);
            return;
        }
    } else {
        titulo.textContent = titulo.textContent.slice(0, -1);
        if (titulo.textContent.length === 0) {
            apagando = false;
            i = (i + 1) % mensagens.length;
            j = 0;
        }
    }
    setTimeout(maquinaEscrever, apagando ? 50 : 100);
}

document.addEventListener("DOMContentLoaded", maquinaEscrever);