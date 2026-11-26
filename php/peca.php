<?php
require_once __DIR__ . '/functions.php';

exigirLogin();

validarCamposTexto($_POST, ['nome', 'codigo', 'fornecedor', 'preco_custo', 'preco_venda', 'qtd_estoque']);

$nome = validarNome($_POST['nome']);
$codigo = validarCodigo($_POST['codigo']);
$fornecedor = validarNome($_POST['fornecedor']);
$preco_custo = validarNumeroNaoNegativo($_POST['preco_custo']);
$preco_venda = validarPrecoVenda($_POST['preco_venda'],$_POST['preco_custo']);
$qtd_estoque = validarNumeroNaoNegativo($_POST['qtd_estoque']);

try {
    require __DIR__ . '/../db/conexao.php';

    $consulta = $conexao->prepare(
        'SELECT id FROM pecas WHERE codigo = ?'
    );

    $consulta->execute([$codigo]);

    if ($consulta->fetch()) {
        responderJSON(false, 'Já existe uma peça com este código.', 409);
    }
    
    $cadastro = $conexao->prepare(
        'INSERT INTO pecas (nome, codigo, fornecedor, preco_custo, preco_venda, qtd_estoque)
         VALUES (?, ?, ?, ?, ?, ?)'
    );

    $cadastro->execute([
        $nome,
        $codigo,
        $fornecedor,
        $preco_custo,
        $preco_venda,
        $qtd_estoque
    ]);

    responderJSON(true, 'Peça cadastrado com sucesso.', 201);

} catch (Throwable $erro) {
    error_log($erro->getMessage());
    responderJSON(false, 'Erro ao cadastrar a peça.', 500);
}

?>