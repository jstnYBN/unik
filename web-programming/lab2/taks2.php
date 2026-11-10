<?php
header('Content-Type: text/html; charset=utf-8');

$grades = [4, 5, 3, 4, 5];

foreach ($grades as $grade) {
    echo "$grade<br>";
}

$average = round(array_sum($grades) / count($grades), 2);

echo "Средний балл: $average";