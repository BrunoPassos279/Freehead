<?php
//---------------- Incluindo autenticação ----------------//
// Esse arquivo inicia a sessão
require_once '../includes/auth.inc.php';

//---------------- Limpando dados da sessão ----------------//
$_SESSION = [];

//---------------- Removendo cookie da sessão ----------------//
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

//---------------- Destruindo sessão ----------------//
session_destroy();

//---------------- Voltando para o login ----------------//
header('Location: ../pages/index.php');
exit;