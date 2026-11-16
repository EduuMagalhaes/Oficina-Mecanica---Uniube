<?php

function validarNome(string $nome) {

    $nomeClean = trim($nome);
    $nomeClean = preg_replace('/\s+/', ' ', $nomeClean);

    if ($nomeClean === '') responderJSON(false, 'Nome obrigatório.', 400);

    $tamanhoNome = mb_strlen($nomeClean, 'UTF-8');
    if ($tamanhoNome > 150 || $tamanhoNome < 2) responderJSON(false, 'Tamanho do nome inválido.', 400);
   
    // - ^[\p{L}] : Começa obrigatoriamente com uma letra
    // - [\p{L}\s\'.-]* : Permite letras (com acentos), espaços, apóstrofos, pontos e hífens
    // - [\p{L}.]$ : Termina obrigatoriamente com uma letra ou ponto
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

function validarPlaca($placa) {
    $placa = strtoupper(preg_replace("/[^A-Z0-9]/", "", $placa));
    $regex = "/^[A-Z]{3}[0-9][A-Z0-9][0-9]{2}$/";
    return preg_match($regex, $placa);
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

    if ($objData > $hoje) {
        responderJSON(false, 'A data não pode ser futura.', 400);
    }

    return $dataClean;
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

function validarAnoVeiculo($ano) {
    return is_numeric($ano) && $ano >= 1950 && $ano <= date("Y") + 1;
}

function responderJSON($sucesso, $mensagem, $status) {
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

?>