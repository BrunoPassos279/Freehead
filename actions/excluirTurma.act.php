<?php
//---------------- Retorno em JSON ----------------//
header('Content-Type: application/json; charset=utf-8');

//---------------- Incluindo autenticação ----------------//
require_once __DIR__ . '/../includes/auth.inc.php';

//---------------- Incluindo repository de turmas ----------------//
require_once __DIR__ . '/../repositories/TurmaRepository.php';

//---------------- Validando sessão ----------------//
validarSessao();

//---------------- Validando método ----------------//
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Método inválido.'
    ]);
    exit;
}

//---------------- Lendo dados enviados pelo JS ----------------//
$entrada = json_decode(file_get_contents('php://input'), true);

//---------------- Validando ID ----------------//
if (!$entrada || empty($entrada['id_turma'])) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'ID da turma não informado.'
    ]);
    exit;
}

//---------------- ID da escola logada ----------------//
$idEscolaLogada = getEscolaLogadaId();

try {
    //---------------- Excluindo turma logicamente ----------------//
    excluirTurma($idEscolaLogada, $entrada['id_turma']);

    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Turma excluída com sucesso.'
    ], JSON_UNESCAPED_UNICODE);
    exit;

} catch (Exception $erro) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao excluir turma: ' . $erro->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    exit;
}