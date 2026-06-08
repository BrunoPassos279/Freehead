<?php
//---------------- Incluindo conexão com banco ----------------//
require_once __DIR__ . '/../config/database.php';


//---------------- Buscando pagamentos realizados da escola ----------------//
// Usado na página financeiro.php
function buscarPagamentosRealizadosPorEscola($idEscola) {
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
            pg.observacao,

            a.id_aluno,
            a.nome AS nome_aluno

        FROM pagamentos pg

        INNER JOIN matriculas m
            ON m.id_matricula = pg.id_matricula

        INNER JOIN alunos a
            ON a.id_aluno = m.id_aluno

        WHERE a.id_escola = :id_escola
        AND pg.status = 'pago'
        AND pg.data_pagamento IS NOT NULL

        ORDER BY pg.data_pagamento DESC
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id_escola' => $idEscola
    ]);

    return $stmt->fetchAll();
}


//---------------- Buscando totais financeiros da escola ----------------//
// Calcula receita recebida, mensalidades a receber e mensalidades em atraso
function buscarResumoFinanceiroPorEscola($idEscola) {
    $pdo = conectarBanco();

    //---------------- Mês atual ----------------//
    $mesReferencia = date('Y-m');
    $inicioMes = $mesReferencia . '-01';
    $fimMes = date('Y-m-t', strtotime($inicioMes));


    //---------------- Receita total recebida ----------------//
    // Soma todos os pagamentos pagos da escola
    $sqlReceita = "
        SELECT COALESCE(SUM(pg.valor), 0) AS total
        FROM pagamentos pg

        INNER JOIN matriculas m
            ON m.id_matricula = pg.id_matricula

        INNER JOIN alunos a
            ON a.id_aluno = m.id_aluno

        WHERE a.id_escola = :id_escola
        AND pg.status = 'pago'
        AND pg.data_pagamento IS NOT NULL
    ";

    $stmtReceita = $pdo->prepare($sqlReceita);

    $stmtReceita->execute([
        ':id_escola' => $idEscola
    ]);

    $receitaTotal = $stmtReceita->fetch()['total'] ?? 0;


    //---------------- Total a receber ----------------//
    // Mensalidades do mês atual que ainda não foram pagas
    // e cujo vencimento ainda não passou
    $sqlAReceber = "
        SELECT COALESCE(SUM(m.valor_mensalidade), 0) AS total

        FROM matriculas m

        INNER JOIN alunos a
            ON a.id_aluno = m.id_aluno

        INNER JOIN turmas t
            ON t.id_turma = m.id_turma

        LEFT JOIN pagamentos pg
            ON pg.id_matricula = m.id_matricula
            AND pg.mes_referencia = :mes_referencia
            AND pg.tipo_pagamento = 'mensalidade'
            AND pg.status = 'pago'

        WHERE a.id_escola = :id_escola
        AND a.status = 'ativo'
        AND m.status_aluno = 'ativo'

        /* A turma precisa já ter começado até o final do mês atual */
        AND t.data_inicio <= :fim_mes

        /* A matrícula também precisa já existir até o final do mês atual */
        AND m.data_inicio <= :fim_mes

        /* Se já existe pagamento pago desse mês, não entra em aberto */
        AND pg.id_pagamento IS NULL

        /* Vencimento ainda não passou */
        AND STR_TO_DATE(
            CONCAT(
                :mes_referencia,
                '-',
                LPAD(
                    LEAST(
                        COALESCE(m.dia_vencimento, 10),
                        DAY(LAST_DAY(:inicio_mes))
                    ),
                    2,
                    '0'
                )
            ),
            '%Y-%m-%d'
        ) >= CURDATE()
    ";

    $stmtAReceber = $pdo->prepare($sqlAReceber);

    $stmtAReceber->execute([
        ':id_escola' => $idEscola,
        ':mes_referencia' => $mesReferencia,
        ':inicio_mes' => $inicioMes,
        ':fim_mes' => $fimMes
    ]);

    $totalAReceber = $stmtAReceber->fetch()['total'] ?? 0;


    //---------------- Total em atraso ----------------//
    // Mensalidades do mês atual que ainda não foram pagas
    // e cujo vencimento já passou
    $sqlAtrasado = "
        SELECT COALESCE(SUM(m.valor_mensalidade), 0) AS total

        FROM matriculas m

        INNER JOIN alunos a
            ON a.id_aluno = m.id_aluno

        INNER JOIN turmas t
            ON t.id_turma = m.id_turma

        LEFT JOIN pagamentos pg
            ON pg.id_matricula = m.id_matricula
            AND pg.mes_referencia = :mes_referencia
            AND pg.tipo_pagamento = 'mensalidade'
            AND pg.status = 'pago'

        WHERE a.id_escola = :id_escola
        AND a.status = 'ativo'
        AND m.status_aluno = 'ativo'

        /* A turma precisa já ter começado até o final do mês atual */
        AND t.data_inicio <= :fim_mes

        /* A matrícula também precisa já existir até o final do mês atual */
        AND m.data_inicio <= :fim_mes

        /* Se já existe pagamento pago desse mês, não entra em atraso */
        AND pg.id_pagamento IS NULL

        /* Vencimento já passou */
        AND STR_TO_DATE(
            CONCAT(
                :mes_referencia,
                '-',
                LPAD(
                    LEAST(
                        COALESCE(m.dia_vencimento, 10),
                        DAY(LAST_DAY(:inicio_mes))
                    ),
                    2,
                    '0'
                )
            ),
            '%Y-%m-%d'
        ) < CURDATE()
    ";

    $stmtAtrasado = $pdo->prepare($sqlAtrasado);

    $stmtAtrasado->execute([
        ':id_escola' => $idEscola,
        ':mes_referencia' => $mesReferencia,
        ':inicio_mes' => $inicioMes,
        ':fim_mes' => $fimMes
    ]);

    $totalAtrasado = $stmtAtrasado->fetch()['total'] ?? 0;


    //---------------- Retornando totais ----------------//
    return [
        'receita_total' => $receitaTotal,
        'a_receber' => $totalAReceber,
        'atrasado' => $totalAtrasado
    ];
}


//---------------- Buscando alunos para registrar pagamento ----------------//
// Usado no modal de pagamento da página financeiro.php
function buscarAlunosParaPagamento($idEscola) {
    $pdo = conectarBanco();

    $sql = "
        SELECT DISTINCT
            a.id_aluno,
            a.nome

        FROM alunos a

        INNER JOIN matriculas m
            ON m.id_aluno = a.id_aluno

        WHERE a.id_escola = :id_escola
        AND a.status = 'ativo'
        AND m.status_aluno = 'ativo'

        ORDER BY a.nome ASC
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id_escola' => $idEscola
    ]);

    return $stmt->fetchAll();
}


//---------------- Buscando matrícula ativa do aluno ----------------//
// Usado para registrar pagamento na matrícula atual do aluno
function buscarMatriculaAtivaDoAluno($idEscola, $idAluno) {
    $pdo = conectarBanco();

    $sql = "
        SELECT 
            m.id_matricula,
            m.id_aluno,
            m.id_turma,
            m.valor_mensalidade,
            m.dia_vencimento

        FROM matriculas m

        INNER JOIN alunos a
            ON a.id_aluno = m.id_aluno

        WHERE a.id_escola = :id_escola
        AND a.id_aluno = :id_aluno
        AND m.status_aluno = 'ativo'

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


//---------------- Verificando pagamento existente ----------------//
// Evita registrar duas mensalidades para o mesmo aluno no mesmo mês
function verificarPagamentoExistente($idMatricula, $mesReferencia) {
    $pdo = conectarBanco();

    $sql = "
        SELECT id_pagamento
        FROM pagamentos
        WHERE id_matricula = :id_matricula
        AND mes_referencia = :mes_referencia
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id_matricula'   => $idMatricula,
        ':mes_referencia' => $mesReferencia
    ]);

    return $stmt->fetch();
}


//---------------- Montando data de vencimento ----------------//
// Usa o dia de vencimento da matrícula e o mês de referência informado
function montarDataVencimentoPagamento($mesReferencia, $diaVencimento) {
    $diaVencimento = (int) $diaVencimento;

    if ($diaVencimento <= 0) {
        $diaVencimento = 10;
    }

    $ultimoDiaMes = date('t', strtotime($mesReferencia . '-01'));

    if ($diaVencimento > $ultimoDiaMes) {
        $diaVencimento = $ultimoDiaMes;
    }

    return $mesReferencia . '-' . str_pad($diaVencimento, 2, '0', STR_PAD_LEFT);
}

//---------------- Buscando mensalidade existente ----------------//
// Usado para quitar uma mensalidade já criada no mês
function buscarMensalidadeExistente($idMatricula, $mesReferencia) {
    $pdo = conectarBanco();

    $sql = "
        SELECT id_pagamento
        FROM pagamentos
        WHERE id_matricula = :id_matricula
        AND mes_referencia = :mes_referencia
        AND tipo_pagamento = 'mensalidade'
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id_matricula'   => $idMatricula,
        ':mes_referencia' => $mesReferencia
    ]);

    return $stmt->fetch();
}

//---------------- Quitando mensalidade existente ----------------//
// Se a mensalidade já existe, apenas atualiza para pago
function quitarMensalidadeExistente($idPagamento, $valor, $formaPagamento, $observacao) {
    $pdo = conectarBanco();

    $sql = "
        UPDATE pagamentos
        SET
            valor = :valor,
            data_pagamento = NOW(),
            forma_pagamento = :forma_pagamento,
            status = 'pago',
            observacao = :observacao
        WHERE id_pagamento = :id_pagamento
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':valor' => $valor,
        ':forma_pagamento' => $formaPagamento,
        ':observacao' => $observacao !== '' ? $observacao : null,
        ':id_pagamento' => $idPagamento
    ]);

    return [
        'sucesso' => true,
        'id_pagamento' => $idPagamento
    ];
}

//---------------- Registrando pagamento ----------------//
// Salva o pagamento no banco real
function registrarPagamento($idEscola, $idAluno, $mesReferencia, $valor, $formaPagamento, $observacao, $tipoPagamento = 'mensalidade') {
    $pdo = conectarBanco();

    //---------------- Buscando matrícula ativa ----------------//
    $matricula = buscarMatriculaAtivaDoAluno($idEscola, $idAluno);

    if (!$matricula) {
        return [
            'sucesso' => false,
            'erro' => 'matricula_nao_encontrada'
        ];
    }

    //---------------- Definindo valor do pagamento ----------------//
    // Se o usuário não informar valor, usa o valor da mensalidade da matrícula
    if ($valor === null || $valor === '') {
        $valor = $matricula['valor_mensalidade'] ?? null;
    }

    //---------------- Normalizando valor ----------------//
    // Aceita tanto 350.00 quanto 350,00
    $valor = str_replace(',', '.', $valor);
    $valor = (float) $valor;

    //---------------- Validando valor ----------------//
    if ($valor <= 0) {
        return [
            'sucesso' => false,
            'erro' => 'valor_mensalidade_nao_informado'
        ];
    }

    //---------------- Validando tipo de pagamento ----------------//
    $tiposPermitidos = [
        'mensalidade',
        'material',
        'matricula',
        'aula_extra',
        'outro'
    ];

    if (!in_array($tipoPagamento, $tiposPermitidos)) {
        $tipoPagamento = 'mensalidade';
    }

    //---------------- Verificando mensalidade existente ----------------//
    // Mensalidade só pode existir uma vez por mês.
    // Se já existir, atualiza para pago em vez de inserir outra.
    if ($tipoPagamento === 'mensalidade') {
        $mensalidadeExistente = buscarMensalidadeExistente(
            $matricula['id_matricula'],
            $mesReferencia
        );

        if ($mensalidadeExistente) {
            return quitarMensalidadeExistente(
                $mensalidadeExistente['id_pagamento'],
                $valor,
                $formaPagamento,
                $observacao
            );
        }
    }

    //---------------- Definindo vencimento ----------------//
    $dataVencimento = montarDataVencimentoPagamento(
        $mesReferencia,
        $matricula['dia_vencimento'] ?? 10
    );

    //---------------- Inserindo pagamento ----------------//
    $sql = "
        INSERT INTO pagamentos (
            id_matricula,
            mes_referencia,
            tipo_pagamento,
            valor,
            data_vencimento,
            data_pagamento,
            forma_pagamento,
            status,
            observacao
        ) VALUES (
            :id_matricula,
            :mes_referencia,
            :tipo_pagamento,
            :valor,
            :data_vencimento,
            NOW(),
            :forma_pagamento,
            'pago',
            :observacao
        )
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id_matricula'    => $matricula['id_matricula'],
        ':mes_referencia'  => $mesReferencia,
        ':tipo_pagamento'  => $tipoPagamento,
        ':valor'           => $valor,
        ':data_vencimento' => $dataVencimento,
        ':forma_pagamento' => $formaPagamento,
        ':observacao'      => $observacao !== '' ? $observacao : null
    ]);

    return [
        'sucesso' => true,
        'id_pagamento' => $pdo->lastInsertId()
    ];
}

//---------------- Editando pagamento ----------------//
// Atualiza dados de um pagamento da escola logada
function editarPagamento($idEscola, $idPagamento, $dadosPagamento) {
    $pdo = conectarBanco();

    //---------------- Tipos permitidos ----------------//
    $tiposPermitidos = [
        'mensalidade',
        'material',
        'matricula',
        'aula_extra',
        'outro'
    ];

    $tipoPagamento = $dadosPagamento['tipo_pagamento'] ?? 'mensalidade';

    if (!in_array($tipoPagamento, $tiposPermitidos)) {
        $tipoPagamento = 'mensalidade';
    }

    //---------------- Normalizando valor ----------------//
    $valor = $dadosPagamento['valor'] ?? 0;
    $valor = str_replace(',', '.', $valor);
    $valor = (float) $valor;

    if ($valor <= 0) {
        return [
            'sucesso' => false,
            'mensagem' => 'Informe um valor válido.'
        ];
    }

    //---------------- Buscando matrícula do pagamento ----------------//
    $sqlPagamento = "
        SELECT 
            pg.id_pagamento,
            m.dia_vencimento

        FROM pagamentos pg

        INNER JOIN matriculas m
            ON m.id_matricula = pg.id_matricula

        INNER JOIN alunos a
            ON a.id_aluno = m.id_aluno

        WHERE pg.id_pagamento = :id_pagamento
        AND a.id_escola = :id_escola

        LIMIT 1
    ";

    $stmtPagamento = $pdo->prepare($sqlPagamento);

    $stmtPagamento->execute([
        ':id_pagamento' => $idPagamento,
        ':id_escola' => $idEscola
    ]);

    $pagamento = $stmtPagamento->fetch();

    if (!$pagamento) {
        return [
            'sucesso' => false,
            'mensagem' => 'Pagamento não encontrado.'
        ];
    }

    //---------------- Montando vencimento ----------------//
    $mesReferencia = $dadosPagamento['mes_referencia'];
    $dataVencimento = montarDataVencimentoPagamento(
        $mesReferencia,
        $pagamento['dia_vencimento'] ?? 10
    );

    try {
        //---------------- Atualizando pagamento ----------------//
        $sql = "
            UPDATE pagamentos
            SET
                mes_referencia = :mes_referencia,
                tipo_pagamento = :tipo_pagamento,
                valor = :valor,
                data_vencimento = :data_vencimento,
                forma_pagamento = :forma_pagamento,
                observacao = :observacao
            WHERE id_pagamento = :id_pagamento
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':mes_referencia' => $mesReferencia,
            ':tipo_pagamento' => $tipoPagamento,
            ':valor' => $valor,
            ':data_vencimento' => $dataVencimento,
            ':forma_pagamento' => $dadosPagamento['forma_pagamento'],
            ':observacao' => !empty($dadosPagamento['observacao']) ? $dadosPagamento['observacao'] : null,
            ':id_pagamento' => $idPagamento
        ]);

        return [
            'sucesso' => true,
            'mensagem' => 'Pagamento atualizado com sucesso.'
        ];

    } catch (Exception $erro) {
        return [
            'sucesso' => false,
            'mensagem' => 'Erro ao editar pagamento: ' . $erro->getMessage()
        ];
    }
}

//---------------- Cancelando pagamento ----------------//
// Não apaga do banco, apenas muda status para cancelado
function cancelarPagamento($idEscola, $idPagamento) {
    $pdo = conectarBanco();

    $sql = "
        UPDATE pagamentos pg

        INNER JOIN matriculas m
            ON m.id_matricula = pg.id_matricula

        INNER JOIN alunos a
            ON a.id_aluno = m.id_aluno

        SET pg.status = 'cancelado'

        WHERE pg.id_pagamento = :id_pagamento
        AND a.id_escola = :id_escola
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id_pagamento' => $idPagamento,
        ':id_escola' => $idEscola
    ]);

    return [
        'sucesso' => true,
        'mensagem' => 'Pagamento cancelado com sucesso.'
    ];
}

//---------------- Listando mensalidades em aberto ----------------//
// Tipo pode ser: a_receber ou atrasado
function buscarMensalidadesEmAbertoPorEscola($idEscola, $tipo) {
    $pdo = conectarBanco();

    $comparadorVencimento = $tipo === 'atrasado'
        ? '< CURDATE()'
        : '>= CURDATE()';

    $sql = "
        SELECT
            a.id_aluno,
            a.nome AS nome_aluno,

            m.id_matricula,
            m.valor_mensalidade,
            m.dia_vencimento,

            t.id_turma,
            t.nome_turma,

            DATE_FORMAT(CURDATE(), '%Y-%m') AS mes_referencia,

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
            ) AS data_vencimento

        FROM matriculas m

        INNER JOIN alunos a
            ON a.id_aluno = m.id_aluno

        INNER JOIN turmas t
            ON t.id_turma = m.id_turma

        LEFT JOIN pagamentos pg
            ON pg.id_matricula = m.id_matricula
            AND pg.mes_referencia = DATE_FORMAT(CURDATE(), '%Y-%m')
            AND pg.tipo_pagamento = 'mensalidade'
            AND pg.status = 'pago'

        WHERE a.id_escola = :id_escola
        AND a.status = 'ativo'
        AND m.status_aluno = 'ativo'
        AND t.data_inicio <= LAST_DAY(CURDATE())
        AND m.data_inicio <= LAST_DAY(CURDATE())
        AND pg.id_pagamento IS NULL

        HAVING data_vencimento {$comparadorVencimento}

        ORDER BY data_vencimento ASC, a.nome ASC
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id_escola' => $idEscola
    ]);

    return $stmt->fetchAll();
}