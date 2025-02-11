<?php
// Inicia a sessão apenas se ainda não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado
$idusuario = $_SESSION['usuario_id'] ?? null; // Define $idusuario ou null se não estiver logado

require_once "c.php"; // Inclui a configuração do banco de dados

// Inicializa $id_plano_atual para evitar avisos
$id_plano_atual = null;

// Consulta o plano atual se o usuário estiver logado
if ($idusuario) {
    $sql_plano_atual = "SELECT idassinatura FROM usuario WHERE idusuario = ?";
    $stmt_plano_atual = $conexao->prepare($sql_plano_atual);
    $stmt_plano_atual->bind_param("i", $idusuario);
    $stmt_plano_atual->execute();
    $stmt_plano_atual->bind_result($id_plano_atual);
    $stmt_plano_atual->fetch();
    $stmt_plano_atual->close();
}

// Se o formulário for enviado, vincula o novo plano ao usuário logado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $idusuario && isset($_POST['nome_plano'])) {
    $nome_plano = $_POST['nome_plano']; // Nome do plano a ser selecionado

    // Consulta para obter o id do plano com base no nome
    $sql_plano = "SELECT idassinatura FROM assinatura WHERE nome = ?";
    $stmt_plano = $conexao->prepare($sql_plano);
    $stmt_plano->bind_param("s", $nome_plano);
    $stmt_plano->execute();
    $stmt_plano->bind_result($idassinatura);
    $stmt_plano->fetch();
    $stmt_plano->close();

    if (isset($idassinatura)) {
        // Atualiza a tabela usuário com o plano selecionado
        $sql = "UPDATE usuario SET idassinatura = ? WHERE idusuario = ?";
        $stmt = $conexao->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ii", $idassinatura, $idusuario);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                header("location: assinatura.php");
                exit;
            } else {
                echo "<p>Erro ao vincular plano ao usuário. Tente novamente.</p>";
            }

            $stmt->close();
        } else {
            echo "<p>Erro ao preparar a consulta. Tente novamente.</p>";
        }
    } else {
        echo "<p>Plano não encontrado.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TraduGeek - Planos de Assinatura</title>
    <link rel="stylesheet" href="css/assinatura.css">
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
                <li><a href="index.php">Voltar à tela principal</a></li>
                <?php if (isset($_SESSION['online']) && $_SESSION['online'] === true): ?>
                    <li class="logOut"><a href="script/logOut.php">Log Out</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="cadastro.php">Cadastrar-se</a></li>
                <?php endif; ?>
            </ul>
            <button class="mudarTema" id="altTema" title="Alternar Tema">☀️</button>
        </nav>
    </header>

    <main>
        <div id="cherry-blossoms"></div>
        <?php
// Consulta para buscar os planos
$sql = "SELECT * FROM assinatura";
$resultado = $conexao->query($sql);

if ($resultado->num_rows > 0): ?>
    <section class="section-admin">
        <h2>Planos de Assinatura <br> <span class="ilustrativo" style=" font-weight: 400; font-size: 15pt;">(Os dados a seguir são apenas ilustrativos e não correspondem aos valores finais)</span></h2>
        
        <div class="planos-container">
            <?php 
            // Definir imagens padrão para os planos
            $imagens = [
                "Imagens/avatar-julio-assinatura1.png", 
                "Imagens/avatar-julio-assinatura2.png", 
                "Imagens/avatar-julio-assinatura3.png"
            ];

            // Contador para determinar qual imagem usar
            $imagemIndex = 0;

            while ($plano = $resultado->fetch_assoc()): ?>
                <div class="plano-card">
                    <h3><?php echo htmlspecialchars($plano['nome']); ?></h3>
                    <ul>
                        <li><strong>Custo:</strong> <br><?php echo htmlspecialchars($plano['custo']); ?></li>
                        <li><strong>Traduções Permitidas:</strong> <br><?php echo htmlspecialchars($plano['traduPermitidas']); ?></li>
                        <li><strong>Tamanho Máximo do Arquivo:</strong> <br><?php echo htmlspecialchars($plano['maxArquivos']); ?> MB</li>
                        <li><strong>Recursos Adicionais:</strong> <br><?php echo htmlspecialchars($plano['recursos']); ?></li>
                        <li><strong>Suporte:</strong> <br><?php echo htmlspecialchars($plano['suporte']); ?></li>
                        <?php if (!empty($plano['beneficiosExtras'])): ?>
                            <li><strong>Benefícios Extras:</strong> <br><?php echo htmlspecialchars($plano['beneficiosExtras']); ?></li>
                        <?php endif; ?>
                        <?php if (!empty($plano['limitacoes'])): ?>
                            <li><strong>Limitações:</strong> <br><?php echo htmlspecialchars($plano['limitacoes']); ?></li>
                        <?php endif; ?>
                    </ul>

                    <!-- Exibe a imagem correspondente ao plano -->
                    <?php 
                    // Verifica se existe uma imagem personalizada no banco
                    $imagemPlano = !empty($plano['imagem']) ? $plano['imagem'] : $imagens[$imagemIndex];
                    ?>
                    <img src="<?php echo $imagemPlano; ?>" id="imagem-<?php echo $imagemIndex + 1; ?>" alt="Imagem do <?php echo htmlspecialchars($plano['nome']); ?>">

                    <!-- Incrementa o contador para usar a próxima imagem -->
                    <?php $imagemIndex++; ?>
                    <?php if ($imagemIndex >= count($imagens)) $imagemIndex = 0; ?> <!-- Caso tenha mais planos que imagens, reinicia o contador -->

                    <!-- Verifica se o plano é o plano atual do usuário -->
                    <?php if ($plano['idassinatura'] == $id_plano_atual): ?>
                        <p>Plano Atual</p>
                    <?php elseif (empty($_SESSION['admin-master']) || !$_SESSION['admin-master']): ?>
                        <!-- Formulário do plano para usuários comuns -->
                        <?php if ($idusuario): ?>
                            <form method="POST" action="assinatura.php">
                                <input type="hidden" name="nome_plano" value="<?php echo htmlspecialchars($plano['nome']); ?>">
                                <button class="select-plan-button" type="submit">Selecionar Plano</button>
                            </form>
                        <?php else: ?>
                            <p>Faça login ou se cadastre para adquirir um plano.</p>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="admin-note">Visualização apenas</p>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
<?php else: ?>
    <p>Nenhum plano disponível no momento.</p>
<?php endif; ?>


    </main>

    <footer>
        <div class="footer-content">
            <p>© 2024 TraduGeek. Todos os direitos reservados.</p>
        </div>
    </footer>
    <?php
    include 'botaoVoltarAoTopo.php';
    ?>
<script src="script/altTemaImg.js"></script>
</body>
</html>