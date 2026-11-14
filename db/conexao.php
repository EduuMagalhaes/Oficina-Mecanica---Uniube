<?php

$servidor = 'localhost';
$banco = 'OficinaMecanica';

$conexao = new PDO(
    "sqlsrv:Server=$servidor;Database=$banco;Encrypt=yes;TrustServerCertificate=yes",
    null,
    null,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
);