<?php

// Функция getenv() читает переменные окружения, переданные из docker-compose
// Мы используем оператор ?? для установки значений по умолчанию,
// если переменные окружения по какой-то причине не установлены.

$host = getenv('DB_HOST') ?? 'mysql';
$name = getenv('DB_NAME') ?? 'yii2basic';
$user = getenv('DB_USER') ?? 'root';
$password = getenv('DB_PASSWORD') ?? '';
$port = getenv('DB_PORT') ?? '3306';

return [
    'class' => 'yii\db\Connection',
    'dsn' => "mysql:host={$host};port={$port};dbname={$name}",
    'username' => $user,
    'password' => $password,
    'charset' => 'utf8',
];