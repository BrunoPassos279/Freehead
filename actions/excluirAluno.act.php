<?php
//---------------- Retorno em JSON ----------------//
// Essa action será chamada pelo fetch do modalAluno.js
header('Content-Type: application/json; charset=utf-8');

//---------------- Incluindo autenticação ----------------//
// Esse arquivo inicia a sessão e permite validar o login
require_once __DIR__ . '/../includes/auth.inc.php';

//---------------- Incluindo repository de alunos ----------------//
// Esse arquivo exclui alunos no banco real
require_once __DIR__ . '/../repositories/AlunoRepository.php';

//---------------- Validando sessão ----------------//
// Garante que apenas usuário logado consiga excluir aluno
validarSessao();

//---------------- Validando método ----------------//
// Essa action só aceita POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Método inválido.'
    ]);
    exit;
}

//---------------- Lendo dados enviados pelo JavaScript ----------------//
// O modalAluno.js envia os dados em JSON
$entrada = json_decode(file_get_contents('php://input'), true);

//---------------- Validando ID do aluno ----------------//
if (empty($entrada['id_aluno'])) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'ID do aluno não informado.'
    ]);
    exit;
}

//---------------- ID da escola logada ----------------//
// Garante que o usuário só exclua aluno da própria escola
$idEscolaLogada = getEscolaLogadaId();

try {
    //---------------- Excluindo aluno logicamente ----------------//
    excluirAluno($idEscolaLogada, $entrada['id_aluno']);

    //---------------- Retornando sucesso ----------------//
    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Aluno excluído com sucesso.'
    ]);
    exit;

} catch (Exception $erro) {
    //---------------- Retornando erro ----------------//
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao excluir aluno: ' . $erro->getMessage()
    ]);
    exit;
}