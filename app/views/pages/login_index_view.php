<h2>Enter to Photo Feed</h2>
<form action="<?= app\core\Route::url("authorization", "login")?>" method="post" enctype="multipart/form-data">
    <?php if(isset($errorMessage)): ?>
        <span id="error-message"><?= $errorMessage?></span>
    <?php endif; ?>
    <input type="text" name="identity" minlength="3" maxlength="15" placeholder="User name or email" value="<?= $inputtedIdentity ?? ''?>" autofocus required>
    <input type="password" name="pass" minlength="8" maxlength="20" placeholder="Password" value="<?= $inputtedPass ?? ''?>" required></input>
    <input type="submit" value="Log in">
    <a href="<?= app\core\Route::url('authorization', 'registration')?>" id="registr-button">Create new account</a>
</form>