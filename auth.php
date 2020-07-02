<?php
require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';
require_once 'sql-queries.php';
require_once 'constants.php';

if (isset($_SESSION['user'])) {
    header("Location: /");
    die();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = remove_space($_POST);
    $rules = [
        'email' => function($value) {
            return validate_email($value);
        },
        'password' => function($value) {
            return validate_field_completion($value);
        },
    ];

    $errors = array_filter(validate($user, $rules));

    if (!count($errors)) {
        $user_db = get_sql_user($link, 'email', $user['email']);

        if (!empty($user_db) && password_verify($user['password'], $user_db['password'])) {
            $_SESSION['user'] = $user_db;
        } else {
            $errors['email'] = 'Вы ввели неверный email/пароль';
            $errors['password'] = 'Вы ввели неверный email/пароль';
        }
    }

    if (!count($errors)) {
        header('Location: /');
        die();
    }
}

$page_content = include_template('auth.php', [
    'user' => $user ?? [],
    'errors' => $errors ?? [],
]);

$layout_content = include_template('layout.php', [
    'page_content' => $page_content,
    'title' => 'Дела в порядке: аутентификация',
]);

print $layout_content;
