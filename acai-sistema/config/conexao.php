<?php
// ============================================================
//  config/conexao.php
//  C:\xampp\htdocs\acai-sistema\config\conexao.php
// ============================================================

define('DB_HOST',    'localhost');
define('DB_NAME',    'pontoAcai');
define('DB_USER',    'root');   // padrão XAMPP
define('DB_PASS',    '');       // padrão XAMPP (sem senha)
define('DB_CHARSET', 'utf8mb4');

function conectar(): PDO {
    static $pdo = null;

    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST
             . ";dbname=" . DB_NAME
             . ";charset=" . DB_CHARSET;

        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }

    return $pdo;
}
