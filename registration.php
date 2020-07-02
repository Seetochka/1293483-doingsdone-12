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
            return validate_password($value);
        },
        'name' => function($value) {
            return validate_field_completion($value);
        },
    ];

    $errors = validate($user, $rules);

    if (empty($errors['email'])) {
        $errors['email'] = get_sql_user($link, 'email', $user['email']) ? 'Пользователь с таким email уже существует' : '';
    }

    $errors = array_filter($errors);

    if (empty($errors)) {
        $result = create_sql_user($link, $user);

        if ($result) {
            header("Location: /");
            die();
        }
    }
}

$page_content = include_template('registration.php', [
    'user' => $user ?? [],
    'errors' => $errors ?? [],
]);
$layout_content = include_template('layout.php', [
    'page_content' => $page_content,
    'title' => 'Дела в порядке: регистрация',
]);

print $layout_content;
