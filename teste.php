<?php
session_start();
include 'c.php';

if (isset($_POST['login']) && isset($_POST['password'])) {
    $login = $_POST['login'];
    $senha = $_POST['password'];

    $stmt = $conexao->prepare("SELECT * FROM usuario WHERE usuario_login = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario'] = $usuario['usuario_login']; // Login do usuário
            $_SESSION['usuario_id'] = $usuario['idusuario'];
            $_SESSION['admin-master'] = $usuario['adm'] == 1;
            $_SESSION['tentativas_2fa'] = 0;

            // Redireciona para o 2FA (independente de ser master ou comum)
            $_SESSION['online'] = false; // Sessão só ativa após o 2FA
            header("Location: login_2fa.php");
            exit();
        } else {
            $_SESSION['erro_login'] = "Senha inválida!";
            $_SESSION['online'] = false;
        }
    } else {
        $_SESSION['erro_login'] = "Usuário não encontrado!";
        $_SESSION['online'] = false;
    }

    $stmt->close();
    $conexao->close();

    header("Location: login.php");
    exit();
} else {
    $_SESSION['erro_login'] = "Por favor, preencha todos os campos!";
    header("Location: login.php");
    exit();
}
?>