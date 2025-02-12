const elements = document.querySelectorAll('body, h1, h2, p, a, button, li'); // Seleciona todos os elementos que possuem texto
let fontSizeChange = 0; // Controle de quanto aumentamos ou diminuímos a fonte

// Armazena o tamanho de fonte original de cada elemento
const originalFontSizes = Array.from(elements).map(el => {
    return window.getComputedStyle(el).fontSize;
});

// Função para ajustar o tamanho da fonte
function ajustarFonte(acao) {
    if (acao === 'aumentar') {
        fontSizeChange += 2;
    } else if (acao === 'diminuir') {
        fontSizeChange -= 2;
    }

    // Aplica o novo tamanho de forma proporcional a cada elemento
    elements.forEach((el, index) => {
        const originalSize = parseFloat(originalFontSizes[index]);
        el.style.fontSize = (originalSize + fontSizeChange) + 'px';
    });
}

// Eventos para os botões
document.getElementById('aumentarFonte').addEventListener('click', () => ajustarFonte('aumentar'));
document.getElementById('diminuirFonte').addEventListener('click', () => ajustarFonte('diminuir'));