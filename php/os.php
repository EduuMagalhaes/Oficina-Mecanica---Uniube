<?php
require_once __DIR__ . '/functions.php';

exigirLogin();

validarCamposTexto($_POST, ['cliente_id', 'veiculo_id', 'mecanico_id', 'entrada', 'defeito', 'status']);

$cliente_id = validarId($_POST['cliente_id'], 'ID do cliente');
$veiculo_id = validarId($_POST['veiculo_id'], 'ID do veículo');
$mecanico_id = validarId($_POST['mecanico_id'], 'ID do mecânico');
$entrada = validarDataNaoFutura($_POST['entrada']);
$defeito = trim($_POST['defeito']);
$status = validarStatus($_POST['status']);

try {
    require __DIR__ . '/../db/conexao.php';

    $consulta = $conexao->prepare(
    'SELECT id
     FROM ordens_servico
     WHERE veiculo_id = ?
       AND status IN (?, ?, ?)'
    );

    $consulta->execute([
        $veiculo_id,
        'Aberta',
        'Em andamento',
        'Aguardando peça'
    ]);

    if ($consulta->fetch()) {
        responderJSON(false, 'O veículo selecionado já possui uma ordem de serviço aberta.', 409);
    }
    
    $cadastro = $conexao->prepare(
        'INSERT INTO ordens_servico (cliente_id, veiculo_id, mecanico_id, defeito, status, data_entrada)
         VALUES (?, ?, ?, ?, ?, ?)'
    );

    $cadastro->execute([
        $cliente_id,
        $veiculo_id,
        $mecanico_id,
        $defeito,
        $status,
        $entrada        
    ]);

    responderJSON(true, 'Ordem de serviço cadastrada com sucesso.', 201);

} catch (Throwable $erro) {
    responderJSON(
        false,
        'Erro: ' . $erro->getMessage(),
        500
    );
}

?>