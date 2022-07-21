<?php

include $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php' ?>

<div class="flex-wrapper">
    <section class="section-content">
        <h1><?=showTitle($menu)?></h1>

        <p>Вести свои личные списки, например покупки в магазине, цели, задачи и многое другое. Делится списками с друзьями и просматривать списки друзей.</p>
    </section>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/include/autorization/index.php' ?>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php' ?>
