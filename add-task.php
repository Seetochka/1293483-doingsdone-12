<?php
require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';
require_once 'sql-queries.php';

date_default_timezone_set('Europe/Moscow');

$user_name = 'Светлана';
$user_id = 2;

$projects = get_sql_projects($link, ['user_id = ?' => $user_id]);
$all_tasks = get_sql_tasks($link, ['user_id = ?' => $user_id]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task = remove_space($_POST);
    $rules['title'] = function ($value) {
        return validate_field_completion($value);
    };

    if (!empty($task['due_date'])) {
        $rules['due_date'] = function ($value) {
            return validate_due_date($value);
        };
    }

    $errors = validate($task, $rules);
    $errors['project_id'] = get_sql_projects($link, ['user_id = ?' => $user_id, 'id = ?' => $task['project_id']]) ?
        '' : 'Данный проект не существует';
    $errors = array_filter($errors);

    if (!count($errors)) {
        $task = array_filter($task);
        $result = create_sql_task($link, $user_id, $task);

        if ($result) {
            header("Location: /index.php");
            die();
        }

        mysqli_error($link);
    }
}

$page_content = include_template('add-task.php', [
    'projects' => $projects,
    'task' => $task ?? [],
    'errors' => $errors ?? [],
]);
$layout_content = include_template('layout.php', [
    'projects' => $projects,
    'all_tasks' => $all_tasks,
    'page_content' => $page_content,
    'title' => 'Дела в порядке: добавление задачи',
    'user_name' => $user_name,
]);

print $layout_content;
