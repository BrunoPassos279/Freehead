<?php
//---------------- Retorno em JSON ----------------//
header('Content-Type: application/json; charset=utf-8');

//---------------- Incluindo autenticação ----------------//
require_once __DIR__ . '/../includes/auth.inc.php';

//---------------- Incluindo repository de turmas ----------------//
require_once __DIR__ . '/../repositories/TurmaRepository.php';

//---------------- Validando sessão ----------------//
validarSessao();

//---------------- ID da escola logada ----------------//
$idEscolaLogada = getEscolaLogadaId();

try {
    //---------------- Buscando turmas no banco ----------------//
    $turmas = buscarTurmasPorEscola($idEscolaLogada);

    //---------------- Retornando somente turmas ativas ----------------//
    $turmasAtivas = array_filter($turmas, function ($turma) {
        return $turma['status'] === 'ativa';
    });

    echo json_encode([
        'sucesso' => true,
        'turmas' => array_values($turmasAtivas)
    ], JSON_UNESCAPED_UNICODE);
    exit;

} catch (Exception $erro) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao listar turmas: ' . $erro->getMessage()
    ]);
    exit;
}