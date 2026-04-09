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
                    <h3>Idiomas da sua escola</h3>

                    <div class="checkIdioma">
                        <label class="idioma-item"> <!-- INGLÊS -->
                            <input type="checkbox" name="idiomas[]" value="1"> <!-- id do inglês no banco -->
                            <img src="../assets/img/images/bandeiraEUA.svg" alt="Inglês">
                        </label>

                        <label class="idioma-item"> <!-- ESPANHOL -->
                            <input type="checkbox" name="idiomas[]" value="2"> <!-- id do espanhol no banco -->
                            <img src="../assets/img/images/bandeiraEspanha.svg" alt="Espanhol">
                        </label>

                        <label class="idioma-item"> <!-- FRANCÊS -->
                            <input type="checkbox" name="idiomas[]" value="2"> <!-- id do francês no banco -->
                            <img src="../assets/img/images/bandeiraFrança.svg" alt="Francês">
                        </label>

                        <label class="idioma-item"> <!-- ALEMÃO -->
                            <input type="checkbox" name="idiomas[]" value="2"> <!-- id do alemão no banco -->
                            <img src="../assets/img/images/bandeiraAlemanha.svg" alt="Alemão">
                        </label>

                        <label class="idioma-item"> <!-- JAPONES -->
                            <input type="checkbox" name="idiomas[]" value="2"> <!-- id do japones no banco -->
                            <img src="../assets/img/images/bandeiraJapão.svg" alt="Japones">
                        </label>

                        <label class="idioma-item"> <!-- ÁRABE -->
                            <input type="checkbox" name="idiomas[]" value="2"> <!-- id do árabe no banco -->
                            <img src="../assets/img/images/bandeiraArábia.svg" alt="Árabe">
                        </label>
                    </div>
                </div>

                <!-- Botão de criar conta -->
                <?php $btnLabel = "Criar conta"; $btnClass = "btn-laranja btn-tamanho"; ?>
                <?php include '../includes/btn.inc.php'; ?>
            </form>
            <!-- Fim do formulário de cadastro -->

            <!-- Logo Freehead -->
             <img class="logo" src="../assets/img/logos/logoBrancaLaranja.svg" alt="">
        </div>
        <!---------------- FIM DO LADO ESQUERDO ---------------->
        
        <!---------------- LADO DIREITO ---------------->
        <div class="direita">

            <!-- Textos -->
            <div class="conteudoDireita">
                <article class="boasVindas">
                    <h1>Seja bem-vindo!</h1>
                    <p>Ficaremos felizes em receber a sua escola em nosso sistema!</p>
                </article>

                <!-- Imagem de login -->
                <img id="imagemCadastro" src="../assets/img/images/criarConta.svg" alt="Desenho com pessoas fazendo login">

                <!-- Botão de voltar a tela de login -->
                <?php $btnLabel = "Entrar"; $btnClass = "btn-azul btn-tamanho"; $btnLink = "../pages/index.php";?>
                <?php include '../includes/btn.inc.php'; ?>
            </div>

            <!-- Efeito visual de onda (Utilizei I.A para este efeito) -->
            <div class="ocean">
                <svg class="wave wave-1" id="w1" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"></svg>
                <svg class="wave wave-2" id="w2" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"></svg>
            </div>
            <script>
            /* Gera um path de onda matematicamente perfeito usando seno */
            function gerarOnda(svgId, amplitude, comprimento, alturaBase) {
            const svg = document.getElementById(svgId);

            /* Largura total do SVG (200% da tela = dois ciclos visíveis) */
            const largura = window.innerWidth * 2;
            const altura = 100;

            svg.setAttribute("viewBox", `0 0 ${largura} ${altura}`);

            const pontos = [];
            const passos = 200; /* quantidade de pontos — quanto mais, mais suave */

            for (let i = 0; i <= passos; i++) {
                const x = (i / passos) * largura;
                /* Math.sin gera valores entre -1 e 1, multiplicamos pela amplitude */
                const y = alturaBase + Math.sin((i / passos) * comprimento * Math.PI * 2) * amplitude;
                pontos.push(`${i === 0 ? "M" : "L"}${x},${y}`);
            }

            /* Fecha a forma embaixo para ter preenchimento */
            pontos.push(`L${largura},${altura} L0,${altura} Z`);

            const path = document.createElementNS("http://www.w3.org/2000/svg", "path");
            path.setAttribute("d", pontos.join(" "));
            svg.appendChild(path);
            }

            /* comprimento: quantos ciclos de onda cabem na tela (2 = loop perfeito) */
            gerarOnda("w1", 18, 2, 50); /* amplitude 18px, 2 ciclos, base em y=50 */
            gerarOnda("w2", 12, 2, 40); /* amplitude menor e base diferente para variar */
            </script>   
        </div>
    <!---------------- FIM DO LADO DIREITO ---------------->
    </section>
    <!---------------- FIM DA TELA ---------------->
</body>
</html>