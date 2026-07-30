<?php
require_once __DIR__ . '/dotenv.php';
try {

    loadEnv(__DIR__ . '/../.env');

    $host = $_ENV['DB_HOST'];
    $name = $_ENV['DB_NAME_NEGOSYOCENTER'];
    $user = $_ENV['DB_USER_NEGOSYOCENTER'];
    $pass =  $_ENV['DB_PASS_NEGOSYOCENTER'];

    $dsn = 'mysql:host=' . $host . ';dbname=' . $name . ';charset=utf8mb4';

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $con = new PDO($dsn, $user, $pass, $options);

} catch (PDOException $e) {
    die('Database Connection Failed: ' . $e->getMessage());
}