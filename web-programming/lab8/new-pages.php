<?php
require __DIR__ . '/config.php';

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$q = $_GET['q'] ?? '';
$sort = $_GET['sort'] ?? 'new';
$rawPage = $_GET['page'] ?? '1';

if (!is_string($q) || !mb_check_encoding($q, 'UTF-8')) {
    http_response_code(400);
    exit('Поисковый запрос должен быть строкой UTF-8. Вернитесь к форме.');
}
$q = trim($q);
if (mb_strlen($q, 'UTF-8') > 100) {
    http_response_code(400);
    exit('Введите не больше 100 символов для поиска.');
}

$orders = ['new' => 'id DESC', 'old' => 'id ASC'];
if (!is_string($sort) || !isset($orders[$sort])) {
    http_response_code(400);
    exit('Неизвестная сортировка. Выберите new или old.');
}
$orderBy = $orders[$sort];

$requestedPage = is_string($rawPage)
    ? filter_var($rawPage, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])
    : false;
if ($requestedPage === false) {
    http_response_code(400);
    exit('Номер страницы должен быть положительным целым числом.');
}

$perPage = 3;
$where = '';
$countParams = [];
if ($q !== '') {
    $where = ' WHERE title LIKE :query';
    $countParams['query'] = '%' . $q . '%';
}

$countStmt = $pdo->prepare('SELECT COUNT(*) FROM news' . $where);
$countStmt->execute($countParams);
$total = (int) $countStmt->fetchColumn();

$totalPages = max(1, (int) ceil($total / $perPage));
$page = min($requestedPage, $totalPages);
$offset = ($page - 1) * $perPage;
$sql = 'SELECT id, title, content FROM news' . $where
    . ' ORDER BY ' . $orderBy . ' LIMIT :limit OFFSET :offset';
$stmt = $pdo->prepare($sql);
if ($q !== '') {
    $stmt->bindValue(':query', '%' . $q . '%', PDO::PARAM_STR);
}
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$news = $stmt->fetchAll(PDO::FETCH_ASSOC);

function pageUrl(int $number, string $q, string $sort): string {
    return 'news-pages.php?' . http_build_query([
        'q' => $q,
        'sort' => $sort,
        'page' => $number,
    ]);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Новости по страницам</title>
</head>
<body>
<h1>Новости по страницам</h1>

<form method="get" action="news-pages.php">
    <p><label>Слово в заголовке
        <input name="q" maxlength="100" value="<?= e($q) ?>">
    </label></p>
    <p><label>Порядок
        <select name="sort">
            <option value="new" <?= $sort === 'new' ? 'selected' : '' ?>>Сначала новые</option>
            <option value="old" <?= $sort === 'old' ? 'selected' : '' ?>>Сначала старые</option>
        </select>
    </label></p>
    <button type="submit">Показать</button>
    <a href="news-pages.php">Сбросить поиск</a>
</form>

<p>Найдено новостей: <?= $total ?>.</p>
<?php if ($total === 0): ?>
    <p>Ничего не найдено. Измените слово или сбросьте поиск.</p>
<?php else: ?>
    <p>Страница <?= $page ?> из <?= $totalPages ?>.</p>
<?php endif; ?>

<?php foreach ($news as $item): ?>
    <article>
        <h2><?= e($item['title']) ?></h2>
        <p>Номер записи: <?= (int) $item['id'] ?></p>
        <p><?= nl2br(e($item['content'])) ?></p>
    </article>
<?php endforeach; ?>

<?php if ($totalPages > 1): ?>
    <nav aria-label="Страницы новостей">
        <?php if ($page > 1): ?>
            <a href="<?= e(pageUrl($page - 1, $q, $sort)) ?>">← Назад</a>
        <?php endif; ?>
        <?php if ($page < $totalPages): ?>
            <a href="<?= e(pageUrl($page + 1, $q, $sort)) ?>">Вперёд →</a>
        <?php endif; ?>
    </nav>
<?php endif; ?>
</body>
</html>