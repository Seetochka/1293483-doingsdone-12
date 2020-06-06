<?php
require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';
require_once 'sql-queries.php';

date_default_timezone_set('Europe/Moscow');

define('HOURS_A_DAY', 24);
$show_complete_tasks = rand(0, 1);

$user_name = 'Светлана';
$user_id = 2;

$projects = get_sql_projects($link, $user_id);
$tasks = get_sql_tasks($link, $user_id);

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
    'projects' => $projects,
    'tasks' => $tasks,
    'show_complete_tasks' => $show_complete_tasks,
]);
$layout_content = include_template('layout.php', [
    'page_content' => $page_content,
    'title' => 'Дела в порядке',
    'user_name' => $user_name,
]);

print $layout_content;
