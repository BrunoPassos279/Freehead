<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de login</title>
    <link rel="stylesheet" href="../assets/css/root.css">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/pages/index.css">
</head>
<body>

    <!---------------- TELA ---------------->
    <section class="pagelog">

        <!---------------- LADO ESQUERDO ---------------->
        <div class="ladoEsquerdo">
            <div class="text">
                <h1>Gerenciar uma escola nunca foi tão fácil!</h1>
                <p>Deixe que a Freehead te ajude</p>
            </div>

            <div class="images">
                <img class="mulherFeiz" src="../assets/img/images/mulherFeliz.svg" alt="Imagem de uma mulher pulando de alegria">
                <img id="logo" src="../assets/img/logos/Freehead Logo W&O.svg" alt="Logo da Freehead Laranja e branca">
            </div>
        </div>
        <!---------------- FIM DO LADO ESQUERDO ---------------->

        <!---------------- LADO DIREITO ---------------->
        <div class="ladoDireito">

            <form class="form" action="">
                <h2>Acessar escola</h2>

                <div class="inputs">
                    <!-- Input email -->
                    <?php $inputId = "email"; $inputLabel = "Email"; $inputTipo = "email"; $inputPlaceholder = "Digite o seu email..."; ?>
                    <?php include '../includes/input.inc.php'; ?>

                    <!-- Input senha -->
                    <?php $inputId = "senha"; $inputLabel = "Senha"; $inputTipo = "password"; $inputPlaceholder = "Digite a sua senha..."; ?>
                    <?php include '../includes/input.inc.php'; ?> 
                </div>

                <div class="botoes">
                    <!-- Botões de login e criar contas -->
                    <?php $btnLabel = "Entrar"; $btnClass = "btn-vermelho btn-tamanho"; ?>
                    <?php include '../includes/btn.inc.php'; ?>

                    <?php $btnLabel = "Criar conta"; $btnClass = "btn-branco btn-tamanho"; ?>
                    <?php include '../includes/btn.inc.php'; ?> 
                </div>
            </form>
        </div>
        <!---------------- FIM DO LADO DIREITO ---------------->
    </section>
    <!---------------- FIM DA TELA ---------------->
</body>
</html>