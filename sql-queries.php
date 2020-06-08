<?php
/**
 * Получает массив с проектами из БД по id пользователя
 *
 * @param mysqli $connection Ресурс соединения
 * @param int $user_id id пользователя
 *
 * @return array Массив с проектами
 */
function get_sql_projects($connection, int $user_id): array {
    $sql_projects = 'SELECT p.id, p.title FROM projects p WHERE p.user_id = ?';

    return fetch_all($connection, $sql_projects, [$user_id]);
}

/**
 * Получает массив с задачами из БД
 *
 * @param mysqli $connection Ресурс соединения
 * @param array $params Массив с параметрами запроса
 *
 * @return array Массив с задачами
 */
function get_sql_tasks($connection, array $params): array {
    $sql_tasks = 'SELECT t.id, t.dt_add, t.status, t.title, t.file, t.due_date, t.user_id, t.project_id FROM tasks t';

    if (!empty($params)) {
        $sql_tasks .= ' WHERE ' . implode(' AND ', array_keys($params));
    }

    return fetch_all($connection, $sql_tasks, $params);
}
