<?php
//---------------- Incluindo conexão com banco ----------------//
// Esse arquivo permite acessar a função conectarBanco()
require_once __DIR__ . '/../config/database.php';


//---------------- Buscando professores da escola ----------------//
// Retorna os professores ativos da escola logada
function buscarProfessoresPorEscola($idEscola) {
    $pdo = conectarBanco();

    $sql = "
        SELECT 
            p.id_professor,
            p.id_escola,
            p.nome,
            p.email,
            p.telefone,
            p.status,
            p.data_cadastro,

            GROUP_CONCAT(DISTINCT i.id_idioma ORDER BY i.id_idioma SEPARATOR ',') AS idiomas_ids,
            GROUP_CONCAT(DISTINCT i.nome ORDER BY i.id_idioma SEPARATOR ', ') AS idiomas_nomes,

            COUNT(DISTINCT t.id_turma) AS qtd_turmas

        FROM professores p

        LEFT JOIN professor_idioma pi
            ON pi.id_professor = p.id_professor

        LEFT JOIN idiomas i
            ON i.id_idioma = pi.id_idioma

        LEFT JOIN turmas t
            ON t.id_professor = p.id_professor
            AND t.status = 'ativa'

        WHERE p.id_escola = :id_escola
        AND p.status = 'ativo'

        GROUP BY 
            p.id_professor,
            p.id_escola,
            p.nome,
            p.email,
            p.telefone,
            p.status,
            p.data_cadastro

        ORDER BY p.nome ASC
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id_escola' => $idEscola
    ]);

    return $stmt->fetchAll();
}

//---------------- Sincronizando idiomas do professor ----------------//
// Remove vínculos antigos e recria com os idiomas selecionados
function sincronizarIdiomasProfessor($pdo, $idProfessor, $idiomas) {
    $sqlDelete = "
        DELETE FROM professor_idioma
        WHERE id_professor = :id_professor
    ";

    $stmtDelete = $pdo->prepare($sqlDelete);
    $stmtDelete->execute([
        ':id_professor' => $idProfessor
    ]);

    if (empty($idiomas)) {
        return;
    }

    $sqlInsert = "
        INSERT INTO professor_idioma (
            id_professor,
            id_idioma
        ) VALUES (
            :id_professor,
            :id_idioma
        )
    ";

    $stmtInsert = $pdo->prepare($sqlInsert);

    foreach ($idiomas as $idIdioma) {
        $stmtInsert->execute([
            ':id_professor' => $idProfessor,
            ':id_idioma' => $idIdioma
        ]);
    }
}


//---------------- Salvando professor ----------------//
// Cria professor e vincula idiomas selecionados
function salvarProfessor($idEscola, $nome, $idiomas) {
    $pdo = conectarBanco();

    try {
        $pdo->beginTransaction();

        //---------------- Inserindo professor ----------------//
        $sqlProfessor = "
            INSERT INTO professores (
                id_escola,
                nome,
                status
            ) VALUES (
                :id_escola,
                :nome,
                'ativo'
            )
        ";

        $stmtProfessor = $pdo->prepare($sqlProfessor);

        $stmtProfessor->execute([
            ':id_escola' => $idEscola,
            ':nome' => $nome
        ]);

        //---------------- Pegando ID criado ----------------//
        $idProfessor = $pdo->lastInsertId();

        //---------------- Inserindo idiomas do professor ----------------//
        if (!empty($idiomas)) {
            $sqlIdioma = "
                INSERT INTO professor_idioma (
                    id_professor,
                    id_idioma
                ) VALUES (
                    :id_professor,
                    :id_idioma
                )
            ";

            $stmtIdioma = $pdo->prepare($sqlIdioma);

            foreach ($idiomas as $idIdioma) {
                $stmtIdioma->execute([
                    ':id_professor' => $idProfessor,
                    ':id_idioma' => (int) $idIdioma
                ]);
            }
        }

        $pdo->commit();

        return [
            'sucesso' => true,
            'mensagem' => 'Professor cadastrado com sucesso.',
            'id_professor' => $idProfessor
        ];

    } catch (Exception $erro) {
        $pdo->rollBack();

        return [
            'sucesso' => false,
            'mensagem' => 'Erro ao salvar professor: ' . $erro->getMessage()
        ];
    }
}


//---------------- Editando professor ----------------//
// Atualiza nome e idiomas do professor
function editarProfessor($idEscola, $idProfessor, $nome, $idiomas) {
    $pdo = conectarBanco();

    $pdo->beginTransaction();

    try {
        $sql = "
            UPDATE professores
            SET nome = :nome
            WHERE id_professor = :id_professor
            AND id_escola = :id_escola
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':nome' => $nome,
            ':id_professor' => $idProfessor,
            ':id_escola' => $idEscola
        ]);

        sincronizarIdiomasProfessor($pdo, $idProfessor, $idiomas);

        $pdo->commit();

        return [
            'sucesso' => true
        ];

    } catch (Exception $erro) {
        $pdo->rollBack();

        return [
            'sucesso' => false,
            'mensagem' => $erro->getMessage()
        ];
    }
}


//---------------- Excluindo professor logicamente ----------------//
// Não apaga do banco, apenas muda status para inativo
function excluirProfessor($idEscola, $idProfessor) {
    $pdo = conectarBanco();

    $sql = "
        UPDATE professores
        SET status = 'inativo'
        WHERE id_professor = :id_professor
        AND id_escola = :id_escola
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id_professor' => $idProfessor,
        ':id_escola' => $idEscola
    ]);

    return true;
}


//---------------- Associando professor a uma turma ----------------//
// Atualiza a turma escolhida para usar o professor selecionado
function associarProfessorTurma($idEscola, $idProfessor, $idTurma) {
    $pdo = conectarBanco();

    //---------------- Validando professor ----------------//
    $sqlProfessor = "
        SELECT id_professor
        FROM professores
        WHERE id_professor = :id_professor
        AND id_escola = :id_escola
        AND status = 'ativo'
        LIMIT 1
    ";

    $stmtProfessor = $pdo->prepare($sqlProfessor);

    $stmtProfessor->execute([
        ':id_professor' => $idProfessor,
        ':id_escola' => $idEscola
    ]);

    if (!$stmtProfessor->fetch()) {
        return [
            'sucesso' => false,
            'mensagem' => 'Professor não encontrado.'
        ];
    }

    //---------------- Validando turma ----------------//
    $sqlTurma = "
        SELECT id_turma
        FROM turmas
        WHERE id_turma = :id_turma
        AND id_escola = :id_escola
        AND status = 'ativa'
        LIMIT 1
    ";

    $stmtTurma = $pdo->prepare($sqlTurma);

    $stmtTurma->execute([
        ':id_turma' => $idTurma,
        ':id_escola' => $idEscola
    ]);

    if (!$stmtTurma->fetch()) {
        return [
            'sucesso' => false,
            'mensagem' => 'Turma não encontrada.'
        ];
    }

    //---------------- Atualizando professor da turma ----------------//
    $sqlUpdate = "
        UPDATE turmas
        SET id_professor = :id_professor
        WHERE id_turma = :id_turma
        AND id_escola = :id_escola
    ";

    $stmtUpdate = $pdo->prepare($sqlUpdate);

    $stmtUpdate->execute([
        ':id_professor' => $idProfessor,
        ':id_turma' => $idTurma,
        ':id_escola' => $idEscola
    ]);

    return [
        'sucesso' => true,
        'mensagem' => 'Professor associado à turma com sucesso.'
    ];
}

//---------------- Buscando idiomas permitidos da escola ----------------//
// Usado no modal de professor para mostrar somente os idiomas da escola logada
function buscarIdiomasPermitidosProfessor($idEscola) {
    $pdo = conectarBanco();

    $sql = "
        SELECT 
            ie.id_idioma,

            CASE ie.id_idioma
                WHEN 1 THEN 'Inglês'
                WHEN 2 THEN 'Espanhol'
                WHEN 3 THEN 'Francês'
                WHEN 4 THEN 'Alemão'
                WHEN 5 THEN 'Japonês'
                WHEN 6 THEN 'Árabe'
                ELSE 'Idioma'
            END AS nome_idioma

        FROM idiomas_escolas ie

        WHERE ie.id_escola = :id_escola

        ORDER BY FIELD(ie.id_idioma, 1, 2, 3, 4, 5, 6)
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id_escola' => $idEscola
    ]);

    return $stmt->fetchAll();
}