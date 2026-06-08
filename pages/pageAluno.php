<?php
//---------------- Incluindo autenticação ----------------//
// Esse arquivo inicia a sessão e permite validar o login
require_once __DIR__ . '/../includes/auth.inc.php';

//---------------- Incluindo repository de alunos ----------------//
// Esse arquivo busca alunos e pagamentos no banco real
require_once __DIR__ . '/../repositories/AlunoRepository.php';

//---------------- Validando sessão ----------------//
// Se o usuário não estiver logado, volta para a tela de login
validarSessao();

//---------------- ID da escola logada ----------------//
// Usado para garantir que o aluno pertence à escola atual
$idEscolaLogada = getEscolaLogadaId();

//---------------- Pegando ID do aluno pela URL ----------------//
// Exemplo: pageAluno.php?id_aluno=1
$idAluno = $_GET['id_aluno'] ?? null;

//---------------- Validando se veio ID do aluno ----------------//
if (!$idAluno) {
    header('Location: alunos.php?erro=aluno_nao_encontrado');
    exit;
}

//---------------- Buscando aluno no banco ----------------//
// Carrega dados do aluno, matrícula ativa, turma, idioma, nível e professor
$alunoEncontrado = buscarAlunoDetalhadoPorId($idEscolaLogada, $idAluno);

//---------------- Caso aluno não seja encontrado ----------------//
if (!$alunoEncontrado) {
    header('Location: alunos.php?erro=aluno_nao_encontrado');
    exit;
}

//---------------- Buscando pagamentos do aluno ----------------//
// Carrega todos os pagamentos vinculados às matrículas do aluno
$pagamentosDoAluno = buscarPagamentosDoAluno($idEscolaLogada, $idAluno);

//---------------- Calculando idade do aluno ----------------//
$idadeAluno = 'Idade não informada';

if (!empty($alunoEncontrado['nascimento'])) {
    $dataNascimento = new DateTime($alunoEncontrado['nascimento']);
    $dataAtual      = new DateTime();
    $idadeAluno     = $dataNascimento->diff($dataAtual)->y . ' anos';
}

//---------------- Valores padrão caso não exista matrícula ativa ----------------//
$nomeIdioma    = $alunoEncontrado['nome_idioma'] ?? 'Sem idioma';
$nomeNivel     = $alunoEncontrado['nome_nivel'] ?? 'Sem nível';
$nomeTurma     = $alunoEncontrado['nome_turma'] ?? 'Sem turma';
$nomeProfessor = $alunoEncontrado['nome_professor'] ?? 'Sem professor';

//---------------- Formatando valor em real ----------------//
function formatarValor($valor) {
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

//---------------- Formatando data ----------------//
function formatarData($data) {
    if (empty($data)) {
        return '-';
    }

    return date('d/m/Y', strtotime($data));
}

//---------------- Formatando descrição do pagamento ----------------//
function formatarDescricaoPagamento($tipoPagamento, $mesReferencia) {
    $partes = explode('-', $mesReferencia ?? '');

    $ano = $partes[0] ?? '';
    $mes = $partes[1] ?? '';

    $meses = [
        '01' => 'janeiro',
        '02' => 'fevereiro',
        '03' => 'março',
        '04' => 'abril',
        '05' => 'maio',
        '06' => 'junho',
        '07' => 'julho',
        '08' => 'agosto',
        '09' => 'setembro',
        '10' => 'outubro',
        '11' => 'novembro',
        '12' => 'dezembro'
    ];

    $nomeMes = $meses[$mes] ?? $mes;
    $referencia = trim($nomeMes . ' ' . $ano);

    return match ($tipoPagamento) {
        'mensalidade' => 'Mensalidade ' . $referencia,
        'material'    => 'Material didático ' . $referencia,
        'matricula'   => 'Matrícula ' . $referencia,
        'aula_extra'  => 'Aula extra ' . $referencia,
        'outro'       => 'Outro pagamento ' . $referencia,
        default       => 'Pagamento ' . $referencia
    };
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de aluno</title>

    <link rel="stylesheet" href="../assets/css/root.css">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/modal.css">
    <link rel="stylesheet" href="../assets/css/pages/pageAluno.css">
</head>
<body>

    <!---------------- TELA ---------------->
    <div class="pagina">
        <?php require_once __DIR__ . '/../includes/sidebar.inc.php'; ?>

        <main>
            <div class="infoAluno">
                <img class="fotoAluno" src="../assets/img/images/perfilImagem2.svg" alt="Foto de perfil do aluno">

                <div class="dados">
                    <div class="cabecalho">
                        <h1><?php echo htmlspecialchars($alunoEncontrado['nome']); ?></h1>
                        <p><?php echo htmlspecialchars($idadeAluno); ?></p>
                    </div>

                    <div class="blocoInfos">
                        <div class="bloco">
                            <p>Idioma</p>
                            <span><?php echo htmlspecialchars($nomeIdioma); ?></span>
                        </div>

                        <div class="bloco">
                            <p>Nível</p>
                            <span><?php echo htmlspecialchars($nomeNivel); ?></span>
                        </div>

                        <div class="bloco">
                            <p>Turma</p>
                            <span><?php echo htmlspecialchars($nomeTurma); ?></span>
                        </div>

                        <div class="bloco">
                            <p>Professor(a)</p>
                            <span><?php echo htmlspecialchars($nomeProfessor); ?></span>
                        </div>              
                        <div class="bloco">
                        <p>Matrícula</p>
                        <span>
                            <?php 
                                echo htmlspecialchars(
                                    !empty($alunoEncontrado['status_aluno']) 
                                        ? ucfirst($alunoEncontrado['status_aluno']) 
                                        : 'Sem matrícula ativa'
                                ); 
                            ?>
                        </span>
                    </div>

                    <div class="bloco">
                        <p>Mensalidade</p>
                        <span>
                            <?php 
                                echo htmlspecialchars(
                                    !empty($alunoEncontrado['valor_mensalidade']) 
                                        ? formatarValor($alunoEncontrado['valor_mensalidade']) 
                                        : '-'
                                ); 
                            ?>
                        </span>
                    </div> 
                    </div>

                    <hr>
                </div>           
            </div>

            <div class="pgto">
                <?php $btnLabel = "+ Novo pagamento"; ?>
                <?php $btnClass = "btn-laranja btn-tamanho btn-novo-pagamento"; ?>
                <?php include __DIR__ . '/../includes/btn.inc.php'; ?>

                <table class="tabela-customizada">
                    <thead>
                        <tr class="linha-aluno">
                            <th>Pagamento</th>
                            <th>Valor</th>
                            <th>Forma de pagamento</th>
                            <th>Data</th>
                            <th>Observação</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($pagamentosDoAluno)): ?>

                            <?php foreach ($pagamentosDoAluno as $pagamento): ?>
                                <tr class="linha-aluno">
                                    <td>
                                        <?php 
                                            echo htmlspecialchars(formatarDescricaoPagamento(
                                                $pagamento['tipo_pagamento'] ?? 'mensalidade',
                                                $pagamento['mes_referencia'] ?? null
                                            )); 
                                        ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars(formatarValor($pagamento['valor'] ?? 0)); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($pagamento['forma_pagamento'] ?? '-'); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars(formatarData($pagamento['data_pagamento'] ?? null)); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($pagamento['observacao'] ?? '-'); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="5">Nenhum pagamento encontrado para este aluno.</td>
                            </tr>

                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>     
    </div>

    <?php
    //---------------- Dados do aluno para o modal de pagamento ----------------//
    // Como estamos na página do aluno, o modal não precisa mostrar busca de aluno
    $idAlunoPagamentoFixo = $alunoEncontrado['id_aluno'];
    $nomeAlunoPagamentoFixo = $alunoEncontrado['nome'];
    ?>

    <!---------------- Modal de pagamento ---------------->
    <?php include __DIR__ . '/../includes/modalPagamento.inc.php'; ?>

    <!---------------- Scripts ---------------->
    <script src="../assets/js/modalPagamento.js?v=6"></script>
    <script src="../assets/js/sidebar.js"></script>

</body>
</html>