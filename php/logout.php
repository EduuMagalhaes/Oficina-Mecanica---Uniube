<?php
require_once __DIR__ . '/functions.php';

session_start();
$_SESSION = [];
session_destroy();

responderJSON(true, 'Logout realizado.', 200);