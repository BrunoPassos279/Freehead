<?php
header('Content-Type: application/json; charset=utf-8');

try {
    require_once __DIR__ . '/../includes/auth.inc.php';
    require_once __DIR__ . '/../repositories/TurmaRepository.php';

    validarSessao();

    $idEscolaLogada = getEscolaLogadaId();
    $idTurma = $_GET['id_turma'] ?? null;

    if (!$idTurma) {
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Turma não informada.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $turma = buscarDetalhesTurmaPorId($idEscolaLogada, $idTurma);

    if (!$turma) {
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Turma não encontrada.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $alunos = buscarAlunosDaTurma($idEscolaLogada, $idTurma);

    echo json_encode([
        'sucesso' => true,
        'turma' => $turma,
        'alunos' => $alunos
    ], JSON_UNESCAPED_UNICODE);
    exit;

} catch (Throwable $erro) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro interno: ' . $erro->getMessage(),
        'arquivo' => $erro->getFile(),
        'linha' => $erro->getLine()
    ], JSON_UNESCAPED_UNICODE);
    exit;
}