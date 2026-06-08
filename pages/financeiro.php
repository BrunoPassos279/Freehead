<?php
//---------------- Incluindo autenticação ----------------//
// Esse arquivo inicia a sessão e permite validar o login
require_once __DIR__ . '/../includes/auth.inc.php';

//---------------- Incluindo repository de pagamentos ----------------//
// Esse arquivo busca dados financeiros no banco real
require_once __DIR__ . '/../repositories/PagamentoRepository.php';

//---------------- Validando sessão ----------------//
// Se o usuário não estiver logado, volta para a tela de login
validarSessao();

//---------------- ID da escola logada ----------------//
// Usado para filtrar os dados financeiros da escola atual
$idEscolaLogada = getEscolaLogadaId();

//---------------- Buscando resumo financeiro ----------------//
// Dados usados nos cards do financeiro
$resumoFinanceiro = buscarResumoFinanceiroPorEscola($idEscolaLogada);

//---------------- Buscando pagamentos realizados ----------------//
// Dados usados na tabela
$pagamentosRealizados = buscarPagamentosRealizadosPorEscola($idEscolaLogada);

//---------------- Buscando alunos disponíveis para pagamento ----------------//
// Dados usados no modal de pagamento
$alunosPagamento = buscarAlunosParaPagamento($idEscolaLogada);

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
    <title>Financeiro - Freehead</title>

    <link rel="stylesheet" href="../assets/css/root.css">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/pages/financeiro.css">
    <link rel="stylesheet" href="../assets/css/modal.css">
</head>
<body>

    <div class="pagina">
        <?php include __DIR__ . '/../includes/sidebar.inc.php'; ?>

        <main class="conteudo">

            <!---------------- Introdução página ---------------->
            <div class="introPage">
                <div class="textIntroPage">
                    <h1>Financeiro</h1>
                    <p>Analise pagamentos e mensalidades Freehead</p>
                </div>
            </div>
            <!---------------- Fim Introdução página ---------------->

            <!---------------- Cards financeiro ---------------->
            <div class="cardsFinanceiro">
                <div class="cardFinanca">
                    <p>RECEITA TOTAL</p>
                    <h2><?php echo formatarValor($resumoFinanceiro['receita_total']); ?></h2>
                    <span>Total já recebido.</span>
                </div>

                <div id="cardAReceber" class="cardFinanca card-financeiro-clicavel" data-tipo="a_receber">
                    <p>A RECEBER</p>
                    <h2><?php echo formatarValor($resumoFinanceiro['a_receber']); ?></h2>
                    <span>Pagamentos pendentes.</span>
                </div>

               <div id="cardAtraso" class="cardFinanca card-financeiro-clicavel" data-tipo="atrasado">
                    <p>ATRASO</p>
                    <h2><?php echo formatarValor($resumoFinanceiro['atrasado']); ?></h2>
                    <span>Pagamentos atrasados!</span>
                </div>
            </div>
            <!---------------- Fim cards financeiro ---------------->

            <!---------------- Tabela de pagamentos ---------------->
            <div class="pgto"> 
                <?php $btnLabel = "+ Novo pagamento"; ?>
                <?php $btnClass = "btn-laranja btn-tamanho btn-novo-pagamento"; ?> 
                <?php include __DIR__ . '/../includes/btn.inc.php'; ?> 

                <table class="tabela-customizada"> 
                    <thead> 
                        <tr class="linha-aluno"> 
                            <th>Aluno</th>
                            <th>Pagamento</th> 
                            <th>Valor</th> 
                            <th>Forma de pagamento</th> 
                            <th>Data</th>
                            <th>Observação</th> 
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($pagamentosRealizados)): ?>

                            <?php foreach ($pagamentosRealizados as $pagamento): ?>
                                <tr 
                                    class="linha-aluno"
                                    onclick="window.location.href='pageAluno.php?id_aluno=<?php echo htmlspecialchars($pagamento['id_aluno']); ?>'"
                                >
                                    <td>
                                        <?php echo htmlspecialchars($pagamento['nome_aluno']); ?>
                                    </td>

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
                                <td colspan="6">Nenhum pagamento realizado encontrado.</td>
                            </tr>

                        <?php endif; ?>
                    </tbody> 
                </table> 
            </div>
            <!---------------- Fim tabela de pagamentos ---------------->

        </main>
    </div>

    <!---------------- Modal de pagamento ---------------->
    <?php include __DIR__ . '/../includes/modalPagamento.inc.php'; ?>

    <div class="modal-overlay" id="modalPendenciasOverlay"></div>

    <div class="modal" id="modalPendenciasFinanceiras">
        <div class="modal-header">
            <h2 class="modal-titulo" id="tituloPendenciasFinanceiras">Pendências</h2>

            <button type="button" class="modal-fechar" id="btnFecharPendenciasFinanceiras">
                ✕
            </button>
        </div>

        <div class="modal-body">
            <div id="listaPendenciasFinanceiras">
                <p>Carregando...</p>
            </div>
        </div>
    </div>

    <!---------------- Scripts ---------------->
    <script src="../assets/js/modalPagamento.js?v=8"></script>
    <script src="../assets/js/pendenciasFinanceiras.js?v=1"></script>
    <script src="../assets/js/sidebar.js"></script>

</body>
</html>