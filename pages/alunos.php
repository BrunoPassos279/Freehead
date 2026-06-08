<?php
//---------------- Incluindo autenticação ----------------//
// Esse arquivo inicia a sessão e permite validar o login
require_once __DIR__ . '/../includes/auth.inc.php';

//---------------- Incluindo repository de alunos ----------------//
// Esse arquivo busca alunos no banco real
require_once __DIR__ . '/../repositories/AlunoRepository.php';

//---------------- Validando sessão ----------------//
// Se o usuário não estiver logado, volta para a tela de login
validarSessao();

//---------------- ID da escola logada ----------------//
// Usado para filtrar alunos da escola atual
$idEscolaLogada = getEscolaLogadaId();

//---------------- Buscando alunos no banco ----------------//
// Retorna apenas alunos ativos da escola logada
$alunos = buscarAlunosPorEscola($idEscolaLogada);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alunos - Freehead</title>

    <link rel="stylesheet" href="../assets/css/root.css">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/pages/aluno.css">
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
                    <h1>Alunos</h1>
                    <p><?php echo count($alunos); ?> alunos matriculados</p>
                </div>

                <?php $btnLabel = "+ Novo aluno"; ?>
                <?php $btnClass = "btn-laranja btn-tamanho btn-novo-aluno"; ?>
                <?php include __DIR__ . '/../includes/btn.inc.php'; ?>
            </div>
            <!---------------- Fim introdução da página ---------------->

            <!---------------- Tabela de alunos ---------------->
            <table class="tabela-customizada">
                <thead>
                    <tr>
                        <th>Nome do Aluno</th>
                        <th>Turma</th>
                        <th>Nível</th>
                        <th>Mensalidade</th>
                        <th>Matrícula</th>
                        <th>Acessar</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($alunos)): ?>

                        <?php foreach ($alunos as $aluno): ?>

                            <?php
                            //---------------- Dados da turma ----------------//
                            $nomeTurma = !empty($aluno['nome_turma'])
                                ? $aluno['nome_turma']
                                : '—';

                            //---------------- Dados do nível ----------------//
                            $nomeNivel = !empty($aluno['nome_nivel'])
                                ? $aluno['nome_nivel']
                                : '—';

                            //---------------- Status da mensalidade ----------------//
                            $statusMensalidade = '—';

                            if (!empty($aluno['status_pagamento'])) {
                                $statusMensalidade = match ($aluno['status_pagamento']) {
                                    'pago'     => 'Em dia',
                                    'pendente' => 'Atenção',
                                    'atrasado' => 'Atraso',
                                    default    => '—'
                                };
                            }

                            //---------------- Status da matrícula ----------------//
                            $statusMatricula = !empty($aluno['status_aluno'])
                                ? ucfirst($aluno['status_aluno'])
                                : 'Inativo';

                            //---------------- ID da turma ativa ----------------//
                            $idTurmaAtiva = $aluno['id_turma'] ?? '';
                            ?>

                            <!--
                                Todos os dados do aluno ficam nos data-*.
                                O JS lê esses valores e preenche o modal.
                            -->
                            <tr class="linha-aluno"
                                data-id_aluno="<?php echo htmlspecialchars($aluno['id_aluno'] ?? ''); ?>"
                                data-nome="<?php echo htmlspecialchars($aluno['nome'] ?? ''); ?>"
                                data-nascimento="<?php echo htmlspecialchars($aluno['nascimento'] ?? ''); ?>"
                                data-data_cadastro="<?php echo htmlspecialchars($aluno['data_cadastro'] ?? ''); ?>"
                                data-endereco="<?php echo htmlspecialchars($aluno['endereco'] ?? ''); ?>"
                                data-pai="<?php echo htmlspecialchars($aluno['pai'] ?? ''); ?>"
                                data-mae="<?php echo htmlspecialchars($aluno['mae'] ?? ''); ?>"
                                data-telefone_aluno="<?php echo htmlspecialchars($aluno['telefone_aluno'] ?? ''); ?>"
                                data-telefone_responsavel="<?php echo htmlspecialchars($aluno['telefone_responsavel'] ?? ''); ?>"
                                data-email="<?php echo htmlspecialchars($aluno['email'] ?? ''); ?>"
                                data-id_turma="<?php echo htmlspecialchars($idTurmaAtiva); ?>">

                                <td><?php echo htmlspecialchars($aluno['nome'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($nomeTurma); ?></td>
                                <td><?php echo htmlspecialchars($nomeNivel); ?></td>
                                <td><?php echo htmlspecialchars($statusMensalidade); ?></td>
                                <td><?php echo htmlspecialchars($statusMatricula); ?></td>
                                <td>
                                    <a 
                                        href="pageAluno.php?id_aluno=<?php echo htmlspecialchars($aluno['id_aluno']); ?>" 
                                        class="btn-acessar-aluno"
                                        onclick="event.stopPropagation();"
                                    >
                                        Acessar
                                    </a>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="6">Nenhum aluno encontrado.</td>
                        </tr>

                    <?php endif; ?>
                </tbody>
            </table>
            <!---------------- Fim tabela de alunos ---------------->

        </main>
    </div>

    <!---------------- Modal de aluno ---------------->
    <?php include __DIR__ . '/../includes/modalAluno.inc.php'; ?>

    <!---------------- Scripts ---------------->
    <script src="../assets/js/modalAluno.js"></script>
    <script src="../assets/js/sidebar.js"></script>

</body>
</html>