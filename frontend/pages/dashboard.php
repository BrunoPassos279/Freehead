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

    //---------------- Banco temporário JSON ----------------//
    // Esse JSON será usado apenas enquanto o banco real ainda não estiver conectado
    $caminhoJson = __DIR__ . '/dados.json';

    $dados = [];

    //---------------- Lendo dados do JSON ----------------//
    if (file_exists($caminhoJson)) {
        $json = file_get_contents($caminhoJson);
        $dados = json_decode($json, true);
    }

//---------------- Separando tabelas do JSON ----------------//
$escolas     = $dados['escolas'] ?? [];
$alunos      = $dados['alunos'] ?? [];
$professores = $dados['professores'] ?? [];
$turmas      = $dados['turmas'] ?? [];
$matriculas  = $dados['matriculas'] ?? [];

//---------------- Buscando dados da escola atual ----------------//
$escolaAtual = null;

foreach ($escolas as $escola) {
    if ($escola['id_escola'] == $idEscolaLogada) {
        $escolaAtual = $escola;
        break;
    }
}

//---------------- Nome do gestor ----------------//
$nomeGestor = $escolaAtual['gestor'] ?? 'Gestor';

//---------------- Filtrando turmas da escola logada ----------------//
$turmasDaEscola = array_filter($turmas, function ($turma) use ($idEscolaLogada) {
    return $turma['id_escola'] == $idEscolaLogada;
});

//---------------- Pegando os IDs das turmas da escola ----------------//
$idsTurmasDaEscola = array_column($turmasDaEscola, 'id_turma');

//---------------- Filtrando matrículas ativas da escola ----------------//
$matriculasAtivas = array_filter($matriculas, function ($matricula) use ($idsTurmasDaEscola) {
    return in_array($matricula['id_turma'], $idsTurmasDaEscola)
        && $matricula['status_aluno'] === 'ativo';
});

//---------------- Pegando alunos ativos sem repetir ----------------//
$idsAlunosAtivos = array_unique(array_column($matriculasAtivas, 'id_aluno'));

//---------------- Total de alunos ativos ----------------//
$totalAlunosAtivos = count($idsAlunosAtivos);

//---------------- Total de professores da escola ----------------//
// No JSON atual professor ainda não tem status, então conta todos da escola
$totalProfessoresAtivos = count(array_filter($professores, function ($professor) use ($idEscolaLogada) {
    return $professor['id_escola'] == $idEscolaLogada;
}));

//---------------- Total de turmas ativas ----------------//
$totalTurmasAtivas = count(array_filter($turmas, function ($turma) use ($idEscolaLogada) {
    return $turma['id_escola'] == $idEscolaLogada
        && $turma['status'] === 'ativa';
}));
?>

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

    <!---------------- TELA ---------------->
    <div class="pagina">
        <?php require_once '../includes/sidebar.inc.php'; ?>

        <!---------------- Boas vindas do usuário ---------------->
        <main class="conteudo">
            <div class="boasVindas">
                <!---------------- Cabeçalho do dashboard ---------------->
                <div class="boasVindasTopo">
                    <div>
                        <h1>Olá <?php echo htmlspecialchars($nomeGestor); ?>! 👋</h1>
                        <p>Aqui está o panorama da sua escola hoje.</p>
                    </div>

                    <!---------------- Botão de logout ---------------->
                    <a href="../actions/logout.act.php" class="btnLogout">
                        Sair
                    </a>
                </div>
            </div>

            <!----- Conteúdo do dash ----->
            <div class="content">

                <!----- Lado esquerdo conteúdo (Todos os cards) ----->
                <div class="objetosLinks">

                    <!----- Três cards iniciais ----->
                    <div class="contagem">
                        <a href="../pages/alunos.php" class="cardContagem">
                            <div class="textCard">
                                <p>Alunos ativos</p>
                                <span><?php echo str_pad($totalAlunosAtivos, 3, '0', STR_PAD_LEFT); ?></span>
                            </div>
                            <img id="imgAluno" src="../assets/img/icons/bigImageAluno.svg" alt=" ícone de aluno">
                        </a>

                        <a href="../pages/professores.php" class="cardContagem">
                            <div class="textCard">
                                <p>Professores ativos</p>
                                <span><?php echo str_pad($totalProfessoresAtivos, 3, '0', STR_PAD_LEFT); ?></span>
                            </div>
                            <img src="../assets/img/icons/bigImageTeach.svg" alt=" ícone de professor">
                        </a>

                        <a href="../pages/turmas.php" class="cardContagem">
                            <div class="textCard">
                                <p>Turmas ativas</p>
                                <span><?php echo str_pad($totalTurmasAtivas, 3, '0', STR_PAD_LEFT); ?></span>
                            </div>
                            <img src="../assets/img/icons/bigImageClass.svg" alt=" ícone das turmas">
                        </a>
                    </div>
                    <!----- Fim três cards iniciais ----->
                    
                    <!----- Cards acesso rápido ----->
                     <div class="acesso">
                        <h3>Acesso rápído</h3>
                        
                        <!----- 2 Cards Acesso rápido (Parte de cima) ----->
                        <div class="acessoBloco">
                            <a href="../pages/alunos.php" class="cardAcesso">
                                <div class="imgLinkAcesso">
                                    <img src="../assets/img/icons/infoAluno.svg" alt="ícone de aluno">
                                    <img class="setaLink" src="../assets/img/icons/setaLink.svg" alt="Seta diagonal">
                                </div> 
                                <div class="textAcessoCard">
                                    <h2>Alunos</h2>
                                    <p>Matrículas, frequência e desempenho.</p>
                                </div>
                            </a>

                             <a href="../pages/professores.php" class="cardAcesso">
                                <div class="imgLinkAcesso">
                                    <img src="../assets/img/icons/IconProf.svg" alt="Ícone de professor">
                                    <img class="setaLink" src="../assets/img/icons/setaLink.svg" alt="Seta diagonal">
                                </div> 
                                <div class="textAcessoCard">
                                    <h2>Professores</h2>
                                    <p>Em atuação e seus idiomas trabalhados</p>
                                </div>
                            </a>
                        </div>
                        <!----- Fim 2 Cards Acesso rápido (Parte de cima) ----->
                        
                        <!----- 2 Cards Acesso rápido (Parte de baixo) ----->
                        <div class="acessoBloco">
                            <a href="../pages/turmas.php" class="cardAcesso">
                                <div class="imgLinkAcesso">
                                    <img src="../assets/img/icons/IconTurma.svg" alt="ícone das turmas">
                                    <img class="setaLink" src="../assets/img/icons/setaLink.svg" alt="Seta diagonal">
                                </div> 
                                <div class="textAcessoCard">
                                    <h2>Turmas</h2>
                                    <p>Gerenciamento, mudanças e atualização</p>
                                </div>
                            </a>

                             <a href="../pages/financeiro.php" class="cardAcesso">
                                <div class="imgLinkAcesso">
                                    <img src="../assets/img/icons/pagamentoIcon.svg" alt="Ícone de $">
                                    <img class="setaLink" src="../assets/img/icons/setaLink.svg" alt="Seta diagonal">
                                </div> 
                                <div class="textAcessoCard">
                                    <h2>Financeiro</h2>
                                    <p>Registrar novos pagamentos e históricos</p>
                                </div>
                            </a>
                        </div>
                        <!----- Fim 2 Cards Acesso rápido (Parte de baixo) ----->
                    </div>
                    <!----- Fim Cards acesso rápido ----->
                
                <!----- Fim Lado esquerdo conteúdo (Todos os cards) ----->
                </div>

                <!----- Imagem institucional ----->
                <img src="../assets/img/images/marketingFH.svg" alt="Imagem de marketing do Freehead">

            <!----- Fim Conteúdo do dash ----->
            </div>
        </main> 

    <!---------------- FIM DA TELA ---------------->
    </div>  

    <!---------------- Efeito do sidebar sobre a página que está aberta ---------------->
    <script src="../assets/js/sidebar.js"></script>
</body>
</html>