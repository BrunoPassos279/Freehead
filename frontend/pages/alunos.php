<?php
//---------------- Incluindo autenticação ----------------//
// Esse arquivo inicia a sessão e valida se existe uma escola logada
require_once '../includes/auth.inc.php';

//---------------- Validando sessão ----------------//
// Se não existir login ativo, volta para a tela de login
validarSessao();

//---------------- Pegando escola logada ----------------//
// Esse ID vem da sessão criada no login
$idEscolaLogada = getEscolaLogadaId();

//---------------- Lendo banco temporário JSON ----------------//
// Esse JSON será usado apenas enquanto o banco real ainda não estiver conectado
$json = file_get_contents('dados.json');
$dados = json_decode($json, true);

//---------------- Separando dados do JSON ----------------//
$matriculas = $dados['matriculas'];
$pagamentos = $dados['pagamentos'];
$turmas     = $dados['turmas'];
$niveis     = $dados['niveis'];

//---------------- Filtrando alunos da escola logada ----------------//
// Mostra apenas os alunos vinculados à escola salva na sessão
$alunos = array_filter($dados['alunos'], function ($aluno) use ($idEscolaLogada) {
    return $aluno['id_escola'] === $idEscolaLogada;
});
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
<div class="pagina">
    <?php include '../includes/sidebar.inc.php'; ?>
    <main class="conteudo">

        <div class="introPage">
            <div class="textIntroPage">
                <h1>Alunos</h1>
                <p><?php echo count($alunos); ?> alunos matriculados</p>
            </div>
            <?php $btnLabel = "+ Novo aluno"; $btnClass = "btn-laranja btn-tamanho btn-novo-aluno"; ?>
            <?php include '../includes/btn.inc.php'; ?>
        </div>

        <table class="tabela-customizada">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome do Aluno</th>
                    <th>Turma</th>
                    <th>Nível</th>
                    <th>Mensalidade</th>
                    <th>Matrícula</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alunos as $aluno): ?>
                <?php
                // ---- Matrícula ativa ----
                $matriculaAtiva = null;
                foreach ($matriculas as $m) {
                    if ($m['id_aluno'] === $aluno['id_aluno'] && $m['status_aluno'] === 'ativo') {
                        $matriculaAtiva = $m; break;
                    }
                }

                // ---- Turma e nível ----
                $nomeTurma    = '—';
                $nomeNivel    = '—';
                $idTurmaAtiva = '';
                if ($matriculaAtiva) {
                    foreach ($turmas as $t) {
                        if ($t['id_turma'] === $matriculaAtiva['id_turma']) {
                            $nomeTurma    = $t['nome_turma'];
                            $idTurmaAtiva = $t['id_turma'];
                            foreach ($niveis as $n) {
                                if ($n['id_nivel'] === $t['id_nivel']) { $nomeNivel = $n['nome_nivel']; break; }
                            }
                            break;
                        }
                    }
                }

                // ---- Status mensalidade ----
                $statusMensalidade = '—';
                if ($matriculaAtiva) {
                    $ultimoPag = null;
                    foreach ($pagamentos as $p) {
                        if ($p['id_matricula'] === $matriculaAtiva['id_matricula']) {
                            if (!$ultimoPag || $p['data_vencimento'] > $ultimoPag['data_vencimento']) $ultimoPag = $p;
                        }
                    }
                    if ($ultimoPag) {
                        $statusMensalidade = match($ultimoPag['status']) {
                            'pago'     => 'Em dia',
                            'pendente' => 'Atenção',
                            'atrasado' => 'Atraso',
                            default    => '—'
                        };
                    }
                }

                $statusMatricula = $matriculaAtiva ? ucfirst($matriculaAtiva['status_aluno']) : 'Inativo';
                ?>

                <!--
                    Todos os dados do aluno ficam nos data-*.
                    O JS lê esses valores e preenche o modal — sem nenhuma
                    requisição extra ao servidor para buscar os dados.
                -->
                <tr class="linha-aluno"
                    data-id="<?php echo $aluno['id_aluno']; ?>"
                    data-nome="<?php echo htmlspecialchars($aluno['nome']); ?>"
                    data-nascimento="<?php echo $aluno['nascimento']; ?>"
                    data-data_cadastro="<?php echo $aluno['data_cadastro']; ?>"
                    data-endereco="<?php echo htmlspecialchars($aluno['endereco']); ?>"
                    data-pai="<?php echo htmlspecialchars($aluno['pai']); ?>"
                    data-mae="<?php echo htmlspecialchars($aluno['mae']); ?>"
                    data-telefone_aluno="<?php echo $aluno['telefone_aluno']; ?>"
                    data-telefone_responsavel="<?php echo $aluno['telefone_responsavel']; ?>"
                    data-email="<?php echo $aluno['email']; ?>"
                    data-id_turma="<?php echo $idTurmaAtiva; ?>">

                    <td><?php echo $aluno['id_aluno']; ?></td>
                    <td><?php echo htmlspecialchars($aluno['nome']); ?></td>
                    <td><?php echo $nomeTurma; ?></td>
                    <td><?php echo $nomeNivel; ?></td>
                    <td><?php echo $statusMensalidade; ?></td>
                    <td><?php echo $statusMatricula; ?></td>
                </tr>

                <?php endforeach; ?>
            </tbody>
        </table>

    </main>
</div>

<?php include '../includes/modalAluno.inc.php'; ?>
<script src="../assets/js/modalAluno.js"></script>
<script src="../assets/js/sidebar.js"></script>
</body>
</html>