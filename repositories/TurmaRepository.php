<?php
//---------------- Incluindo conexão com banco ----------------//
// Esse arquivo permite acessar a função conectarBanco()
require_once __DIR__ . '/../config/database.php';


//---------------- Buscando turmas da escola ----------------//
// Retorna as turmas da escola logada com idioma, nível, professor e quantidade de alunos
function buscarTurmasPorEscola($idEscola) {
    $pdo = conectarBanco();

    $sql = "
        SELECT 
            t.id_turma,
            t.id_escola,
            t.id_idioma,
            t.id_nivel,
            t.id_professor,
            t.status,
            t.nome_turma,
            t.dia_semana,
            t.hora_inicio,
            t.hora_fim,
            t.data_inicio,
            t.data_fim,
            t.capacidade,
            t.observacao,

            i.nome AS nome_idioma,
            n.nome_nivel,
            p.nome AS nome_professor,

            COUNT(m.id_matricula) AS qtd_alunos

        FROM turmas t

        INNER JOIN idiomas i
            ON i.id_idioma = t.id_idioma

        INNER JOIN niveis n
            ON n.id_nivel = t.id_nivel

        INNER JOIN professores p
            ON p.id_professor = t.id_professor

        LEFT JOIN matriculas m
            ON m.id_turma = t.id_turma
            AND m.status_aluno = 'ativo'

        WHERE t.id_escola = :id_escola

        GROUP BY
            t.id_turma,
            t.id_escola,
            t.id_idioma,
            t.id_nivel,
            t.id_professor,
            t.status,
            t.nome_turma,
            t.dia_semana,
            t.hora_inicio,
            t.hora_fim,
            t.data_inicio,
            t.data_fim,
            t.capacidade,
            t.observacao,
            i.nome,
            n.nome_nivel,
            p.nome

        ORDER BY t.nome_turma ASC
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id_escola' => $idEscola
    ]);

    return $stmt->fetchAll();
}


//---------------- Buscando idiomas da escola ----------------//
// Usado no modal de turma
function buscarIdiomasDaEscola($idEscola) {
    $pdo = conectarBanco();

    $sql = "
        SELECT 
            i.id_idioma,
            i.nome,
            i.bandeira

        FROM idiomas i

        INNER JOIN idiomas_escolas ie
            ON ie.id_idioma = i.id_idioma

        WHERE ie.id_escola = :id_escola

        ORDER BY i.nome ASC
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id_escola' => $idEscola
    ]);

    return $stmt->fetchAll();
}


//---------------- Buscando níveis da escola ----------------//
// Usado no modal de turma
function buscarNiveisDaEscola($idEscola) {
    $pdo = conectarBanco();

    $sql = "
        SELECT 
            id_nivel,
            id_escola,
            id_idioma,
            nome_nivel,
            ordem

        FROM niveis

        WHERE id_escola = :id_escola

        ORDER BY id_idioma ASC, ordem ASC
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id_escola' => $idEscola
    ]);

    return $stmt->fetchAll();
}


//---------------- Buscando professores ativos da escola ----------------//
// Usado no modal de turma
// Retorna também os idiomas que o professor leciona
function buscarProfessoresAtivosDaEscola($idEscola) {
    $pdo = conectarBanco();

    $sql = "
        SELECT 
            p.id_professor,
            p.id_escola,
            p.nome,
            p.email,
            p.telefone,

            GROUP_CONCAT(DISTINCT pi.id_idioma ORDER BY pi.id_idioma SEPARATOR ',') AS idiomas_ids

        FROM professores p

        LEFT JOIN professor_idioma pi
            ON pi.id_professor = p.id_professor

        WHERE p.id_escola = :id_escola
        AND p.status = 'ativo'

        GROUP BY
            p.id_professor,
            p.id_escola,
            p.nome,
            p.email,
            p.telefone

        ORDER BY p.nome ASC
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id_escola' => $idEscola
    ]);

    return $stmt->fetchAll();
}


//---------------- Salvando nova turma ----------------//
// Insere uma turma no banco vinculada à escola logada
function salvarTurma($idEscola, $dadosTurma) {
    $pdo = conectarBanco();

    $sql = "
        INSERT INTO turmas (
            id_escola,
            id_idioma,
            id_nivel,
            id_professor,
            status,
            nome_turma,
            dia_semana,
            hora_inicio,
            hora_fim,
            data_inicio,
            data_fim,
            capacidade,
            observacao
        ) VALUES (
            :id_escola,
            :id_idioma,
            :id_nivel,
            :id_professor,
            :status,
            :nome_turma,
            :dia_semana,
            :hora_inicio,
            :hora_fim,
            :data_inicio,
            :data_fim,
            :capacidade,
            :observacao
        )
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id_escola'    => $idEscola,
        ':id_idioma'    => $dadosTurma['id_idioma'],
        ':id_nivel'     => $dadosTurma['id_nivel'],
        ':id_professor' => $dadosTurma['id_professor'],
        ':status'       => $dadosTurma['status'],
        ':nome_turma'   => $dadosTurma['nome_turma'],
        ':dia_semana'   => !empty($dadosTurma['dia_semana']) ? $dadosTurma['dia_semana'] : null,
        ':hora_inicio'  => $dadosTurma['hora_inicio'],
        ':hora_fim'     => $dadosTurma['hora_fim'],
        ':data_inicio'  => $dadosTurma['data_inicio'],
        ':data_fim'     => !empty($dadosTurma['data_fim']) ? $dadosTurma['data_fim'] : null,
        ':capacidade'   => !empty($dadosTurma['capacidade']) ? $dadosTurma['capacidade'] : 0,
        ':observacao'   => !empty($dadosTurma['observacao']) ? $dadosTurma['observacao'] : null
    ]);

    return $pdo->lastInsertId();
}


//---------------- Editando turma ----------------//
// Atualiza uma turma da escola logada
function editarTurma($idEscola, $idTurma, $dadosTurma) {
    $pdo = conectarBanco();

    $sql = "
        UPDATE turmas
        SET
            id_idioma = :id_idioma,
            id_nivel = :id_nivel,
            id_professor = :id_professor,
            status = :status,
            nome_turma = :nome_turma,
            dia_semana = :dia_semana,
            hora_inicio = :hora_inicio,
            hora_fim = :hora_fim,
            data_inicio = :data_inicio,
            data_fim = :data_fim,
            capacidade = :capacidade,
            observacao = :observacao

        WHERE id_turma = :id_turma
        AND id_escola = :id_escola
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id_idioma'    => $dadosTurma['id_idioma'],
        ':id_nivel'     => $dadosTurma['id_nivel'],
        ':id_professor' => $dadosTurma['id_professor'],
        ':status'       => $dadosTurma['status'],
        ':nome_turma'   => $dadosTurma['nome_turma'],
        ':dia_semana'   => !empty($dadosTurma['dia_semana']) ? $dadosTurma['dia_semana'] : null,
        ':hora_inicio'  => $dadosTurma['hora_inicio'],
        ':hora_fim'     => $dadosTurma['hora_fim'],
        ':data_inicio'  => $dadosTurma['data_inicio'],
        ':data_fim'     => !empty($dadosTurma['data_fim']) ? $dadosTurma['data_fim'] : null,
        ':capacidade'   => !empty($dadosTurma['capacidade']) ? $dadosTurma['capacidade'] : 0,
        ':observacao'   => !empty($dadosTurma['observacao']) ? $dadosTurma['observacao'] : null,
        ':id_turma'     => $idTurma,
        ':id_escola'    => $idEscola
    ]);

    return true;
}


//---------------- Excluindo turma logicamente ----------------//
// Não apaga do banco, apenas marca como cancelada
function excluirTurma($idEscola, $idTurma) {
    $pdo = conectarBanco();

    $sql = "
        UPDATE turmas
        SET status = 'cancelada'
        WHERE id_turma = :id_turma
        AND id_escola = :id_escola
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id_turma'  => $idTurma,
        ':id_escola' => $idEscola
    ]);

    return true;
}


//---------------- Matriculando aluno na turma ----------------//
// Cria matrícula ativa para o aluno e cancela matrícula ativa anterior
function matricularAlunoNaTurma($idEscola, $idAluno, $idTurma, $valorMensalidade, $diaVencimento) {
    $pdo = conectarBanco();

    //---------------- Verificando se aluno pertence à escola ----------------//
    $sqlAluno = "
        SELECT id_aluno
        FROM alunos
        WHERE id_aluno = :id_aluno
        AND id_escola = :id_escola
        AND status = 'ativo'
        LIMIT 1
    ";

    $stmtAluno = $pdo->prepare($sqlAluno);

    $stmtAluno->execute([
        ':id_aluno'  => $idAluno,
        ':id_escola' => $idEscola
    ]);

    if (!$stmtAluno->fetch()) {
        return [
            'sucesso' => false,
            'mensagem' => 'Aluno não encontrado ou inativo.'
        ];
    }

    //---------------- Verificando se turma pertence à escola ----------------//
    $sqlTurma = "
        SELECT
            id_turma,
            capacidade
        FROM turmas
        WHERE id_turma = :id_turma
        AND id_escola = :id_escola
        AND status = 'ativa'
        LIMIT 1
    ";

    $stmtTurma = $pdo->prepare($sqlTurma);

    $stmtTurma->execute([
        ':id_turma'  => $idTurma,
        ':id_escola' => $idEscola
    ]);

    $turma = $stmtTurma->fetch();

    if (!$turma) {
        return [
            'sucesso' => false,
            'mensagem' => 'Turma não encontrada ou não está ativa.'
        ];
    }

    //---------------- Verificando capacidade da turma ----------------//
    if (!empty($turma['capacidade'])) {
        $sqlTotalAlunos = "
            SELECT COUNT(*) AS total
            FROM matriculas
            WHERE id_turma = :id_turma
            AND status_aluno = 'ativo'
        ";

        $stmtTotalAlunos = $pdo->prepare($sqlTotalAlunos);
        $stmtTotalAlunos->execute([
            ':id_turma' => $idTurma
        ]);

        $totalAlunos = (int) ($stmtTotalAlunos->fetch()['total'] ?? 0);

        if ($totalAlunos >= (int) $turma['capacidade']) {
            return [
                'sucesso' => false,
                'mensagem' => 'Essa turma já atingiu a capacidade máxima.'
            ];
        }
    }

    try {
        //---------------- Iniciando transação ----------------//
        $pdo->beginTransaction();

        //---------------- Cancelando matrícula ativa anterior ----------------//
        $sqlCancelar = "
            UPDATE matriculas
            SET 
                status_aluno = 'cancelado',
                data_fim = CURDATE()
            WHERE id_aluno = :id_aluno
            AND status_aluno = 'ativo'
        ";

        $stmtCancelar = $pdo->prepare($sqlCancelar);

        $stmtCancelar->execute([
            ':id_aluno' => $idAluno
        ]);

        //---------------- Criando nova matrícula ----------------//
        $sqlMatricula = "
            INSERT INTO matriculas (
                id_aluno,
                id_turma,
                status_aluno,
                data_inicio,
                valor_mensalidade,
                dia_vencimento
            ) VALUES (
                :id_aluno,
                :id_turma,
                'ativo',
                CURDATE(),
                :valor_mensalidade,
                :dia_vencimento
            )
        ";

        $stmtMatricula = $pdo->prepare($sqlMatricula);

        $stmtMatricula->execute([
            ':id_aluno' => $idAluno,
            ':id_turma' => $idTurma,
            ':valor_mensalidade' => $valorMensalidade,
            ':dia_vencimento' => $diaVencimento
        ]);

        $pdo->commit();

        return [
            'sucesso' => true,
            'mensagem' => 'Aluno matriculado com sucesso.'
        ];

    } catch (Exception $erro) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        return [
            'sucesso' => false,
            'mensagem' => 'Erro ao matricular aluno: ' . $erro->getMessage()
        ];
    }
}


//---------------- Buscando detalhes completos da turma ----------------//
// Usado ao clicar no card da turma
function buscarDetalhesTurmaPorId($idEscola, $idTurma) {
    $pdo = conectarBanco();

    $sql = "
        SELECT 
            t.id_turma,
            t.id_escola,
            t.id_idioma,
            t.id_nivel,
            t.id_professor,
            t.status,
            t.nome_turma,
            t.dia_semana,
            t.hora_inicio,
            t.hora_fim,
            t.data_inicio,
            t.data_fim,
            t.capacidade,
            t.observacao,

            i.nome AS nome_idioma,
            n.nome_nivel,
            p.nome AS nome_professor,

            COUNT(m.id_matricula) AS qtd_alunos

        FROM turmas t

        INNER JOIN idiomas i
            ON i.id_idioma = t.id_idioma

        INNER JOIN niveis n
            ON n.id_nivel = t.id_nivel

        INNER JOIN professores p
            ON p.id_professor = t.id_professor

        LEFT JOIN matriculas m
            ON m.id_turma = t.id_turma
            AND m.status_aluno = 'ativo'

        WHERE t.id_escola = :id_escola
        AND t.id_turma = :id_turma

        GROUP BY
            t.id_turma,
            t.id_escola,
            t.id_idioma,
            t.id_nivel,
            t.id_professor,
            t.status,
            t.nome_turma,
            t.dia_semana,
            t.hora_inicio,
            t.hora_fim,
            t.data_inicio,
            t.data_fim,
            t.capacidade,
            t.observacao,
            i.nome,
            n.nome_nivel,
            p.nome

        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id_escola' => $idEscola,
        ':id_turma'  => $idTurma
    ]);

    return $stmt->fetch();
}


//---------------- Buscando alunos matriculados na turma ----------------//
// Retorna a lista de alunos ativos da turma
function buscarAlunosDaTurma($idEscola, $idTurma) {
    $pdo = conectarBanco();

    $sql = "
        SELECT
            a.id_aluno,
            a.nome,
            a.email,
            a.status,

            m.id_matricula,
            m.data_inicio,
            m.valor_mensalidade,
            m.dia_vencimento,
            m.status_aluno

        FROM matriculas m

        INNER JOIN alunos a
            ON a.id_aluno = m.id_aluno

        INNER JOIN turmas t
            ON t.id_turma = m.id_turma

        WHERE t.id_escola = :id_escola
        AND t.id_turma = :id_turma
        AND m.status_aluno = 'ativo'

        ORDER BY a.nome ASC
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id_escola' => $idEscola,
        ':id_turma'  => $idTurma
    ]);

    return $stmt->fetchAll();
}