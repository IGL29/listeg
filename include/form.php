<div class="index-auth">
    <form action="/?login=yes" method="post">
        <label class="label" for="login_id">Ваш e-mail:</label>
        <?php if (isset($entered) && $entered === 'login') { ?>

            <input class="input" type="hidden" id="login_id" size="30" name="login" value="<?= $_COOKIE["login"] ?>">
            <p><?=htmlOutput($_COOKIE["login"])?>, Подтвердите, что это действительно Вы!</p>

        <?php } else { ?>

            <input class="input" id="login_id" size="30" name="login" value="<?= $_POST['login'] ?? '' ?>">

        <?php } ?>

        <label class="label" for="password_id">Ваш пароль:</label>
        <input class="input" id="password_id" size="30" name="password" type="password">
        
        <?php if (isset($isRegistered) && $isRegistered) {?>
            <p>Подтвердите, что это действительно Вы!</p>
        <?php }?>
        <input class="button" type="submit" value="Войти">
    </form>
</div>