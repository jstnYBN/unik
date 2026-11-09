<?php
require __DIR__ . '/news.php';

$popularCount = 0;

foreach ($news as $item) {
    if ($item['views'] > 200) {
        $title = htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8');

        echo "{$title} — {$item['views']} просмотров — популярная новость!<br>";

        $popularCount++;
    }
}

echo "Всего популярных новостей: {$popularCount}";