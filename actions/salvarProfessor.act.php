<?php
//---------------- Retorno em JSON ----------------//
header('Content-Type: application/json; charset=utf-8');

//---------------- Incluindo autenticação ----------------//
require_once __DIR__ . '/../includes/auth.inc.php';

//---------------- Incluindo repository de professores ----------------//
require_once __DIR__ . '/../repositories/ProfessorRepository.php';

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
if (!$entrada || empty(trim($entrada['nome'] ?? ''))) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Nome do professor é obrigatório.'
    ]);
    exit;
}

//---------------- Pegando dados ----------------//
$idEscolaLogada = getEscolaLogadaId();
$nome = trim($entrada['nome']);
$idiomas = $entrada['idiomas'] ?? [];

//---------------- Salvando professor ----------------//
$resultado = salvarProfessor($idEscolaLogada, $nome, $idiomas);

//---------------- Retornando resposta ----------------//
echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
exit;