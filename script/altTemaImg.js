const $html = document.querySelector('html');
const $button = document.querySelector('#altTema');
const spanicon = document.getElementById('icon-h1');
const cherryBlossomsContainer = document.getElementById('cherry-blossoms');

// Verifique se as imagens existem na página antes de selecioná-las
const imgJulio = document.getElementById('avatar-julio');
const imgPamela = document.getElementById('avatar-pamela');
const imgRafael = document.getElementById('avatar-rafael');
const imgPatrick = document.getElementById('avatar-patrick');
const imgPamelaEntrada = document.getElementById('avatar-pamela-entrada');
const divImgLogin = document.querySelector('.avatarJuLogin');
const imgAssinatura1 = document.getElementById('imagem-1');
const imgAssinatura2 = document.getElementById('imagem-2');
const imgAssinatura3 = document.getElementById('imagem-3');

// Função para atualizar o background das cerejeiras
let blossomInterval;
let isDarkMode = localStorage.getItem('theme') === 'dark';

function createBlossom() {
  const blossom = document.createElement('div');
  blossom.classList.add('blossom');
  blossom.style.left = Math.random() * 100 + 'vw';
  blossom.style.animationDuration = Math.random() * 3 + 3 + 's';
  blossom.style.opacity = Math.random() + 0.5;
  cherryBlossomsContainer.appendChild(blossom);

  setTimeout(() => {
    blossom.remove();
  }, 5000); 
}

// Função para iniciar a animação das flores com duração de 15 segundos
function startBlossomAnimation() {
  blossomInterval = setInterval(createBlossom, 300); // Cria flores a cada 300ms
  setTimeout(stopBlossomAnimation, 15000); // Para a animação após 15 segundos
}

// Função para parar a animação das flores e iniciar o intervalo de pausa
function stopBlossomAnimation() {
  clearInterval(blossomInterval); // Para a criação de novas flores

  // Aplica a classe fade-out com um atraso individual para cada folha remanescente
  const blossoms = document.querySelectorAll('.blossom');
  blossoms.forEach((blossom, index) => {
    setTimeout(() => {
      blossom.classList.add('fade-out'); // Adiciona a transição suave
      setTimeout(() => blossom.remove(), 2000); // Remove a folha após 2 segundos da transição
    }, index * 200); // Adiciona um atraso de 200ms entre cada folha
  });

  // Agenda o próximo início da animação após 1 minuto
  setTimeout(startBlossomAnimation, 60000);
}

// Função para iniciar ou parar a criação das flores, conforme o tema
function toggleBlossomCreation(isDark) {
  if (isDark) {
    clearInterval(blossomInterval);
    removeBlossoms();
  } else {
    startBlossomAnimation(); // Inicia o loop de animação de flores
  }
}

// Função para remover todas as flores existentes
function removeBlossoms() {
  const blossoms = document.querySelectorAll('.blossom');
  blossoms.forEach(blossom => blossom.remove());
}

// Função para remover todas as flores existentes
function removeBlossoms() {
const blossoms = document.querySelectorAll('.blossom');
blossoms.forEach(blossom => blossom.remove());
}

function toggleTheme(isDark) {
  if (isDark) {
    // Tema escuro
    $html.classList.add('dark-mode');
    $html.classList.remove('light-mode');

    // Remove as flores de cerejeira no modo escuro
    toggleBlossomCreation(true); // Parar flores


  //!!!Altera as imagens para tema escuro, se existirem!!!//
    //Index INÍCIO//
      if (imgJulio) imgJulio.src = 'Imagens/avatar-julio-sobrenos.jpeg';  
      if (imgPamela) imgPamela.src = 'Imagens/avatar-pam-sobrenos.jpeg';
      if (imgRafael) imgRafael.src = 'Imagens/avatar-rafael-sobrenos.jpeg'; 
      if (imgPatrick) imgPatrick.src = 'Imagens/avatar-patrick-sobrenos-2.jpeg'; 
      if (imgPamelaEntrada) imgPamelaEntrada.src = 'Imagens/avatar-pam-index-transparente.png';
      if (imgAssinatura1) imgAssinatura1.src = 'Imagens/avatar-julio-assinatura1-escuro.jpeg';
      if (imgAssinatura2) imgAssinatura2.src = 'Imagens/avatar-julio-assinatura2-escuro.png';
      if (imgAssinatura3) imgAssinatura3.src = 'Imagens/avatar-julio-assinatura3-escuro.png';
    //Index FIM//

    //Login INÍCIO//
      if (divImgLogin) { divImgLogin.style.backgroundImage = "url('Imagens/avatar-patrick-login-transparente.png')"; }
    //Login FIM//

    $button.textContent = '☀️';
    if (spanicon) spanicon.textContent = '👾';  
    localStorage.setItem('theme', 'dark');
  } else {
    // Tema claro (padrão)
    $html.classList.add('light-mode');
    $html.classList.remove('dark-mode');

    // Adiciona as flores de cerejeira no modo claro
    toggleBlossomCreation(false); // Iniciar flores

  //!!!Altera as imagens para tema escuro, se existirem!!!//

    //Index INÍCIO//
      if (imgJulio) imgJulio.src = 'Imagens/AvatarJulioSobreNos.jpeg';  
      if (imgPamela) imgPamela.src = 'Imagens/AvatarPamelaSobreNos.PNG';
      if (imgRafael) imgRafael.src = 'Imagens/AvatarRafaelSobreNos.png';  
      if (imgPatrick) imgPatrick.src = 'Imagens/AvatarPatrickSobreNos.jpeg';  
      if (imgPamelaEntrada) imgPamelaEntrada.src = 'Imagens/Avatar-Index-Claro.png';
      if (imgAssinatura1) imgAssinatura1.src = 'Imagens/avatar-julio-assinatura1.png';
      if (imgAssinatura2) imgAssinatura2.src = 'Imagens/avatar-julio-assinatura2.png';
      if (imgAssinatura3) imgAssinatura3.src = 'Imagens/avatar-julio-assinatura3.png';
    //Index FIM//

    //Login INÍCIO//
      if (divImgLogin) { divImgLogin.style.backgroundImage = "url('Imagens/AvatarPatrickLogin.png')"; }
    //Login FIM//
    
    $button.textContent = '👾'; 
    if (spanicon) spanicon.textContent = '🌸';  
    localStorage.setItem('theme', 'light');
  }
}

// Aplica o tema de acordo com a preferência salva
toggleTheme(isDarkMode);

// Alternar o tema ao clicar no botão
if ($button) {
  $button.addEventListener('click', function() {
    isDarkMode = !isDarkMode;
    toggleTheme(isDarkMode);
  });
}