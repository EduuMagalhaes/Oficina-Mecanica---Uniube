<?php
require_once __DIR__ . '/functions.php';

validarCamposTexto($_POST, ['nome', 'cpf', 'telefone', 'email', 'endereco']);

$nome = validarNome($_POST['nome']);
$cpf = validarCPF($_POST['cpf']);
$telefone = validarTelefone($_POST['telefone']);
$email = validarEmail($_POST['email']);
$endereco = trim($_POST['endereco']);

try {
    require __DIR__ . '/../db/conexao.php';

    $consulta = $conexao->prepare(
        'SELECT id FROM clientes WHERE cpf = ?'
    );

    $consulta->execute([$cpf]);

    if ($consulta->fetch()) {
        responderJSON(false, 'Já existe um cliente cadastrado com este CPF.', 409);
    }

    $cadastro = $conexao->prepare(
        'INSERT INTO clientes (nome, cpf, telefone, email, endereco)
         VALUES (?, ?, ?, ?, ?)'
    );

    $cadastro->execute([
        $nome,
        $cpf,
        $telefone,
        $email,
        $endereco
    ]);

    responderJSON(true, 'Cliente cadastrado com sucesso.', 201);

} catch (Throwable $erro) {
    error_log($erro->getMessage());
    responderJSON(false, 'Erro ao cadastrar o cliente.', 500);
}