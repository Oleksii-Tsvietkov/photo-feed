<div id="registration">
    <form action="<?= app\core\Route::url("authorization", "store")?>" method="post" enctype="multipart/form-data">
        <label for="user-email">Email</label>
        <input type="email" id="user-email" name="email" minlength="7" maxlength="25" autofocus required>
        <label>Date of birth</label>
        <input type="date" required><!--Modify-->
        <label for="user-login">Login</label> 
        <input type="text" id="user-login" name="login" minlength="3" maxlength="15" required>
        <label for="user-pass">Password</label>
        <input type="password" id="user-pass" name="pass" minlength="8" maxlength="20" required></input>
        <label for="user-pass-conf">Confirm Password</label>
        <input type="password" id="user-pass-conf" name="pass-conf" minlength="8" maxlength="20" required></input>
        <input type="submit" value="Registrate">
    </form>
</div>