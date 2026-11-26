<?php
require_once __DIR__ . '/functions.php';

session_start();

if (empty($_SESSION['usuario_id'])) {
    responderJSON(false, 'Não autenticado.', 401);
}

echo json_encode([
    'sucesso' => true,
    'usuario' => $_SESSION['usuario_nome']
]);