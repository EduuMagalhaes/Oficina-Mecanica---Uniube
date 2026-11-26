<?php
require_once __DIR__ . '/functions.php';

try {
    require __DIR__ . '/../db/conexao.php';

    $clienteId = $_GET['cliente_id'] ?? null;

    if (!$clienteId) {
        responderJSON(false, 'ID do cliente não fornecido.', 400);
        exit;
    }

    $consulta = $conexao->prepare(
        'SELECT * FROM veiculos WHERE cliente_id = ?'
    );

    $consulta->execute([$clienteId]);

    $veiculos = $consulta->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'sucesso' => true,
        'veiculos' => $veiculos
    ]);
    exit;

}   catch (Throwable $erro) {
    error_log($erro->getMessage());
    responderJSON(false, 'Erro ao buscar veículos.', 500);
}