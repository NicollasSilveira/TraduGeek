<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "tradugeek"; 

$conexao = new mysqli($servername, $username, $password, $dbname);

if ($conexao->connect_error) {
    die("Conexão falhou: " . $conexao->connect_error);
} 

?>

<!-- Dados do Usuario Master 

Login: tdGeek
Senha: tradgeek
Data Nascimento: 12/06/1980
Nome da Mãe: TraduGeek Criadores
Endereço: 21863-000

-->
