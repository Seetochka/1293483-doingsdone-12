<?php
/**
 * Выполняет подключение к MySQL
 * @param array $db_connection_data Массив с параметрами подключения к MySQL
 *
 * @return musqli Ресурс соединения или false, если подключение не удалось
 */
function get_db_connection($db_connection_data)
{
    return mysqli_connect(
        $db_connection_data['host'],
        $db_connection_data['user'],
        $db_connection_data['password'],
        $db_connection_data['database']);
}

/**
 * Считает количество задач в проекте
 * @param array $tasks Массив с задачами
 * @param string $project_id Название проекта
 *
 * @return int Число задач для переданного проекта
 */
function count_tasks(array $tasks, string $project_id): int
{
    $count = 0;

    foreach ($tasks as $task) {
        if (strval($task['project_id']) === $project_id) {
            ++$count;
        }
    }

    return $count;
}

/**
 * Проверяет заканчивается ли время выполнения задачи
 * @param array $task Массив с задачей
 *
 * @return bool true если заканчивается, иначе false
 */
function is_important_task(array $task): bool
{
    if (!empty($task['due_date'])) {
        $date_diff = strtotime($task['due_date']) - strtotime('now');
        $hours_count = $date_diff / 3600;

        if ($hours_count <= HOURS_A_DAY) {
            return true;
        }
    }

    return false;
}

/**
 * Проверяет существует ли такой проект
 * @param array $projects Массив с проектами
 * @param int $active_project_id id проверяемого проекта
 *
 * @return bool true если существует, иначе false
 */
function is_project_exist(array $projects, int $active_project_id): bool
{
    $res = false;

    foreach ($projects as $project) {
        if ($active_project_id === $project['id']) {
            $res = true;
        }
    }

    return $res;
}

/**
 * Выполняет SQL запрос к базе данных на основе подготовленного выражения
 * @param mysqli $connection Ресурс соединения
 * @param string $sql_request SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return
 */
function get_prepare_stmt($connection, string $sql_request, array $data = [])
{
    $stmt = db_get_prepare_stmt($connection, $sql_request, $data);

    if (!mysqli_stmt_execute($stmt)) {
        $error_msg = mysqli_error($connection);
        die($error_msg);
    }

    return mysqli_stmt_get_result($stmt);
}

/**
 * Возвращает все записи результата SQL запроса
 * @param mysqli $connection Ресурс соединения
 * @param string $sql_request SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return array | null Массив в случае удачного выполнения SQL запроса или false в случае ошибки
 */
function fetch_all($connection, string $sql_request, array $data = []): ?array
{
    $result = get_prepare_stmt($connection, $sql_request, $data);

    if (!$result) {
        $error_msg = mysqli_error($connection);
        die($error_msg);
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Возвращает ряд результата SQL запроса в качестве ассоциативного массива
 * @param mysqli $connection Ресурс соединения
 * @param string $sql_request SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return array | null Ассоциативный массив в случае удачного выполнения SQL запроса или false в случае ошибки
 */
function fetch_assoc($connection, string $sql_request, array $data = []): ?array
{
    $result = get_prepare_stmt($connection, $sql_request, $data);

    if (!$result) {
        $error_msg = mysqli_error($connection);
        die($error_msg);
    }

    return mysqli_fetch_assoc($result);
}

/**
 * Формирует URL исходя из переданного пути и параметров запроса
 * @param array $params Массив с параметрами запрса
 * @param string $path Адрес страницы
 *
 * @return string Сформираванный URL
 */
function get_query_href(array $params, string $path): string
{
    $current_params = $_GET;
    $merged_params = array_merge($current_params, $params);
    $query = http_build_query($merged_params);

    return $path . ($query ? "?$query" : '');
}

/**
 * Удаляет пробелы в начале и конце строк массива
 * @param array $array Массив
 *
 * @return array Массив, в котором из каждой строки удалили пробелы в начале и конце
 */
function remove_space(array $array): array
{
    return array_map(function ($value) {
        return trim($value);
    }, $array);
}

/**
 * Проверяет, что дата больше или равна текущей
 * @param string $value Содержимое поля дата
 *
 * @return bool true если указанная дата больше или равна текущей, иначе false
 */
function is_date_correct(string $value): bool
{
    if (strtotime($value) >= strtotime('today')) {
        return true;
    }

    return false;
}

/**
 * Проверяет является ли строка корректным email
 * @param string $value Email
 *
 * @return bool true при правильном email, иначе false
 */
function is_email(string $value): bool
{
    return boolval(filter_var($value, FILTER_VALIDATE_EMAIL));
}

/**
 * Проверяет, чтобы в составе пароля были минимум одна цифра и по одной букве верхнего и нижнего регистров
 * @param string $value Пароль
 *
 * @return bool true соответствии пароля условию, иначе false
 */
function is_strong_password(string $value): bool
{
    return boolval(preg_match('/^\S*(?=\S{6,128})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/', $value));
}

/**
 * Валидация заполненности поля
 * @param string $value Содержимое поля Название
 *
 * @return string | null Текст ошибки или null, если валидация пройдена
 */
function validate_field_completion(string $value): ?string
{
    $error_message = null;

    switch (false) {
        case (!empty($value)):
            $error_message = 'Это поле должно быть заполнено';
            break;
    }

    return $error_message;
}

/**
 * Валидация поля Дата выполнения
 * @param string $value Содержимое поля Дата выполнения
 *
 * @return string | null Текст ошибки или null, если валидация пройдена
 */
function validate_due_date(string $value): ?string
{
    $error_message = null;

    switch (false) {
        case (is_date_valid($value)):
            $error_message = 'Введите дату в формате ГГГГ-ММ-ДД';
            break;
        case (is_date_correct($value)):
            $error_message = 'Дата должна быть больше или равна текущей';
            break;
    }

    return $error_message;
}

/**
 * Валидация поля Электронная почта
 * @param string $value Содержимое поля Электронная почта
 *
 * @return string | null Текст ошибки или null, если валидация пройдена
 */
function validate_email(string $value): ?string
{
    $error_message = null;

    switch (false) {
        case (!empty($value)):
            $error_message = 'Это поле должно быть заполнено';
            break;
        case (is_email($value)):
            $error_message = 'Введите корректный Email';
            break;
    }

    return $error_message;
}

/**
 * Валидация поля Пароль
 * @param string $value Содержимое поля Пароль
 *
 * @return string | null Текст ошибки или null, если валидация пройдена
 */
function validate_password(string $value): ?string
{
    $error_message = null;

    switch (false) {
        case (!empty($value)):
            $error_message = 'Это поле должно быть заполнено';
            break;
        case (is_strong_password($value)):
            $error_message = 'Пароль должен содержать не менее 6 символов, 
                в нем должны быть цифры и латинские буквы верхнего и нижнего регистров';
            break;
    }

    return $error_message;
}

/**
 * Валидация массива данных по массиву правил
 * @param array $data_array Массив данных для валидации
 * @param array $rules_array Массив правил валидации
 *
 * @return array Массив с ошибками
 */
function validate(array $data_array, array $rules_array): array
{
    $errors = [];

    foreach ($data_array as $key => $value) {
        if (isset($rules_array[$key])) {
            $rule = $rules_array[$key];
            $errors[$key] = $rule($value);
        }
    }

    return $errors;
}

/**
 * Загружает файл
 * @param array $file Файл, который нужно записать
 *
 * @return string Имя записанного файла или null если записать не удалось
 */
function upload_file(array $file): ?string
{
    $tmp_name = $file['tmp_name'];
    $path = $file['name'];
    $filename = uniqid() . '.' . pathinfo($path, PATHINFO_EXTENSION);

    if (move_uploaded_file($tmp_name, $filename)) {
        return $filename;
    }

    return null;
}
