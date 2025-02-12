<?php
include_once 'autenticaAdmin.php';
include_once('c.php'); // Conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idusuario'])) {
    $idusuario = (int)$_POST['idusuario'];

    if (isset($_POST['idassinatura']) && isset($_POST['status'])) {
        // Atualizar no banco de dados
        $novoPlano = $_POST['idassinatura'];
        $novoStatus = $_POST['status'];

        $sql_update = "UPDATE usuario SET idassinatura = ?, status = ? WHERE idusuario = ?";
        $stmt_update = $conexao->prepare($sql_update);
        $stmt_update->bind_param("iii", $novoPlano, $novoStatus, $idusuario);

        if ($stmt_update->execute()) {
            echo "Usuário atualizado com sucesso!";
            header("Location: logado.php"); // Redireciona para a tela principal
            exit;
        } else {
            echo "Erro ao atualizar o usuário.";
        }
    } else {
        // Buscar informações do usuário
        $sql_usuario = "SELECT idusuario, nome, idassinatura, status FROM usuario WHERE idusuario = ?";
        $stmt = $conexao->prepare($sql_usuario);
        $stmt->bind_param("i", $idusuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();
        } else {
            echo "Usuário não encontrado.";
            exit;
        }
    }
} else {
    echo "ID do usuário não foi enviado.";
    exit;
}

// Buscar todas as assinaturas disponíveis
$sql_assinaturas = "SELECT idassinatura, nome FROM assinatura";
$result_assinaturas = $conexao->query($sql_assinaturas);
$assinaturas = $result_assinaturas->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
    <link rel="stylesheet" href="css\editando.css">
</head>
<body>
<header>
        <nav class="barraNav">
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
              <li><a href="index.php">Voltar à tela principal</a></li>
                <li><a href="admin.php">Admin</a></li>
                <li><a href="logado.php">Dados</a></li>
            </ul>
            <button class="mudarTema" id="altTema" title="Alternar Tema">☀️</button>
        </nav>
    </header>
    
<main>
<div class="signup-container">
    <div class="signup-box">
    <h2 id="typing">Editar Usuário</h2>
        <form action="editando.php" method="POST">
            <!-- Inclui o ID do usuário -->
            <input type="hidden" name="idusuario" value="<?php echo $idusuario; ?>">

            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" class="form-control" 
                       value="<?php echo htmlspecialchars($usuario['nome']); ?>" readonly>
            </div>

            <div class="form-group">
                <label for="idassinatura">Plano:</label>
                <select name="idassinatura" id="idassinatura" class="form-control">
                    <?php foreach ($assinaturas as $assinatura): ?>
                        <option value="<?php echo $assinatura['idassinatura']; ?>" 
                                <?php echo ($usuario['idassinatura'] == $assinatura['idassinatura']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($assinatura['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <select name="status" id="status" class="form-control">
                    <option value="1" <?php if ($usuario['status'] == 1) echo 'selected'; ?>>Ativo</option>
                    <option value="0" <?php if ($usuario['status'] == 0) echo 'selected'; ?>>Inativo</option>
                </select>
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn-submit">Salvar Alterações</button>
                <a href="<?php echo isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'logado.php'; ?>" class="btn-back">Voltar</a>
            </div>
        </form>

    </div>
</div>
</main>
    <footer>
        <div class="footer-content">
            <p>© 2024 TraduGeek. Todos os direitos reservados.</p>
        </div>
    </footer>
    <script src="script\altTemaImg.js"></script>
</body>
</html>

