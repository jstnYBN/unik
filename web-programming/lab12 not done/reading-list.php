<?php
session_start();
require __DIR__ . '/config.php';

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

if (!isset($_SESSION['reading_list']) || !is_array($_SESSION['reading_list'])) {
    $_SESSION['reading_list'] = [];
}

if (!isset($_SESSION['reading_csrf']) || !is_string($_SESSION['reading_csrf'])) {
    $_SESSION['reading_csrf'] = bin2hex(random_bytes(32));
}

$error = '';
$stmt = $pdo->query(
    'SELECT id, title, content, created_at FROM news ORDER BY id DESC'
);
$news = $stmt->fetchAll(PDO::FETCH_ASSOC);

$existingIds = [];
foreach ($news as $item) {
    $existingIds[(int) $item['id']] = true;
}

foreach ($_SESSION['reading_list'] as $savedId => $selected) {
    if (!isset($existingIds[$savedId])) {
        unset($_SESSION['reading_list'][$savedId]);
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf'] ?? '';
    $action = $_POST['action'] ?? '';
    $rawId = $_POST['id'] ?? '';
    $id = false;

    if (is_string($rawId)) {
        $id = filter_var($rawId, FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]);
    }

    if (!is_string($token) || !hash_equals($_SESSION['reading_csrf'], $token)) {
        http_response_code(403);
        $error = 'Форма устарела или её код неверен. Обновите страницу и попробуйте снова.';
    } elseif (!is_string($action) || ($action !== 'add' && $action !== 'remove')) {
        http_response_code(422);
        $error = 'Неизвестная операция. Используйте кнопки под новостью.';
    } elseif ($id === false) {
        http_response_code(422);
        $error = 'Номер новости должен быть целым положительным числом.';
    } elseif (!isset($existingIds[$id])) {
        http_response_code(404);
        $error = 'Такой новости больше нет. Обновите список.';
    } else {
        if ($action === 'add') {
            $_SESSION['reading_list'][$id] = true;
            $_SESSION['reading_message'] = 'Новость добавлена в список «Прочитать позже».';
        } else {
            unset($_SESSION['reading_list'][$id]);
            $_SESSION['reading_message'] = 'Отметка убрана. Сама новость осталась на сайте.';
        }

        header('Location: reading-list.php', true, 303);
        exit;
    }
}
$count = count($_SESSION['reading_list']);
$message = $_SESSION['reading_message'] ?? '';
unset($_SESSION['reading_message']);
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Прочитать позже</title>
</head>
<body>
<main>
   <main>
    <h1>Новости: прочитать позже</h1>

    <?php if ($count === 0): ?>
        <p>Вы ещё ничего не отложили</p>
    <?php else: ?>
        <p>Можно вернуться к выбранным новостям позже</p>
    <?php endif; ?>

    <p>Отложено новостей: <strong><?= $count ?></strong></p>
    <p>Отметки относятся к сессии этого браузера.</p>
    
    <?php if ($error !== ''): ?>
        <p role="alert"><?= e($error) ?></p>
    <?php endif; ?>

    <?php if ($message !== ''): ?>
        <p role="status"><?= e($message) ?></p>
    <?php endif; ?>

    <?php if ($news === []): ?>
        <p>Новостей пока нет. Когда они появятся, их можно будет отложить.</p>
    <?php endif; ?>
        <?php foreach ($news as $item): ?>
        <?php $isSelected = isset($_SESSION['reading_list'][(int) $item['id']]); ?>
        <article>
            <h2><?= e($item['title']) ?></h2>
            <p>Новость №<?= (int) $item['id'] ?> · <?= e($item['created_at']) ?></p>
            <p><?= e($item['content']) ?></p>

            <?php if ($isSelected): ?>
                <p><strong>В списке «Прочитать позже»</strong></p>
            <?php else: ?>
                <p>Пока не отложена</p>
            <?php endif; ?>

            <form action="reading-list.php" method="post">
                <input type="hidden" name="csrf" value="<?= e($_SESSION['reading_csrf']) ?>">
                <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                <button type="submit" name="action" value="add">Прочитать позже</button>
                <button type="submit" name="action" value="remove">Убрать отметку</button>
            </form>
        </article>
        <hr>
    <?php endforeach; ?>
</main>
</body>
</html>