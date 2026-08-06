<?php

define('DB_HOST', '186.209.113.107');
define('DB_USER', 'dema5738_apasbs');
define('DB_PASS', 'Dema@1973');
define('DB_NAME', 'dema5738_apasbs');
define('DB_CHARSET', 'utf8mb4');

function getConnection(): mysqli {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        error_log('Erro de conexão: ' . $conn->connect_error);
        die(json_encode(['erro' => 'Falha na conexão com o banco de dados.']));
    }

    $conn->set_charset(DB_CHARSET);
    return $conn;
}

function getPDO(): PDO {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    return new PDO($dsn, DB_USER, DB_PASS, $options);
}
