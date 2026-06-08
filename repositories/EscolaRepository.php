<?php
//---------------- Incluindo conexão com banco ----------------//
require_once __DIR__ . '/../config/database.php';


//---------------- Verificando se email já existe ----------------//
// A tabela escolas já possui email único
function verificarEmailExistente($email) {
    $pdo = conectarBanco();

    $sql = "
        SELECT id_escola
        FROM escolas
        WHERE email = :email
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':email' => $email
    ]);

    return $stmt->fetch();
}


//---------------- Verificando se CNPJ já existe ----------------//
// A tabela escolas já possui CNPJ único
function verificarCnpjExistente($cnpj) {
    $pdo = conectarBanco();

    $sql = "
        SELECT id_escola
        FROM escolas
        WHERE cnpj = :cnpj
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':cnpj' => $cnpj
    ]);

    return $stmt->fetch();
}


//---------------- Cadastrando escola ----------------//
function cadastrarEscola($nomeEscola, $nomeGestor, $email, $senha, $cnpj, $idiomas) {
    $pdo = conectarBanco();

    try {
        //---------------- Iniciando transação ----------------//
        $pdo->beginTransaction();

        //---------------- Criptografando senha ----------------//
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        //---------------- Criando escola ----------------//
        $sqlEscola = "
            INSERT INTO escolas (
                nome_escola,
                gestor,
                cnpj,
                email,
                senha
            ) VALUES (
                :nome_escola,
                :gestor,
                :cnpj,
                :email,
                :senha
            )
        ";

        $stmtEscola = $pdo->prepare($sqlEscola);

        $stmtEscola->execute([
            ':nome_escola' => $nomeEscola,
            ':gestor'      => $nomeGestor,
            ':cnpj'        => $cnpj,
            ':email'       => $email,
            ':senha'       => $senhaHash
        ]);

        //---------------- Pegando ID da escola criada ----------------//
        $idEscola = $pdo->lastInsertId();

        //---------------- Salvando idiomas da escola ----------------//
        $sqlIdioma = "
            INSERT INTO idiomas_escolas (
                id_escola,
                id_idioma
            ) VALUES (
                :id_escola,
                :id_idioma
            )
        ";

        $stmtIdioma = $pdo->prepare($sqlIdioma);

        foreach ($idiomas as $idIdioma) {
            $idIdioma = (int) $idIdioma;

            if ($idIdioma < 1 || $idIdioma > 6) {
                continue;
            }

            $stmtIdioma->execute([
                ':id_escola' => $idEscola,
                ':id_idioma' => $idIdioma
            ]);
        }

        //---------------- Confirmando transação ----------------//
        $pdo->commit();

        return [
            'sucesso' => true,
            'id_escola' => $idEscola
        ];

    } catch (Exception $erro) {
        //---------------- Desfazendo alterações em caso de erro ----------------//
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        return [
            'sucesso' => false,
            'mensagem' => $erro->getMessage()
        ];
    }
}