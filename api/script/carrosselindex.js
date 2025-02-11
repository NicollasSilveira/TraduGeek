// Funções de Modal
function abrirModal(membro, disableAnimation = false) {
  const modal = document.getElementById(`modal-${membro}`);
  if (modal) {
    const conteudo = modal.querySelector('.modal-conteudo');

    // Resetar transformações anteriores
    modal.style.transform = 'none';
    modal.style.opacity = '1';

    modal.style.display = 'flex';

    if (disableAnimation) {
      modal.classList.add('show');
      if (conteudo) conteudo.style.animation = 'none';
    } else {
      conteudo.style.animation = 'none';
      void conteudo.offsetWidth; // Força reflow
      
      // Aplica nova animação
      conteudo.style.animation = 'modalEntradaSuave 0.3s ease-out forwards';
      
      modal.classList.add('show');
    }
  } else {
    console.error(`Modal com id "modal-${membro}" não encontrado!`);
  }
}

function fecharModal(membro) {
  const modal = document.getElementById(`modal-${membro}`);
  if (modal) {
    const conteudo = modal.querySelector('.modal-conteudo');
    
    // Aplica animação de saída
    conteudo.style.animation = 'modalSaidaSuave 0.2s ease-in forwards';
    
    setTimeout(() => {
      modal.classList.remove('show');
      modal.style.display = 'none';
      // Remove a animação após concluir
      conteudo.style.animation = '';
    }, 200);
  }
}

window.onclick = function(event) {
  if (event.target.classList.contains('modal-curriculo')) {
    fecharModal(event.target.id.replace('modal-', ''));
  }
};

function navegarModal(direction) {
  const membros = ["julio", "pamela", "rafael", "patrick", "mari", "nicollas", "gabriel"];
  const modalAtual = document.querySelector('.modal-curriculo.show');

  if (!modalAtual) return;

  const nomeAtual = modalAtual.id.replace("modal-", "");
  const indiceAtual = membros.indexOf(nomeAtual);
  const novoIndice = (direction === 'proximo') 
    ? (indiceAtual + 1) % membros.length 
    : (indiceAtual - 1 + membros.length) % membros.length;
  
  const novoMembro = membros[novoIndice];
  const modalNovo = document.getElementById(`modal-${novoMembro}`);

  if (!modalNovo) return;

  modalNovo.style.display = 'flex';
  modalNovo.classList.remove('show');

  const initialPos = (direction === 'proximo') ? 'translateX(100%)' : 'translateX(-100%)';
  modalNovo.style.transform = initialPos;
  modalNovo.style.opacity = '0';

  modalNovo.offsetWidth;

  modalAtual.style.transition = 'transform 0.5s ease, opacity 0.5s ease';
  modalNovo.style.transition = 'transform 0.5s ease, opacity 0.5s ease';

  modalAtual.style.transform = (direction === 'proximo') ? 'translateX(-100%)' : 'translateX(100%)';
  modalAtual.style.opacity = '0';
  modalNovo.style.transform = 'translateX(0)';
  modalNovo.style.opacity = '1';

  modalAtual.addEventListener('transitionend', function handler(e) {
    if (e.propertyName === 'transform') {
      modalAtual.removeEventListener('transitionend', handler);
      modalAtual.style.display = 'none';
      modalAtual.style.transition = '';
      modalAtual.style.transform = '';
      modalAtual.style.opacity = '';
      modalAtual.classList.remove('show');

      modalNovo.classList.add('show');
      modalNovo.style.transition = '';
      modalNovo.style.transform = '';
      modalNovo.style.opacity = '';
    }
  });

  abrirModal(novoMembro, true);
}

function nextItem() {
    const carrossel = document.querySelector('.carrossel');
    // Anima a transição deslocando 25% (um item)
    carrossel.style.transition = 'transform 0.5s ease';
    carrossel.style.transform = 'translateX(-25%)';
    
    carrossel.addEventListener('transitionend', function handler() {
      // Remove a transição para reposicionar sem animação
      carrossel.style.transition = 'none';
      // Move o primeiro item para o final
      carrossel.appendChild(carrossel.firstElementChild);
      // Reseta a posição
      carrossel.style.transform = 'translateX(0)';
      carrossel.removeEventListener('transitionend', handler);
      updateActive();
    });
  }

  function prevItem() {
    const carrossel = document.querySelector('.carrossel');
    // Remove transição para reposicionar sem animação
    carrossel.style.transition = 'none';
    // Move o último item para o início
    carrossel.insertBefore(carrossel.lastElementChild, carrossel.firstElementChild);
    // Posiciona o carrossel deslocado (um item à esquerda)
    carrossel.style.transform = 'translateX(-25%)';
    
    // Força o reflow e então anima de volta para a posição zero
    setTimeout(() => {
      carrossel.style.transition = 'transform 0.5s ease';
      carrossel.style.transform = 'translateX(0)';
    }, 100);
    updateActive();
  }

  // Define o item ativo (em foco): neste exemplo, o item com índice 1 (segundo item visível)
  function updateActive() {
    const items = document.querySelectorAll('.carrossel .molduraSobreNos');
    items.forEach(item => item.classList.remove('active'));
    if (items.length > 1) {
      items[1].classList.add('active');
    }
  }

  // Atualiza o item ativo ao carregar a página
  window.addEventListener('load', updateActive);