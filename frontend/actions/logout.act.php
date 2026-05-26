<?php
//---------------- Incluindo autenticação ----------------//
// Aqui temos acesso à função que encerra a sessão
require_once '../includes/auth.inc.php';

//---------------- Encerrando sessão ----------------//
// Remove os dados do usuário logado
encerrarSessao();

//---------------- Voltando para o login ----------------//
header('Location: ../pages/index.php');
exit;