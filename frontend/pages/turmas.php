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

    $json  = file_get_contents('dados.json');
    $dados = json_decode($json, true);

    $turmas             = array_filter($dados['turmas'], fn($t) => $t['id_escola'] == $idEscolaLogada);
    $professores        = $dados['professores'];
    $idiomas            = $dados['idiomas'];
    $niveis             = $dados['niveis'];
    $professor_idiomas  = $dados['professor_idioma'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Turmas - Freehead</title>
    <link rel="stylesheet" href="../assets/css/root.css">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/pages/turmas.css">
    <link rel="stylesheet" href="../assets/css/modal.css">
</head>
<body>
<div class="pagina">
    <?php include '../includes/sidebar.inc.php'; ?>
    <main class="conteudo">

        <div class="introPage">
            <div class="textIntroPage">
                <h1>Turmas</h1>
                <p><?php echo count($turmas); ?> turmas ativas</p>
            </div>
            <?php $btnLabel = "+ Nova turma"; $btnClass = "btn-laranja btn-tamanho btn-nova-turma"; ?>
            <?php include '../includes/btn.inc.php'; ?>
        </div>

        <div class="cardsTurmas">
            <?php foreach ($turmas as $turma): ?>
            <?php
            $nomeIdioma = '';
            foreach ($idiomas as $i)    { if ($i['id_idioma']   === $turma['id_idioma'])   { $nomeIdioma = $i['nome'];        break; } }
            $nomeProf   = '';
            foreach ($professores as $p){ if ($p['id_professor'] === $turma['id_professor']){ $nomeProf   = $p['nome'];        break; } }
            $nomeNivel  = '';
            foreach ($niveis as $n)     { if ($n['id_nivel']     === $turma['id_nivel'])    { $nomeNivel  = $n['nome_nivel'];  break; } }
            $dataInicio = date('d/m/Y', strtotime($turma['data_inicio']));
            ?>

            <!--
                Todos os campos da turma ficam nos data-*.
                O JS lê esses valores ao clicar e preenche o modal,
                incluindo os selects de nível e professor.
            -->
            <div class="cardTurma"
                 data-id_turma="<?php echo $turma['id_turma']; ?>"
                 data-nome_turma="<?php echo htmlspecialchars($turma['nome_turma']); ?>"
                 data-id_idioma="<?php echo $turma['id_idioma']; ?>"
                 data-id_nivel="<?php echo $turma['id_nivel']; ?>"
                 data-id_professor="<?php echo $turma['id_professor']; ?>"
                 data-dia_semana="<?php echo htmlspecialchars($turma['dia_semana']); ?>"
                 data-hora_inicio="<?php echo $turma['hora_inicio']; ?>"
                 data-hora_fim="<?php echo $turma['hora_fim']; ?>"
                 data-data_inicio="<?php echo $turma['data_inicio']; ?>"
                 data-data_fim="<?php echo $turma['data_fim']; ?>"
                 data-capacidade="<?php echo $turma['capacidade']; ?>"
                 data-status="<?php echo $turma['status']; ?>"
                 data-observacao="<?php echo htmlspecialchars($turma['observacao'] ?? ''); ?>">

                <div class="infoTurma">
                    <h3 class="idiomaTurma"><?php echo $nomeIdioma; ?></h3>
                    <p><?php echo htmlspecialchars($turma['nome_turma']); ?></p>
                </div>
                <div class="linhaInfo">
                    <img src="../assets/img/icons/clockIcon.svg" alt="">
                    <p><?php echo $turma['dia_semana']; ?></p>
                    <p>•</p>
                    <p><?php echo $turma['hora_inicio']; ?> às <?php echo $turma['hora_fim']; ?></p>
                </div>
                <div class="linhaInfo">
                    <img src="../assets/img/icons/localIcon.svg" alt="">
                    <p>Prof: <?php echo htmlspecialchars($nomeProf); ?></p>
                </div>
                <p class="nivel"><?php echo $nomeNivel; ?></p>
                <div class="date">
                    <hr>
                    <h3>Data de início</h3>
                    <p><?php echo $dataInicio; ?></p>
                </div>
            </div>

            <?php endforeach; ?>
        </div>

    </main>
</div>

<?php include '../includes/modalTurma.inc.php'; ?>
<script src="../assets/js/modalTurma.js"></script>
<script src="../assets/js/sidebar.js"></script>
</body>
</html>