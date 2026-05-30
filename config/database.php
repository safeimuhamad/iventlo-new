<?php

return [
    'host' => getenv('DB_HOST') ?: 'localhost',
    'dbname' => getenv('DB_DATABASE') ?: 'iventlo-new',
    'username' => getenv('DB_USERNAME') ?: 'root',
    'password' => getenv('DB_PASSWORD') ?: '',
    'charset' => getenv('DB_CHARSET') ?: 'utf8mb4'
];
