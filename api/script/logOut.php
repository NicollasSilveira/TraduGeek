<?php
    session_start();

    if(isset($_SESSION["usuario"])){

        session_destroy(); #encerra sessao (PHP).

        echo"<html> <!-- 0 -->
        <head>
            <script>
                localStorage.setItem('SubLogOut','false');
                 window.location.href = '../index.php';
            </script>
        </head>
        </html><!-- 0 -->";
        /*
        Redirecionamento para a tela principal em JS.
        E limpar alerta de logOut (Bug removido)
        */
    }else{
        echo"<html> <!-- 0 -->
        <head>
            <script>
                 window.location.href = '../index.php';
            </script>
        </head>
        </html><!-- 0 -->";
        #Somente Redireciona.
    }

    exit();
?>