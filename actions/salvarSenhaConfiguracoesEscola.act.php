<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/auth.inc.php';
require_once __DIR__ . '/../repositories/ConfiguracoesEscolaRepository.php';

validarSessao();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Método inválido.'
    ]);
    exit;
}

$entrada = json_decode(file_get_contents('php://input'), true);

$idEscolaLogada = getEscolaLogadaId();

$senhaAtual = $entrada['senha_atual'] ?? '';
$novaSenha = $entrada['nova_senha'] ?? '';
$confirmarSenha = $entrada['confirmar_senha'] ?? '';

if ($senhaAtual === '' || $novaSenha === '' || $confirmarSenha === '') {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Preencha todos os campos de senha.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if (strlen($novaSenha) < 6) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'A nova senha precisa ter pelo menos 6 caracteres.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($novaSenha !== $confirmarSenha) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'As senhas não conferem.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$resultado = alterarSenhaConfiguracaoEscola(
    $idEscolaLogada,
    $senhaAtual,
    $novaSenha
);

echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
exit;