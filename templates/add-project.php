<section class="content__side">
    <h2 class="content__side-heading">Проекты</h2>
    <nav class="main-navigation">
        <ul class="main-navigation__list">
            <?php foreach ($projects as $project): ?>
                <li class="main-navigation__list-item">
                    <a class="main-navigation__list-item-link
                    <?= !empty($active_project_id) && $active_project_id === $project['id'] ? 'main-navigation__list-item--active' : ''; ?>"
                       href="<?= get_query_href(['project-id' => $project['id']], '/index.php'); ?>"><?= htmlspecialchars($project['title']); ?></a>
                    <span class="main-navigation__list-item-count"><?= count_tasks($all_tasks, $project['id']); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <a class="button button--transparent button--plus content__side-button" href="add-project.php">Добавить проект</a>
</section>
<main class="content__main">
    <h2 class="content__main-heading">Добавление проекта</h2>
    <form class="form"  action="/add-project.php" method="post" autocomplete="off">
        <div class="form__row">
            <label class="form__label" for="project_name">Название <sup>*</sup></label>
            <input class="form__input <?= !empty($errors['title']) ? 'form__input--error' : ''; ?>"
                   type="text"
                   name="title"
                   id="project_name"
                   value="<?= !empty($new_project['title']) ? htmlspecialchars($new_project['title']) : ''; ?>"
                   placeholder="Введите название проекта">
            <?php if (!empty($errors['title'])): ?>
                <p class="form__message"><?= $errors['title']; ?></p>
            <?php endif; ?>
        </div>
        <div class="form__row form__row--controls">
            <?php if (count($errors) > 0) : ?>
                <p class="error-message">Пожалуйста, исправьте ошибки в форме</p>
            <?php endif; ?>
            <input class="button" type="submit" name="" value="Добавить">
        </div>
    </form>
</main>
