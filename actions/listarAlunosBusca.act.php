<?php
//---------------- Incluindo autenticação ----------------//
require_once __DIR__ . '/../includes/auth.inc.php';

//---------------- Incluindo repository de alunos ----------------//
require_once __DIR__ . '/../repositories/AlunoRepository.php';

//---------------- Retorno em JSON ----------------//
header('Content-Type: application/json; charset=utf-8');

//---------------- Validando sessão ----------------//
validarSessao();

//---------------- Pegando escola logada ----------------//
$idEscolaLogada = getEscolaLogadaId();

//---------------- Pegando termo buscado ----------------//
$busca = trim($_GET['busca'] ?? '');

//---------------- Retornando vazio se não digitou nada ----------------//
if ($busca === '') {
    echo json_encode([]);
    exit;
}

//---------------- Buscando alunos no banco ----------------//
$alunos = buscarAlunosPorNome($idEscolaLogada, $busca);

//---------------- Retornando resultado ----------------//
echo json_encode($alunos, JSON_UNESCAPED_UNICODE);
exit;