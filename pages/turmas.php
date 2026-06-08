<?php
//---------------- Incluindo autenticação ----------------//
// Esse arquivo inicia a sessão e permite validar o login
require_once __DIR__ . '/../includes/auth.inc.php';

//---------------- Incluindo repository de turmas ----------------//
// Esse arquivo busca turmas no banco real
require_once __DIR__ . '/../repositories/TurmaRepository.php';

//---------------- Validando sessão ----------------//
// Se o usuário não estiver logado, volta para a tela de login
validarSessao();

//---------------- ID da escola logada ----------------//
// Usado para filtrar turmas da escola atual
$idEscolaLogada = getEscolaLogadaId();

//---------------- Buscando turmas no banco ----------------//
// Retorna as turmas da escola logada
$turmas = buscarTurmasPorEscola($idEscolaLogada);

//---------------- Buscando dados auxiliares do modal ----------------//
// Essas listas são usadas no modal de turma
$idiomas = buscarIdiomasDaEscola($idEscolaLogada);
$niveis = buscarNiveisDaEscola($idEscolaLogada);
$professores = buscarProfessoresAtivosDaEscola($idEscolaLogada);

//---------------- Contando turmas ativas ----------------//
$totalTurmasAtivas = count(array_filter($turmas, function ($turma) {
    return $turma['status'] === 'ativa';
}));
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

    <!---------------- TELA ---------------->
    <div class="pagina">

        <?php include __DIR__ . '/../includes/sidebar.inc.php'; ?>

        <main class="conteudo">

            <!---------------- Introdução da página ---------------->
            <div class="introPage">
                <div class="textIntroPage">
                    <h1>Turmas</h1>
                    <p><?php echo $totalTurmasAtivas; ?> turmas ativas</p>
                </div>

                <?php $btnLabel = "+ Nova turma"; ?>
                <?php $btnClass = "btn-laranja btn-tamanho btn-nova-turma"; ?>
                <?php include __DIR__ . '/../includes/btn.inc.php'; ?>
            </div>
            <!---------------- Fim introdução da página ---------------->

            <!---------------- Cards das turmas ---------------->
            <div class="cardsTurmas">

                <?php if (!empty($turmas)): ?>

                    <?php foreach ($turmas as $turma): ?>

                        <?php
                        //---------------- Formatando data de início ----------------//
                        $dataInicio = !empty($turma['data_inicio'])
                            ? date('d/m/Y', strtotime($turma['data_inicio']))
                            : '—';

                        //---------------- Formatando horários ----------------//
                        $horaInicio = !empty($turma['hora_inicio'])
                            ? substr($turma['hora_inicio'], 0, 5)
                            : '';

                        $horaFim = !empty($turma['hora_fim'])
                            ? substr($turma['hora_fim'], 0, 5)
                            : '';

                        //---------------- Status visual da turma ----------------//
                        $statusTurma = $turma['status'] ?? 'ativa';

                        $textoStatusTurma = match ($statusTurma) {
                            'ativa'     => 'Ativa',
                            'encerrada' => 'Encerrada',
                            'cancelada' => 'Cancelada',
                            default     => 'Inativa'
                        };

                        $classeStatusTurma = match ($statusTurma) {
                            'ativa'     => 'status-ativa',
                            'encerrada' => 'status-encerrada',
                            'cancelada' => 'status-cancelada',
                            default     => 'status-inativa'
                        };
                        ?>

                        <!--
                            Todos os campos da turma ficam nos data-*.
                            O JS lê esses valores ao clicar e preenche o modal.
                        -->
                        <div class="cardTurma"
                            data-id_turma="<?php echo htmlspecialchars($turma['id_turma']); ?>"
                            data-nome_turma="<?php echo htmlspecialchars($turma['nome_turma'] ?? ''); ?>"
                            data-id_idioma="<?php echo htmlspecialchars($turma['id_idioma']); ?>"
                            data-id_nivel="<?php echo htmlspecialchars($turma['id_nivel']); ?>"
                            data-id_professor="<?php echo htmlspecialchars($turma['id_professor']); ?>"
                            data-dia_semana="<?php echo htmlspecialchars($turma['dia_semana'] ?? ''); ?>"
                            data-hora_inicio="<?php echo htmlspecialchars($horaInicio); ?>"
                            data-hora_fim="<?php echo htmlspecialchars($horaFim); ?>"
                            data-data_inicio="<?php echo htmlspecialchars($turma['data_inicio'] ?? ''); ?>"
                            data-data_fim="<?php echo htmlspecialchars($turma['data_fim'] ?? ''); ?>"
                            data-capacidade="<?php echo htmlspecialchars($turma['capacidade'] ?? ''); ?>"
                            data-status="<?php echo htmlspecialchars($statusTurma); ?>"
                            data-observacao="<?php echo htmlspecialchars($turma['observacao'] ?? ''); ?>">

                            <div class="infoTurma">
                                <div class="topoTurma">
                                    <h3 class="idiomaTurma">
                                        <?php echo htmlspecialchars($turma['nome_idioma'] ?? 'Sem idioma'); ?>
                                    </h3>

                                    <span class="statusTurma <?php echo htmlspecialchars($classeStatusTurma); ?>">
                                        <?php echo htmlspecialchars($textoStatusTurma); ?>
                                    </span>
                                </div>

                                <p>
                                    <?php echo htmlspecialchars($turma['nome_turma'] ?? 'Sem nome'); ?>
                                </p>
                            </div>

                            <div class="linhaInfo">
                                <img src="../assets/img/icons/clockIcon.svg" alt="">

                                <p><?php echo htmlspecialchars($turma['dia_semana'] ?? '—'); ?></p>
                                <p>•</p>
                                <p><?php echo htmlspecialchars($horaInicio); ?> às <?php echo htmlspecialchars($horaFim); ?></p>
                            </div>

                            <div class="linhaInfo">
                                <img src="../assets/img/icons/localIcon.svg" alt="">

                                <p>
                                    Prof: <?php echo htmlspecialchars($turma['nome_professor'] ?? 'Sem professor'); ?>
                                </p>
                            </div>

                            <p class="nivel">
                                <?php echo htmlspecialchars($turma['nome_nivel'] ?? 'Sem nível'); ?>
                            </p>

                            <div class="date">
                                <hr>

                                <h3>Data de início</h3>
                                <p><?php echo htmlspecialchars($dataInicio); ?></p>
                            </div>
                        </div>

                    <?php endforeach; ?>

                <?php else: ?>

                    <p>Nenhuma turma encontrada.</p>

                <?php endif; ?>

            </div>
            <!---------------- Fim cards das turmas ---------------->

        </main>
    </div>

    <!---------------- Modal de detalhes da turma ---------------->
    <?php include __DIR__ . '/../includes/modalDetalheTurma.inc.php'; ?>

    <!---------------- Modal de criar/editar turma ---------------->
    <?php include __DIR__ . '/../includes/modalTurma.inc.php'; ?>

    <!---------------- Scripts ---------------->
    <script src="../assets/js/modalDetalheTurma.js?v=1"></script>
    <script src="../assets/js/modalTurma.js?v=6"></script>
    <script src="../assets/js/sidebar.js"></script>
</body>
</html>