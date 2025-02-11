<?php
// Inicia a sessão
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['online']) || $_SESSION['online'] !== true) {
    header('Location: index.php');
    exit();
}

// Inclui o arquivo de configuração do banco de dados
require_once "c.php";

// Obtém o ID do usuário da sessão
$usuario_id = $_SESSION['usuario_id'];

// Atualiza a tabela de usuários para desvincular a assinatura
$stmt = $conexao->prepare("UPDATE usuario SET idassinatura = NULL WHERE idusuario = ?");
$stmt->bind_param("i", $usuario_id);
if ($stmt->execute()) {
    // Redireciona para a página de perfil após o cancelamento
    header("Location: usuario.php");
    exit();
} else {
    echo "Erro ao cancelar assinatura.";
}
$stmt->close();
?>

