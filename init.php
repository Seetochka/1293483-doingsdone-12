<?php
require_once 'db.php';
require_once 'functions.php';

error_reporting(E_ALL);
ini_set('error_log', __DIR__ . '/php-errors.log');
error_log('Запись в лог', 0);

$link = get_db_connection($db);
mysqli_set_charset($link, "utf8");

if (!$link) {
    header("HTTP/1.0 500 Internal Server Error");
    $error_msg = 'Не удалось выполнить подключение к серверу: ' . mysqli_connect_error();
    die($error_msg);
}
