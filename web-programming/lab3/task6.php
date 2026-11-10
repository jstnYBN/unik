<?php
require __DIR__ . '/news.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Лента новостей</title>
</head>
<body>
    <h1>Лента новостей</h1>

    <ul>
        <?php foreach ($news as $item): ?>
            <li>
                <strong><?= htmlspecialchars(
                    $item['title'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?></strong>
                — <?= $item['views'] ?> просмотров
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>