<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/auth.inc.php';
require_once __DIR__ . '/../repositories/PagamentoRepository.php';

validarSessao();

$idEscolaLogada = getEscolaLogadaId();

$tipo = $_GET['tipo'] ?? '';

if (!in_array($tipo, ['a_receber', 'atrasado'])) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Tipo inválido.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$pendencias = buscarMensalidadesEmAbertoPorEscola($idEscolaLogada, $tipo);

echo json_encode([
    'sucesso' => true,
    'tipo' => $tipo,
    'pendencias' => $pendencias
], JSON_UNESCAPED_UNICODE);
exit;