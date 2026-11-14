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

function validarCPF($cpf) {
    $cpf = preg_replace("/\D/", "", $cpf);
    return strlen($cpf) === 11;
}

function validarTelefone($telefone) {
    $telefone = preg_replace("/\D/", "", $telefone);
    return strlen($telefone) >= 10;
}

function validarEmail($email) {
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

function validarDataNaoFutura($data) {
    return $data <= date("Y-m-d");
}

function validarNumeroNaoNegativo($valor) {
    return is_numeric($valor) && $valor >= 0;
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