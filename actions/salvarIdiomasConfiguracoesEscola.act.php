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
$idiomas = $entrada['idiomas'] ?? [];

if (empty($idiomas) || !is_array($idiomas)) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Selecione pelo menos um idioma.'
    ]);
    exit;
}

$resultado = salvarIdiomasConfiguracaoEscola($idEscolaLogada, $idiomas);

echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
exit;