<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/auth.inc.php';
require_once __DIR__ . '/../repositories/ConfiguracoesEscolaRepository.php';

validarSessao();

$idEscolaLogada = getEscolaLogadaId();

$escola = buscarDadosConfiguracaoEscola($idEscolaLogada);
$idiomas = buscarIdiomasConfiguracaoEscola($idEscolaLogada);
$niveis = buscarNiveisConfiguracaoEscola($idEscolaLogada);

echo json_encode([
    'sucesso' => true,
    'escola' => $escola,
    'idiomas' => $idiomas,
    'niveis' => $niveis
], JSON_UNESCAPED_UNICODE);

exit;