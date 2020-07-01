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

$user_data = $_SESSION['user'];

$active_project_id = filter_input(INPUT_GET, 'project-id', FILTER_VALIDATE_INT);
$projects = get_sql_projects($link, ['user_id = ?' => $user_data['id']]);

if (!empty($active_project_id)) {
    $res = is_project_exist($projects, $active_project_id);

    if (!$res) {
        header("HTTP/1.0 404 Not Found");
        die();
    }
}

$show_complete_tasks = filter_input(INPUT_GET, 'show_completed');
$complete_tasks = filter_input(INPUT_GET, 'complete_task');

if (!empty($complete_tasks)) {
    $task = get_sql_task($link, $complete_tasks);
    toggle_status($link, $task);
    $path = $_SERVER['HTTP_REFERER'] ?? '/';
    header("Location: $path");
    die();
}

$search_query = trim(filter_input(INPUT_GET, 'q'));

if (!empty($search_query)) {
    $tasks = get_sql_tasks($link, ['user_id = ?' => $user_data['id'], 'q' => $search_query]);
} else {
    $query_param['user_id = ?'] = $user_data['id'];
    $filter = filter_input(INPUT_GET, 'filter');

    switch ($filter) {
        case 'today':
            $query_param['due_date = ?'] = date('Y-m-d');
            break;
        case 'tomorrow':
            $query_param['due_date = ?'] = date('Y-m-d', strtotime('tomorrow'));
            break;
        case 'overdue':
            $query_param['status = ?'] = 0;
            $query_param['due_date < ?'] = date('Y-m-d');
            break;
    }

    if (!empty($active_project_id)) {
        $query_param['project_id = ?'] = $active_project_id;
    }

    $tasks = get_sql_tasks($link, $query_param);
}

$all_tasks = get_sql_tasks($link, ['user_id = ?' => $user_data['id']]);

foreach ($tasks as $key => $task) {
    $tasks[$key]['important'] = is_important_task($task);
}

$page_content = include_template('main.php', [
    'tasks' => $tasks,
    'show_complete_tasks' => $show_complete_tasks,
    'projects' => $projects,
    'all_tasks' => $all_tasks,
    'active_project_id' => $active_project_id,
    'search_query' => $search_query,
    'filter' => $filter ?? null,
]);

$layout_content = include_template('layout.php', [
    'page_content' => $page_content,
    'title' => 'Дела в порядке: главная',
]);

print $layout_content;
