<?php
require_once __DIR__ . '/functions.php';

exigirLogin();

validarCamposTexto($_POST, ['nome', 'especialidade', 'telefone', 'data_admissao', 'valor_hora']);

$nome = validarNome($_POST['nome']);
$especialidade = trim($_POST['especialidade']);
$telefone = validarTelefone($_POST['telefone']);
$data_admissao = validarDataNaoFutura($_POST['data_admissao']);
$valor_hora = validarNumeroNaoNegativo($_POST['valor_hora']);

try {
    require __DIR__ . '/../db/conexao.php';

    $consulta = $conexao->prepare(
        'SELECT id FROM mecanicos WHERE nome = ?'
    );

    $consulta->execute([$nome]);

    if ($consulta->fetch()) {
        responderJSON(false, 'Já existe um mecânico com este nome.', 409);
    }
    
    $cadastro = $conexao->prepare(
        'INSERT INTO mecanicos (nome, especialidade, telefone, data_admissao, valor_hora)
         VALUES (?, ?, ?, ?, ?)'
    );

    $cadastro->execute([
        $nome,
        $especialidade,
        $telefone,
        $data_admissao,
        $valor_hora
    ]);

    responderJSON(true, 'Mecânico cadastrado com sucesso.', 201);

} catch (Throwable $erro) {
    error_log($erro->getMessage());
    responderJSON(false, 'Erro ao cadastrar o mecânico.', 500);
}