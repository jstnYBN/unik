<?php
header('Content-Type: text/html; charset=utf-8');

function getGradeLabel($grade) {
    if ($grade == 5) {
        return "Отлично";
    } elseif ($grade == 4) {
        return "Хорошо";
    } elseif ($grade == 3) {
        return "Удовлетворительно";
    } elseif ($grade == 2) {
        return "Неудовлетворительно";
    } else {
        return "Неизвестная ошибка";
    }
}

$grades = [5, 4, 3, 4, 5];

foreach ($grades as $grade) {
    $label = getGradeLabel($grade);
    echo "Оценка {$grade}: {$label}<br>";
}