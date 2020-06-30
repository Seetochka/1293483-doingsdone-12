<?php
/**
 * Получает массив с проектами из БД по id пользователя
 *
 * @param mysqli $connection Ресурс соединения
 * @param array $params Массив с параметрами запроса
 *
 * @return array Массив с проектами
 */
function get_sql_projects($connection, array $params): array
{
    $sql_projects = 'SELECT p.id, p.title FROM projects p';

    if (!empty($params)) {
        $sql_projects .= ' WHERE ' . implode(' AND ', array_keys($params));
    }

    return fetch_all($connection, $sql_projects, $params);
}

/**
 * Получает массив с задачами из БД
 *
 * @param mysqli $connection Ресурс соединения
 * @param array $params Массив с параметрами запроса
 *
 * @return array Массив с задачами
 */
function get_sql_tasks($connection, array $params): array
{
    $sql_tasks = 'SELECT t.id, t.dt_add, t.status, t.title, t.file, t.due_date, t.user_id, t.project_id FROM tasks t';

    if (count($params) > 0 && !array_key_exists('q', $params)) {
        $sql_tasks .= ' WHERE ' . implode(' AND ', array_keys($params));
    }

    if (array_key_exists('q', $params)) {
        $sql_tasks .= ' WHERE t.user_id = ? AND MATCH(t.title) AGAINST(?)';

        return fetch_all($connection, $sql_tasks, $params);
    }

    $sql_tasks .= ' ORDER BY dt_add DESC';

    return fetch_all($connection, $sql_tasks, $params);
}

/**
 * Создает новую задачу
 *
 * @param mysqli $connection Ресурс соединения
 * @param int $user_id id пользователя
 * @param array $task Массив данными задачи
 *
 * @return bool true если задача сохранена в БД, иначе false
 */
function create_sql_task($connection, int $user_id, array $task): bool
{
    $sql_task = 'INSERT INTO tasks (dt_add, title, project_id';

    $sql_task .= array_key_exists('due_date', $task) ? ', due_date' : '';

    if (!empty($_FILES['file']['name'])) {
        $task['file'] = upload_file($_FILES['file']);
        $sql_task .= ', file';
    }

    $task['user_id'] = $user_id;

    $sql_task .= ', user_id) VALUES (NOW(), ?, ?, ?';
    $sql_task .= array_key_exists('due_date', $task) ? ', ?' : '';
    $sql_task .= array_key_exists('file', $task) ? ', ?' : '';
    $sql_task .= ')';

    $stmt_task = db_get_prepare_stmt($connection, $sql_task, $task);

    return mysqli_stmt_execute($stmt_task);
}

/**
 * Получает данные пользователя по email
 *
 * @param mysqli $connection Ресурс соединения
 * @param string $user_email email пользователя
 *
 * @return array | null Массив с данными пользователя или null, если пользователя с таким email нет
 */
function get_sql_user($connection, string $user_email): ?array
{
    $sql_user = 'SELECT u.id, u.dt_add, u.email, u.name, u.password FROM users u WHERE u.email = ?';

    return fetch_assoc($connection, $sql_user, [$user_email]);
}

/**
 * Создает нового пользователя
 *
 * @param mysqli $connection Ресурс соединения
 * @param array $user_data Массив с данными пользователя
 *
 * @return bool true если пользователь сохранен в БД, иначе false
 */
function create_sql_user($connection, array $user_data): bool
{
    $user_data['password'] = password_hash($user_data['password'], PASSWORD_DEFAULT);

    $sql_user = 'INSERT INTO users (dt_add, email, password, name) VALUE (NOW(), ?, ?, ?)';

    $stmt_user = db_get_prepare_stmt($connection, $sql_user, $user_data);

    return mysqli_stmt_execute($stmt_user);
}