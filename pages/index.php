<?php
//---------------- Incluindo autenticação ----------------//
// Se o usuário já estiver logado, ele será enviado direto para o dashboard
require_once '../includes/auth.inc.php';

redirecionarSeLogado();

//---------------- Mensagem de erro do login ----------------//
// Define uma mensagem amigável de acordo com o erro recebido pela URL
$mensagemErro = '';

if (isset($_GET['erro'])) {
    if ($_GET['erro'] === 'campos_vazios') {
        $mensagemErro = 'Preencha o e-mail e a senha.';
    }

    if ($_GET['erro'] === 'credenciais_invalidas') {
        $mensagemErro = 'E-mail ou senha inválidos.';
    }

    if ($_GET['erro'] === 'login_obrigatorio') {
        $mensagemErro = 'Faça login para acessar o sistema.';
    }

    if ($_GET['erro'] === 'json_nao_encontrado') {
        $mensagemErro = 'Banco temporário não encontrado.';
    }

    if ($_GET['erro'] === 'json_invalido') {
        $mensagemErro = 'Banco temporário inválido.';
    }
}
?>

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
                <img id="logo" src="../assets/img/logos/logoBrancaLaranja.svg" alt="Logo da Freehead branca e laranja">
            </div>
        </div>
        <!---------------- FIM DO LADO ESQUERDO ---------------->

        <!---------------- LADO DIREITO ---------------->
        <div class="ladoDireito">

            <form class="form" action="../actions/login.act.php" method="POST">
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
                    <?php $btnLabel = "Entrar"; $btnClass = "btn-laranja btn-tamanho"; ?>
                    <?php include '../includes/btn.inc.php'; ?>

                    <?php $btnLabel = "Criar conta"; $btnClass = "btn-branco btn-tamanho"; $btnLink = "../pages/cadastroEscola.php"; ?>
                    <?php include '../includes/btn.inc.php'; ?>
                </div>
                <?php if (!empty($mensagemErro)): ?>
                    <p class="mensagem-erro">
                <?php echo htmlspecialchars($mensagemErro); ?>
                    </p>
                <?php endif; ?>
            </form>
        </div>
        <!---------------- FIM DO LADO DIREITO ---------------->
    </section>
    <!---------------- FIM DA TELA ---------------->
</body>
</html>