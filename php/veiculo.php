<?php
require_once __DIR__ . '/functions.php';

validarCamposTexto($_POST, ['placa', 'marca', 'modelo', 'ano', 'quilometragem', 'cliente_id']);

$placa = validarPlaca($_POST['placa']);
$marca = validarMarcaModelo($_POST['marca']);
$modelo = validarMarcaModelo($_POST['modelo']);
$ano = validarAnoVeiculo($_POST['ano']);
$quilometragem = validarQuilometragem($_POST['quilometragem']);
$clienteId = validarClienteId($_POST['cliente_id']);

try {
    require __DIR__ . '/../db/conexao.php';

    $consulta = $conexao->prepare(
        'SELECT id FROM veiculos WHERE placa = ?'
    );

    $consulta->execute([$placa]);

    if ($consulta->fetch()) {
        responderJSON(false, 'Já existe um veículo com esta placa.', 409);
    }

    $cadastro = $conexao->prepare(
        'INSERT INTO veiculos (placa, marca, modelo, ano, quilometragem, cliente_id)
         VALUES (?, ?, ?, ?, ?, ?)'
    );

    $cadastro->execute([
        $placa,
        $marca,
        $modelo,
        $ano,
        $quilometragem,
        $clienteId
    ]);

    responderJSON(true, 'Veículo cadastrado com sucesso.', 201);

} catch (Throwable $erro) {
    error_log($erro->getMessage());
    responderJSON(false, 'Erro ao cadastrar o veículo.', 500);
}

?>