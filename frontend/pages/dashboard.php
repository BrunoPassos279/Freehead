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

    <div class="pagina">

        <?php require_once '../includes/sidebar.inc.php'; ?>

        <main class="conteudo">
            <h1>Seja bem vindo </h1><?php ?>

            <!-- Imagem e card financeiro -->
            <div class="topLine">

                <img class="imagemDash" src="" alt="">
                <div class="cardFinanceiro">
                    <div class="text">
                        <p class="acessar">Acessar...</p>
                        <p class="fin">Financeiro</p>
                        <img src="Dinhero" alt="">
                    </div>
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
                            <p class="total">Total de alunos</p>
                            <p class="cont">000</p>
                        </div>
                        <div class="buttonsContent">
                            <a href="alunos.php" class="stat-link"><img src="../assets/img/icons/infoStudent.svg" alt="">Lista de alunos...</a>
                            <a href="alunos.php?acao=adicionar" class="stat-link"><img src="../assets/img/icons/addStudent.svg" alt="">Adiciona aluno...</a>
                        </div>
                    </div>
                    <!-- Ícone do lado dos textos no card -->
                    <img class="imageContent" src="../assets/img/icons/bigImageAluno.svg" alt="">
                </div>

                  <!-- Card turmas -->
                  <div class="cardDash">
                    <div class="content">
                        <div class="textContent">
                            <p class="total">Total de turmas</p>
                            <p class="cont">000</p>
                        </div>
                        <div class="buttonsContent">
                            <a href="turmas.php" class="stat-link"><img src="../assets/img/icons/infoClass.svg" alt="">Lista de turmas...</a>
                            <a href="turmas.php?acao=adicionar" class="stat-link"><img src="../assets/img/icons/addClass.svg" alt="">Adicionar turma...</a>
                        </div>
                    </div>
                    <!-- Ícone do lado dos textos no card -->
                    <img class="imageContent" src="../assets/img/icons//bigImageClass.svg" alt="">
                </div>


                  <!-- Card professores -->
                  <div class="cardDash">
                    <div class="content">
                        <div class="textContent">
                            <p class="total">Total de professores</p>
                            <p class="cont">000</p>
                        </div>
                        <div class="buttonsContent">
                            <a href="professores.php" class="stat-link"><img src="../assets/img/icons/infoTeach.svg" alt="">Lista de professores...</a>
                            <a href="professores.php.php?acao=adicionar" class="stat-link"><img src="../assets/img/icons/addTeach.svg" alt="">Adicionar professor...</a>
                        </div>
                    </div>
                    <!-- Ícone do lado dos textos no card -->
                    <img class="imageContent" src="../assets/img/icons/bigImageTeach.svg" alt="">
                </div>

            </div>

            <div class="barraIdiomas">
                <div class="idioma">
                    <img src="" alt="">
                    <span class="contIdioma">0</span>
                    <span class="nomeIdioma">Inglês</span>
                </div>
                <div class="idioma">
                    <img src="" alt="">
                    <span class="contIdioma">0</span>
                    <span class="nomeIdioma">Espanhol</span>
                </div>
                <div class="idioma">
                    <img src="" alt="">
                    <span class="contIdioma">0</span>
                    <span class="nomeIdioma">Francês</span>
                </div>
                <div class="idioma">
                    <img src="" alt="">
                    <span class="contIdioma">0</span>
                    <span class="nomeIdioma">Alemão</span>
                </div>
                <div class="idioma">
                    <img src="" alt="">
                    <span class="contIdioma">0</span>
                    <span class="nomeIdioma">Japonês</span>
                </div>
                <div class="idioma">
                    <img src="" alt="">
                    <span class="contIdioma">0</span>
                    <span class="nomeIdioma">Árabe</span>
                </div>
                <div class="idioma maisIdiomas">
                    <span>•••</span>
                </div>
            </div>
        </main> 
    </div>

    <script src="../assets/js/sidebar.js"></script>
</body>
</html>