<?php
//---------------- Incluindo autenticação ----------------//
// Aqui iniciamos a sessão e usamos as funções de login
require_once '../includes/auth.inc.php';

//---------------- Validando método da requisição ----------------//
// Garante que esse arquivo só será acessado pelo formulário via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/index.php');
    exit;
}

//---------------- Pegando dados do formulário ----------------//
// trim remove espaços antes e depois do texto digitado
$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');

//---------------- Validando campos vazios ----------------//
// Se algum campo estiver vazio, volta para o login com erro
if ($email === '' || $senha === '') {
    header('Location: ../pages/index.php?erro=campos_vazios');
    exit;
}

//---------------- Lendo banco temporário JSON ----------------//
// Esse JSON será substituído pelo banco de dados real futuramente
$caminhoJson = __DIR__ . '/../pages/dados.json';

if (!file_exists($caminhoJson)) {
    header('Location: ../pages/index.php?erro=json_nao_encontrado');
    exit;
}

$json = file_get_contents($caminhoJson);
$dados = json_decode($json, true);

//---------------- Validando estrutura do JSON ----------------//
// Garante que a chave escolas existe antes de tentar fazer login
if (!isset($dados['escolas']) || !is_array($dados['escolas'])) {
    header('Location: ../pages/index.php?erro=json_invalido');
    exit;
}

//---------------- Buscando escola pelo e-mail ----------------//
$escolaEncontrada = null;

foreach ($dados['escolas'] as $escola) {
    if (isset($escola['email']) && strtolower($escola['email']) === strtolower($email)) {
        $escolaEncontrada = $escola;
        break;
    }
}

//---------------- Validando e-mail e senha ----------------//
// password_verify compara a senha digitada com o hash salvo no JSON
if (!$escolaEncontrada || !password_verify($senha, $escolaEncontrada['senha'])) {
    header('Location: ../pages/index.php?erro=credenciais_invalidas');
    exit;
}

//---------------- Criando sessão do usuário ----------------//
// Regenera o ID da sessão para aumentar a segurança depois do login
session_regenerate_id(true);

$_SESSION['id_escola']    = $escolaEncontrada['id_escola'];
$_SESSION['nome_escola']  = $escolaEncontrada['nome_escola'];
$_SESSION['nome_gestor']  = $escolaEncontrada['gestor'];
$_SESSION['email_escola'] = $escolaEncontrada['email'];

//---------------- Redirecionando para o dashboard ----------------//
header('Location: ../pages/dashboard.php');
exit;