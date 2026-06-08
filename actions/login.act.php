<?php
//---------------- Incluindo autenticação ----------------//
// Aqui iniciamos a sessão e usamos funções auxiliares
require_once '../includes/auth.inc.php';

//---------------- Incluindo repository de autenticação ----------------//
// Aqui ficam as consultas de login no banco
require_once '../repositories/AuthRepository.php';

//---------------- Validando método da requisição ----------------//
// Garante que esse arquivo só será acessado pelo formulário via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/index.php');
    exit;
}

//---------------- Pegando dados do formulário ----------------//
// trim remove espaços antes e depois do texto digitado
$email = trim($_POST['email'] ?? '');
$senhaDigitada = trim($_POST['senha'] ?? '');

//---------------- Validando campos vazios ----------------//
// Se algum campo estiver vazio, volta para o login com erro
if ($email === '' || $senhaDigitada === '') {
    header('Location: ../pages/index.php?erro=campos_vazios');
    exit;
}

//---------------- Buscando escola/usuário no banco ----------------//
// Procura a escola pelo e-mail informado
$usuario = buscarUsuarioPorEmail($email);

//---------------- Validando usuário encontrado ----------------//
if (!$usuario) {
    header('Location: ../pages/index.php?erro=credenciais_invalidas');
    exit;
}

//---------------- Validando status do usuário ----------------//
// Como a tabela escolas não tem status, o repository retorna ativo por padrão
if ($usuario['status'] !== 'ativo') {
    header('Location: ../pages/index.php?erro=usuario_inativo');
    exit;
}

//---------------- Validando senha ----------------//
// Compara a senha digitada com o hash salvo no banco
if (!password_verify($senhaDigitada, $usuario['senha'])) {
    header('Location: ../pages/index.php?erro=credenciais_invalidas');
    exit;
}

//---------------- Criando sessão do usuário ----------------//
// Regenera o ID da sessão para aumentar a segurança depois do login
session_regenerate_id(true);

$_SESSION['id_usuario']      = $usuario['id_usuario'];
$_SESSION['id_escola']       = $usuario['id_escola'];
$_SESSION['nome_usuario']    = $usuario['nome'];
$_SESSION['email_usuario']   = $usuario['email'];
$_SESSION['nivel_permissao'] = $usuario['nivel_permissao'];

$_SESSION['nome_escola'] = $usuario['nome_escola'];
$_SESSION['gestor']      = $usuario['gestor'];
$_SESSION['nome_gestor'] = $usuario['gestor'];

//---------------- Redirecionando para o dashboard ----------------//
header('Location: ../pages/dashboard.php');
exit;