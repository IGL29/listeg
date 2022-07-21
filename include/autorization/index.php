<?php

$isNotAuthorized = !isset($entered) || $entered !== true;
$isShowRegistrationForm = isset($_GET['registration']) && $_GET['registration'] === 'yes' && $isNotAuthorized;
$isShowForm = isset($_GET['login']) && $_GET['login'] == 'yes' && $isNotAuthorized;
$loggedInCookies = isset($entered) && $entered == 'login' && !isset($_SESSION['authorization']);

?>

<section class="section-authorization">
    <div>
        <ul class="project-folders-v">
            <?php if ((((isset($entered) && $entered === true) || (isset($entered) && $entered === 'login')) || (isset($authorization) && $authorization === true)) && isset($_SESSION['authorization'])){?>

                <li><a class="link-exit" href="/?exit=yes">Выход</a></li>

            <?php } else {?>

                <li <?=$isShowForm || $loggedInCookies ? 'class="project-folders-v-active"' : ''?>><a href="/?login=yes">Авторизация</a></li>
                <li <?=$isShowRegistrationForm ? 'class="project-folders-v-active"' : ''?>><a href="/?registration=yes">Регистрация</a></li>
                <li><a href="#">Забыли пароль?</a></li>

            <?php } ?>
        </ul>
    </div>

    <?php if ($isShowForm || $loggedInCookies) {
        if ($authorization === false) {
            include $_SERVER['DOCUMENT_ROOT'] . '/include/message_form/error.php';
            include $_SERVER['DOCUMENT_ROOT'] . '/include/form.php';
        } elseif ($authorization === true) {
            include $_SERVER['DOCUMENT_ROOT'] . '/include/message_form/success.php';
        } else {
            include $_SERVER['DOCUMENT_ROOT'] . '/include/form.php';
        }
    }?>

    <?php if ($isShowRegistrationForm) {
        include $_SERVER['DOCUMENT_ROOT'] . '/include/registration_form.php';
    }?>
</section>
