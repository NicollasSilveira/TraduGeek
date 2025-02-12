<?php

    if(isset($_SESSION['usuario'])){
            echo "<input class='isOnline' type='text' value='logado' hidden>";

        }else{
            echo "<input class='isOnline' type='text' value='deslogado' hidden>";
            
        }
?>