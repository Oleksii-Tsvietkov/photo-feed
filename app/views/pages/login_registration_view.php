<div>
    <div id="registration-page">
        <a href="<?= app\core\Route::url("authorization", "index")?>" id="button" ><img src="<?= $this->getResourcesPath("left-arrow.png")?>" alt="Left arrow"></a>
        <form action="<?= app\core\Route::url("authorization", "store")?>" method="post" enctype="multipart/form-data">
            <?php if(isset($errorMessage)): ?>
                <?php include_once $this->getTemplatesPath("error_message")?>
            <?php endif; ?>
            <label for="user-email">Email</label>
            <input type="email" id="user-email" name="email" minlength="<?= EMAIL_MIN?>" maxlength="<?= EMAIL_MAX?>" value="<?= $email ?? ''?>" placeholder="Email" autofocus required>
            <label for="user-login">Login</label> 
            <input type="text" id="user-login" name="login" minlength="<?= LOGIN_MIN?>" maxlength="<?= LOGIN_MAX?>" value="<?= $login ?? ''?>" placeholder="User name" required>
            <label for="user-pass">Password</label>
            <input type="password" id="user-pass" name="pass" minlength="<?= PASS_MIN?>" maxlength="<?= PASS_MAX?>" value="<?= $pass ?? ''?>" placeholder="Password" required></input>
            <label for="user-pass-conf">Confirm Password</label>
            <input type="password" id="user-pass-conf" name="pass-conf" minlength="<?= PASS_MIN?>" maxlength="<?= PASS_MAX?>" value="<?= $passConf ?? ''?>" placeholder="Confirm password" required></input>
            <input type="submit" value="Submit">
        </form>
    <div>
</div>