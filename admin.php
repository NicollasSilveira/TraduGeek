<?php

// Inclui a autenticação de admin primeiro
require_once 'autenticaAdmin.php';

// Inclui o arquivo de configuração do banco de dados
require_once "c.php";

// Inicializa a variável que vai conter o total de usuários
$total_usuarios = 0;
$novos_usuarios = 0;
$sql = "SELECT COUNT(*) as novos_usuarios FROM usuario WHERE data_criacao >= NOW() - INTERVAL 5 MINUTE";
if($res = mysqli_query($conexao, $sql)){
    $row = mysqli_fetch_assoc($res);
    $novos_usuarios = $row['novos_usuarios'];
}
// Prepara a consulta para contar o número de usuários
$sql = "SELECT COUNT(*) as total FROM Usuario";

if ($result = mysqli_query($conexao, $sql)) {
    // Recupera o resultado
    $row = mysqli_fetch_assoc($result);
    $total_usuarios = $row['total'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupera os valores do formulário usando POST
    $nome = $_POST['nome-plano'];
    $custo = $_POST['custo'];
    $tradPermitidas = $_POST['traducao'];
    $maxArquivos = $_POST['tamanho'];
    $recursos = $_POST['recursos'];
    $suporte = $_POST['suporte'] === 'sim' ? 1 : 0; // Converte suporte para 1 ou 0
    $limitacoes = $_POST['limitacoes'];
    $beneficiosExtras = 'não definido'; // Número aleatório para benefíciosExtras (substituir conforme lógica futura)

    // Prepara a consulta SQL para inserir os dados
    $sql_inserir = "INSERT INTO assinatura (nome, custo, traduPermitidas, maxArquivos, recursos, suporte, limitacoes, beneficiosExtras) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)"; // 1 é o valor fixo para usuario_idusuario

    // Prepara o statement
    if ($stmt = mysqli_prepare($conexao, $sql_inserir)) {
        // Vincula os parâmetros da consulta
        mysqli_stmt_bind_param($stmt, 'ssdsssss', 
            $nome, $custo, $tradPermitidas, $maxArquivos, $recursos, $suporte, 
            $limitacoes, $beneficiosExtras
        );

        // Executa o statement
        if (mysqli_stmt_execute($stmt)) {
            // Após o sucesso, redireciona para a mesma página ou outra
            header("Location: " . $_SERVER['PHP_SELF']); // Redireciona para a mesma página
            exit(); // Encerra o script para evitar que o código posterior seja executado
        } else {
            echo "Erro ao inserir plano de assinatura: " . mysqli_error($conexao);
        }

        // Fecha o statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Erro ao preparar a consulta: " . mysqli_error($conexao);
    }
}

// Fecha a conexão com o banco de dados


?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TraduGeek - Painel Admin</title>
    <link rel="stylesheet" href="css\admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <header>
        <nav class="navbar-admin">
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
                <li><a href="#dashboard">Dashboard</a></li>
                <li><a href="#usuarios">Gerenciar Usuários</a></li>
                <li><a href="#logs">Log de Ações</a></li>
                <li><a href="#planos">Gestão de Planos</a></li>
                <li><a href="#estatisticas">Estatísticas</a></li>
                <li class="logOut"><a href="script/logOut.php">Log Out</a></li>
            </ul>
            <button class="mudarTema" id="altTema" title="Alternar Tema">☀️</button>
        </nav>
    </header>

    <main>
        <section id="dashboard" class="section-admin">
            <h2>Dashboard <br> <span class="ilustrativo" style="font-weight: 400; font-size: 15pt;">(Os dados apresentados são meramente ilustrativos, referentes a uma funcionalidade futura)</span></h2>

            <!-- Contêiner para os gráficos -->
            <div class="charts-container">
                <div class="chart-container">
                    <h3>Novos Usuários</h3>
                    <canvas id="newUsersChart"></canvas>
                </div>
                <div class="chart-container">
                    <h3>Receita Mensal</h3>
                    <canvas id="monthlyRevenueChart"></canvas>
                </div>
                <div class="chart-container">
                    <h3>Perda de Receita Mensal</h3>
                    <canvas id="revenueLossChart"></canvas>
                </div>
                <div class="chart-container">
                    <h3>Média de Traduções por Mês</h3>
                    <canvas id="translationsAverageChart"></canvas>
                </div>
            </div>
        </section>

        <section id="usuarios" class="section-admin">  
        <h2>Gerenciar Usuários</h2>
            <table class="tabela-usuarios">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Assinatura Ativa</th>
                        <th>Tipo de Assinatura</th>
                        <th>Alterar</th>
                        <th>Deletar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $sql = "SELECT usuario.idusuario, usuario.nome, usuario.email, usuario.idassinatura, assinatura.nome AS tipo_assinatura
                            FROM usuario 
                            LEFT JOIN assinatura ON usuario.idassinatura = assinatura.idassinatura";
                    $result = $conexao->query($sql);

                    if ($result->num_rows > 0) {
                        while ($linha = $result->fetch_assoc()) {
                            $assinaturaAtiva = $linha['idassinatura'] ? 'Sim' : 'Não';
                            $tipoAssinatura = $linha['tipo_assinatura'] ? $linha['tipo_assinatura'] : 'Nenhuma';

                            echo "<tr>
                                <td>" . $linha['idusuario'] . "</td>
                                <td>" . $linha['nome'] . "</td>
                                <td>" . $linha['email'] . "</td>
                                <td>" . $assinaturaAtiva . "</td>
                                <td>" . $tipoAssinatura . "</td>
                                <td>
                                    <form action='editando.php' method='POST'>
                                        <input type='hidden' name='idusuario' value='" . $linha['idusuario'] . "' />
                                        <button type='submit' class='btn-acao'>Alterar</button>
                                    </form>
                                </td>
                                <td>
                                    <button onclick='confirmDelete(" . $linha['idusuario'] . ")' class='btn-acao'>Deletar</button>
                                </td>
                            </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
            <div class="view-more-container">
                <a href="logado.php" class="btn-view-more">Ver Mais Informações</a>
            </div>
        </section>

        <!-- SweetAlert Script -->
        <script>
            function confirmDelete(id) {
                Swal.fire({
                    title: 'Tem certeza?',
                    text: 'Esta ação não pode ser desfeita!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sim, excluir!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'deletarAdmtela.php?idusuario=' + id;
                    }
                });
            }
        </script>

        <section id="logs" class="section-admin">
            <h2>Log de Ações</h2>
            <table class="tabela-logs">
                <thead>
                    <tr>
                        <th>Data e Hora</th>
                        <th>Usuário</th>
                        <th>Pergunta</th>
                        <th>Resposta</th>
                        <th>Resultado</th>
                        
                        
                    </tr>
                </thead>
                <?php
$sql = "
SELECT 
    log.dataAcesso, 
    usuario.nome AS nome_usuario, 
    log.resultado, 
    log.pergunta, 
    log.resposta
FROM log
INNER JOIN usuario ON log.usuario_idusuario = usuario.idusuario
ORDER BY log.dataAcesso DESC";

$result = $conexao->query($sql);

if ($result->num_rows > 0) {
$contador = 0; // Variável de controle
while ($linha = $result->fetch_assoc()) {
    echo '<tr>
            <td>' . $linha['dataAcesso'] . '</td>
            <td>' . $linha['nome_usuario'] . '</td>
            <td>' . ($linha['pergunta'] ?? 'N/A') . '</td>
            <td>' . ($linha['resposta'] ?? 'N/A') . '</td>
            <td>' . $linha['resultado'] . '</td>
        </tr>';

    $contador++;
    if ($contador >= 4) {
        break; // Interrompe o loop após 4 registros
    }
}
} else {
echo '<tr><td colspan="5">Nenhum log encontrado.</td></tr>';
}
?>
            </table>
            <div class="view-more-container">
            <a href="logado.php" class="btn-view-more">Ver Mais Informações</a>
            </div>

            
        </section>

        <section id="planos" class="section-admin">
    <h2>Gestão de Planos de Assinatura</h2>

    <div class="planos-actions">
        <!-- Botão para criar um novo plano -->
        <button class="btn-acao" onclick="document.getElementById('modal-criar').style.display='block'">
            Criar Novo Plano
        </button>
    </div>

    <table class="tabela-planos">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome do Plano</th>
                <th>Custo</th>
                <th>Traduções Permitidas</th>
                <th>Tamanho Máximo do Arquivo</th>
                <th>Recursos Adicionais</th>
                <th>Suporte</th>
                <th>Limitações</th>
                <th>Editar</th>
                <th>Deletar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT idassinatura, nome, custo, traduPermitidas, maxArquivos, recursos, suporte, limitacoes FROM assinatura";
            $result = mysqli_query($conexao, $sql);

            // Verifica se há resultados
            if ($result && mysqli_num_rows($result) > 0) {
                // Itera sobre os resultados e cria as linhas da tabela
                while ($row = mysqli_fetch_assoc($result)) {
                    $idassinatura = htmlspecialchars($row['idassinatura']);
                    echo "<tr>";
                    echo "<td>" . $idassinatura . "</td>";
                    echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['custo']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['traduPermitidas']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['maxArquivos']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['recursos']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['suporte']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['limitacoes']) . "</td>";
                    echo "<td><a href='editar_assinatura.php?idassinatura=$idassinatura' class='btn-acao'>Editar</a></td>";
                    echo "<td><button class='btn-acao btn-delete' onclick=\"confirmarExclusaoPlano('$idassinatura')\">Deletar</button></td>";
                    echo "</tr>";
                }
            } else {
                // Exibe uma mensagem se não houver resultados
                echo "<tr><td colspan='10'>Nenhum plano encontrado.</td></tr>";
            }
            ?>
        </tbody>
            </table>
            <script>
                function confirmarExclusaoPlano(idassinatura) {
                    Swal.fire({
                        title: 'Tem certeza?',
                        text: 'Você deseja deletar este plano de assinatura?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sim, excluir!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redireciona para o PHP para exclusão
                            window.location.href = `deletarAssinatura.php?idassinatura=${idassinatura}`;
                        }
                    });
                }
            </script>

            <!-- Modal para criar novo plano -->
            <div id="modal-criar" class="modal">
                <div class="modal-content">
                    <span class="close"
                        onclick="document.getElementById('modal-criar').style.display='none'">&times;</span>
                    <h2>Criar Novo Plano</h2>
                    <form action="admin.php" method="post">
                        <label for="nome-plano">Nome do Plano:</label>
                        <input type="text" id="nome-plano" name="nome-plano" required>
                        <label for="custo">Custo:</label>
                        <input type="text" id="custo" name="custo" required>
                        <label for="traducao">Traduções Permitidas:</label>
                        <input type="number" id="traducao" name="traducao" required>
                        <label for="tamanho">Tamanho Máximo do Arquivo:</label>
                        <input type="text" id="tamanho" name="tamanho" required>
                        <label for="recursos">Recursos Adicionais:</label>
                        <textarea id="recursos" name="recursos" required></textarea>
                        <label for="suporte">Suporte:</label>
                        <select id="suporte" name="suporte">
                            <option value="sim">Sim</option>
                            <option value="nao">Não</option>
                        </select>
                        <label for="limitacoes">Limitações:</label>
                        <textarea id="limitacoes" name="limitacoes" required></textarea>
                        <button type="submit" class="btn-acao">Criar Plano</button>
                    </form>
                </div>
            </div>

            <!-- Modal para editar plano -->
            <div id="modal-editar" class="modal">
                <div class="modal-content">
                    <span class="close"
                        onclick="document.getElementById('modal-editar').style.display='none'">&times;</span>
                    <h2>Editar Plano</h2>
                    <form action="POST">
                        <label for="nome-plano-editar">Nome do Plano:</label>
                        <input type="text" id="nome-plano-editar" name="nome-plano-editar" value="Plano Básico"
                            required>
                        <label for="custo-editar">Custo:</label>
                        <input type="text" id="custo-editar" name="custo-editar" value="R$ 29,99" required>
                        <label for="traducao-editar">Traduções Permitidas:</label>
                        <input type="number" id="traducao-editar" name="traducao-editar" value="50" required>
                        <label for="tamanho-editar">Tamanho Máximo do Arquivo:</label>
                        <input type="text" id="tamanho-editar" name="tamanho-editar" value="10 MB" required>
                        <label for="recursos-editar">Recursos Adicionais:</label>
                        <textarea id="recursos-editar" name="recursos-editar" required>Tradução automática</textarea>
                        <label for="suporte-editar">Suporte:</label>
                        <select id="suporte-editar" name="suporte-editar">
                            <option value="sim" selected>Sim</option>
                            <option value="nao">Não</option>
                        </select>
                        <label for="limitacoes-editar">Limitações:</label>
                        <textarea id="limitacoes-editar" name="limitacoes-editar" required>Sem suporte 24/7</textarea>
                        <button type="submit" class="btn-acao">Salvar Alterações</button>
                    </form>
                </div>
            </div>
        </section>

        <section id="estatisticas" class="section-admin">
            <h2>Estatísticas do Site <br> <span class="ilustrativo" style="font-weight: 400; font-size: 15pt;">(Os dados de estatísticas referentes ao total de receita, cancelamentos, perdas totais e traduções realizadas são apenas ilustrativos, uma vez que se tratam de uma funcionalidade futura)</span></h2>
            <div class="estatisticas-container">
                <div class="estatistica-item">
                    <h3>Novos Usuários</h3>
                    <p><?php echo $novos_usuarios; ?></p>
                </div>
                <div class="estatistica-item">
                    <h3>Total de Usuários</h3>
                    <p><?php echo $total_usuarios; ?></p>
                </div>
                <div class="estatistica-item">
                    <h3>Total de Receita</h3>
                    <p>R$ 5.000</p>
                </div>
                <div class="estatistica-item">
                    <h3>Cancelamentos</h3>
                    <p>10</p>
                </div>
                <div class="estatistica-item">
                    <h3>Total de Perda</h3>
                    <p>R$ 500</p>
                </div>
                <div class="estatistica-item">
                    <h3>Traduções Realizadas</h3>
                    <p>320</p>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>© 2024 TraduGeek - Painel de Administração</p>
    </footer>
    <?php 
    include 'botaoVoltarAoTopo.php'; 
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Gráficos existentes
            var ctxNewUsers = document.getElementById('newUsersChart').getContext('2d');
            var newUsersChart = new Chart(ctxNewUsers, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Novos Usuários',
                        data: [10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 110, 120],
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            var ctxMonthlyRevenue = document.getElementById('monthlyRevenueChart').getContext('2d');
            var monthlyRevenueChart = new Chart(ctxMonthlyRevenue, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Receita Mensal',
                        data: [1000, 1200, 1100, 1300, 1400, 1500, 1600, 1700, 1800, 1900, 2000, 2100],
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            var ctxRevenueLoss = document.getElementById('revenueLossChart').getContext('2d');
            var revenueLossChart = new Chart(ctxRevenueLoss, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Perda de Receita Mensal',
                        data: [50, 60, 55, 65, 70, 75, 80, 85, 90, 95, 100, 105],
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 2,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            var ctxTranslationsAverage = document.getElementById('translationsAverageChart').getContext('2d');
            var translationsAverageChart = new Chart(ctxTranslationsAverage, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Média de Traduções por Mês',
                        data: [25, 30, 28, 35, 40, 45, 50, 55, 60, 65, 70, 75],
                        backgroundColor: 'rgba(255, 206, 86, 0.2)',
                        borderColor: 'rgba(255, 206, 86, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
    <script src="script\altTemaImg.js"></script>
</body>
</html>
