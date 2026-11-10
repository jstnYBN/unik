<?php
header('Content-Type: text/html; charset=utf-8');

$name = $_POST['name'] ?? '';

$safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

echo "Здравствуйте, {$safeName}!";