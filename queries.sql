USE doingsdone;

/*Добавляет пользователей*/
INSERT INTO users (dt_add, email, name, password)
VALUES ('2019-05-26 18:49', 'vladik@gmail.com', 'Владик', 'qwerty'),
       ('2019-10-07 22:11', 'svetlana@gmail.com', 'Светлана', '$2y$10$7wC8zkdanq/.1FmGw0xe8elKLNviCHl2jPXLD69FknDbx0Kh1xBnO');

/*Добавляет список проектов*/
INSERT INTO projects (title, user_id)
VALUES ('Входящие', 2),
       ('Учеба', 2),
       ('Работа', 2),
       ('Домашние дела', 2),
       ('Авто', 2);

/*Добавляет список задач*/
INSERT INTO tasks (dt_add, status, title, due_date, user_id, project_id)
VALUES ('2020-04-28 22:11', 0, 'Собеседование в IT компании', '2020-06-07', 2, 3),
       ('2020-04-29 21:14', 0, 'Выполнить тестовое задание', '2020-06-06', 2, 3),
       ('2020-05-11 13:22', 0, 'Сделать задание первого раздела', '2020-06-05', 2, 2),
       ('2020-05-17 18:05', 0, 'Встреча с другом', '2020-06-11', 2, 1);

INSERT INTO tasks (dt_add, status, title, user_id, project_id)
VALUES ('2020-05-25 09:54', 0, 'Купить корм для кота', 2, 4),
       ('2020-05-28 12:37', 0, 'Заказать роллы', 2, 4);

/*Получает список из всех проектов для одного пользователя*/
SELECT p.id, p.title FROM projects p WHERE p.user_id = 2;

/*Получает список из всех задач для одного проекта*/
SELECT t.id, t.dt_add, t.status, t.title, t.file, t.due_date, t.user_id, t.project_id FROM tasks t WHERE t.project_id = 3;

/*Помечает задачу как выполненную*/
UPDATE tasks SET status = 1 WHERE id = 3;

/*Обновляет название задачи по её идентификатору*/
UPDATE tasks SET title = 'Заказать пиццу' WHERE id = 6;
