<?php
if(isset($_POST['submit'])){
  
  include_once('c.php');

  // Captura dos valores do formulário
  $nome = $_POST['name'];
  $dataNascimento = $_POST['dataNascimento'];
  $sexo = $_POST['sexo'];
  $nomeMaterno = $_POST['nomeMaterno'];
  $cpf = $_POST['cpf'];
  $email = $_POST['email'];
  $telefoneCelular = $_POST['telefoneCelular'];
  $telefoneFixo = $_POST['telefoneFixo'];
  $cep = $_POST['cep'];
  $logradouro = $_POST['logradouro'];
  $bairro = $_POST['bairro'];
  $cidade = $_POST['cidade'];
  $estado = $_POST['estado'];
  $complemento = $_POST['complemento'];
  $numero = $_POST['numero'];
  $login = $_POST['login'];
  $senha = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash da senha
  $status = 1;
  // Preparação da query para evitar SQL Injection
  $stmt = $conexao->prepare("INSERT INTO usuario (nome, dataNascimento, sexo, nomeMaterno, cpf, email, telefoneCelular, telefoneFixo, cep, logradouro, bairro, cidade, estado, complemento, numero, usuario_login, senha, status) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

  // Bind dos parâmetros
  $stmt->bind_param("sssssssssssssssssi", $nome, $dataNascimento, $sexo, $nomeMaterno, $cpf, $email, $telefoneCelular, $telefoneFixo, $cep, $logradouro, $bairro, $cidade, $estado, $complemento, $numero, $login, $senha, $status);
  // Execução da query
  if ($stmt->execute()) {
    // Redireciona o usuário para uma nova página
    header("Location: resultadoCad.php");
    exit(); // Importante para interromper a execução do script após o redirecionamento
  } else {
    echo "Erro: " . $stmt->error;
  }

  // Fechamento da declaração e da conexão
  $stmt->close();
  $conexao->close();
}
?>



<!DOCTYPE html>

<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - TraduGeek 👾</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Consolas&family=Courier+New&family=Source+Code+Pro&display=swap">
    <link rel="stylesheet" href="css/cadastro.css">
</head>

<body>
    <header>
        <nav class="navbar">
            <ul>
                <li><a href="index.php">Voltar à tela principal</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
            <button class="mudarTema" id="altTema" title="Alternar Tema">☀️</button>
        </nav>
    </header>

    <main>
        <div id="cherry-blossoms"></div>

        <div class="signup-container">
            <div class="signup-box">
                <h2 id="tituloCad">Cadastro</h2> 
                <form id="form" action="cadastro.php" method="POST">
                    <div class="camposCad">
                        <label for="name">Nome Completo</label>
                        <input type="text" id="name" name="name" minlength="15" maxlength="80" required class="field" oninput="validarNome();">
                        <span id="nameErro" class="span-required" style="display:none;">Nome deve ter no mínimo 15 caracteres</span>
                    </div>

                    <div class="camposCad">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" placeholder="xxxx@xxxx.com" required class="field" oninput="validarEmail()">
                        <span id="emailErro" class="span-required" style="display: none;">Informe um E-mail válido!</span>
                    </div>

                    <div class="camposCad">
                        <label for="login">Login</label>
                        <input type="text" id="login" name="login" required class="field" maxlength="6" oninput="validarLogin()">
                        <span id="loginErro" class="span-required" style="display: none;">Login deve possuir apenas 6 caracteres.</span>
                    </div>

                    <div class="camposCad">
                        <label for="password">Senha</label>
                        <input type="password" id="password" name="password" required class="field" maxlength="8" oninput="validarSenha()" onkeypress="return bloquearNumeros(event)">
                        <span id="senhaErro" class="span-required" style="display: none;">A senha deve conter exatamente 8 caracteres alfabéticos.</span>
                    </div>

                    <div class="camposCad">
                        <label for="confirm-password">Confirmação da Senha</label>
                        <input type="password" id="confirm-password" name="confirm-password" required class="field" maxlength="8" oninput="validarConfirmacao()" onkeypress="return bloquearNumeros(event)">
                        <span id="confirmSenhaErro" class="span-required" style="display: none;"></span>
                    </div>

                    <div class="botaoCad">
                        <input type="submit" name="enviar" value="Enviar">
                        <input type="reset" value="Limpar Tela">
                    </div>
                </form>

                <div class="signup-info">
                    <p>Já possui uma conta? <a href="login.php">Faça login aqui</a></p>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p class="textoRodapeCad">"Conhecimento sem limites, traduções sem fronteiras." © 2024 TraduGeek</p>
    </footer>

    <?php include 'botaoVoltarAoTopo.php'; ?>

    <script src="script/cadastro.js"></script>
    <script src="script/alternarTema.js"></script>
    <script src="script/jquery.js"></script>
    <script src="script/cpf.js"></script>
</body>
</html>