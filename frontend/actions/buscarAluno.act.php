<?php
//---------------- Incluindo autenticação ----------------//
// Inicia a sessão e permite pegar a escola logada
require_once '../includes/auth.inc.php';

//---------------- Validando sessão ----------------//
// Garante que só usuário logado possa buscar aluno
validarSessao();

//---------------- Pegando escola logada ----------------//
// Usado para buscar apenas alunos da escola atual
$idEscolaLogada = getEscolaLogadaId();

//---------------- Pegando termo pesquisado ----------------//
// Recebe o texto digitado na sidebar
$busca = trim($_GET['busca'] ?? '');

//---------------- Validando busca vazia ----------------//
// Se não digitou nada, volta para o dashboard
if ($busca === '') {
    header('Location: ../pages/dashboard.php');
    exit;
}

//---------------- Lendo banco temporário JSON ----------------//
// Esse JSON será substituído pelo banco real futuramente
$json = file_get_contents('../pages/dados.json');
$dados = json_decode($json, true);

//---------------- Buscando aluno ----------------//
// Procura aluno pelo nome dentro da escola logada
$alunoEncontrado = null;

foreach ($dados['alunos'] as $aluno) {
    $nomeAluno = strtolower($aluno['nome']);
    $termoBusca = strtolower($busca);

    if (
        $aluno['id_escola'] == $idEscolaLogada &&
        str_contains($nomeAluno, $termoBusca)
    ) {
        $alunoEncontrado = $aluno;
        break;
    }
}

//---------------- Redirecionando para página do aluno ----------------//
// Se encontrou, abre a página de detalhes do aluno
if ($alunoEncontrado) {
    header('Location: ../pages/aluno.php?id_aluno=' . $alunoEncontrado['id_aluno']);
    exit;
}

//---------------- Aluno não encontrado ----------------//
// Se não encontrou, volta para alunos com mensagem
header('Location: ../pages/alunos.php?erro=aluno_nao_encontrado');
exit;