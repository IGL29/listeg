<?php

function showMenu ($menu, $key, $sort)
{
    $menu = arraySort ($menu, $key, $sort);

    include $_SERVER['DOCUMENT_ROOT'].'/templates/menu.php';
}

function arraySort(array $menu, $key = 'sort', $sort = SORT_ASC): array
{
    array_multisort($menu, $sort, array_column($menu, $key));
    return $menu;
}

// Обрезаем лишние символы если длина более 12-ти символов (кодировка utf-8)
function cutString ($line, $length = 12, $appends = '...'): string
{
    return mb_strimwidth($line, 0, $length, $appends,'utf-8');
}

function showTitle ($menu)
{
    foreach ($menu as $value) {
        if (isCurrentUrl($value['path'])) {
            return $value['title'];
        }
    }
    return 'Заголовок';
}

function isCurrentUrl ($url) {
    return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) == $url;
}

function htmlOutput($data) {
    return htmlspecialchars($data, ENT_QUOTES);
}