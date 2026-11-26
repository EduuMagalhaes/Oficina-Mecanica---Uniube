<?php
require __DIR__ . '/functions.php';

try {
    require __DIR__ . '/../db/conexao.php';

    $consulta = $conexao->prepare(
        'SELECT id, nome FROM mecanicos ORDER BY nome'
    );

    $consulta->execute();

    $mecanicos = $consulta->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'sucesso' => true,
        'mecanicos' => $mecanicos
    ]);
    exit;

} catch (Throwable $erro) {
    error_log($erro->getMessage());

    responderJSON(false, 'Erro ao buscar os mecânicos.', 500);
}