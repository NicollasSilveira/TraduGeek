<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificação de administrador
if (isset($_SESSION['admin-master']) && $_SESSION['admin-master'] === true) {
    // O usuário tem acesso permitido
} else {
    // Se o usuário não for admin, redireciona e encerra
    header('Location: index.php');
    exit();
}

?>