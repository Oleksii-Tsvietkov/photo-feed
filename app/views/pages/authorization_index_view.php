<div>
    <h2>Enter to Photo Feed</h2>
    <form action="<?= app\core\Route::url("authorization", "login")?>" method="post" enctype="multipart/form-data">
        <?php if(isset($errorMessage)): ?>
            <?php include_once $this->getTemplatesPath("error_message")?>
        <?php endif; ?>
        <input type="text" name="identity" minlength="<?= LOGIN_MIN?>" maxlength="<?= EMAIL_MAX?>" placeholder="User name or email" <?= isset($identity) ? "value=\"$identity\"": ''?> autocomplete="on" autofocus required>
        <input type="password" name="pass" minlength="<?= PASS_MIN?>" maxlength="<?= PASS_MAX?>" placeholder="Password" <?= isset($pass) ? "value=\"$pass\"": ''?> required></input>
        <input type="submit" value="Log in">
        <a href="<?= app\core\Route::url('authorization', 'registration')?>" class="registr-button flx cntr">Create new account</a>
    </form>
</div>