<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de alunos</title>
    <link rel="stylesheet" href="../assets/css/root.css">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/pages/cadastroEscola.css">
</head>
<body>
    <!---------------- TELA ---------------->
    <section class="pageCadastro">

    <!---------------- LADO ESQUERDO ---------------->
        <div class="esquerda">
            <h2>Criar conta</h2>

            <!-- Formulário de cadastro -->
            <form class="form" action="">

                <!-- conjunto de título com 5 inputs -->
                <div class="inputs">
                    <h3>Informações</h3>

                    <?php $inputId = "nomeEscola"; $inputLabel = "Nome da escola"; $inputTipo = "text"; $inputPlaceholder = "Digite o nome da sua escola..."; $inputClass = "input-cadastro"; ?>
                    <?php include '../includes/input.inc.php'; ?>

                    <?php $inputId = "nomeGestor"; $inputLabel = "Nome do gestor"; $inputTipo = "text"; $inputPlaceholder = "Digite o seui nome..."; $inputClass = "input-cadastro"; ?>
                    <?php include '../includes/input.inc.php'; ?>

                    <?php $inputId = "email"; $inputLabel = "Email"; $inputTipo = "email"; $inputPlaceholder = "Digite o seu email..."; $inputClass = "input-cadastro"; ?>
                    <?php include '../includes/input.inc.php'; ?>

                    <?php $inputId = "senha"; $inputLabel = "Senha"; $inputTipo = "password"; $inputPlaceholder = "Digite a sua senha..."; $inputClass = "input-cadastro"; ?>
                    <?php include '../includes/input.inc.php'; ?>

                    <?php $inputId = "cnpj"; $inputLabel = "CNPJ"; $inputTipo = "text"; $inputPlaceholder = "Digite o cnpj da sua escola..."; $inputClass = "input-cadastro"; ?>
                    <?php include '../includes/input.inc.php'; ?>
                </div>

                <!-- Idiomas possíveis para escolha -->
                <div class="idiomas">
                    <label class="idioma-item">
                        <input type="checkbox" name="idiomas[]" value="1"> <!-- id do inglês no banco -->
                        <img src="../assets/img/images/bandeiraEUA.svg" alt="Inglês">
                    </label>

                    <label class="idioma-item">
                        <input type="checkbox" name="idiomas[]" value="2"> <!-- id do espanhol no banco -->
                        <img src="../assets/img/images/bandeiraEspanha.svg" alt="Espanhol">
                    </label>

                    <label class="idioma-item">
                        <input type="checkbox" name="idiomas[]" value="2"> <!-- id do francês no banco -->
                        <img src="../assets/img/images/bandeiraFrança.svg" alt="Francês">
                    </label>

                    <label class="idioma-item">
                        <input type="checkbox" name="idiomas[]" value="2"> <!-- id do alemão no banco -->
                        <img src="../assets/img/images/bandeiraAlemanha.svg" alt="Alemão">
                    </label>

                    <label class="idioma-item">
                        <input type="checkbox" name="idiomas[]" value="2"> <!-- id do japones no banco -->
                        <img src="../assets/img/images/bandeiraJapão.svg" alt="Japones">
                    </label>

                    <label class="idioma-item">
                        <input type="checkbox" name="idiomas[]" value="2"> <!-- id do árabe no banco -->
                        <img src="../assets/img/images/bandeiraArábia.svg" alt="Árabe">
                    </label>
                </div>

                <!-- Botão de criar conta -->
                <?php $btnLabel = "Criar conta"; $btnClass = "btn-laranja btn-tamanho"; ?>
                    <?php include '../includes/btn.inc.php'; ?>
            </form>
            <!-- Fim do formulário de cadastro -->

        </div>
        <!---------------- FIM DO LADO ESQUERDO ---------------->
        
        <!---------------- LADO DIREITO ---------------->
        <div class="direita">
            <h2>Lado direito</h2>
        </div>
        <!---------------- FIM DO LADO DIREITO ---------------->
    </section>
    <!---------------- FIM DA TELA ---------------->
</body>
</html>