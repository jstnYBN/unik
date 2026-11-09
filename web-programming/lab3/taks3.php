<?php
require __DIR__ . '/news.php';

$i = 0;

while ($i < count($news)) {
    $item = $news[$i];
    $title = htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8');

    if ($item['views'] > 500) {
        echo "Нашли популярную новость: {$title}";
        break;
    }

    $number = $i + 1;
    echo "{$number}. {$title} — {$item['views']} просмотров<br>";

    $i++;
}