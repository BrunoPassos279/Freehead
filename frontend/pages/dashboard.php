<!-- pages/dashboard.php -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Freehead</title>
    <link rel="stylesheet" href="../assets/css/root.css">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/pages/dashboard.css">
</head>
<body>

    <?php
    require_once '../includes/auth.inc.php';
    require_once '../includes/conexao.inc.php';

    // ============================================
    // TODO: CRUD — buscar total de alunos
    // Tabela: alunos
    // Filtro: id_escola = $_SESSION['id_escola']
    // Resultado esperado: $totalAlunos (int)
    // ============================================
    $totalAlunos = 0;

    // ============================================
    // TODO: CRUD — buscar total de turmas
    // Tabela: turmas
    // Filtro: id_escola = $_SESSION['id_escola']
    // Resultado esperado: $totalTurmas (int)
    // ============================================
    $totalTurmas = 0;

    // ============================================
    // TODO: CRUD — buscar total de professores
    // Tabela: professores JOIN professor_idioma JOIN idiomas_escolas
    // Filtro: id_escola = $_SESSION['id_escola']
    // Resultado esperado: $totalProfessores (int)
    // ============================================
    $totalProfessores = 0;

    // ============================================
    // TODO: CRUD — buscar idiomas da escola com contagem de alunos por idioma
    // Tabelas: idiomas_escolas, idiomas, matriculas, turmas, niveis
    // Filtro: id_escola = $_SESSION['id_escola']
    // Resultado esperado: $idiomas (array com nome, bandeira, contagem)
    // ============================================
    $idiomas = [];
    ?>

    <div class="pagina">

        <?php require_once '../includes/sidebar.inc.php'; ?>

        <main class="conteudo">
            <h1>Seja bem vindo <span class="usuario"><?php echo htmlspecialchars($_SESSION['nome']); ?>!</span></h1>

            <!-- Imagem e card financeiro -->
            <div class="topLine">

                <img class="imagemDash" src="" alt="">
                <div class="cardFinanceiro">
                    <div class="text">
                        <p class="acessar">Acessar...</p>
                        <p class="fin">Financeiro</p>
                    </div>
                    <img class="iconFinanceiro" src="../assets/img/icons/cifrao.svg" alt="Ícone cifrão">
                    <div class="buttonsFin">
                        <?php $btnLabel = "Novo pagamento"; $btnClass = "btn-laranja btn-tamanho"; ?>
                        <?php include '../includes/btn.inc.php'; ?>

                        <?php $btnLabel = "Gerenciamento"; $btnClass = "btn-branco btn-tamanho"; ?>
                        <?php include '../includes/btn.inc.php'; ?>
                    </div>
                </div>
            </div>

            <!-- Cards de alunos, turmas e professores -->
            <div class="twoLine">

                <!-- Card alunos -->
                <div class="cardDash">
                    <div class="content">
                        <div class="textContent">
                            <p class="total">Alunos...</p>
                            <p class="cont"><?php echo $totalAlunos; ?></p>
                        </div>
                        <div class="buttonsContent">
                            <a href="alunos.php" class="stat-link"><img src="../assets/img/icons/infoStudent.svg" alt="">Lista de alunos...</a>
                            <a href="alunos.php?acao=adicionar" class="stat-link"><img src="../assets/img/icons/addStudent.svg" alt="">Adicionar aluno...</a>
                        </div>
                    </div>
                    <!-- Ícone do lado dos textos no card -->
                    <img class="imageContent" src="../assets/img/icons/bigImageAluno.svg" alt="">
                </div>

                <!-- Card turmas -->
                <div class="cardDash">
                    <div class="content">
                        <div class="textContent">
                            <p class="total">Turmas...</p>
                            <p class="cont"><?php echo $totalTurmas; ?></p>
                        </div>
                        <div class="buttonsContent">
                            <a href="turmas.php" class="stat-link"><img src="../assets/img/icons/infoClass.svg" alt="">Lista de turmas...</a>
                            <a href="turmas.php?acao=adicionar" class="stat-link"><img src="../assets/img/icons/addClass.svg" alt="">Adicionar turma...</a>
                        </div>
                    </div>
                    <!-- Ícone do lado dos textos no card -->
                    <img class="imageContent" src="../assets/img/icons/bigImageClass.svg" alt="">
                </div>

                <!-- Card professores -->
                <div class="cardDash">
                    <div class="content">
                        <div class="textContent">
                            <p class="total">Professores...</p>
                            <p class="cont"><?php echo $totalProfessores; ?></p>
                        </div>
                        <div class="buttonsContent">
                            <a href="professores.php" class="stat-link"><img src="../assets/img/icons/infoTeach.svg" alt="">Lista de professores...</a>
                            <a href="professores.php?acao=adicionar" class="stat-link"><img src="../assets/img/icons/addTeach.svg" alt="">Adicionar professor...</a>
                        </div>
                    </div>
                    <!-- Ícone do lado dos textos no card -->
                    <img class="imageContent" src="../assets/img/icons/bigImageTeach.svg" alt="">
                </div>

            </div>

            <!-- Barra de idiomas -->
            <div class="barraIdiomas">
                <?php if (!empty($idiomas)): ?>
                    <?php foreach ($idiomas as $idioma): ?>
                        <div class="idioma">
                            <img src="<?php echo htmlspecialchars($idioma['bandeira']); ?>" alt="<?php echo htmlspecialchars($idioma['nome']); ?>">
                            <span class="contIdioma"><?php echo $idioma['contagem']; ?></span>
                            <span class="nomeIdioma"><?php echo htmlspecialchars($idioma['nome']); ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Fallback enquanto o CRUD não estiver implementado -->
                    <div class="idioma">
                        <img src="" alt=""><span class="contIdioma">0</span>
                        <span class="nomeIdioma">Inglês</span>
                    </div>
                    <div class="idioma">
                        <img src="" alt=""><span class="contIdioma">0</span>
                        <span class="nomeIdioma">Espanhol</span>
                    </div>
                    <div class="idioma">
                        <img src="" alt=""><span class="contIdioma">0</span>
                        <span class="nomeIdioma">Francês</span>
                    </div>
                    <div class="idioma">
                        <img src="" alt=""><span class="contIdioma">0</span>
                        <span class="nomeIdioma">Alemão</span>
                    </div>
                    <div class="idioma">
                        <img src="" alt=""><span class="contIdioma">0</span>
                        <span class="nomeIdioma">Japonês</span>
                    </div>
                    <div class="idioma">
                        <img src="" alt=""><span class="contIdioma">0</span>
                        <span class="nomeIdioma">Árabe</span>
                    </div>
                <?php endif; ?>
                <div class="idioma maisIdiomas">
                    <span>•••</span>
                </div>
            </div>

        </main>
    </div>

    <script src="../assets/js/sidebar.js"></script>
</body>
</html>