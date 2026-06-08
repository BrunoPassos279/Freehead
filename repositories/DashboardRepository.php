<?php
//---------------- Incluindo conexão com banco ----------------//
// Esse arquivo permite acessar a função conectarBanco()
require_once __DIR__ . '/../config/database.php';


//---------------- Buscando resumo do dashboard ----------------//
// Retorna os totais principais da escola logada
function buscarResumoDashboard($idEscola) {
    $pdo = conectarBanco();

    //---------------- Total de alunos ativos ----------------//
    $sqlAlunos = "
        SELECT COUNT(*) AS total
        FROM alunos
        WHERE id_escola = :id_escola
        AND status = 'ativo'
    ";

    $stmtAlunos = $pdo->prepare($sqlAlunos);
    $stmtAlunos->execute([
        ':id_escola' => $idEscola
    ]);

    $totalAlunos = $stmtAlunos->fetch()['total'] ?? 0;


    //---------------- Total de professores ativos ----------------//
    $sqlProfessores = "
        SELECT COUNT(*) AS total
        FROM professores
        WHERE id_escola = :id_escola
        AND status = 'ativo'
    ";

    $stmtProfessores = $pdo->prepare($sqlProfessores);
    $stmtProfessores->execute([
        ':id_escola' => $idEscola
    ]);

    $totalProfessores = $stmtProfessores->fetch()['total'] ?? 0;


    //---------------- Total de turmas ativas ----------------//
    $sqlTurmas = "
        SELECT COUNT(*) AS total
        FROM turmas
        WHERE id_escola = :id_escola
        AND status = 'ativa'
    ";

    $stmtTurmas = $pdo->prepare($sqlTurmas);
    $stmtTurmas->execute([
        ':id_escola' => $idEscola
    ]);

    $totalTurmas = $stmtTurmas->fetch()['total'] ?? 0;


    //---------------- Retornando dados ----------------//
    return [
        'total_alunos' => $totalAlunos,
        'total_professores' => $totalProfessores,
        'total_turmas' => $totalTurmas
    ];
}