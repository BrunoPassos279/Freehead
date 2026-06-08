<?php
//---------------- Incluindo conexão com banco ----------------//
require_once __DIR__ . '/../config/database.php';

//---------------- Buscando usuário/escola por email ----------------//
// O login da escola usa a tabela escolas
function buscarUsuarioPorEmail($email) {
    $pdo = conectarBanco();

    $sql = "
        SELECT 
            id_escola,
            nome_escola,
            gestor,
            email,
            senha
        FROM escolas
        WHERE email = :email
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':email' => $email
    ]);

    $escola = $stmt->fetch();

    if (!$escola) {
        return false;
    }

    //---------------- Adaptando retorno para o login atual ----------------//
    return [
        'id_usuario'      => $escola['id_escola'],
        'id_escola'       => $escola['id_escola'],
        'nome'            => $escola['gestor'],
        'email'           => $escola['email'],
        'senha'           => $escola['senha'],
        'status'          => 'ativo',
        'nivel_permissao' => 'admin',
        'nome_escola'     => $escola['nome_escola'],
        'gestor'          => $escola['gestor']
    ];
}