<?php
header('Content-Type: application/json; charset=utf-8');

$product = [
    'name' => 'Клавиатура',
    'price' => 1500
];

echo json_encode($product, JSON_UNESCAPED_UNICODE);