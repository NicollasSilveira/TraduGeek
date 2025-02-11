<?php
include_once 'autenticaAdmin.php';
include_once('c.php'); // Conexão com o banco de dados

// Definir quantos registros exibir por página para a lista de usuários
$itens_por_pagina_usuario = 3;
$paginacao_usuario = isset($_GET['pagina_usuario']) ? (int)$_GET['pagina_usuario'] : 1;
$offset_usuario = ($paginacao_usuario - 1) * $itens_por_pagina_usuario;

// Processar a busca, se houver
$busca = isset($_POST['busca']) ? $_POST['busca'] : '';

// Adicionar a condição de busca na consulta SQL, se houver algo a buscar
$sql_usuario = "SELECT * FROM usuario WHERE idusuario LIKE '%$busca%' OR nome LIKE '%$busca%' LIMIT $itens_por_pagina_usuario OFFSET $offset_usuario";
$resultado_usuario = $conexao->query($sql_usuario);

// Obter o total de registros para calcular quantas páginas exibir para os usuários
$total_resultados_sql_usuario = "SELECT COUNT(*) AS total FROM usuario WHERE idusuario LIKE '%$busca%' OR nome LIKE '%$busca%'";
$total_resultados_usuario = $conexao->query($total_resultados_sql_usuario);
$total_usuario = $total_resultados_usuario->fetch_assoc()['total'];
$total_paginas_usuario = ceil($total_usuario / $itens_por_pagina_usuario);

// Definir quantos registros exibir por página para o log
$itens_por_pagina_log = 10;
$paginacao_log = isset($_GET['pagina_log']) ? (int)$_GET['pagina_log'] : 1;
$offset_log = ($paginacao_log - 1) * $itens_por_pagina_log;

// Processar a busca no log, se houver
$buscaLog = isset($_POST['buscaLog']) ? $_POST['buscaLog'] : '';
$sql_log = "
SELECT 
    log.dataAcesso, 
    CONCAT(usuario.idusuario, ' - ', usuario.nome) AS usuario_info,  -- Exibe ID e nome juntos
    log.pergunta, 
    log.resposta, 
    log.resultado
FROM log
LEFT JOIN usuario ON log.usuario_idusuario = usuario.idusuario  
";

// Se houver uma busca, adiciona a cláusula WHERE
if ($buscaLog) {
    $sql_log .= " WHERE log.dataAcesso LIKE '%$buscaLog%' OR usuario.idusuario LIKE '%$buscaLog%' OR usuario.nome LIKE '%$buscaLog%'";
}

$sql_log .= " LIMIT $itens_por_pagina_log OFFSET $offset_log";
$result_log = $conexao->query($sql_log);

// Obter o total de registros para calcular quantas páginas exibir para o log
$total_resultados_sql_log = "SELECT COUNT(*) AS total FROM log";
$total_resultados_log = $conexao->query($total_resultados_sql_log);
$total_log = $total_resultados_log->fetch_assoc()['total'];
$total_paginas_log = ceil($total_log / $itens_por_pagina_log);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuários</title>
    <link rel="stylesheet" href="css\logado.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<header>
        <nav class="navbar">
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
                <li><a href="index.php">Tela principal</a></li>
                <li><a href="admin.php">Admin</a></li>
                <?php if (isset($_SESSION['online']) && $_SESSION['online'] === true): ?>
                    <li class="logOut"><a href="script/logOut.php">Log Out</a></li>
                <?php else: ?>
                <?php endif; ?>
            </ul>
            <button class="mudarTema" id="altTema" title="Alternar Tema">☀️</button>
        </nav>
    </header>
<main>
<section id="seção-usuarios">
    <h2>Lista de Usuários</h2>
    <form action="" method="post">
        <label for="busca">Buscar por ID ou Nome:</label>
        <input type="text" name="busca" id="busca" required>
        <button type="submit">Buscar</button>
    </form>

    <div class="table-container">
    <?php

if ($resultado_usuario->num_rows > 0) {
    echo "<table>";
    echo "<tr>
            <th>Ações</th> <!-- Coluna Ações colocada no início -->
            <th>ID</th>
            <th>Adm</th>
            <th>Nome</th>
            <th>Data de Nascimento</th>
            <th>Sexo</th>
            <th>Nome Materno</th>
            <th>CPF</th>
            <th>Email</th>
            <th>Telefone Celular</th>
            <th>Telefone Fixo</th>
            <th>CEP</th>
            <th>Logradouro</th>
            <th>Bairro</th>
            <th>Cidade</th>
            <th>Estado</th>
            <th>Complemento</th>
            <th>Número</th>
            <th>Login</th>
            <th>Status</th>
            <th>Data Criação</th>
            <th>Plano</th>
          </tr>";

    while ($linha = $resultado_usuario->fetch_assoc()) {
        echo "<tr>";
        
        echo "<td class='actions'> 
        <form action='editando.php' method='POST' style='display:inline;'>
            <input type='hidden' name='idusuario' value='" . $linha['idusuario'] . "'>
            <button type='submit'>Editar</button>
        </form>
        <a href='#' class='delete' onclick='confirmDelete(" . $linha['idusuario'] . ")'>Excluir</a>
        </td>";

        echo "<td>" . $linha['idusuario'] . "</td>";
        echo "<td>" . $linha['adm'] . "</td>";
        echo "<td>" . $linha['nome'] . "</td>";
        echo "<td>" . $linha['dataNascimento'] . "</td>";
        echo "<td>" . $linha['sexo'] . "</td>";
        echo "<td>" . $linha['nomeMaterno'] . "</td>";
        echo "<td>" . $linha['cpf'] . "</td>";
        echo "<td>" . $linha['email'] . "</td>";
        echo "<td>" . $linha['telefoneCelular'] . "</td>";
        echo "<td>" . $linha['telefoneFixo'] . "</td>";
        echo "<td>" . $linha['cep'] . "</td>";
        echo "<td>" . $linha['logradouro'] . "</td>";
        echo "<td>" . $linha['bairro'] . "</td>";
        echo "<td>" . $linha['cidade'] . "</td>";
        echo "<td>" . $linha['estado'] . "</td>";
        echo "<td>" . $linha['complemento'] . "</td>";
        echo "<td>" . $linha['numero'] . "</td>";
        echo "<td>" . $linha['usuario_login'] . "</td>";
        echo "<td>" . $linha['status'] . "</td>";
        echo "<td>" . $linha['data_criacao'] . "</td>";
        echo "<td>" . $linha['idassinatura'] . "</td>";

        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>Nenhum registro encontrado!</p>";
}
?>
</div>
</section>
<?php
    echo "<div class='pagination'>";
    for ($i = 1; $i <= $total_paginas_usuario; $i++) {
        echo "<a href='?pagina_usuario=$i'>$i</a>";
    }
    echo "</div>";
?>
<section id="Log">
    <h2>Log de Ações</h2>
    <form action="" method="post">
        <label for="buscaLog" id="buscaLogLabel">Buscar por Data, ID ou Nome: </label> 
        <input type="text" name="buscaLog" id="buscaLog" required>
        <button type="submit">Buscar</button>
    </form>
    <div class="table-container-log">
    <table class="tabela-logs" id="table-log">
        <thead>
            <tr>
                <th>Data e Hora</th>
                <th>ID - Usuário</th>
                <th>Pergunta</th>
                <th>Resposta</th>
                <th>Ação</th>
            </tr>
        </thead>
        <?php
        while ($row = $result_log->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['dataAcesso'] . "</td>";
            echo "<td>" . $row['usuario_info'] . "</td>"; 
            echo "<td>" . $row['pergunta'] . "</td>";
            echo "<td>" . $row['resposta'] . "</td>";
            echo "<td>" . $row['resultado'] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
    </div>

</section>
<div class="pagination">
        <?php
        for ($i = 1; $i <= $total_paginas_log; $i++) {
            echo "<a href='?pagina_log=$i'>$i</a>";
        }
        ?>
    </div>
</main>

<footer>
        <div class="footer-content">
            <p>© 2024 TraduGeek. Todos os direitos reservados.</p>
        </div>
    </footer>
    <?php 
        include 'botaoVoltarAoTopo.php'; 
    ?>
    <script>
        function confirmDelete(idusuario) {
            Swal.fire({
                title: 'Tem certeza?',
                text: 'Esta ação não pode ser desfeita!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'deletar.php?idusuario=' + idusuario;
                }
            });
        }
    </script>
</body>
</html>
