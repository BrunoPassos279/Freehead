<?php
//---------------- Mensagens de erro/sucesso ----------------//
$erro = $_GET['erro'] ?? null;
$sucesso = $_GET['sucesso'] ?? null;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de escola - Freehead</title>

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

            <?php if ($erro): ?>
                <div class="mensagem-erro">
                    <?php
                        echo match ($erro) {
                            'campos_obrigatorios' => 'Preencha todos os campos obrigatórios.',
                            'senhas_diferentes' => 'As senhas não conferem.',
                            'email_existente' => 'Este e-mail já está cadastrado.',
                            'cnpj_existente' => 'Este CNPJ já está cadastrado.',
                            'idioma_obrigatorio' => 'Selecione pelo menos um idioma.',
                            'erro_cadastro' => 'Erro ao cadastrar escola. Tente novamente.',
                            default => 'Erro ao realizar cadastro.'
                        };
                    ?>
                </div>
            <?php endif; ?>

            <?php if ($sucesso): ?>
                <div class="mensagem-sucesso">
                    Escola cadastrada com sucesso! Faça login para continuar.
                </div>
            <?php endif; ?>

            <!---------------- Formulário de cadastro ---------------->
            <form class="form" action="../actions/cadastrarEscola.act.php" method="POST">

                <!---------------- Informações ---------------->
                <div class="inputs">
                    <h3>Informações</h3>

                    <?php 
                        $inputId = "nomeEscola";
                        $inputName = "nome_escola";
                        $inputLabel = "Nome da escola";
                        $inputTipo = "text";
                        $inputPlaceholder = "Digite o nome da sua escola...";
                        $inputClass = "input-cadastro";
                        $inputRequired = true;
                    ?>
                    <?php include '../includes/input.inc.php'; ?>

                    <?php 
                        $inputId = "nomeGestor";
                        $inputName = "nome_gestor";
                        $inputLabel = "Nome do gestor";
                        $inputTipo = "text";
                        $inputPlaceholder = "Digite o seu nome...";
                        $inputClass = "input-cadastro";
                        $inputRequired = true;
                    ?>
                    <?php include '../includes/input.inc.php'; ?>

                    <?php 
                        $inputId = "email";
                        $inputName = "email";
                        $inputLabel = "Email";
                        $inputTipo = "email";
                        $inputPlaceholder = "Digite o seu email...";
                        $inputClass = "input-cadastro";
                        $inputRequired = true;
                    ?>
                    <?php include '../includes/input.inc.php'; ?>

                    <?php 
                        $inputId = "senha";
                        $inputName = "senha";
                        $inputLabel = "Senha";
                        $inputTipo = "password";
                        $inputPlaceholder = "Digite a sua senha...";
                        $inputClass = "input-cadastro";
                        $inputRequired = true;
                    ?>
                    <?php include '../includes/input.inc.php'; ?>


                    <?php 
                        $inputId = "confirmarSenha";
                        $inputLabel = "Confirmar senha";
                        $inputTipo = "password";
                        $inputPlaceholder = "Digite a senha novamente...";
                        $inputClass = "input-cadastro";
                    ?>
                    <?php include '../includes/input.inc.php'; ?>

                    <?php 
                        $inputId = "cnpj";
                        $inputName = "cnpj";
                        $inputLabel = "CNPJ";
                        $inputTipo = "text";
                        $inputPlaceholder = "Digite o CNPJ da sua escola...";
                        $inputClass = "input-cadastro";
                        $inputRequired = true;
                    ?>
                    <?php include '../includes/input.inc.php'; ?>
                </div>

                <!---------------- Idiomas possíveis para escolha ---------------->
                <div class="idiomas">
                    <h3>Idiomas da sua escola</h3>

                    <div class="checkIdioma">
                        <label class="idioma-item">
                            <input type="checkbox" name="idiomas[]" value="1">
                            <img src="../assets/img/images/bandeiraEUA.svg" alt="Inglês">
                        </label>

                        <label class="idioma-item">
                            <input type="checkbox" name="idiomas[]" value="2">
                            <img src="../assets/img/images/bandeiraEspanha.svg" alt="Espanhol">
                        </label>

                        <label class="idioma-item">
                            <input type="checkbox" name="idiomas[]" value="3">
                            <img src="../assets/img/images/bandeiraFranca.svg" alt="Francês">
                        </label>

                        <label class="idioma-item">
                            <input type="checkbox" name="idiomas[]" value="4">
                            <img src="../assets/img/images/bandeiraAlemanha.svg" alt="Alemão">
                        </label>

                        <label class="idioma-item">
                            <input type="checkbox" name="idiomas[]" value="5">
                            <img src="../assets/img/images/bandeiraJapao.svg" alt="Japonês">
                        </label>

                        <label class="idioma-item">
                            <input type="checkbox" name="idiomas[]" value="6">
                            <img src="../assets/img/images/bandeiraArabia.svg" alt="Árabe">
                        </label>
                    </div>
                </div>

                <!---------------- Botão de criar conta ---------------->
                <?php 
                    $btnLabel = "Criar conta"; 
                    $btnClass = "btn-laranja btn-tamanho"; 
                ?>
                <?php include '../includes/btn.inc.php'; ?>

            </form>
            <!---------------- Fim do formulário de cadastro ---------------->

            <!---------------- Logo Freehead ---------------->
            <img class="logo" src="../assets/img/logos/logoBrancaLaranja.svg" alt="">
        </div>
        <!---------------- FIM DO LADO ESQUERDO ---------------->


        <!---------------- LADO DIREITO ---------------->
        <div class="direita">

            <div class="conteudoDireita">
                <article class="boasVindas">
                    <h1>Seja bem-vindo!</h1>
                    <p>Ficaremos felizes em receber a sua escola em nosso sistema!</p>
                </article>

                <img id="imagemCadastro" src="../assets/img/images/criarConta.svg" alt="Desenho com pessoas fazendo login">

                <?php 
                    $btnLabel = "Entrar"; 
                    $btnClass = "btn-azul btn-tamanho"; 
                    $btnLink = "../pages/index.php";
                ?>
                <?php include '../includes/btn.inc.php'; ?>
            </div>

            <!---------------- Efeito visual de onda ---------------->
            <div class="ocean">
                <svg class="wave wave-1" id="w1" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"></svg>
                <svg class="wave wave-2" id="w2" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"></svg>
            </div>

            <script>
                function gerarOnda(svgId, amplitude, comprimento, alturaBase) {
                    const svg = document.getElementById(svgId);

                    const largura = window.innerWidth * 2;
                    const altura = 100;

                    svg.setAttribute("viewBox", `0 0 ${largura} ${altura}`);

                    const pontos = [];
                    const passos = 200;

                    for (let i = 0; i <= passos; i++) {
                        const x = (i / passos) * largura;
                        const y = alturaBase + Math.sin((i / passos) * comprimento * Math.PI * 2) * amplitude;

                        pontos.push(`${i === 0 ? "M" : "L"}${x},${y}`);
                    }

                    pontos.push(`L${largura},${altura} L0,${altura} Z`);

                    const path = document.createElementNS("http://www.w3.org/2000/svg", "path");

                    path.setAttribute("d", pontos.join(" "));
                    svg.appendChild(path);
                }

                gerarOnda("w1", 18, 2, 50);
                gerarOnda("w2", 12, 2, 40);
            </script>
        </div>
        <!---------------- FIM DO LADO DIREITO ---------------->

    </section>
    <!---------------- FIM DA TELA ---------------->
    <script src="../assets/js/cadastroEscola.js?v=1"></script>
</body>
</html>