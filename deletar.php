<?php
// Inicia a sessão para verificar se o usuário está logado
session_start();

// Inclui o arquivo de configuração
require_once "c.php";

// Verifica se o parâmetro 'idusuario' foi passado na URL
if (isset($_GET["idusuario"]) && is_numeric($_GET["idusuario"])) {
    $param_id = $_GET["idusuario"];

    // Primeiro, exclui os registros na tabela 'log' que fazem referência ao usuário
    $sqlDeleteLog = "DELETE FROM log WHERE usuario_idusuario = ?";
    if ($stmtDeleteLog = mysqli_prepare($conexao, $sqlDeleteLog)) {
        mysqli_stmt_bind_param($stmtDeleteLog, "i", $param_id);
        
        if (!mysqli_stmt_execute($stmtDeleteLog)) {
            $_SESSION['mensagem'] = "Erro ao excluir registros de log.";
            mysqli_stmt_close($stmtDeleteLog);
            mysqli_close($conexao);
            header("location: logado.php");
            exit();
        }

        // Fecha o statement de exclusão do log
        mysqli_stmt_close($stmtDeleteLog);
    } else {
        $_SESSION['mensagem'] = "Erro ao preparar a consulta para excluir o log.";
        mysqli_close($conexao);
        header("location: logado.php");
        exit();
    }

    // Desvincula a assinatura do usuário, se houver
    $sqlUpdate = "UPDATE usuario SET idassinatura = NULL WHERE idusuario = ?";
    if ($stmtUpdate = mysqli_prepare($conexao, $sqlUpdate)) {
        mysqli_stmt_bind_param($stmtUpdate, "i", $param_id);
        
        if (!mysqli_stmt_execute($stmtUpdate)) {
            $_SESSION['mensagem'] = "Erro ao desvincular a assinatura do usuário.";
            mysqli_stmt_close($stmtUpdate);
            mysqli_close($conexao);
            header("location: logado.php");
            exit();
        }

        // Fecha o statement de atualização
        mysqli_stmt_close($stmtUpdate);
    } else {
        $_SESSION['mensagem'] = "Erro ao preparar a consulta para desvincular a assinatura.";
        mysqli_close($conexao);
        header("location: logado.php");
        exit();
    }

    // Agora, deleta o usuário
    $sqlDelete = "DELETE FROM usuario WHERE idusuario = ?";
    if ($stmtDelete = mysqli_prepare($conexao, $sqlDelete)) {
        mysqli_stmt_bind_param($stmtDelete, "i", $param_id);

        if (mysqli_stmt_execute($stmtDelete)) {
            // Se a exclusão for bem-sucedida, comita a transação
            mysqli_commit($conexao);

            // Fecha o statement
            mysqli_stmt_close($stmtDelete);

            // Fecha a conexão com o banco de dados
            mysqli_close($conexao);

            // Define uma mensagem de sucesso na sessão
            $_SESSION['mensagem'] = "Usuário excluído com sucesso!";
            header("location: logado.php");
            exit();
        } else {
            // Em caso de falha na execução, define uma mensagem de erro
            $_SESSION['mensagem'] = "Erro ao excluir o usuário. Tente novamente.";
            mysqli_stmt_close($stmtDelete);
            mysqli_close($conexao);
            header("location: logado.php");
            exit();
        }
    } else {
        // Se a preparação da consulta falhar
        $_SESSION['mensagem'] = "Erro ao preparar a consulta para excluir o usuário.";
        mysqli_close($conexao);
        header("location: logado.php");
        exit();
    }
} else {
    // Se o id não for passado corretamente
    $_SESSION['mensagem'] = "ID do usuário inválido.";
    header("location: logado.php");
    exit();
}

?>
