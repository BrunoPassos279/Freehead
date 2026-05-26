<?php
//---------------- Incluindo autenticação ----------------//
// Esse arquivo inicia a sessão e permite validar o login
require_once '../includes/auth.inc.php';

//---------------- Validando sessão ----------------//
// Se o usuário não estiver logado, volta para a tela de login
validarSessao();

//---------------- ID da escola logada ----------------//
// Usado para filtrar os dados do JSON temporário
$idEscolaLogada = getEscolaLogadaId();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financeiro - Freehead</title>
    <link rel="stylesheet" href="../assets/css/root.css">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/pages/financeiro.css">
</head>
<body>
    <div class="pagina">
        <?php include '../includes/sidebar.inc.php'; ?>

        <main class="conteudo">
             <!---------------- Introdução página ---------------->
            <div class="introPage">
                <div class="textIntroPage">
                    <h1>Financeiro</h1>
                    <p>Analise e mensalidades Freehead</p>
                </div>
            </div>
            <!---------------- Fim Introdução página ---------------->

            <div class="cardsFinanceiro">
                <div class="cardFinanca">
                    <p>RECEITA DO MÊS</p>
                    <h2>R$ 188,00</h2>
                    <span>Incluir novo pagamento</span>
                </div>
                <div id="branco" class="cardFinanca">
                    <p>A RECEBER</p>
                    <h2>R$ 188,00</h2>
                    <span>Ainda não venceram.</span>
                </div>
                <div class="cardFinanca">
                    <p>ATRASO</p>
                    <h2>R$ 188,00</h2>
                    <span>Pagamento atrasado!</span>
                </div>
            </div>
        </main>
    </div>

    <!---------------- Efeito do sidebar sobre a página que está aberta ---------------->
    <script src="../assets/js/sidebar.js"></script>
</body>
</html>
