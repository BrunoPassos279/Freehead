<?php
//---------------- Incluindo conexão com banco ----------------//
require_once __DIR__ . '/../config/database.php';


//---------------- Buscando alunos pelo nome ----------------//
// Usado na busca inteligente da sidebar
function buscarAlunosPorNome($idEscola, $busca) {
    $pdo = conectarBanco();

    $sql = "
        SELECT 
            id_aluno,
            nome
        FROM alunos
        WHERE id_escola = :id_escola
        AND status = 'ativo'
        AND nome LIKE :busca
        ORDER BY nome ASC
        LIMIT 10
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id_escola' => $idEscola,
        ':busca' => '%' . $busca . '%'
    ]);

    return $stmt->fetchAll();
}


//---------------- Buscando primeiro aluno pelo nome ----------------//
// Usado quando o usuário aperta Enter na busca
function buscarPrimeiroAlunoPorNome($idEscola, $busca) {
    $pdo = conectarBanco();

    $sql = "
        SELECT 
            id_aluno,
            nome
        FROM alunos
        WHERE id_escola = :id_escola
        AND status = 'ativo'
        AND nome LIKE :busca
        ORDER BY nome ASC
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id_escola' => $idEscola,
        ':busca' => '%' . $busca . '%'
    ]);

    return $stmt->fetch();
}

//---------------- Buscando alunos da escola ----------------//
// Carrega alunos + matrícula ativa + turma + idioma + nível + professor
// Também calcula a situação da mensalidade do mês atual para a lista de alunos
function buscarAlunosPorEscola($idEscola) {
    $pdo = conectarBanco();

    $sql = "
        SELECT
            a.id_aluno,
            a.id_escola,
            a.nome,
            a.nascimento,
            a.endereco,
            a.pai,
            a.mae,
            a.telefone_aluno,
            a.telefone_responsavel,
            a.email,
            a.status,
            a.data_cadastro,

            m.id_matricula,
            m.status_aluno,
            m.data_inicio AS data_inicio_matricula,
            m.valor_mensalidade,
            m.dia_vencimento,

            t.id_turma,
            t.nome_turma,

            i.nome AS nome_idioma,
            n.nome_nivel,
            p.nome AS nome_professor,

            pg.id_pagamento AS id_pagamento_mes,

            STR_TO_DATE(
                CONCAT(
                    DATE_FORMAT(CURDATE(), '%Y-%m'),
                    '-',
                    LPAD(
                        LEAST(
                            COALESCE(m.dia_vencimento, 10),
                            DAY(LAST_DAY(CURDATE()))
                        ),
                        2,
                        '0'
                    )
                ),
                '%Y-%m-%d'
            ) AS data_vencimento_mensalidade,

            CASE
                WHEN m.id_matricula IS NULL THEN NULL
                WHEN pg.id_pagamento IS NOT NULL THEN 'pago'
                WHEN t.data_inicio > LAST_DAY(CURDATE()) THEN NULL
                WHEN m.data_inicio > LAST_DAY(CURDATE()) THEN NULL
                WHEN STR_TO_DATE(
                    CONCAT(
                        DATE_FORMAT(CURDATE(), '%Y-%m'),
                        '-',
                        LPAD(
                            LEAST(
                                COALESCE(m.dia_vencimento, 10),
                                DAY(LAST_DAY(CURDATE()))
                            ),
                            2,
                            '0'
                        )
                    ),
                    '%Y-%m-%d'
                ) < CURDATE() THEN 'atrasado'
                ELSE 'pendente'
            END AS status_pagamento

        FROM alunos a

        LEFT JOIN (
            SELECT m1.*
            FROM matriculas m1
            INNER JOIN (
                SELECT 
                    id_aluno,
                    MAX(id_matricula) AS id_matricula
                FROM matriculas
                WHERE status_aluno = 'ativo'
                GROUP BY id_aluno
            ) ultima
                ON ultima.id_matricula = m1.id_matricula
        ) m
            ON m.id_aluno = a.id_aluno

        LEFT JOIN turmas t
            ON t.id_turma = m.id_turma
            AND t.id_escola = a.id_escola

        LEFT JOIN idiomas i
            ON i.id_idioma = t.id_idioma

        LEFT JOIN niveis n
            ON n.id_nivel = t.id_nivel

        LEFT JOIN professores p
            ON p.id_professor = t.id_professor

        LEFT JOIN pagamentos pg
            ON pg.id_matricula = m.id_matricula
            AND pg.mes_referencia = DATE_FORMAT(CURDATE(), '%Y-%m')
            AND pg.tipo_pagamento = 'mensalidade'
            AND pg.status = 'pago'

        WHERE a.id_escola = :id_escola
        AND a.status = 'ativo'

        ORDER BY a.nome ASC
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id_escola' => $idEscola
    ]);

    return $stmt->fetchAll();
}

//---------------- Buscando aluno detalhado por ID ----------------//
// Carrega dados do aluno + matrícula ativa + turma + idioma + nível + professor
function buscarAlunoDetalhadoPorId($idEscola, $idAluno) {
    $pdo = conectarBanco();

    $sql = "
        SELECT
            a.id_aluno,
            a.id_escola,
            a.nome,
            a.nascimento,
            a.endereco,
            a.pai,
            a.mae,
            a.telefone_aluno,
            a.telefone_responsavel,
            a.email,
            a.status,
            a.data_cadastro,

            m.id_matricula,
            m.status_aluno,
            m.data_inicio AS data_inicio_matricula,
            m.valor_mensalidade,
            m.dia_vencimento,

            t.id_turma,
            t.nome_turma,

            i.id_idioma,
            i.nome AS nome_idioma,

            n.id_nivel,
            n.nome_nivel,

            p.id_professor,
            p.nome AS nome_professor

        FROM alunos a

        LEFT JOIN matriculas m
            ON m.id_aluno = a.id_aluno
            AND m.status_aluno = 'ativo'

        LEFT JOIN turmas t
            ON t.id_turma = m.id_turma
            AND t.id_escola = a.id_escola

        LEFT JOIN idiomas i
            ON i.id_idioma = t.id_idioma

        LEFT JOIN niveis n
            ON n.id_nivel = t.id_nivel

        LEFT JOIN professores p
            ON p.id_professor = t.id_professor

        WHERE a.id_escola = :id_escola
        AND a.id_aluno = :id_aluno

        ORDER BY m.data_inicio DESC
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id_escola' => $idEscola,
        ':id_aluno'  => $idAluno
    ]);

    return $stmt->fetch();
}


//---------------- Buscando pagamentos do aluno ----------------//
// Busca todos os pagamentos ativos vinculados às matrículas do aluno
function buscarPagamentosDoAluno($idEscola, $idAluno) {
    $pdo = conectarBanco();

    $sql = "
        SELECT 
            pg.id_pagamento,
            pg.id_matricula,
            pg.mes_referencia,
            pg.tipo_pagamento,
            pg.valor,
            pg.data_vencimento,
            pg.data_pagamento,
            pg.forma_pagamento,
            pg.status,
            pg.observacao

        FROM pagamentos pg

        INNER JOIN matriculas m
            ON m.id_matricula = pg.id_matricula

        INNER JOIN alunos a
            ON a.id_aluno = m.id_aluno

        WHERE a.id_escola = :id_escola
        AND a.id_aluno = :id_aluno
        AND pg.status != 'cancelado'

        ORDER BY 
            pg.data_pagamento DESC,
            pg.id_pagamento DESC
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id_escola' => $idEscola,
        ':id_aluno'  => $idAluno
    ]);

    return $stmt->fetchAll();
}

//---------------- Salvando novo aluno ----------------//
// Insere um aluno no banco vinculado à escola logada
function salvarAluno($idEscola, $dadosAluno) {
    $pdo = conectarBanco();

    $sql = "
        INSERT INTO alunos (
            id_escola,
            nome,
            nascimento,
            endereco,
            pai,
            mae,
            telefone_aluno,
            telefone_responsavel,
            email,
            status
        ) VALUES (
            :id_escola,
            :nome,
            :nascimento,
            :endereco,
            :pai,
            :mae,
            :telefone_aluno,
            :telefone_responsavel,
            :email,
            'ativo'
        )
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id_escola'             => $idEscola,
        ':nome'                  => $dadosAluno['nome'],
        ':nascimento'            => !empty($dadosAluno['nascimento']) ? $dadosAluno['nascimento'] : null,
        ':endereco'              => !empty($dadosAluno['endereco']) ? $dadosAluno['endereco'] : null,
        ':pai'                   => !empty($dadosAluno['pai']) ? $dadosAluno['pai'] : null,
        ':mae'                   => !empty($dadosAluno['mae']) ? $dadosAluno['mae'] : null,
        ':telefone_aluno'        => !empty($dadosAluno['telefone_aluno']) ? $dadosAluno['telefone_aluno'] : null,
        ':telefone_responsavel'  => !empty($dadosAluno['telefone_responsavel']) ? $dadosAluno['telefone_responsavel'] : null,
        ':email'                 => !empty($dadosAluno['email']) ? $dadosAluno['email'] : null
    ]);

    return $pdo->lastInsertId();
}

//---------------- Editando aluno ----------------//
// Atualiza os dados do aluno no banco, garantindo que ele pertence à escola logada
function editarAluno($idEscola, $idAluno, $dadosAluno) {
    $pdo = conectarBanco();

    $sql = "
        UPDATE alunos
        SET
            nome = :nome,
            nascimento = :nascimento,
            endereco = :endereco,
            pai = :pai,
            mae = :mae,
            telefone_aluno = :telefone_aluno,
            telefone_responsavel = :telefone_responsavel,
            email = :email
        WHERE id_aluno = :id_aluno
        AND id_escola = :id_escola
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':nome'                  => $dadosAluno['nome'],
        ':nascimento'            => !empty($dadosAluno['nascimento']) ? $dadosAluno['nascimento'] : null,
        ':endereco'              => !empty($dadosAluno['endereco']) ? $dadosAluno['endereco'] : null,
        ':pai'                   => !empty($dadosAluno['pai']) ? $dadosAluno['pai'] : null,
        ':mae'                   => !empty($dadosAluno['mae']) ? $dadosAluno['mae'] : null,
        ':telefone_aluno'        => !empty($dadosAluno['telefone_aluno']) ? $dadosAluno['telefone_aluno'] : null,
        ':telefone_responsavel'  => !empty($dadosAluno['telefone_responsavel']) ? $dadosAluno['telefone_responsavel'] : null,
        ':email'                 => !empty($dadosAluno['email']) ? $dadosAluno['email'] : null,
        ':id_aluno'              => $idAluno,
        ':id_escola'             => $idEscola
    ]);

    return $stmt->rowCount();
}

//---------------- Excluindo aluno logicamente ----------------//
// Não apaga do banco, apenas muda o status para inativo
// Também cancela as matrículas ativas para não deixar dados inconsistentes
function excluirAluno($idEscola, $idAluno) {
    $pdo = conectarBanco();

    try {
        $pdo->beginTransaction();

        //---------------- Inativando aluno ----------------//
        $sqlAluno = "
            UPDATE alunos
            SET status = 'inativo'
            WHERE id_aluno = :id_aluno
            AND id_escola = :id_escola
        ";

        $stmtAluno = $pdo->prepare($sqlAluno);

        $stmtAluno->execute([
            ':id_aluno'  => $idAluno,
            ':id_escola' => $idEscola
        ]);

        //---------------- Cancelando matrículas ativas ----------------//
        $sqlMatriculas = "
            UPDATE matriculas m
            INNER JOIN alunos a
                ON a.id_aluno = m.id_aluno
            SET
                m.status_aluno = 'cancelado',
                m.data_fim = CURDATE()
            WHERE m.id_aluno = :id_aluno
            AND a.id_escola = :id_escola
            AND m.status_aluno = 'ativo'
        ";

        $stmtMatriculas = $pdo->prepare($sqlMatriculas);

        $stmtMatriculas->execute([
            ':id_aluno'  => $idAluno,
            ':id_escola' => $idEscola
        ]);

        $pdo->commit();

        return $stmtAluno->rowCount();

    } catch (Exception $erro) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        throw $erro;
    }
}

//---------------- Transferindo aluno de turma ----------------//
// Encerra a matrícula ativa atual e cria uma nova matrícula na turma escolhida
// Mantém o valor da mensalidade e o dia de vencimento da matrícula anterior
function transferirAlunoTurma($idEscola, $idAluno, $idTurma) {
    $pdo = conectarBanco();

    //---------------- Verificando aluno e matrícula ativa ----------------//
    $sqlAluno = "
        SELECT
            a.id_aluno,
            m.id_matricula,
            m.valor_mensalidade,
            m.dia_vencimento
        FROM alunos a
        INNER JOIN matriculas m
            ON m.id_aluno = a.id_aluno
            AND m.status_aluno = 'ativo'
        WHERE a.id_aluno = :id_aluno
        AND a.id_escola = :id_escola
        AND a.status = 'ativo'
        ORDER BY m.data_inicio DESC
        LIMIT 1
    ";

    $stmtAluno = $pdo->prepare($sqlAluno);
    $stmtAluno->execute([
        ':id_aluno'  => $idAluno,
        ':id_escola' => $idEscola
    ]);

    $matriculaAtual = $stmtAluno->fetch();

    if (!$matriculaAtual) {
        return [
            'sucesso' => false,
            'mensagem' => 'Aluno não encontrado ou sem matrícula ativa.'
        ];
    }

    if (empty($matriculaAtual['valor_mensalidade']) || (float) $matriculaAtual['valor_mensalidade'] <= 0) {
        return [
            'sucesso' => false,
            'mensagem' => 'A matrícula atual não possui valor de mensalidade. Transfira o aluno pela tela de turmas informando o valor.'
        ];
    }

    //---------------- Verificando se turma pertence à escola ----------------//
    $sqlTurma = "
        SELECT
            id_turma,
            data_inicio,
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
            'mensagem' => 'Turma não encontrada ou inativa.'
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

    //---------------- Definindo vencimento ----------------//
    $diaVencimento = (int) ($matriculaAtual['dia_vencimento'] ?? 0);

    if ($diaVencimento < 1 || $diaVencimento > 31) {
        $diaVencimento = !empty($turma['data_inicio'])
            ? (int) date('d', strtotime($turma['data_inicio']))
            : 10;
    }

    //---------------- Iniciando transação ----------------//
    $pdo->beginTransaction();

    try {
        //---------------- Cancelando matrícula ativa atual ----------------//
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
        $sqlNova = "
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

        $stmtNova = $pdo->prepare($sqlNova);
        $stmtNova->execute([
            ':id_aluno' => $idAluno,
            ':id_turma' => $idTurma,
            ':valor_mensalidade' => $matriculaAtual['valor_mensalidade'],
            ':dia_vencimento' => $diaVencimento
        ]);

        $pdo->commit();

        return [
            'sucesso' => true,
            'mensagem' => 'Aluno transferido com sucesso.'
        ];

    } catch (Exception $erro) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        return [
            'sucesso' => false,
            'mensagem' => 'Erro ao transferir aluno: ' . $erro->getMessage()
        ];
    }
}
