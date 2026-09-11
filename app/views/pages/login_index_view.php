<h2>Enter to Photo Feed</h2>
<?php //include_once $this->getTemplatesPath("error_message")?>
<form action="<?= app\core\Route::url("authorization", "login")?>" method="post" enctype="multipart/form-data">
    <input type="text" name="login" minlength="3" maxlength="15" placeholder="Login, phone number or email" autofocus required>
    <input type="password" name="pass" minlength="8" maxlength="20" placeholder="Password" required></input>
    <input type="submit" value="Log in">
    <a href="<?= app\core\Route::url('authorization', 'registration')?>" id="registr-button">Create new account</a>
</form>