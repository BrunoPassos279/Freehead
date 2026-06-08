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

//---------------- Pegando dados ----------------//
$idAluno = $entrada['id_aluno'] ?? null;
$idTurma = $entrada['id_turma'] ?? null;
$valorMensalidade = str_replace(',', '.', $entrada['valor_mensalidade'] ?? '');
$diaVencimento = (int) ($entrada['dia_vencimento'] ?? 10);

//---------------- Validando dados ----------------//
if (
    !$entrada ||
    empty($idAluno) ||
    empty($idTurma) ||
    empty($valorMensalidade) ||
    $valorMensalidade <= 0 ||
    $diaVencimento < 1 ||
    $diaVencimento > 31
) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Informe aluno, turma, mensalidade e dia de vencimento corretamente.'
    ]);
    exit;
}

//---------------- ID da escola logada ----------------//
$idEscolaLogada = getEscolaLogadaId();

//---------------- Matriculando aluno ----------------//
$resultado = matricularAlunoNaTurma(
    $idEscolaLogada,
    $idAluno,
    $idTurma,
    $valorMensalidade,
    $diaVencimento
);

//---------------- Retornando resultado ----------------//
echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
exit;