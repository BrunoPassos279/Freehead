<?php
//---------------- Retorno em JSON ----------------//
header('Content-Type: application/json; charset=utf-8');

//---------------- Incluindo autenticação ----------------//
require_once __DIR__ . '/../includes/auth.inc.php';

//---------------- Incluindo repository de alunos ----------------//
require_once __DIR__ . '/../repositories/AlunoRepository.php';

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

//---------------- Lendo dados enviados pelo JavaScript ----------------//
$entrada = json_decode(file_get_contents('php://input'), true);

//---------------- Validando dados ----------------//
if (empty($entrada['id_aluno']) || empty($entrada['id_turma'])) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Aluno ou turma não informado.'
    ]);
    exit;
}

//---------------- ID da escola logada ----------------//
$idEscolaLogada = getEscolaLogadaId();

//---------------- Transferindo aluno ----------------//
$resultado = transferirAlunoTurma(
    $idEscolaLogada,
    $entrada['id_aluno'],
    $entrada['id_turma']
);

//---------------- Retornando resultado ----------------//
echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
exit;