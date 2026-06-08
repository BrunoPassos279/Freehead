<?php
//---------------- Incluindo autenticação ----------------//
require_once __DIR__ . '/../includes/auth.inc.php';

//---------------- Incluindo repository de alunos ----------------//
require_once __DIR__ . '/../repositories/AlunoRepository.php';

//---------------- Validando sessão ----------------//
validarSessao();

//---------------- Pegando escola logada ----------------//
$idEscolaLogada = getEscolaLogadaId();

//---------------- Pegando termo pesquisado ----------------//
$busca = trim($_GET['busca'] ?? '');

//---------------- Validando busca vazia ----------------//
if ($busca === '') {
    header('Location: ../pages/dashboard.php');
    exit;
}

//---------------- Buscando aluno no banco ----------------//
$alunoEncontrado = buscarPrimeiroAlunoPorNome($idEscolaLogada, $busca);

//---------------- Se encontrou, vai para página do aluno ----------------//
if ($alunoEncontrado) {
    header('Location: ../pages/pageAluno.php?id_aluno=' . $alunoEncontrado['id_aluno']);
    exit;
}

//---------------- Se não encontrou, volta para alunos ----------------//
header('Location: ../pages/alunos.php?erro=aluno_nao_encontrado');
exit;