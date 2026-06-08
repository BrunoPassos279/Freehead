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

$idIdioma = (int) ($entrada['id_idioma'] ?? 0);
$nomeNivel = trim($entrada['nome_nivel'] ?? '');

if ($idIdioma <= 0 || $nomeNivel === '') {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Informe o idioma e o nome do nível.'
    ]);
    exit;
}

$resultado = adicionarNivelConfiguracaoEscola(
    $idEscolaLogada,
    $idIdioma,
    $nomeNivel
);

echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
exit;