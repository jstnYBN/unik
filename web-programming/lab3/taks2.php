<?php
require __DIR__ . '/news.php';

for ($i = 0; $i < 3 && $i < count($news); $i++) {
    $item = $news[$i];
    $number = $i + 1;
    $title = htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8');

    echo "{$number}. {$title} — {$item['views']} просмотров<br>";
}