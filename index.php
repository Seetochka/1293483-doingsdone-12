<?php
require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';
require_once 'sql-queries.php';
require_once 'constants.php';

if (!isset($_SESSION['user'])) {
    $page_content = include_template('guest.php', []);

    $layout_content = include_template('layout.php', [
        'page_content' => $page_content,
        'title' => 'Дела в порядке: главная',
    ]);

    print $layout_content;
    die();
}

date_default_timezone_set('Europe/Moscow');

$show_complete_tasks = rand(0, 1);
$user_data = $_SESSION['user'];

$active_project_id = filter_input(INPUT_GET, 'project-id', FILTER_VALIDATE_INT);
$projects = get_sql_projects($link, ['user_id = ?' => $user_data['id']]);

if (!empty($active_project_id)) {
    $res = false;

    foreach ($projects as $project) {
        if ($active_project_id === $project['id']) {
            $res = true;
        }
    }

    if (!$res) {
        header("HTTP/1.0 404 Not Found");
        die();
    }
}

$query_param['user_id = ?'] = $user_data['id'];

if (!empty($active_project_id)) {
    $query_param['project_id = ?'] = $active_project_id;
}

$all_tasks = get_sql_tasks($link, ['user_id = ?' => $user_data['id']]);
$tasks = get_sql_tasks($link, $query_param);

foreach ($tasks as $key => $task) {
    if (!empty($task['due_date'])) {
        $date_diff = strtotime($task['due_date']) - strtotime('now');
        $hours_count = $date_diff / 3600;

        if ($hours_count <= HOURS_A_DAY) {
            $tasks[$key]['important'] = true;
        } else {
            $tasks[$key]['important'] = false;
        }
    }
}

$page_content = include_template('main.php', [
    'tasks' => $tasks,
    'show_complete_tasks' => $show_complete_tasks,
    'projects' => $projects,
    'all_tasks' => $all_tasks,
    'active_project_id' => $active_project_id,
]);

$layout_content = include_template('layout.php', [
    'page_content' => $page_content,
    'title' => 'Дела в порядке: главная',
]);

print $layout_content;
