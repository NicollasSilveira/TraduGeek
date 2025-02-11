<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TraduGeek - MER</title>
    <link rel="stylesheet" href="css\mer.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <span class="olaUsuario">
                <?php
                if (isset($_SESSION['usuario']) && $_SESSION['online']) {
                    echo "Olá, " . htmlspecialchars($_SESSION['usuario']) . "!";
                } else {
                    echo "Olá, Visitante!";
                }
                ?>
            </span>
            <ul>
              <li><a href="index.php">Voltar à tela principal</a></li>
            </ul>
            <button class="mudarTema" id="altTema" title="Alternar Tema">☀️</button>
        </nav>
    </header>
    <main>
        <img src="Imagens\novoMer.PNG" id="mer">
        <a href="Imagens\novoMer.PNG" download="MER-TraduGeek.jpg" class="botao-mer"> Baixar MER</a>
        <script src="script\altTemaImg.js"></script>
    </main>
    <footer>
        <div class="footer-content">
            <p>© 2024 TraduGeek. Todos os direitos reservados.</p>
        </div>
    </footer>
    <?php 
    include 'botaoVoltarAoTopo.php'; 
    ?>
</body>
</html>