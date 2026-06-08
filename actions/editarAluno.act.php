<?php
//---------------- Retorno em JSON ----------------//
// Essa action será chamada pelo fetch do modalAluno.js
header('Content-Type: application/json; charset=utf-8');

//---------------- Incluindo autenticação ----------------//
// Esse arquivo inicia a sessão e permite validar o login
require_once __DIR__ . '/../includes/auth.inc.php';

//---------------- Incluindo repository de alunos ----------------//
// Esse arquivo edita alunos no banco real
require_once __DIR__ . '/../repositories/AlunoRepository.php';

//---------------- Validando sessão ----------------//
// Garante que apenas usuário logado consiga editar aluno
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

//---------------- Validando ID do aluno ----------------//
if (empty($entrada['id_aluno'])) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'ID do aluno não informado.'
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
// Garante que o usuário só edite aluno da própria escola
$idEscolaLogada = getEscolaLogadaId();

try {
    //---------------- Editando aluno ----------------//
    editarAluno($idEscolaLogada, $entrada['id_aluno'], [
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
        'mensagem' => 'Aluno atualizado com sucesso.'
    ]);
    exit;

} catch (Exception $erro) {
    //---------------- Retornando erro ----------------//
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao editar aluno: ' . $erro->getMessage()
    ]);
    exit;
}