<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/auth.inc.php';
require_once __DIR__ . '/../repositories/ProfessorRepository.php';

validarSessao();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Método inválido.']);
    exit;
}

$entrada = json_decode(file_get_contents('php://input'), true);

if (!$entrada || empty($entrada['id_professor']) || empty($entrada['id_turma'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Professor ou turma não informado.']);
    exit;
}

$idEscolaLogada = getEscolaLogadaId();

$resultado = associarProfessorTurma(
    $idEscolaLogada,
    $entrada['id_professor'],
    $entrada['id_turma']
);

echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
exit;