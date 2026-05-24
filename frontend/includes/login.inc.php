
<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../pages/index.php");
    exit();
}

$email = trim($_POST["email"] ?? "");
$senha = trim($_POST["senha"] ?? "");

if (empty($email) || empty($senha)) {
    header("Location: ../pages/index.php?erro=2");
    exit();
}

require_once 'conexao.inc.php';

// ============================================
// TODO: CRUD — buscar usuário pelo email
// Tabela: usuarios
// Colunas: id_usuario, id_escola, nome, email, senha, nivel_permissao
// ============================================
$user = null; // substituir pelo resultado da query

if (!$user || !password_verify($senha, $user['senha'])) {
    header("Location: ../pages/index.php?erro=1");
    exit();
}

$_SESSION['id_usuario'] = $user['id_usuario'];
$_SESSION['id_escola'] = $user['id_escola'];
$_SESSION['nome'] = $user['nome'];
$_SESSION['email'] = $user['email'];
$_SESSION['nivel_permissao'] = $user['nivel_permissao'];

header("Location: ../pages/dashboard.php");
exit();