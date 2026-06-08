<?php
//---------------- Incluindo conexão com banco ----------------//
require_once __DIR__ . '/../config/database.php';


//---------------- Lista fixa de idiomas do sistema ----------------//
function listarIdiomasBase() {
    return [
        ['id_idioma' => 1, 'nome_idioma' => 'Inglês'],
        ['id_idioma' => 2, 'nome_idioma' => 'Espanhol'],
        ['id_idioma' => 3, 'nome_idioma' => 'Francês'],
        ['id_idioma' => 4, 'nome_idioma' => 'Alemão'],
        ['id_idioma' => 5, 'nome_idioma' => 'Japonês'],
        ['id_idioma' => 6, 'nome_idioma' => 'Árabe']
    ];
}


//---------------- Buscando dados atuais da escola ----------------//
function buscarDadosConfiguracaoEscola($idEscola) {
    $pdo = conectarBanco();

    $sql = "
        SELECT
            id_escola,
            nome_escola,
            gestor,
            cnpj,
            telefone,
            email
        FROM escolas
        WHERE id_escola = :id_escola
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id_escola' => $idEscola
    ]);

    return $stmt->fetch();
}


//---------------- Buscando idiomas ativos da escola ----------------//
function buscarIdiomasConfiguracaoEscola($idEscola) {
    $pdo = conectarBanco();

    $sql = "
        SELECT id_idioma
        FROM idiomas_escolas
        WHERE id_escola = :id_escola
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id_escola' => $idEscola
    ]);

    $idiomasAtivos = array_column($stmt->fetchAll(), 'id_idioma');

    $idiomas = listarIdiomasBase();

    foreach ($idiomas as &$idioma) {
        $idioma['ativo'] = in_array($idioma['id_idioma'], $idiomasAtivos);
    }

    return $idiomas;
}


//---------------- Buscando níveis cadastrados da escola ----------------//
function buscarNiveisConfiguracaoEscola($idEscola) {
    $pdo = conectarBanco();

    $sql = "
        SELECT
            n.id_nivel,
            n.id_idioma,
            n.nome_nivel
        FROM niveis n
        WHERE n.id_escola = :id_escola
        ORDER BY n.id_idioma ASC, n.nome_nivel ASC
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id_escola' => $idEscola
    ]);

    return $stmt->fetchAll();
}


//---------------- Atualizando dados principais da escola ----------------//
function salvarDadosConfiguracaoEscola($idEscola, $nomeEscola, $gestor, $email) {
    $pdo = conectarBanco();

    $sql = "
        UPDATE escolas
        SET
            nome_escola = :nome_escola,
            gestor = :gestor,
            email = :email
        WHERE id_escola = :id_escola
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':nome_escola' => $nomeEscola,
        ':gestor' => $gestor,
        ':email' => $email,
        ':id_escola' => $idEscola
    ]);

    return [
        'sucesso' => true
    ];
}


//---------------- Verificando email em outra escola ----------------//
function verificarEmailEmOutraEscola($idEscola, $email) {
    $pdo = conectarBanco();

    $sql = "
        SELECT id_escola
        FROM escolas
        WHERE email = :email
        AND id_escola != :id_escola
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':email' => $email,
        ':id_escola' => $idEscola
    ]);

    return $stmt->fetch();
}


//---------------- Salvando idiomas da escola ----------------//
function salvarIdiomasConfiguracaoEscola($idEscola, $idiomas) {
    $pdo = conectarBanco();

    try {
        $pdo->beginTransaction();

        $sqlDelete = "
            DELETE FROM idiomas_escolas
            WHERE id_escola = :id_escola
        ";

        $stmtDelete = $pdo->prepare($sqlDelete);

        $stmtDelete->execute([
            ':id_escola' => $idEscola
        ]);

        $sqlInsert = "
            INSERT INTO idiomas_escolas (
                id_escola,
                id_idioma
            ) VALUES (
                :id_escola,
                :id_idioma
            )
        ";

        $stmtInsert = $pdo->prepare($sqlInsert);

        foreach ($idiomas as $idIdioma) {
            $idIdioma = (int) $idIdioma;

            if ($idIdioma < 1 || $idIdioma > 6) {
                continue;
            }

            $stmtInsert->execute([
                ':id_escola' => $idEscola,
                ':id_idioma' => $idIdioma
            ]);
        }

        $pdo->commit();

        return [
            'sucesso' => true
        ];

    } catch (Exception $erro) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        return [
            'sucesso' => false,
            'mensagem' => $erro->getMessage()
        ];
    }
}


//---------------- Validando se idioma pertence à escola ----------------//
function idiomaPertenceEscola($idEscola, $idIdioma) {
    $pdo = conectarBanco();

    $sql = "
        SELECT id_idioma
        FROM idiomas_escolas
        WHERE id_escola = :id_escola
        AND id_idioma = :id_idioma
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id_escola' => $idEscola,
        ':id_idioma' => $idIdioma
    ]);

    return $stmt->fetch();
}


//---------------- Adicionando nível ----------------//
function adicionarNivelConfiguracaoEscola($idEscola, $idIdioma, $nomeNivel) {
    $pdo = conectarBanco();

    if (!idiomaPertenceEscola($idEscola, $idIdioma)) {
        return [
            'sucesso' => false,
            'mensagem' => 'Este idioma não pertence à escola.'
        ];
    }

    $sqlDuplicado = "
        SELECT id_nivel
        FROM niveis
        WHERE id_escola = :id_escola
        AND id_idioma = :id_idioma
        AND nome_nivel = :nome_nivel
        LIMIT 1
    ";

    $stmtDuplicado = $pdo->prepare($sqlDuplicado);

    $stmtDuplicado->execute([
        ':id_escola' => $idEscola,
        ':id_idioma' => $idIdioma,
        ':nome_nivel' => $nomeNivel
    ]);

    if ($stmtDuplicado->fetch()) {
        return [
            'sucesso' => false,
            'mensagem' => 'Este nível já existe para esse idioma.'
        ];
    }

    $sql = "
        INSERT INTO niveis (
            id_escola,
            id_idioma,
            nome_nivel
        ) VALUES (
            :id_escola,
            :id_idioma,
            :nome_nivel
        )
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id_escola' => $idEscola,
        ':id_idioma' => $idIdioma,
        ':nome_nivel' => $nomeNivel
    ]);

    return [
        'sucesso' => true
    ];
}

//---------------- Alterando senha da escola ----------------//
function alterarSenhaConfiguracaoEscola($idEscola, $senhaAtual, $novaSenha) {
    $pdo = conectarBanco();

    //---------------- Buscando senha atual ----------------//
    $sqlBusca = "
        SELECT senha
        FROM escolas
        WHERE id_escola = :id_escola
        LIMIT 1
    ";

    $stmtBusca = $pdo->prepare($sqlBusca);

    $stmtBusca->execute([
        ':id_escola' => $idEscola
    ]);

    $escola = $stmtBusca->fetch();

    if (!$escola) {
        return [
            'sucesso' => false,
            'mensagem' => 'Escola não encontrada.'
        ];
    }

    //---------------- Validando senha atual ----------------//
    if (!password_verify($senhaAtual, $escola['senha'])) {
        return [
            'sucesso' => false,
            'mensagem' => 'Senha atual incorreta.'
        ];
    }

    //---------------- Gerando nova senha ----------------//
    $novaSenhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

    //---------------- Atualizando senha ----------------//
    $sqlUpdate = "
        UPDATE escolas
        SET senha = :senha
        WHERE id_escola = :id_escola
    ";

    $stmtUpdate = $pdo->prepare($sqlUpdate);

    $stmtUpdate->execute([
        ':senha' => $novaSenhaHash,
        ':id_escola' => $idEscola
    ]);

    return [
        'sucesso' => true,
        'mensagem' => 'Senha alterada com sucesso.'
    ];
}