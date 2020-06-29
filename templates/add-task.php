<h2 class="content__main-heading">Добавление задачи</h2>
<form class="form" action="/add-task.php" method="post" autocomplete="off" enctype="multipart/form-data">
    <div class="form__row">
        <label class="form__label" for="name">Название <sup>*</sup></label>
        <input class="form__input <?= !empty($errors['title']) ? 'form__input--error' : ''; ?>"
               type="text"
               name="title"
               id="name"
               value="<?= !empty($task['title']) ? htmlspecialchars($task['title']) : ''; ?>"
               placeholder="Введите название">
        <?php if (!empty($errors['title'])): ?>
            <p class="form__message"><?= $errors['title']; ?></p>
        <?php endif; ?>
    </div>
    <div class="form__row">
        <label class="form__label" for="project">Проект <sup>*</sup></label>
        <select class="form__input form__input--select <?= !empty($errors['project_id']) ?
            'form__input--error' : ''; ?>"
                name="project_id"
                id="project">
            <?php foreach ($projects as $project): ?>
                <option value="<?= $project['id']; ?>" <?= !empty($task['project_id']) &&
                (int)$task['project_id'] === (int)$project['id'] ?
                    'selected' : ''; ?>><?= $project['title']; ?></option>
            <?php endforeach; ?>
        </select>
        <?php if (!empty($errors['project_id'])): ?>
            <p class="form__message"><?= $errors['project_id']; ?></p>
        <?php endif; ?>
    </div>
    <div class="form__row">
        <label class="form__label" for="date">Дата выполнения</label>
        <input class="form__input form__input--date <?= !empty($errors['due_date']) ? 'form__input--error' : ''; ?>"
               type="text"
               name="due_date"
               id="date"
               value="<?= !empty($task['due_date']) ? htmlspecialchars($task['due_date']) : ''; ?>"
               placeholder="Введите дату в формате ГГГГ-ММ-ДД">
        <?php if (!empty($errors['due_date'])): ?>
            <p class="form__message"><?= $errors['due_date']; ?></p>
        <?php endif; ?>
    </div>
    <div class="form__row">
        <label class="form__label" for="file">Файл</label>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" name="file" id="file" value="">
            <label class="button button--transparent" for="file">
                <span>Выберите файл</span>
            </label>
        </div>
    </div>
    <div class="form__row form__row--controls">
        <input class="button" type="submit" name="" value="Добавить">
    </div>
</form>
