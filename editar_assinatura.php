<?php
session_start();

// Verifica se foi enviado o ID do plano
require_once "c.php";  // Conexão com o banco de dados

if (isset($_GET['idassinatura'])) {
    $idassinatura = $_GET['idassinatura'];

    // Escapar o ID do plano para segurança
    $idassinatura = mysqli_real_escape_string($conexao, $idassinatura);

    // Recupera os dados do plano para preencher o formulário
    $sql = "SELECT idassinatura, nome, custo, traduPermitidas, maxArquivos, recursos, suporte, limitacoes, beneficiosExtras, imagem FROM assinatura WHERE idassinatura = $idassinatura";
    $result = mysqli_query($conexao, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $nome = htmlspecialchars($row['nome']);
        $custo = htmlspecialchars($row['custo']);
        $traduPermitidas = htmlspecialchars($row['traduPermitidas']);
        $maxArquivos = htmlspecialchars($row['maxArquivos']);
        $recursos = htmlspecialchars($row['recursos']);
        $suporte = htmlspecialchars($row['suporte']);
        $limitacoes = htmlspecialchars($row['limitacoes']);
        $beneficiosExtras = htmlspecialchars($row['beneficiosExtras']);
        $imagem = htmlspecialchars($row['imagem']);
    } else {
        echo "Plano não encontrado.";
        exit;
    }

}

// Verifica se o formulário foi enviado para atualizar os dados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Coleta e escapa os dados do formulário para evitar injeção de SQL
    $nome = mysqli_real_escape_string($conexao, $_POST['nome']);
    $custo = mysqli_real_escape_string($conexao, $_POST['custo']);
    $traduPermitidas = mysqli_real_escape_string($conexao, $_POST['traduPermitidas']);
    $maxArquivos = mysqli_real_escape_string($conexao, $_POST['maxArquivos']);
    $recursos = mysqli_real_escape_string($conexao, $_POST['recursos']);
    $suporte = mysqli_real_escape_string($conexao, $_POST['suporte']);
    $limitacoes = mysqli_real_escape_string($conexao, $_POST['limitacoes']);
    $beneficiosExtras = mysqli_real_escape_string($conexao, $_POST['beneficiosExtras']);

    // Variável para a imagem
    $imagem = $_POST['imagem_selecionada']; // Caminho da imagem selecionada

    // Atualiza o plano no banco de dados
    $sql = "UPDATE assinatura 
            SET nome = '$nome', custo = '$custo', traduPermitidas = '$traduPermitidas', 
            maxArquivos = '$maxArquivos', recursos = '$recursos', suporte = '$suporte', 
            limitacoes = '$limitacoes', beneficiosExtras = '$beneficiosExtras', imagem = '$imagem' 
            WHERE idassinatura = $idassinatura";

    if (mysqli_query($conexao, $sql)) {
        echo header("Location: admin.php");
    } else {
        echo "Erro ao atualizar plano: " . mysqli_error($conexao);
    }
}

function listarImagens($diretorio) {
    $imagens = [];
    $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif']; // Extensões de imagem permitidas

    if (is_dir($diretorio)) {
        $arquivos = scandir($diretorio);
        foreach ($arquivos as $arquivo) {
            $extensao = pathinfo($arquivo, PATHINFO_EXTENSION);
            if (in_array(strtolower($extensao), $extensoesPermitidas)) {
                $imagens[] = $arquivo;  // Adiciona o nome da imagem ao array
            }
        }
    }
    return $imagens;
}

// Chama a função para listar as imagens da pasta 'Imagens'
$imagensDisponiveis = listarImagens('Imagens');

// Se não houver imagens disponíveis, exibe uma mensagem
if (empty($imagensDisponiveis)) {
    echo "<p>Não há imagens disponíveis.</p>";
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TraduGeek - Planos de Assinatura</title>
    <link rel="stylesheet" href="css\editarAssinatura.css">
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
            </ul>
            <button class="mudarTema" id="altTema" title="Alternar Tema">☀️</button>
        </nav>
    </header>

<!-- Formulário de edição -->
 <main>
<div class="signup-container">
    <div class="signup-box">
        <h2 id="typing">Editar Assinatura</h2>
        <form method="POST" action="editar_assinatura.php?idassinatura=<?php echo $idassinatura; ?>">
            <input type="hidden" name="idassinatura" value="<?php echo $idassinatura; ?>" />

            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" value="<?php echo $nome; ?>" required />
            </div>

            <div class="form-group">
                <label for="custo">Custo:</label>
                <input type="text" name="custo" value="<?php echo $custo; ?>" required />
            </div>

            <div class="form-group">
                <label for="traduPermitidas">Traduções Permitidas:</label>
                <input type="text" name="traduPermitidas" value="<?php echo $traduPermitidas; ?>" required />
            </div>

            <div class="form-group">
                <label for="maxArquivos">Tamanho Máximo do Arquivo:</label>
                <input type="text" name="maxArquivos" value="<?php echo $maxArquivos; ?>" required />
            </div>

            <div class="form-group">
                <label for="recursos">Recursos Adicionais:</label>
                <input type="text" name="recursos" value="<?php echo $recursos; ?>" required />
            </div>

            <div class="form-group">
                <label for="suporte">Suporte:</label>
                <input type="text" name="suporte" value="<?php echo $suporte; ?>" required />
            </div>

            <div class="form-group">
                <label for="limitacoes">Limitações:</label>
                <input type="text" name="limitacoes" value="<?php echo $limitacoes; ?>" required />
            </div>

            <div class="form-group">
                <label for="beneficiosExtras">Benefícios Extras:</label>
                <input type="text" name="beneficiosExtras" value="<?php echo $beneficiosExtras; ?>" />
            </div>

            <div class="form-group">
                <label for="imagem">Escolha uma Imagem:</label>
                <select name="imagem_selecionada">
                    <option value="">Selecione uma imagem</option>
                    <?php foreach ($imagensDisponiveis as $imagemDisponivel): ?>
                        <option value="Imagens/<?php echo $imagemDisponivel; ?>" 
                            <?php echo ($imagem == "Imagens/$imagemDisponivel") ? 'selected' : ''; ?>>
                            <?php echo $imagemDisponivel; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-buttons">
                <input type="submit" value="Atualizar Plano" />
                <input type="button" value="Voltar" onclick="location.href='admin.php'" />
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
