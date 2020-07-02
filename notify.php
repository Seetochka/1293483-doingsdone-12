<?php
require_once 'vendor/autoload.php';
require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';
require_once 'sql-queries.php';

$transport = (new Swift_SmtpTransport('phpdemo.ru', 25))
    ->setUsername('keks@phpdemo.ru')
    ->setPassword('htmlacademy');

$mailer = new Swift_Mailer($transport);

$tasks = get_sql_tasks($link, ['t.status = ?' => 0, 't.due_date = ?' => date('Y-m-d')]);
$users_id = [];
$message_texts = [];

foreach ($tasks as $task) {
    if (!in_array($task['user_id'], $users_id)) {
        $users_id[] = $task['user_id'];
        $message_texts[$task['user_id']] = $task['title'] . ' на ' . date_format(date_create($task['due_date']), 'Y-m-d');
    } else {
        $message_texts[$task['user_id']] .= ', ' . $task['title'] . ' на ' . date_format(date_create($task['due_date']), 'Y-m-d');
    }
}

foreach ($users_id as $users_id) {
    $user_data = get_sql_user($link, 'id', $users_id);

    $message = new Swift_Message('Уведомление от сервиса «Дела в порядке»');
    $message->setTo([$user_data['email'] => $user_data['name']]);
    $message->setBody('Уважаемый, ' . $user_data['name'] . '. У вас запланирована задача ' . $message_texts[$users_id]);
    $message->setFrom(['keks@phpdemo.ru' => 'doingsdone']);

    $res = $mailer->send($message);
}
