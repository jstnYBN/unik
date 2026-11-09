<?php
require __DIR__ . '/news.php';

$totalViews = 0;

foreach ($news as $item) {
    $totalViews += $item['views'];
}

$newsCount = count($news);

$averageViews = $newsCount > 0
    ? round($totalViews / $newsCount, 1)
    : 0;

echo "Всего просмотров: {$totalViews}<br>";
echo "Среднее число просмотров: {$averageViews}";