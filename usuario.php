<?php
// Inicia a sessão, caso ainda não esteja ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário é um administrador (master) e, se sim, redireciona para a página inicial
if (isset($_SESSION['admin-master']) && $_SESSION['admin-master'] === true) {
    header('Location: index.php');
    exit();
}

// Verifica se o usuário está logado; se não estiver, redireciona para a página de login
if (!isset($_SESSION['online']) || $_SESSION['online'] !== true) {
    header('Location: index.php');
    exit();
}

// Inclui o arquivo de configuração do banco de dados
require_once "c.php";

// Obtém o ID do usuário da sessão
$usuario_id = $_SESSION['usuario_id'];

// Recupera os dados do usuário logado do banco de dados
$stmt = $conexao->prepare(
    "SELECT nome, dataNascimento, sexo, nomeMaterno, cpf, email, telefoneCelular, telefoneFixo, 
            CONCAT(logradouro, ', ', numero, ', ', bairro, ', ', cidade, ' - ', estado, ' CEP: ', cep) AS endereco, 
            usuario_login 
     FROM usuario 
     WHERE idusuario = ?"
);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();
$stmt->close();

// Verifica se o usuário foi encontrado no banco de dados
if (!$usuario) {
    echo "Erro: Usuário não encontrado.";
    exit();
}

// Processa o envio do formulário de alteração de senha
$senhaAtualErr = $novaSenhaErr = $confirmaSenhaErr = $sucesso = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senhaAtual = $_POST['current-password'] ?? '';
    $novaSenha = $_POST['new-password'] ?? '';
    $confirmaSenha = $_POST['confirm-new-password'] ?? '';

    // Verifica se todos os campos foram preenchidos
    if (empty($senhaAtual)) {
        $senhaAtualErr = "Por favor, insira sua senha atual.";
    }
    if (empty($novaSenha)) {
        $novaSenhaErr = "Por favor, insira a nova senha.";
    } elseif (!preg_match('/^[a-zA-Z]{8}$/', $novaSenha)) { // Validação da nova senha
        $novaSenhaErr = "A nova senha deve conter exatamente 8 caracteres alfabéticos.";
    }
    if (empty($confirmaSenha)) {
        $confirmaSenhaErr = "Por favor, confirme a nova senha.";
    }

    // Valida a senha atual
    if (empty($senhaAtualErr)) {
        $stmt = $conexao->prepare("SELECT senha FROM usuario WHERE idusuario = ?");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $stmt->bind_result($senhaHash);
        $stmt->fetch();
        $stmt->close();
    
        if (!password_verify($senhaAtual, $senhaHash)) {
            $senhaAtualErr = "Senha atual incorreta."; // Atualiza a mensagem de erro
        }
    }

    // Verifica se a nova senha e a confirmação são iguais
    if (empty($novaSenhaErr) && empty($confirmaSenhaErr) && $novaSenha !== $confirmaSenha) {
        $confirmaSenhaErr = "As senhas não coincidem.";
    }

    // Atualiza a senha no banco de dados
    if (empty($senhaAtualErr) && empty($novaSenhaErr) && empty($confirmaSenhaErr)) {
        $novaSenhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

        $stmt = $conexao->prepare("UPDATE usuario SET senha = ? WHERE idusuario = ?");
        $stmt->bind_param("si", $novaSenhaHash, $usuario_id);

        if ($stmt->execute()) {
            $sucesso = "Senha alterada com sucesso!";
        } else {
            $sucesso = "Erro ao alterar a senha. Tente novamente.";
        }
        $stmt->close();
    }
}

$sql = "
    SELECT 
        usuario.idassinatura, 
        assinatura.nome AS nome_assinatura, 
        assinatura.custo, 
        assinatura.recursos
    FROM 
        usuario
    LEFT JOIN 
        assinatura ON usuario.idassinatura = assinatura.idassinatura
    WHERE 
        usuario.idusuario = ?
";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $usuario_id); // Use a variável correta
$stmt->execute();
$resultado = $stmt->get_result();
$dados = $resultado->fetch_assoc();

// Verifica se existe uma assinatura ativa
$temAssinatura = !empty($dados['idassinatura']);
$nomeAssinatura = $dados['nome_assinatura'] ?? 'Sem assinatura';
$custo = $dados['custo'] ?? '-';
$recursos = $dados['recursos'] ?? 'Nenhum recurso disponível';

// Verifica se a requisição é para cancelar a assinatura
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancelar_assinatura'])) {
    // Utilize o $usuario_id para identificar corretamente o usuário logado
    $sqlCancel = "UPDATE usuario SET idassinatura = NULL WHERE idusuario = ?";
    $stmtCancel = $conexao->prepare($sqlCancel);
    $stmtCancel->bind_param("i", $usuario_id);  // Substitua idusuario por usuario_id

    if ($stmtCancel->execute()) {
        $_SESSION['mensagem'] = "Assinatura cancelada com sucesso!";
        header("Location: usuario.php"); // Redireciona para a página de assinatura
        exit;
    } else {
        $_SESSION['mensagem'] = "Erro ao cancelar assinatura.";
    }
}
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TraduGeek - Meus Dados</title>
    <link rel="stylesheet" href="css\usuario.css">
    <script src="script\usuario.js"></script>
</head>
<body>
    <header>
        <nav class="barraNav">
            
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
                <li><a href="assinatura.php">Assinatura</a></li>
                <li class="logOut"><a href="script/logOut.php">Log Out</a></li>
            </ul>
            <button class="mudarTema" id="altTema" title="Alternar Tema">☀️</button>
        </nav>
    </header>

    <main>
        <div class="sections-container">
            <section class="user-data">
                <h2>Meus Dados</h2>
                <form method="POST" class="user-data-form">
                    <div class="form-row">
                        <label for="full-name">Nome Completo:</label>
                        <input type="text" id="full-name" name="full-name" value="<?= htmlspecialchars($usuario['nome']); ?>" readonly>

                        <label for="dob">Data de Nascimento:</label>
                        <input type="date" id="dob" name="dob" value="<?= htmlspecialchars($usuario['dataNascimento']); ?>" readonly>
                    </div>
                    <div class="form-row">
                        <label for="gender">Sexo:</label>
                        <input type="text" id="gender" name="gender" value="<?= htmlspecialchars($usuario['sexo']); ?>" readonly>

                        <label for="mother-name">Nome Materno:</label>
                        <input type="text" id="mother-name" name="mother-name" value="<?= htmlspecialchars($usuario['nomeMaterno']); ?>" readonly>
                    </div>
                    <div class="form-row">
                        <label for="cpf">CPF:</label>
                        <input type="text" id="cpf" name="cpf" value="<?= htmlspecialchars($usuario['cpf']); ?>" readonly>

                        <label for="email">E-mail:</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email']); ?>" readonly>
                    </div>
                    <div class="form-row">
                        <label for="mobile-phone">Telefone Celular:</label>
                        <input type="tel" id="mobile-phone" name="mobile-phone" value="<?= htmlspecialchars($usuario['telefoneCelular']); ?>" readonly>

                        <label for="landline">Telefone Fixo:</label>
                        <input type="tel" id="landline" name="landline" value="<?= htmlspecialchars($usuario['telefoneFixo']); ?>" readonly>
                    </div>
                    <div class="form-row">
                        <label for="address">Endereço Completo:</label>
                        <textarea id="address" name="address" rows="3" readonly><?= htmlspecialchars($usuario['endereco']); ?></textarea>
                    </div>
                    <div class="form-row">
                        <label for="login">Login:</label>
                        <input type="text" id="login" name="login" value="<?= htmlspecialchars($usuario['usuario_login']); ?>" readonly>
                    </div>
                <h3>Alterar Senha</h3>
                    <div class="form-row">
                        <label for="current-password">Senha Atual:</label>
                        <input 
                            type="password" 
                            id="current-password" 
                            name="current-password" 
                            placeholder="********" 
                            required
                            maxlength="8"
                            oninput="validarSenhaAtual()">
                        <span id="senhaAtualErro" class="error-message" style="color:red;">
                                <?= htmlspecialchars($senhaAtualErr); ?>
                        </span>
                    </div>

                    <div class="form-row">
                        <label for="new-password">Nova Senha:</label>
                        <input 
                            type="password" 
                            id="new-password" 
                            name="new-password" 
                            placeholder="********" 
                            required
                            maxlength="8"
                            oninput="validarSenha()">
                        <span id="senhaErro" class="error-message" style="display:none; color:red;">A senha deve conter exatamente 8 caracteres alfabéticos.</span>
                    </div>

                    <div class="form-row">
                        <label for="confirm-new-password">Confirmar Nova Senha:</label>
                        <input 
                            type="password" 
                            id="confirm-new-password" 
                            name="confirm-new-password" 
                            placeholder="********" 
                            required
                            maxlength="8"
                            oninput="validarConfirmacao()">
                        <span id="confirmSenhaErro" class="error-message" style="display:none; color:red;">As senhas não coincidem.</span>
                    </div>
                    <button type="submit">Salvar Alterações</button>
                    <p class="success"><?= $sucesso; ?></p>
                </form>
            </section>

            <section class="subscription">
    <h2>Assinatura</h2>
    <div class="subscription-info">
        <?php if ($temAssinatura): ?>
            <!-- Se o usuário tiver uma assinatura ativa -->
            <div class="active-subscription">
                <p><strong>Tipo de Assinatura:</strong> <?php echo htmlspecialchars($nomeAssinatura); ?></p>
                <p><strong>Custo:</strong> <?php echo htmlspecialchars($custo); ?></p>
               
                <form method="POST">
                    <button type="submit" name="cancelar_assinatura" class="cancel-subscription">
                        Cancelar Assinatura
                    </button>
                </form>
            </div>
        <?php else: ?>
            <!-- Se o usuário não tiver assinatura -->
            <div class="no-subscription">
                <p>Você não tem uma assinatura ativa.</p>
              <button  type="submit" name="cancelar_assinatura" class="cancel-subscription"> <a href="assinatura.php" class="buy-subscription">Adquirir Plano</a></button> 
            </div>
        <?php endif; ?>
    </div>
</section>

            <section class="translation-history">
                <h2>Histórico de Traduções <br> <span class="ilustrativo" style="font-weight: 400; font-size: 15pt;">(Os dados apresentados são meramente ilustrativos, referentes a uma funcionalidade futura)</span></h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data</th>
                            <th>Idioma de Origem</th>
                            <th>Idioma de Destino</th>
                            <th>Texto Original</th>
                            <th>Texto Traduzido</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>18/09/2024</td>
                            <td>Inglês</td>
                            <td>Português</td>
                            <td>Hello, how are you?</td>
                            <td>Olá, como você está?</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>17/09/2024</td>
                            <td>Japonês</td>
                            <td>Inglês</td>
                            <td>こんにちは</td>
                            <td>Hello</td>
                        </tr>
                    </tbody>
                </table>
            </section>
        </div>
    </main>

    <footer>
        <p class="footer-text">"Conhecimento sem limites, traduções sem fronteiras." © 2024 TraduGeek</p>
    </footer>
    <?php 
    include 'botaoVoltarAoTopo.php'; 
    ?>
    <script src="script\altTemaImg.js"></script>
</body>
</html>
