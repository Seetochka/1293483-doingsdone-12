<?php
require_once 'helpers.php';

date_default_timezone_set('Europe/Moscow');

// показывать или нет выполненные задачи
define('HOURS_A_DAY', 24);
$show_complete_tasks = rand(0, 1);

$user_name = 'Светлана';

$projects = ['Входящие', 'Учеба', 'Работа', 'Домашние дела', 'Авто'];
$tasks = [
    [
        'task' => 'Собеседование в IT компании',
        'date' => '07.06.2020',
        'category' => 'Работа',
        'completed' => false,
    ],
    [
        'task' => 'Выполнить тестовое задание',
        'date' => '06.06.2020',
        'category' => 'Работа',
        'completed' => false,
    ],
    [
        'task' => 'Сделать задание первого раздела',
        'date' => '05.06.2020',
        'category' => 'Учеба',
        'completed' => true,
    ],
    [
        'task' => 'Встреча с другом',
        'date' => '11.06.2020',
        'category' => 'Входящие',
        'completed' => false,
    ],
    [
        'task' => 'Купить корм для кота',
        'date' => null,
        'category' => 'Домашние дела',
        'completed' => false,
    ],
    [
        'task' => 'Заказать пиццу',
        'date' => null,
        'category' => 'Домашние дела',
        'completed' => false,
    ],
];

/**
 * Считает количество задач в проекте
 * @param array $tasks Массив с задачами
 * @param string $project_name Название проекта
 * @return int Число задач для переданного проекта
 */
function count_tasks(array $tasks, string $project_name): int {
    $count = 0;

    foreach ($tasks as $task) {
        if ($task['category'] === $project_name) {
            ++$count;
        }
    }

    return $count;
}

foreach ($tasks as $key => $task) {
    if (!empty($task['date'])) {
        $date_diff = strtotime($task['date']) - strtotime('now');
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
