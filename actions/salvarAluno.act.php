<?php
//---------------- Retorno em JSON ----------------//
// Essa action será chamada pelo fetch do modalAluno.js
header('Content-Type: application/json; charset=utf-8');

//---------------- Incluindo autenticação ----------------//
// Esse arquivo inicia a sessão e permite validar o login
require_once __DIR__ . '/../includes/auth.inc.php';

//---------------- Incluindo repository de alunos ----------------//
// Esse arquivo salva alunos no banco real
require_once __DIR__ . '/../repositories/AlunoRepository.php';

//---------------- Validando sessão ----------------//
// Garante que apenas usuário logado consiga cadastrar aluno
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

//---------------- Validando JSON recebido ----------------//
if (!$entrada || !is_array($entrada)) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Dados inválidos.'
    ]);
    exit;
}

//---------------- Validando nome obrigatório ----------------//
if (empty(trim($entrada['nome'] ?? ''))) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Nome do aluno é obrigatório.'
    ]);
    exit;
}

//---------------- ID da escola logada ----------------//
// O aluno sempre será vinculado à escola da sessão
$idEscolaLogada = getEscolaLogadaId();

try {
    //---------------- Salvando aluno ----------------//
    $idAluno = salvarAluno($idEscolaLogada, [
        'nome'                 => trim($entrada['nome'] ?? ''),
        'nascimento'           => $entrada['nascimento'] ?? null,
        'endereco'             => trim($entrada['endereco'] ?? ''),
        'pai'                  => trim($entrada['pai'] ?? ''),
        'mae'                  => trim($entrada['mae'] ?? ''),
        'telefone_aluno'       => trim($entrada['telefone_aluno'] ?? ''),
        'telefone_responsavel' => trim($entrada['telefone_responsavel'] ?? ''),
        'email'                => trim($entrada['email'] ?? '')
    ]);

    //---------------- Retornando sucesso ----------------//
    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Aluno cadastrado com sucesso.',
        'id_aluno' => $idAluno
    ]);
    exit;

} catch (Exception $erro) {
    //---------------- Retornando erro ----------------//
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao cadastrar aluno: ' . $erro->getMessage()
    ]);
    exit;
}