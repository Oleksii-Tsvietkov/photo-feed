<header class="feed-header flx">
    <a href="<?= app\core\Route::url("index", "index")?>" class="flx cntr">
        <?php include_once $this->getTemplatesPath("logo_img")?>
        <h1><?= SITE_NAME?></h1>
    </a>
    <div class="flx cntr" id="right">
        <img src="<?= $user['image']?>" alt="User image">
        <span><?= $user['login']?></span>
        <a href="<?= app\core\Route::url('index', 'create')?>" id="add-button" title="New post" tabindex="1">+</a>
        <a href="<?= app\core\Route::url('authorization', 'logout')?>" title="Logout">Exit</a>
    </div>
</header>