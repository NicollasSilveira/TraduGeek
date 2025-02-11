<?php
// Conexão com o banco de dados
require_once "c.php"; 

// Verifica se foi passado o ID do plano
if (isset($_GET['idassinatura'])) {
    $idassinatura = $_GET['idassinatura'];

    // Escapa o ID para segurança
    $idassinatura = mysqli_real_escape_string($conexao, $idassinatura);

    // Primeiro, desvincula todos os usuários que têm este plano
    $sqlDesvincular = "UPDATE usuario SET idassinatura = NULL WHERE idassinatura = $idassinatura";
    if (mysqli_query($conexao, $sqlDesvincular)) {
        // Agora, deleta o plano
        $sqlDeletar = "DELETE FROM assinatura WHERE idassinatura = $idassinatura";
        if (mysqli_query($conexao, $sqlDeletar)) {
            header("Location: admin.php");
        } else {
            echo "Erro ao deletar o plano: " . mysqli_error($conexao);
        }
    } else {
        echo "Erro ao desvincular usuários: " . mysqli_error($conexao);
    }
} else {
    echo "ID do plano não fornecido!";
}
?>
