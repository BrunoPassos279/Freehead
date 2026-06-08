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

//---------------- Validando dados ----------------//
if (
    !$entrada ||
    empty($entrada['id_turma']) ||
    empty(trim($entrada['nome_turma'] ?? '')) ||
    empty($entrada['id_idioma']) ||
    empty($entrada['id_nivel']) ||
    empty($entrada['id_professor']) ||
    empty($entrada['hora_inicio']) ||
    empty($entrada['hora_fim']) ||
    empty($entrada['data_inicio'])
) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Preencha todos os campos obrigatórios.'
    ]);
    exit;
}

//---------------- ID da escola logada ----------------//
$idEscolaLogada = getEscolaLogadaId();

try {
    //---------------- Editando turma ----------------//
    editarTurma($idEscolaLogada, $entrada['id_turma'], [
        'nome_turma'   => trim($entrada['nome_turma']),
        'id_idioma'    => $entrada['id_idioma'],
        'id_nivel'     => $entrada['id_nivel'],
        'id_professor' => $entrada['id_professor'],
        'status'       => $entrada['status'] ?? 'ativa',
        'dia_semana'   => trim($entrada['dia_semana'] ?? ''),
        'hora_inicio'  => $entrada['hora_inicio'],
        'hora_fim'     => $entrada['hora_fim'],
        'data_inicio'  => $entrada['data_inicio'],
        'data_fim'     => $entrada['data_fim'] ?? null,
        'capacidade'   => $entrada['capacidade'] ?? 0,
        'observacao'   => trim($entrada['observacao'] ?? '')
    ]);

    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Turma atualizada com sucesso.'
    ], JSON_UNESCAPED_UNICODE);
    exit;

} catch (Exception $erro) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao editar turma: ' . $erro->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    exit;
}