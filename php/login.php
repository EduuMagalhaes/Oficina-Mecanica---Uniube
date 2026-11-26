<?php
require_once __DIR__ . '/functions.php';

validarCamposTexto($_POST, ['login', 'senha']);

$login = trim($_POST['login']);
$senha = $_POST['senha'];

try {
    require __DIR__ . '/../db/conexao.php';

    $consulta = $conexao->prepare(
    'SELECT id, nome, email, senha_hash, tipo FROM usuarios WHERE email = ?'
);
    $consulta->execute([$login]);

    $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

    if (!$usuario || !password_verify($senha, $usuario['senha_hash'])) {
        responderJSON(false, 'E-mail ou senha inválidos.', 401);
    }

    session_start();
    session_regenerate_id(true);

    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['usuario_nome'] = $usuario['nome'];
    $_SESSION['usuario_tipo'] = $usuario['tipo'];

    responderJSON(true, 'Login realizado com sucesso.', 200);

} catch (Throwable $erro) {
    error_log($erro->getMessage());
    responderJSON(false, 'Erro ao realizar o login.', 500);
}