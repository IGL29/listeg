<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

session_name('session_id');
session_start();

include $_SERVER['DOCUMENT_ROOT'].'/helpers/verification.php';
include $_SERVER['DOCUMENT_ROOT'].'/helpers/registerUser.php';

if (isset($_SESSION['authorization']) && $_SESSION['authorization'] == true && !empty($_COOKIE['login'])) {

    $entered = true;

    setcookie('login', $_COOKIE['login'], time() + 86400 * 30, '/');

} elseif (!isset($_SESSION['authorization']) && !empty($_COOKIE['login'])) {

    $entered = 'login';
}


if (!isset($entered) || $entered == 'login') {

    $authorization = null;

    if (isset($_POST['login']) && isset($_POST['password']) && !isset($_POST['full_name'])) {

        $authorization = dataValidation($_POST['login'], $_POST['password']);
    }
}


if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['full_name'])) {

    $isRegistered = registerUser($_POST['email'], $_POST['password'], $_POST['full_name']);
    
    if ($isRegistered) {
        header('Location: /?login=yes');
    }
}


if (isset($_GET['exit'])) {

    unset($_SESSION['authorization']);
    setcookie('login', '', time() + 1);
    $entered = false;
    header('Location: /');

} elseif ($authorization == true) {

    $entered = true;
}

include $_SERVER['DOCUMENT_ROOT'].'/helpers/showData.php';


if (!isCurrentUrl('/') && (!isset($entered) || $entered !== true)) {
    header('Location: /?login=yes');
    exit();
}

include $_SERVER['DOCUMENT_ROOT'].'/include/data/menu/main_menu.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


if (isset($_GET['id']) && $_GET['id'] && $entered) {
    include $_SERVER['DOCUMENT_ROOT'] . '/config.php';

    $pdo = new PDO($dbh, $user, $pass);
    if (isset($_SESSION['userId'])) {
        $userId = $_SESSION['userId'];
    } else {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email');
        $stmt->execute([':email' => $_COOKIE['login']]);
        $userId = ($stmt->fetch())['id'];
    }

    $stmt = $pdo->prepare('SELECT * FROM messages WHERE id = :id AND (sender_id = :userId OR recipient_id = :userId)');
    $stmt->execute([':id' => $_GET['id'], ':userId' => $userId]);
    $result = $stmt->fetch();

    if (empty($result)) {
        header("HTTP/1.0 404 Not Found");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="/styles/style.css" rel="stylesheet">
    <title>Project - ведение списков</title>
</head>
<body>

<header>
    <div class="header">
        <div class="logo"><img src="/images/logo.png" width="68" height="23" alt="Project"></div>
    </div>

    <nav class="header-nav">
        <?php showMenu ($menu, 'sort', SORT_ASC) ?>
    </nav>
</header>
