<?php
require __DIR__ . '/news.php';

$number = 1;

foreach ($news as $item) {
    $title = htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8');

    echo "{$number}. {$title} — {$item['views']} просмотров<br>";

    $number++;
}