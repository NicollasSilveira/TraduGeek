<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TraduGeek 👾</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Consolas&family=Courier+New&family=Source+Code+Pro&display=swap">
    <link rel="stylesheet" href="css\style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!-- 
        Script/Link para inportar a bliblioteca do swal(SeewtAlert)
        Confira mais em: https://sweetalert.js.org/ 
    -->
    
    <script src="script/artigos.js"></script>
</head>
 
<body>
    <header>
        <nav class="barraNav">
           <!-- <span class="olaUsuario">
                <?php
                /*if (isset($_SESSION['usuario']) && $_SESSION['online']) {
                    echo "Olá, " . htmlspecialchars($_SESSION['usuario']) . "!";
                } else {
                    echo "Olá, Visitante!";
                }
                */?>
            </span>
            -->
            <ul>
                <li><a href="#TraduBlog">TraduQuiz</a></li>
                <li><a href="#SobreNos">Sobre Nós</a></li>
                <li><a href="#CentralDeAjuda">Central de Ajuda</a></li>
                <li><a href="#Acessibilidade">Acessibilidade</a></li>

                <!-- Mostrar opções com base no tipo de usuário -->
                <?php if (isset($_SESSION['online']) && $_SESSION['online'] === true): ?>
                    <?php if ($_SESSION['admin-master']): ?>
                        <!-- Opções para usuário master -->
                        <li><a href="admin.php">Admin</a></li>
                        <li><a href="assinatura.php">Assinaturas</a></li>
                    <?php else: ?>
                        <!-- Opções para usuário comum -->
                        <li><a href="assinatura.php">Assinaturas</a></li>
                        <li><a href="usuario.php">Meus Dados</a></li>
                        <li><a href="TraduQuiz\index.html">TraduQuiz</a></li>
                    <?php endif; ?>
                    <li class="logOut"><a href="script/logOut.php">Log Out</a></li>
                <?php else: ?>
                    <!-- Opções para visitantes (não logados) -->
                    <li class="loginNav"><a href="login.php">Login</a></li>
                    <li class="cadastroNav"><a href="cadastro.php">Cadastrar-se</a></li>
                    <li><a href="assinatura.php">Assinaturas</a></li>
                <?php endif; ?>
            </ul>
            <button class="mudarTema" id="altTema" title="Alternar Tema">☀️</button>
        </nav>
    </header>

    <main>
        <div id="cherry-blossoms"></div>

        <!-- Seção TraduGeek -->

        <section class="secaoTraduGeek">
            <div class="signup-box">
            <h1 id="typing">Bem-vindo ao TraduNerd <span id="icon-h1">👾</span></h1>
            </div>
            <div class="conteudoTraduGeek">
                <div class="textoTraduGeek">
                    <p>E aí, galera! Sejam muito bem-vindos ao TraduNerd! <br> Aqui a parada é séria, mas de um jeito MUITO divertido: vamos colocar seus conhecimentos de inglês e japonês à prova com aquele toque nerd que a gente tanto ama! Prepare-se para desafios épicos e bora mergulhar nos idiomas como se fosse uma maratona de anime! Vem comigo nessa jornada linguística, porque aqui, aprender nunca foi tão empolgante!</p>
                </div>
                <img src="Imagens/avatar-pamela1.png" id="avatar-pamela-entrada" alt="Avatar da Pâmela" class="avatarPamela">
            </div>
            <div class="botoesTraducao">
 <!--               <button>PDF</button>
                <button>WEB</button> -->
            </div>
        </section>


        <section class="secaoTraduBlog" id="TraduBlog">
            <h2>TraduQuiz</h2>
            <p style="color: black; margin-bottom: 40px; font-size: 1.1rem;"> O momento épico chegou! Teste suas habilidades de tradução e prove que seu nível está mais para Jedi poliglota ou Mestre das Línguas Élficas. Mostre o grande nerd que há em você e encare o desafio.</p>
            <div class="conteudoTraduBlog">
                <div class="cardsTraduQuiz">
                    <h3 class='tradquiz-h3'>Inglês</h3>
                    <div class='traduquiz-img'><img  id='tdq-Eng' src="Imagens\traduQuiz-Ingles(Sem fundo).png"></img></div>
                    <p>Teste seu inglês e veja se arrancaria um olhar aprovador do Batman ou uma piada afiada do Tony Stark! Get ready!</p>
                    <button>INICIAR</button>
                    
                </div>
                <div class="cardsTraduQuiz">
                    <h3 class='tradquiz-h3'>Japonês</h3>
                    <div class='traduquiz-img'><img  id='tdq-Eng' src="Imagens\traduQuiz-Japones(Sem fundo).png"></div>
                    <p>Teste seu inglês e veja se arrancaria um olhar aprovador do Batman ou uma piada afiada do Tony Stark! Get ready!</p>
                        <button>INICIAR</button>
                </div>
                <div class="cardsTraduQuiz">
                    <h3 id='tradquiz-h3'>Coreano</h3>
                    <div class='traduquiz-img'><img  id='tdq-Eng' src="Imagens\traduQuiz-Coreano(Sem fundo).png"></div>
                    <p>Teste seu inglês e veja se arrancaria um olhar aprovador do Batman ou uma piada afiada do Tony Stark! Get ready!</p>
                        <button>INICIAR</button>
                </div>
            </div>
        </section>
<!--
        <section class="secaoTraduBlog" id="TraduBlog">
            <h2>TraduBlog</h2>
            <div class="conteudoTraduBlog">
                <div class="cardsTraduBlog">
                    <h3>Mangás e Animes</h3>
                    <p>Muitos termos em inglês, como "superhero" e "sci-fi", foram adaptados para o japonês, criando
                        palavras como "スーパーヒーロー"
                        (sūpā hīrō) e "SF" (esefu), refletindo a influência da cultura pop ocidental nos animes e
                        mangás.</p>
                </div>
                <div class="cardsTraduBlog">
                    <h3>Kawaii</h3>
                    <p>A palavra japonesa "kawaii" (かわいい), que significa "fofo", é um conceito central na cultura geek
                        japonesa, influenciando desde
                        a moda até a criação de personagens em jogos e animes.</p>
                </div>
                <div class="cardsTraduBlog">
                    <h3>Palavras em Jogos</h3>
                    <p>Em jogos de RPG, muitos personagens têm nomes em inglês que soam mais impactantes, mas no Japão,
                        esses nomes são frequentemente
                        adaptados em katakana, como "ドラゴン" (doragon) para "dragon".</p>
                </div>
            </div>
        </section>
-->

        <!-- Seção Sobre Nós -->

        <section class="secaoSobreNos" id="SobreNos">
  <h2>Sobre Nós</h2>
  <p>Conheça os mestres por trás do projeto TraduGeek!</p>
  
  <!-- Carrossel wrapper com botões de navegação -->
  <div class="carrossel-wrapper">
    <button class="carousel-nav left" onclick="prevItem()">&#9664;</button>
    
    <div class="carrossel-container">
      <div class="carrossel">
        <!-- Ítem 1: Julio -->
        <div class="molduraSobreNos" data-name="julio" onclick="abrirModal('julio')">
          <div class="avatarIntegrantes">
            <img src="Imagens/avatar-julio2.jpeg" alt="Avatar Julio">
          </div>
          <h2 class="nomeIntegrantes">Julio</h2>
        </div>
        <!-- Ítem 2: Pâmela -->
        <div class="molduraSobreNos" data-name="pamela" onclick="abrirModal('pamela')">
          <div class="avatarIntegrantes">
            <img src="Imagens/avatar-pamela2.jpeg" alt="Avatar Pâmela">
          </div>
          <h2 class="nomeIntegrantes">Pâmela</h2>
        </div>
        <!-- Ítem 3: Rafael -->
        <div class="molduraSobreNos" data-name="rafael" onclick="abrirModal('rafael')">
          <div class="avatarIntegrantes">
            <img src="Imagens/AvatarRafaelSobreNos.png" alt="Avatar Rafael">
          </div>
          <h2 class="nomeIntegrantes">Rafael</h2>
        </div>
        <!-- Ítem 4: Patrick -->
        <div class="molduraSobreNos" data-name="patrick" onclick="abrirModal('patrick')">
          <div class="avatarIntegrantes">
            <img src="Imagens/avatar-rafael1.jpeg" alt="Avatar Patrick">
          </div>
          <h2 class="nomeIntegrantes">Patrick</h2>
        </div>
        <!-- Ítem 5: Mari -->
        <div class="molduraSobreNos" data-name="mari" onclick="abrirModal('mari')">
          <div class="avatarIntegrantes">
            <img src="Imagens/avatar-mari.jpeg" alt="Avatar Mari">
          </div>
          <h2 class="nomeIntegrantes">Marina</h2>
        </div>
        <!-- Ítem 6: Nicollas -->
        <div class="molduraSobreNos" data-name="nicollas" onclick="abrirModal('nicollas')">
          <div class="avatarIntegrantes">
            <img src="Imagens/avatar-nicollas.jpeg" alt="Avatar Nicollas">
          </div>
          <h2 class="nomeIntegrantes">Nicollas</h2>
        </div>
        <!-- Ítem 7: Gabriel -->
        <div class="molduraSobreNos" data-name="gabriel" onclick="abrirModal('gabriel')">
          <div class="avatarIntegrantes">
            <img src="Imagens/avatar-gabriel.jpeg" alt="Avatar Gabriel">
          </div>
          <h2 class="nomeIntegrantes">Gabriel</h2>
        </div>
      </div><!-- .carrossel -->
    </div><!-- .carrossel-container -->
    
    <button class="carousel-nav right" onclick="nextItem()">&#9654;</button>
  </div><!-- .carrossel-wrapper -->
</section>

        <!-- Modais -->
        <div id="modal-julio" class="modal-curriculo">
            <button class="nav-modal esquerda" onclick="navegarModal('anterior')">
                <svg viewBox="0 0 24 24"><path d="M15.41 16.59L10.83 12l4.58-4.59L14 6l-6 6 6 6 1.41-1.41z"/></svg>
            </button>
            
            <div class="modal-conteudo">
                <span class="fechar-modal" onclick="fecharModal('julio')">&times;</span>
                <div class="curriculo-header">
                <img src="Imagens/avatar-julio2.jpeg" class="curriculo-foto" alt="Julio">
                <h2>Julio César</h2>
                <p>Desenvolvedor Full-Stack 👨💻</p>
                </div>
                
                <div class="curriculo-section">
                <h3>🚀 Habilidades Técnicas</h3>
                <ul>
                    <li>PHP | JavaScript | Python</li>
                    <li>React | Node.js | SQL</li>
                    <li>Git | Docker | AWS</li>
                </ul>
                </div>
                
                <div class="curriculo-section">
                <h3>🎯 Projetos Destacados</h3>
                <p style="color: black;">Desenvolvimento do sistema de tradução automática do TraduGeek</p>
                </div>
        </div>

        
        

  <button class="nav-modal direita" onclick="navegarModal('proximo')">
    <svg viewBox="0 0 24 24"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/></svg>
  </button>
</div>

<div id="modal-pamela" class="modal-curriculo">
            <button class="nav-modal esquerda" onclick="navegarModal('anterior')">
                <svg viewBox="0 0 24 24"><path d="M15.41 16.59L10.83 12l4.58-4.59L14 6l-6 6 6 6 1.41-1.41z"/></svg>
            </button>
            
            <div class="modal-conteudo">
                <span class="fechar-modal" onclick="fecharModal('julio')">&times;</span>
                <div class="curriculo-header">
                <img src="Imagens/avatar-julio2.jpeg" class="curriculo-foto" alt="Julio">
                <h2>Pâmela Salazar</h2>
                <p>Desenvolvedor Full-Stack 👨💻</p>
                </div>
                
                <div class="curriculo-section">
                <h3>🚀 Habilidades Técnicas</h3>
                <ul>
                    <li>PHP | JavaScript | Python</li>
                    <li>React | Node.js | SQL</li>
                    <li>Git | Docker | AWS</li>
                </ul>
                </div>
                
                <div class="curriculo-section">
                <h3>🎯 Projetos Destacados</h3>
                <p>Desenvolvimento do sistema de tradução automática do TraduGeek</p>
                </div>
        </div>
  <button class="nav-modal direita" onclick="navegarModal('proximo')">
    <svg viewBox="0 0 24 24"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/></svg>
  </button>
</div>

<div id="modal-rafael" class="modal-curriculo">
            <button class="nav-modal esquerda" onclick="navegarModal('anterior')">
                <svg viewBox="0 0 24 24"><path d="M15.41 16.59L10.83 12l4.58-4.59L14 6l-6 6 6 6 1.41-1.41z"/></svg>
            </button>
            
            <div class="modal-conteudo">
                <span class="fechar-modal" onclick="fecharModal('julio')">&times;</span>
                <div class="curriculo-header">
                <img src="Imagens/avatar-julio2.jpeg" class="curriculo-foto" alt="Julio">
                <h2>Rafael Paulino</h2>
                <p>Desenvolvedor Full-Stack 👨💻</p>
                </div>
                
                <div class="curriculo-section">
                <h3>🚀 Habilidades Técnicas</h3>
                <ul>
                    <li>PHP | JavaScript | Python</li>
                    <li>React | Node.js | SQL</li>
                    <li>Git | Docker | AWS</li>
                </ul>
                </div>
                
                <div class="curriculo-section">
                <h3>🎯 Projetos Destacados</h3>
                <p>Desenvolvimento do sistema de tradução automática do TraduGeek</p>
                </div>
        </div>
  <button class="nav-modal direita" onclick="navegarModal('proximo')">
    <svg viewBox="0 0 24 24"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/></svg>
  </button>
</div>

<div id="modal-patrick" class="modal-curriculo">
            <button class="nav-modal esquerda" onclick="navegarModal('anterior')">
                <svg viewBox="0 0 24 24"><path d="M15.41 16.59L10.83 12l4.58-4.59L14 6l-6 6 6 6 1.41-1.41z"/></svg>
            </button>
            
            <div class="modal-conteudo">
                <span class="fechar-modal" onclick="fecharModal('julio')">&times;</span>
                <div class="curriculo-header">
                <img src="Imagens/avatar-julio2.jpeg" class="curriculo-foto" alt="Julio">
                <h2>Patrick Amorim</h2>
                <p>Desenvolvedor Full-Stack 👨💻</p>
                </div>
                
                <div class="curriculo-section">
                <h3>🚀 Habilidades Técnicas</h3>
                <ul>
                    <li>PHP | JavaScript | Python</li>
                    <li>React | Node.js | SQL</li>
                    <li>Git | Docker | AWS</li>
                </ul>
                </div>
                
                <div class="curriculo-section">
                <h3>🎯 Projetos Destacados</h3>
                <p>Desenvolvimento do sistema de tradução automática do TraduGeek</p>
                </div>
        </div>
  <button class="nav-modal direita" onclick="navegarModal('proximo')">
    <svg viewBox="0 0 24 24"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/></svg>
  </button>
</div>

<div id="modal-mari" class="modal-curriculo">
            <button class="nav-modal esquerda" onclick="navegarModal('anterior')">
                <svg viewBox="0 0 24 24"><path d="M15.41 16.59L10.83 12l4.58-4.59L14 6l-6 6 6 6 1.41-1.41z"/></svg>
            </button>
            
            <div class="modal-conteudo">
                <span class="fechar-modal" onclick="fecharModal('julio')">&times;</span>
                <div class="curriculo-header">
                <img src="Imagens/avatar-julio2.jpeg" class="curriculo-foto" alt="Julio">
                <h2>Marina Moretti</h2>
                <p>Desenvolvedor Full-Stack 👨💻</p>
                </div>
                
                <div class="curriculo-section">
                <h3>🚀 Habilidades Técnicas</h3>
                <ul>
                    <li>PHP | JavaScript | Python</li>
                    <li>React | Node.js | SQL</li>
                    <li>Git | Docker | AWS</li>
                </ul>
                </div>
                
                <div class="curriculo-section">
                <h3>🎯 Projetos Destacados</h3>
                <p>Desenvolvimento do sistema de tradução automática do TraduGeek</p>
                </div>
        </div>
  <button class="nav-modal direita" onclick="navegarModal('proximo')">
    <svg viewBox="0 0 24 24"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/></svg>
  </button>
</div>

<div id="modal-nicollas" class="modal-curriculo">
            <button class="nav-modal esquerda" onclick="navegarModal('anterior')">
                <svg viewBox="0 0 24 24"><path d="M15.41 16.59L10.83 12l4.58-4.59L14 6l-6 6 6 6 1.41-1.41z"/></svg>
            </button>
            
            <div class="modal-conteudo">
                <span class="fechar-modal" onclick="fecharModal('julio')">&times;</span>
                <div class="curriculo-header">
                <img src="Imagens/avatar-julio2.jpeg" class="curriculo-foto" alt="Julio">
                <h2>Nicollas Gomes</h2>
                <p>Desenvolvedor Full-Stack 👨💻</p>
                </div>
                
                <div class="curriculo-section">
                <h3>🚀 Habilidades Técnicas</h3>
                <ul>
                    <li>PHP | JavaScript | Python</li>
                    <li>React | Node.js | SQL</li>
                    <li>Git | Docker | AWS</li>
                </ul>
                </div>
                
                <div class="curriculo-section">
                <h3>🎯 Projetos Destacados</h3>
                <p>Desenvolvimento do sistema de tradução automática do TraduGeek</p>
                </div>
        </div>
  <button class="nav-modal direita" onclick="navegarModal('proximo')">
    <svg viewBox="0 0 24 24"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/></svg>
  </button>
</div>

<div id="modal-gabriel" class="modal-curriculo">
            <button class="nav-modal esquerda" onclick="navegarModal('anterior')">
                <svg viewBox="0 0 24 24"><path d="M15.41 16.59L10.83 12l4.58-4.59L14 6l-6 6 6 6 1.41-1.41z"/></svg>
            </button>
            
            <div class="modal-conteudo">
                <span class="fechar-modal" onclick="fecharModal('julio')">&times;</span>
                <div class="curriculo-header">
                <img src="Imagens/avatar-julio2.jpeg" class="curriculo-foto" alt="Julio">
                <h2>Gabriel Menicke</h2>
                <p>Desenvolvedor Full-Stack 👨💻</p>
                </div>
                
                <div class="curriculo-section">
                <h3>🚀 Habilidades Técnicas</h3>
                <ul>
                    <li>PHP | JavaScript | Python</li>
                    <li>React | Node.js | SQL</li>
                    <li>Git | Docker | AWS</li>
                </ul>
                </div>
                
                <div class="curriculo-section">
                <h3>🎯 Projetos Destacados</h3>
                <p>Desenvolvimento do sistema de tradução automática do TraduGeek</p>
                </div>
        </div>
  <button class="nav-modal direita" onclick="navegarModal('proximo')">
    <svg viewBox="0 0 24 24"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/></svg>
  </button>
</div>

        <!-- Seção Central de Ajuda -->

        <section class="secaoCentralDeAjuda" id="CentralDeAjuda">
            <h2>Central de Ajuda</h2>
            <p>Encontre aqui respostas para suas perguntas, tutoriais e suporte para resolver qualquer problema que você
                possa encontrar com nosso
                serviço.</p>

            <!-- Artigos -->

            <div>
                <h3 class="artigo" onclick="toggleTextoArtigo('artigo1')">Como criar uma conta</h3>
                <p id="artigo1" class="textoArtigo oculto">Para criar uma conta, clique em 'Cadastre-se' na página
                    inicial e preencha os campos
                    obrigatórios. Você receberá um e-mail para confirmar seu cadastro.</p>
            </div>

            <div>
                <h3 class="artigo" onclick="toggleTextoArtigo('artigo2')">Como recuperar a senha</h3>
                <p id="artigo2" class="textoArtigo oculto">Para recuperar sua senha, clique em 'Esqueci minha senha' na
                    tela de login. Siga as
                    instruções enviadas para seu e-mail para redefinir sua senha.</p>
            </div>

            <div>
                <h3 class="artigo" onclick="toggleTextoArtigo('artigo3')">Como entrar em contato com o suporte</h3>
                <p id="artigo3" class="textoArtigo oculto">Você pode entrar em contato com o suporte clicando em 'Fale
                    conosco' na parte inferior
                    da página. Preencha o formulário e nossa equipe responderá o mais breve possível.</p>
            </div>
        </section>

        <!-- Seção Acessibilidade -->

        <section class="secaoAcessibilidade" id="Acessibilidade">
            <h2>Acessibilidade</h2>
            <p>Exploradores do ciberespaço, temos recursos para tornar nosso site mais acessível para todos vocês! Use
                as ferramentas abaixo para
                personalizar sua jornada digital e adaptar o site às suas necessidades únicas.</p>
            <div class="ajusteFonteAcessibilidade">
                <button id="aumentarFonte">Aumentar Fonte</button>
                <button id="diminuirFonte">Diminuir Fonte</button>
            </div>
        </section>


    </main>
    <footer>
        <p class="textoFooter">"Conhecimento sem limites, traduções sem fronteiras." © 2024 TraduGeek</p>
    </footer>
    <script src="script/tamanhoDaFont.js"></script>
    <script src="script/altTemaImg.js"></script>
    <script src="script\carrosselindex.js"></script>
    <script>

        
    </script>
    <?php 
    include 'botaoVoltarAoTopo.php'; 
    include 'verificarLogin.php';
    ?>

</body>
</html>