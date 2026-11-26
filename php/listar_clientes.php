<?php
require_once __DIR__ . '/functions.php';

try {
    require __DIR__ . '/../db/conexao.php';

    $consulta = $conexao->prepare(
        'SELECT id, nome FROM clientes ORDER BY nome'
    );

    $consulta->execute();

    $clientes = $consulta->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'sucesso' => true,
        'clientes' => $clientes
    ]);
    exit;

}   catch (Throwable $erro) {
    error_log($erro->getMessage());
    responderJSON(false, 'Erro ao buscar clientes.', 500);
}