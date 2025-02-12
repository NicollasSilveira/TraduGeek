<?php
session_start();
include 'c.php';

// Função para converter data entre formatos
function converterData($data, $paraFormatoAmericano = true) {
    $data = str_replace('/', '-', $data); // Substitui "/" por "-" para uniformizar
    if ($paraFormatoAmericano) {
        return implode('-', array_reverse(explode('-', $data))); // DD/MM/YYYY -> YYYY-MM-DD
    } else {
        return implode('/', array_reverse(explode('-', $data))); // YYYY-MM-DD -> DD/MM/YYYY
    }
}

// Verifica se o usuário veio do login e tem ID na sessão
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Define as perguntas de segurança
$perguntas = [
    "nomeMaterno" => "Qual o nome da sua mãe?",
    "dataNascimento" => "Qual a data do seu nascimento? (DD/MM/YYYY)",
    "cep" => "Qual o CEP do seu endereço?"
];

// Seleciona a pergunta aleatoriamente
function selecionarPergunta($perguntas, $perguntaAtual = null) {
    do {
        $chave_pergunta = array_rand($perguntas);
    } while ($perguntaAtual !== null && $chave_pergunta === $perguntaAtual);
    return $chave_pergunta;
}

// Define ou redefine a pergunta de segurança
if (!isset($_SESSION['chave_pergunta']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $chave_pergunta = selecionarPergunta($perguntas);
    $_SESSION['chave_pergunta'] = $chave_pergunta;
    $_SESSION['pergunta'] = $perguntas[$chave_pergunta];
} else {
    $chave_pergunta = $_SESSION['chave_pergunta'];
}

// Obtém o valor esperado do banco
$stmt = $conexao->prepare("SELECT $chave_pergunta FROM usuario WHERE idusuario = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
$resposta_correta = $resultado->fetch_assoc()[$chave_pergunta];
$stmt->close();

// Converte a resposta correta do banco para exibição, se for uma data
if ($chave_pergunta === 'dataNascimento') {
    $resposta_correta_exibicao = converterData($resposta_correta, false); // YYYY-MM-DD -> DD/MM/YYYY
} else {
    $resposta_correta_exibicao = $resposta_correta;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resposta_usuario = trim($_POST['resposta']);

    // Converte a resposta do usuário para o formato americano, se for uma data
    if ($chave_pergunta === 'dataNascimento') {
        $resposta_usuario = converterData($resposta_usuario, true); // DD/MM/YYYY -> YYYY-MM-DD
    }

    if ($resposta_usuario === $resposta_correta) {
        // Registro de sucesso no log
        $stmt = $conexao->prepare(
            "INSERT INTO log (dataAcesso, pergunta, resposta, resultado, usuario_idusuario) 
            VALUES (NOW(), ?, ?, 'Sucesso', ?)"
        );
        $stmt->bind_param("ssi", $_SESSION['pergunta'], $_POST['resposta'], $usuario_id);
        $stmt->execute();
        $stmt->close();

        // Login bem-sucedido
        $_SESSION['online'] = true;
        unset($_SESSION['tentativas_2fa']); // Remove o contador de tentativas

        // Redireciona com base no tipo de usuário
        header("Location: index.php");
        exit();
    } else {
        // Incrementa as tentativas
        $_SESSION['tentativas_2fa'] = ($_SESSION['tentativas_2fa'] ?? 0) + 1;

        // Registro de falha no log
        $stmt = $conexao->prepare(
            "INSERT INTO log (dataAcesso, pergunta, resposta, resultado, usuario_idusuario) 
            VALUES (NOW(), ?, ?, 'Falha', ?)"
        );
        $stmt->bind_param("ssi", $_SESSION['pergunta'], $_POST['resposta'], $usuario_id);
        $stmt->execute();
        $stmt->close();

        // Verifica se o limite de tentativas foi atingido
        if ($_SESSION['tentativas_2fa'] >= 3) {
            session_unset();
            session_destroy();
            session_start();
            $_SESSION['erro_login'] = "3 tentativas sem sucesso! Favor realizar Login novamente.";
            header("Location: login.php");
            exit();
        } else {
            $erro = "Resposta incorreta! Você tem " . (3 - $_SESSION['tentativas_2fa']) . " tentativa(s) restante(s).";

            // Seleciona uma nova pergunta
            $nova_chave_pergunta = selecionarPergunta($perguntas, $chave_pergunta);
            $_SESSION['chave_pergunta'] = $nova_chave_pergunta;
            $_SESSION['pergunta'] = $perguntas[$nova_chave_pergunta];
        }
    }
}

$pergunta = $_SESSION['pergunta'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autenticação de Dois Fatores</title>
    <link rel="stylesheet" href="css/login_2fa.css">
    <script>
        // Máscara para CEP e Data de Nascimento
        function aplicarMascara(input, tipo) {
            let valor = input.value.replace(/\D/g, ''); // Remove tudo que não for número
            if (tipo === 'cep') {
                input.value = valor.replace(/(\d{5})(\d{3})/, '$1-$2');
            } else if (tipo === 'data') {
                input.value = valor.replace(/(\d{2})(\d{2})(\d{4})/, '$1/$2/$3');
            }
        }
    </script>
</head>
<body>
    <header>
        <nav class="navbar">
            <ul>
                <!--<li><a href="login.php">Voltar à tela de Login</a></li>-->
                <li>Preencha o 2FA corretamente pra efetuar o Login!</li>
            </ul>
            <button class="mudarTema" id="altTema" title="Alternar Tema">☀️</button>
        </nav>
    </header>

    <main>
        <div class="fa-container">
            <form method="POST" action="">
                <label for="pergunta" id="pergunta-de-seguranca">Pergunta de Segurança:</label>
                <p><?= htmlspecialchars($pergunta) ?></p>
                <input
                    class="input-2fa" 
                    type="text" 
                    name="resposta" 
                    id="resposta" 
                    required
                    <?php if ($_SESSION['chave_pergunta'] === 'cep') : ?>
                        oninput="aplicarMascara(this, 'cep')"
                    <?php elseif ($_SESSION['chave_pergunta'] === 'dataNascimento') : ?>
                        oninput="aplicarMascara(this, 'data')"
                    <?php endif; ?>
                >
                <?php if (isset($erro)): ?>
                    <div class="erro"><?= htmlspecialchars($erro) ?></div>
                <?php endif; ?>
                </br>
                <button class="select-plan-button" type="submit">Confirmar</button>
            </form>
        </div>
    </main>

    <footer>
        <div class="footer-content">
            <p>© 2024 TraduGeek. Todos os direitos reservados.</p>
        </div>
    </footer>
    <script src="script\altTemaImg.js"></script>
</body>
</html>