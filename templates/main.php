<section class="content__side">
    <h2 class="content__side-heading">Проекты</h2>
    <nav class="main-navigation">
        <ul class="main-navigation__list">
            <?php foreach ($projects as $project): ?>
                <li class="main-navigation__list-item">
                    <a class="main-navigation__list-item-link <?= !empty($active_project_id) && $active_project_id === $project['id'] ? 'main-navigation__list-item--active' : ''; ?>"
                       href="<?= get_query_href(['project-id' => $project['id'], 'q' => null, 'filter' => null], '/index.php'); ?>"> <?= htmlspecialchars($project['title']); ?></a>
                    <span class="main-navigation__list-item-count"><?= count_tasks($all_tasks, $project['id']); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <a class="button button--transparent button--plus content__side-button"
       href="add-project.php" target="project_add">Добавить проект</a>
</section>
<main class="content__main">
    <h2 class="content__main-heading">Список задач</h2>
    <form class="search-form" action="index.php" method="get" autocomplete="off">
        <input class="search-form__input" type="text" name="q" value="<?= !empty($search_query) ? $search_query : '' ?>" placeholder="Поиск по задачам">
        <input class="search-form__submit" type="submit" name="" value="Искать">
    </form>
    <div class="tasks-controls">
        <nav class="tasks-switch">
            <a href="<?= get_query_href(['project-id' => null, 'q' => null, 'filter' => null], '/'); ?>"
               class="tasks-switch__item <?= empty($filter) ? 'tasks-switch__item--active' : '' ?>">Все задачи</a>
            <a href="<?= get_query_href(['project-id' => null, 'q' => null, 'filter' => 'today'], '/'); ?>"
               class="tasks-switch__item <?= !empty($filter) && $filter === 'today' ? 'tasks-switch__item--active' : '' ?>">Повестка дня</a>
            <a href="<?= get_query_href(['project-id' => null, 'q' => null, 'filter' => 'tomorrow'], '/'); ?>"
               class="tasks-switch__item <?= !empty($filter) && $filter === 'tomorrow'  ? 'tasks-switch__item--active' : '' ?>">Завтра</a>
            <a href="<?= get_query_href(['project-id' => null, 'q' => null, 'filter' => 'overdue'], '/'); ?>"
               class="tasks-switch__item <?= !empty($filter) && $filter === 'overdue'  ? 'tasks-switch__item--active' : '' ?>">Просроченные</a>
        </nav>
        <label class="checkbox">
            <input class="checkbox__input visually-hidden show-completed"
                   type="checkbox"
                   name="show_completed"
                   value="1"
                   <?= $show_complete_tasks == 1 ? 'checked' : ''; ?>>
            <span class="checkbox__text">Показывать выполненные</span>
        </label>
    </div>
    <table class="tasks">
        <?php foreach ($tasks as $task):
            if ($task['status'] && $show_complete_tasks === null) {
                continue;
            } ?>
            <tr class="tasks__item task <?= !empty($task['status']) ? 'task--completed' : ''; ?> <?= !empty($task['important']) ? 'task--important' : ''; ?>">
                <td class="task__select">
                    <label class="checkbox task__checkbox">
                        <input class="checkbox__input visually-hidden task__checkbox"
                               type="checkbox"
                               name="complete_task"
                               value="<?= $task['id']; ?>"
                               data-toggle="true"
                               <?= $task['status'] == 1 ? 'checked' : '' ?>>
                        <span class="checkbox__text"><?= htmlspecialchars($task['title']); ?></span>
                    </label>
                </td>
                <td class="task__file">
                    <?php if (!empty($task['file'])): ?>
                        <a class="download-link" href="<?= $task['file']; ?>"><?= $task['file']; ?></a>
                    <?php endif; ?>
                </td>
                <td class="task__date"><?= !empty($task['due_date']) ? date_format(date_create($task['due_date']), 'd.m.Y') : ''; ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if(empty($tasks) && !empty($search_query)) : ?>
            <p>Ничего не найдено по вашему запросу</p>
        <?php endif; ?>
    </table>
</main>
