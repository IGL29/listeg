<?php

include $_SERVER['DOCUMENT_ROOT'].'/templates/header.php'?>

<div class="flex-wrapper">
    <section class="section-content">
        <h1><?=showTitle($menu)?></h1>

        <?php if (isCurrentUrl('/route/profile/')) {
            include $_SERVER['DOCUMENT_ROOT'] . '/include/profile.php';
        } elseif (isCurrentUrl('/route/posts/')) {
            include $_SERVER['DOCUMENT_ROOT'] . '/include/posts.php';
        } elseif (isCurrentUrl('/route/posts/add')) {
            include $_SERVER['DOCUMENT_ROOT'] . '/include/posts_add.php';
        } elseif (isCurrentUrl('/route/post/detail.php')) {
            include $_SERVER['DOCUMENT_ROOT'] . '/include/detail.php';
        }?>
    </section>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/include/autorization/index.php' ?>

</div>

<?php include $_SERVER['DOCUMENT_ROOT'].'/templates/footer.php' ?>
