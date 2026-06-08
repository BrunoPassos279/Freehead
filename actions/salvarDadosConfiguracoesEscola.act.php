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

$nomeEscola = trim($entrada['nome_escola'] ?? '');
$gestor = trim($entrada['gestor'] ?? '');
$email = trim($entrada['email'] ?? '');

if ($nomeEscola === '' || $gestor === '' || $email === '') {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Preencha todos os campos obrigatórios.'
    ]);
    exit;
}

if (verificarEmailEmOutraEscola($idEscolaLogada, $email)) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Este e-mail já está em uso por outra escola.'
    ]);
    exit;
}

$resultado = salvarDadosConfiguracaoEscola(
    $idEscolaLogada,
    $nomeEscola,
    $gestor,
    $email
);

if ($resultado['sucesso']) {
    $_SESSION['nome_escola'] = $nomeEscola;
    $_SESSION['gestor'] = $gestor;
    $_SESSION['nome_gestor'] = $gestor;
    $_SESSION['nome_usuario'] = $gestor;
    $_SESSION['email_usuario'] = $email;
}

echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
exit;