<?php
// Inclua o arquivo de conexão com o banco de dados
require_once 'conexao.php';

// Função para verificar a senha atual no banco de dados
function verificarSenhaAtual($senhaAtual, $usuarioId) {
    global $pdo; // Conexão com o banco de dados

    // Consulta SQL para pegar a senha criptografada do banco
    $stmt = $pdo->prepare("SELECT senha FROM usuario WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $usuarioId]);
    $usuario = $stmt->fetch();

    // Verifica se a senha atual fornecida corresponde ao hash armazenado
    return password_verify($senhaAtual, $usuario['senha']);
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Captura os dados do formulário
    $senhaAtual = $_POST['current-password'];
    $novaSenha = $_POST['new-password'];
    $confirmarNovaSenha = $_POST['confirm-new-password'];

    // Verifica se a senha atual fornecida é válida
    if (!verificarSenhaAtual($senhaAtual, $_SESSION['usuario_id'])) {
        // Se a senha atual estiver incorreta, exibe um erro
        echo "<script>alert('A senha atual está incorreta.');</script>";
    } else {
        // Verifica se a nova senha e a confirmação coincidem
        if ($novaSenha === $confirmarNovaSenha) {
            // Criptografa a nova senha
            $novaSenhaHash = password_hash($novaSenha, PASSWORD_BCRYPT);

            // Atualiza a senha no banco de dados
            $stmt = $pdo->prepare("UPDATE usuario SET senha = :novaSenha WHERE id = :id");
            $stmt->execute([
                ':novaSenha' => $novaSenhaHash,
                ':id' => $_SESSION['usuario_id']
            ]);

            // Mensagem de sucesso
            echo "<script>alert('Senha alterada com sucesso!');</script>";
        } else {
            // Se as senhas não coincidem, exibe um erro
            echo "<script>alert('As senhas novas não coincidem.');</script>";
        }
    }
}
?>