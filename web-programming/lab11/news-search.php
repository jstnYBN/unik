<?php
ini_set('display_errors', '0');
header('Content-Type: text/html; charset=utf-8');

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$errors = [];
$news = [];
$q = '';
$sort = 'new';
$orders = [
    'new' => 'id DESC',
    'old' => 'id ASC',
];

$rawQ = $_GET['q'] ?? '';
$rawSort = $_GET['sort'] ?? 'new';

if (!is_string($rawQ)) {
    $errors[] = 'Поисковый запрос должен быть строкой.';
} elseif (!mb_check_encoding($rawQ, 'UTF-8')) {
    $errors[] = 'Введите поисковый запрос в кодировке UTF-8.';
} else {
    $q = trim($rawQ);
    if (mb_strlen($q, 'UTF-8') > 100) {
        $errors[] = 'Поисковый запрос должен быть не длиннее 100 символов.';
    }
}

if (!is_string($rawSort) || !isset($orders[$rawSort])) {
    $errors[] = 'Неизвестный порядок. Выберите «Сначала новые» или «Сначала старые».';
} else {
    $sort = $rawSort;
}

if ($errors) {
    http_response_code(400);
}
if (!$errors) {
    try {
        require __DIR__ . '/config.php';

        $sql = 'SELECT id, title, content, created_at FROM news';
        $params = [];

        if ($q !== '') {
            $sql .= ' WHERE title LIKE :q';
            $params['q'] = '%' . $q . '%';
        }

        $sql .= ' ORDER BY ' . $orders[$sort];
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $news = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Throwable $error) {
        error_log((string) $error);
        http_response_code(500);
        $errors[] = 'Не удалось загрузить новости. Проверьте сервер и подключение к базе.';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Поиск новостей</title>
</head>
<body>
<h1>Поиск новостей</h1>
<p>Ищем только в заголовках. Пустое поле покажет все новости.</p>

<form method="get" action="news-search.php">
    <p>
        <label for="q">Слово или часть заголовка</label>
        <input id="q" name="q" maxlength="100" value="<?= e($q) ?>">
    </p>
    <p>
        <label for="sort">Порядок</label>
        <select id="sort" name="sort">
            <option value="new" <?= $sort === 'new' ? 'selected' : '' ?>>Сначала новые</option>
            <option value="old" <?= $sort === 'old' ? 'selected' : '' ?>>Сначала старые</option>
        </select>
    </p>
    <button type="submit">Найти</button>
    <a href="news-search.php">Сбросить условия</a>
</form>

<?php foreach ($errors as $error): ?>
    <p role="alert"><?= e($error) ?></p>
<?php endforeach; ?>
<?php if (!$errors): ?>
    <h2>Результаты</h2>
    
    <!-- НАЧАЛО НОВОГО БЛОКА -->
    <?php if ($q === ''): ?>
        <p>Показаны все новости</p>
    <?php else: ?>
        <p>Поиск по заголовку: <?= e($q) ?></p>
    <?php endif; ?>
    <!-- КОНЕЦ НОВОГО БЛОКА -->

    <p>Найдено новостей: <?= count($news) ?>.</p>

    <?php if (!$news): ?>
        <?php if ($q === ''): ?>
            <p>Новостей пока нет.</p>
        <?php else: ?>
            <p>По вашему запросу ничего не найдено. Попробуйте другое слово.</p>
        <?php endif; ?>
    <?php endif; ?>

    <?php foreach ($news as $item): ?>
        <article>
            <h3><?= e($item['title']) ?></h3>
            <p>Номер: <?= (int) $item['id'] ?>. Дата: <?= e($item['created_at']) ?></p>
            <p><?= nl2br(e($item['content'])) ?></p>
        </article>
    <?php endforeach; ?>
<?php endif; ?>
</body>
</html>