<?php
require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';
require_once 'sql-queries.php';

if (!isset($_SESSION['user'])) {
    header("Location: /");
    die();
}

$user_data = $_SESSION['user'];
$projects = get_sql_projects($link, ['user_id = ?' => $user_data['id']]);
$all_tasks = get_sql_tasks($link, ['user_id = ?' => $user_data['id']]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project = remove_space($_POST);
    $rules['title'] = function ($value) {
        return validate_field_completion($value);
    };

    $errors = array_filter(validate($project, $rules));

    if (!count($errors)) {
        $errors['title'] = get_sql_projects($link, [
            'user_id = ?' => $user_data['id'],
            'title = ?' => $project['title']
        ]) ? 'Данный проект уже существует' : '';
    }

    $errors = array_filter($errors);

    if (!count($errors)) {
        $result = create_sql_project($link, $user_data['id'], $project['title']);

        if ($result) {
            header("Location: /");
            die();
        }

        mysqli_error($link);
    }
}

$page_content = include_template('add-project.php', [
    'projects' => $projects,
    'all_tasks' => $all_tasks,
    'new_project' => $project ?? [],
    'errors' => $errors ?? [],
]);

$layout_content = include_template('layout.php', [
    'page_content' => $page_content,
    'title' => 'Дела в порядке: добавление задачи',
]);

print $layout_content;
