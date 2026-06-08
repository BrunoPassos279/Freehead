<?php
//---------------- Incluindo autenticação ----------------//
// Esse arquivo inicia a sessão e permite validar o login
require_once __DIR__ . '/../includes/auth.inc.php';

//---------------- Incluindo repository de pagamentos ----------------//
// Esse arquivo registra pagamentos no banco real
require_once __DIR__ . '/../repositories/PagamentoRepository.php';

//---------------- Validando sessão ----------------//
// Se o usuário não estiver logado, volta para a tela de login
validarSessao();

//---------------- Validando método ----------------//
// Esse arquivo só deve receber dados via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/financeiro.php');
    exit;
}

//---------------- ID da escola logada ----------------//
// Usado para garantir que o pagamento pertence à escola atual
$idEscolaLogada = getEscolaLogadaId();

//---------------- Pegando dados do formulário ----------------//
$idAluno        = $_POST['id_aluno'] ?? null;
$mesReferencia  = $_POST['mes_referencia'] ?? null;
$valor          = $_POST['valor'] ?? null;
$formaPagamento = $_POST['forma_pagamento'] ?? null;
$tipoPagamento  = $_POST['tipo_pagamento'] ?? 'mensalidade';
$observacao     = trim($_POST['observacao'] ?? '');
$origem         = $_POST['origem'] ?? 'financeiro.php';

//---------------- Normalizando valor informado ----------------//
// Aceita tanto 350.00 quanto 350,00
if ($valor !== null && $valor !== '') {
    $valor = str_replace(',', '.', $valor);
}

//---------------- Validando campos obrigatórios ----------------//
// Valor não é obrigatório, pois pode usar o valor da matrícula
if (!$idAluno || !$mesReferencia || !$formaPagamento) {
    header('Location: ../pages/financeiro.php?erro=campos_obrigatorios');
    exit;
}

//---------------- Registrando pagamento no banco ----------------//
$resultado = registrarPagamento(
    $idEscolaLogada,
    $idAluno,
    $mesReferencia,
    $valor,
    $formaPagamento,
    $observacao,
    $tipoPagamento
);

//---------------- Tratando erro ao registrar ----------------//
if (!$resultado['sucesso']) {
    $erro = $resultado['erro'] ?? 'erro_registrar_pagamento';

    if ($origem === 'pageAluno.php') {
        header('Location: ../pages/pageAluno.php?id_aluno=' . $idAluno . '&erro=' . $erro);
        exit;
    }

    header('Location: ../pages/financeiro.php?erro=' . $erro);
    exit;
}

//---------------- Redirecionando após salvar ----------------//
if ($origem === 'pageAluno.php') {
    header('Location: ../pages/pageAluno.php?id_aluno=' . $idAluno . '&sucesso=pagamento_registrado');
    exit;
}

header('Location: ../pages/financeiro.php?sucesso=pagamento_registrado');
exit;