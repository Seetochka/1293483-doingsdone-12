<?php
/**
 * Считает количество задач в проекте
 *
 * @param array $tasks Массив с задачами
 * @param string $project_id Название проекта
 *
 * @return int Число задач для переданного проекта
 */
function count_tasks(array $tasks, string $project_id): int {
    $count = 0;

    foreach ($tasks as $task) {
        if (strval($task['project_id']) === $project_id) {
            ++$count;
        }
    }

    return $count;
}

/**
 * Выполняет SQL запрос к базе данных на основе подготовленного выражения
 *
 * @param mysqli $connection Ресурс соединения
 * @param string $sql_request SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return
 */
function get_prepare_stmt($connection, string $sql_request, array  $data = []) {
    $stmt = db_get_prepare_stmt($connection, $sql_request, $data);

    if (!mysqli_stmt_execute($stmt)) {
        $error_msg = mysqli_error($connection);
        die($error_msg);
    }

    return mysqli_stmt_get_result($stmt);
}

/**
 * Возвращает все записи результата SQL запроса
 *
 * @param mysqli $connection Ресурс соединения
 * @param string $sql_request SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return array | null Массив в случае удачного выполнения SQL запроса или false в случае ошибки
 */
function fetch_all($connection, string $sql_request, array  $data = []): ?array {
    $result = get_prepare_stmt($connection, $sql_request, $data);

    if (!$result) {
        $error_msg = mysqli_error($connection);
        die($error_msg);
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Возвращает ряд результата SQL запроса в качестве ассоциативного массива
 *
 * @param mysqli $connection Ресурс соединения
 * @param string $sql_request SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return array | null Ассоциативный массив в случае удачного выполнения SQL запроса или false в случае ошибки
 */
function fetch_assoc($connection, string $sql_request, array  $data = []): ?array {
    $result = get_prepare_stmt($connection, $sql_request, $data);

    if (!$result) {
        $error_msg = mysqli_error($connection);
        die($error_msg);
    }

    return mysqli_fetch_assoc($result);
}

/**
 * Формирует URL исходя из переданного пути и параметров запроса
 *
 * @param array $params Массив с параметрами запрса
 * @param string $path Адрес страницы
 *
 * @return string Сформираванный URL
 */
function get_query_href(array $params, string $path): string {
    $current_params = $_GET;
    $merged_params = array_merge($current_params, $params);
    $query = http_build_query($merged_params);

    return $path . ($query ? "?$query" : '');
}
