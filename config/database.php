<?php
//---------------- Criando conexão PDO ----------------//
// Essa função será usada pelos repositories/actions para acessar o banco
function conectarBanco() {
    //---------------- Configurações do banco ----------------//
    // Variáveis locais para evitar conflito com outras variáveis do sistema
    $dbHost = 'localhost';
    $dbNome = 'freehead';
    $dbUsuario = 'root';
    $dbSenha = '';
    $dbPorta = '3306';
    $dbCharset = 'utf8mb4';

    try {
        //---------------- Montando DSN ----------------//
        $dsn = "mysql:host={$dbHost};port={$dbPorta};dbname={$dbNome};charset={$dbCharset}";

        //---------------- Criando conexão ----------------//
        $pdo = new PDO($dsn, $dbUsuario, $dbSenha);

        //---------------- Configurando erros do PDO ----------------//
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //---------------- Configurando retorno padrão ----------------//
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $pdo;

    } catch (PDOException $erro) {
        die('Erro ao conectar com o banco de dados: ' . $erro->getMessage());
    }
}