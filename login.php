<?php
session_start();
if (isset($_SESSION['erro_login'])) {
    $erro_login = $_SESSION['erro_login'];
    unset($_SESSION['erro_login']); // Limpa a mensagem após exibi-la
} else {
    $erro_login = null;
}
?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TraduGeek 👾</title>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Consolas&family=Courier+New&family=Source+Code+Pro&display=swap">
    <link rel="stylesheet" href="css/login.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .forgot-password {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar">
            <ul>
                <li><a href="index.php">Voltar à tela principal</a></li>
                <li><a href="cadastro.php">Cadastre-se</a></li>
            </ul>
            <button class="mudarTema" id="altTema" title="Alternar Tema">☀️</button>
        </nav>
    </header>

    <main>
        <div id="cherry-blossoms"></div>

        <div class="login-container">
            <div class="login-box-container">
                <div class="login-box">
                    <div class="signup-box">
                        <h1 id="typing">Login</h1>
                    </div>
                    <!-- Formulário de login -->
                    <form action="teste.php" method="POST" onsubmit="return validarLogin()">
                        <label for="username">Nome de Usuário</label>
                        <input type="text" id="username" name="login" required maxlength="6" oninput="validarLogin()" onkeypress="return bloquearNumeros(event)">
                        <span id="loginErro" class="span-required" style="display: none;"></span>

                        <label for="password">Senha</label>
                        <input type="password" id="password" name="password" required maxlength="8" oninput="validarSenha()" onkeypress="return bloquearNumeros(event)">
                        <span id="senhaErro" class="span-required" style="display: none;">Senha deve conter 8 caracteres alfabéticos.</span>                        
   
                        <!--<div class="forgot-password">
                            <a href="#recuperar-senha">Esqueceu sua senha?</a>
                        </div>-->

                        <div class="button-container">
                            <input type="submit" name="submit" value="Entrar">
                            <input type="reset" value="Limpar">
                        </div>
                    </form>
                    <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se aqui</a></p>
                </div>

                <div class="avatarJuLogin"></div>
            </div>
        </div>
    </main>

    <footer>
        <p class="footer-text">"Conhecimento sem limites, traduções sem fronteiras." © 2024 TraduGeek</p>
    </footer>
    <script src="script\login.js"></script>
    <script src="script\altTemaImg.js"></script>

    <!-- SweetAlert para erro de login -->
    <script>
        <?php if ($erro_login): ?>
            Swal.fire({
                icon: 'error',
                title: 'Erro no Login',
                text: '<?= htmlspecialchars($erro_login) ?>',
                customClass: {
                    confirmButton: 'swal2-confirm' // Classe personalizada para o botão OK
                }
            });
        <?php endif; ?>
    </script>
</body>

</html>