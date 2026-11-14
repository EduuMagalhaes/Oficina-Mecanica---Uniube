<?php
require_once __DIR__ . '/functions.php';

validarCamposTexto($_POST, ['nome', 'email', 'senha', 'tipo']);

$nome = validarNome($_POST['nome']);
$email = validarEmail($_POST['email']);
$senha = validarSenha($_POST['senha']);

if ($_POST['tipo'] === "admin" || $_POST['tipo'] === "mecanico") {
    $tipo = $_POST['tipo'];
} else {
    responderJSON(false, 'Tipo de usuário inválido.', 400);
}

try {
    require __DIR__ . '/../db/conexao.php';

    $consulta = $conexao->prepare(
        'SELECT id FROM usuarios WHERE email = ?'
    );

    $consulta->execute([$email]);

    if ($consulta->fetch()) {
        responderJSON(false, 'Já existe um usuário com este e-mail.', 409);
    }

    $senhaHash = password_hash($senha, PASSWORD_BCRYPT);
    
    $cadastro = $conexao->prepare(
        'INSERT INTO usuarios (nome, email, senha_hash, tipo)
         VALUES (?, ?, ?, ?)'
    );

    $cadastro->execute([
        $nome,
        $email,
        $senhaHash,
        $tipo
    ]);

    responderJSON(true, 'Usuário cadastrado com sucesso.', 201);

} catch (Throwable $erro) {
    error_log($erro->getMessage());
    responderJSON(false, 'Erro ao cadastrar o usuário.', 500);
}