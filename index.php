<?php
require_once 'helpers.php';

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

$user_name = 'Светлана';

$projects = ['Входящие', 'Учеба', 'Работа', 'Домашние дела', 'Авто'];
$tasks = [
    [
        'task' => 'Собеседование в IT компании',
        'date' => '01.12.2019',
        'category' => 'Работа',
        'completed' => false,
    ],
    [
        'task' => 'Выполнить тестовое задание',
        'date' => '25.12.2019',
        'category' => 'Работа',
        'completed' => false,
    ],
    [
        'task' => 'Сделать задание первого раздела',
        'date' => '21.12.2019',
        'category' => 'Учеба',
        'completed' => true,
    ],
    [
        'task' => 'Встреча с другом',
        'date' => '22.12.2019',
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
