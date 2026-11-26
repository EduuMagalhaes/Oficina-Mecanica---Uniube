<?php

function validarNome(string $nome) {

    $nomeClean = trim($nome);
    $nomeClean = preg_replace('/\s+/', ' ', $nomeClean);

    if ($nomeClean === '') responderJSON(false, 'Nome obrigatório.', 400);

    $tamanhoNome = mb_strlen($nomeClean, 'UTF-8');
    if ($tamanhoNome > 150 || $tamanhoNome < 2) responderJSON(false, 'Tamanho do nome inválido.', 400);

    $regex = '/^[\p{L}][\p{L}\s\'.-]*[\p{L}.]$/u';

    if (!preg_match($regex, $nomeClean)) responderJSON(false, 'Nome inválido.', 400);

    return $nomeClean;
}

function validarCPF(string $cpf) {
    $cpfClean = preg_replace('/\D/', '', $cpf);

    if (strlen($cpfClean) !== 11) responderJSON(false, 'O CPF deve conter 11 dígitos.', 400);

    for ($t = 9; $t < 11; $t++) {
        $soma = 0;
        for ($c = 0; $c < $t; $c++) {
            $soma += (int) $cpfClean[$c] * (($t + 1) - $c);
        }

        $digito = ((10 * $soma) % 11) % 10;

        if ((int) $cpfClean[$t] !== $digito) {
            responderJSON(false, 'CPF inválido.', 400);
        }
    }

    return $cpfClean;    
}

function validarTelefone(string $telefone) {
    $telClean = preg_replace("/\D/", "", $telefone);
    
    $tamanho = strlen($telClean);
    if ($tamanho !== 10 && $tamanho !== 11) responderJSON(false, 'Telefone inválido. Informe DDD + número.', 400);

    if (preg_match('/^(\d)\1+$/', $telClean)) responderJSON(false, 'Telefone inválido.', 400);

    return $telClean;    
}

function validarEmail(string $email) {
    $emailClean = mb_strtolower(trim($email), 'UTF-8');

    if (strlen($emailClean) > 254) responderJSON(false, 'Tamanho do e-mail inválido.', 400);
    if (filter_var($emailClean, FILTER_VALIDATE_EMAIL) === false) responderJSON(false, 'E-mail inválido.', 400);

    return $emailClean;
}

function validarSenha(string $senha) {
    $tamanho = mb_strlen($senha, 'UTF-8');

    if ($tamanho < 8 || $tamanho > 30) responderJSON(false, 'Tamanho da senha inválido.', 400);

    if (!preg_match('/[a-z]/', $senha)) responderJSON(false, 'A senha deve conter pelo menos uma letra minúscula.', 400);
    if (!preg_match('/[A-Z]/', $senha)) responderJSON(false, 'A senha deve conter pelo menos uma letra maiúscula.', 400);
    if (!preg_match('/[0-9]/', $senha)) responderJSON(false, 'A senha deve conter pelo menos um número.', 400);
    if (!preg_match('/[\W_]/', $senha)) responderJSON(false, 'A senha deve conter pelo menos um caractere especial.', 400);

    return $senha;
}

function validarPlaca(string $placa) {
    $placaClean = strtoupper(trim($placa));

    if ($placaClean === '') responderJSON(false, 'Placa obrigatória.', 400);

    $mercossul = preg_match('/^[A-Z]{3}[0-9][A-Z][0-9]{2}$/', $placaClean) === 1;
    $antiga = preg_match('/^[A-Z]{3}[0-9]{4}$/', $placaClean) === 1;

    if (!$mercossul && !$antiga) responderJSON(false, 'Placa invalida. Use o formato ABC1234 ou ABC1D23.', 400);

    return $placaClean;
}

function validarDataNaoFutura(string $data) {
    $dataClean = trim($data);

    date_default_timezone_set('America/Sao_Paulo');

    $objData = DateTimeImmutable::createFromFormat('Y-m-d', $dataClean);
    $erros = DateTimeImmutable::getLastErrors();

    if (
        $objData === false ||
        $objData->format('Y-m-d') !== $dataClean ||
        ($erros !== false && ($erros['warning_count'] > 0 || $erros['error_count'] > 0))
    ) {
        responderJSON(false, 'Data inválida.', 400);
    }

    $hoje = new DateTimeImmutable('today');

    if ($objData->format('Y-m-d') > $hoje->format('Y-m-d')) {
        responderJSON(false, 'A data não pode ser futura.', 400);
    }

    return $objData->format('Ymd H:i:s.v');
}

function validarNumeroNaoNegativo(string $valor) {    
    $valorClean = trim($valor);
    
    if ($valorClean === '' || !preg_match('/^-?\d+(\.\d+)?$/', $valorClean)) {
        responderJSON(false, "$campo deve ser um número válido.", 400);
    }

    $numero = (float) $valorClean;

    if ($numero < 0) {
        responderJSON(false, "$campo não pode ser negativo.", 400);
    }

    return $numero;
}

function validarAnoVeiculo(string $ano) {
    $anoClean = trim($ano);

    if ($anoClean === '') responderJSON(false, 'Ano do veículo não informado.', 400);

    if (!preg_match('/^\d{4}$/', $anoClean)) responderJSON(false, 'Ano do veículo inválido.', 400);

    $anoInt = (int) $anoClean;

    if ($anoInt < 1950 || $anoInt > date('Y') + 1) {
        responderJSON(false, 'Ano do veículo fora do intervalo permitido.', 400);
    }

    return $anoInt;
}

function responderJSON(bool $sucesso, string $mensagem, int $status) {
    http_response_code($status);

    echo json_encode([
        'sucesso' => $sucesso,
        'mensagem' => $mensagem
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

function validarCamposTexto(array $dados, array $campos) {
    foreach ($campos as $campo) {
        if (!isset($dados[$campo]) || !is_string($dados[$campo])) {
            responderJSON(
                false,
                "Dados inválidos na solicitação.",
                400
            );
        }
    }
}

function validarCodigo(string $codigo) {
    $codigo = trim($codigo);

    if ($codigo === '') responderJSON(false, "$codigo não informado.", 400);
    
    if(preg_match('/^[A-Za-z0-9]+$/', $codigo) !== 1) responderJSON(false, "$codigo deve conter apenas letras e números.", 400);

    return $codigo;
}

function validarPrecoVenda(string $preco_venda, string $preco_custo) {
    $valor = validarNumeroNaoNegativo($preco_venda);
    if ($valor <= $preco_custo) responderJSON(false, "Valor de venda nao pode ser menor que valor de custo", 400);
    return $valor;
}

function validarMarcaModelo(string $marcaModelo) {
    $marcaModeloClean = trim($marcaModelo);

    if ($marcaModeloClean === '') responderJSON(false, 'Marca ou modelo não informada.', 400);    

    if (!preg_match('/^[A-Za-zÀ-ÿ0-9][A-Za-zÀ-ÿ0-9\s\-\.&]*$/u', $marcaModeloClean)) {
        responderJSON(false, 'A marca ou modelo contém caracteres inválidos.', 400);
    }

    return $marcaModeloClean;
}

function validarQuilometragem(string $quilometragem) {
    $quilometragemClean = trim($quilometragem);

    if ($quilometragemClean === '') responderJSON(false, 'Quilometragem não informada.', 400);

    if (!preg_match('/^\d+$/', $quilometragemClean)) responderJSON(false, 'Quilometragem inválida.', 400);

    return (int) $quilometragemClean;
}

function validarId(string $id, string $campo) {
    $idClean = trim($id);

    if ($idClean === '') responderJSON(false, "$campo não informado.", 400);

    if (!preg_match('/^\d+$/', $idClean)) responderJSON(false, '$campo inválido.', 400);

    return (int) $idClean;
}

function validarStatus(string $status) {
    $statusClean = trim($status);

    $statusPermitidos = ['Aberta', 'Em andamento', 'Aguardando peça', 'Finalizada'];

    if (!in_array($statusClean, $statusPermitidos, true)) {
        responderJSON(false, 'Status inválido.', 400);
    }

    return $statusClean;
}

function exigirLogin() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['usuario_id'])) {
        responderJSON(false, 'Acesso não autorizado.', 401);
    }
}

?>