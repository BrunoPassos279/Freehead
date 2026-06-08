<?php
//---------------- Retorno em JSON ----------------//
header('Content-Type: application/json; charset=utf-8');

//---------------- Incluindo autenticação ----------------//
require_once __DIR__ . '/../includes/auth.inc.php';

//---------------- Incluindo repositories ----------------//
require_once __DIR__ . '/../repositories/TurmaRepository.php';
require_once __DIR__ . '/../repositories/AlunoRepository.php';

//---------------- Validando sessão ----------------//
validarSessao();

//---------------- Escola logada ----------------//
$idEscolaLogada = getEscolaLogadaId();

try {
    //---------------- Buscando dados reais do banco ----------------//
    $idiomas = buscarIdiomasDaEscola($idEscolaLogada);
    $niveis = buscarNiveisDaEscola($idEscolaLogada);
    $professores = buscarProfessoresAtivosDaEscola($idEscolaLogada);
    $alunos = buscarAlunosPorEscola($idEscolaLogada);

    //---------------- Retornando dados ----------------//
    echo json_encode([
        'idiomas' => $idiomas,
        'niveis' => $niveis,
        'professores' => $professores,
        'alunos' => $alunos
    ], JSON_UNESCAPED_UNICODE);
    exit;

} catch (Exception $erro) {
    echo json_encode([
        'idiomas' => [],
        'niveis' => [],
        'professores' => [],
        'alunos' => [],
        'erro' => $erro->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    exit;
}