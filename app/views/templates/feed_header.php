<header id="feed-header">
    <a href="<?= app\core\Route::url("index", "index")?>">
        <?php include_once $this->getTemplatesPath("logo_img")?>
        <h1><?= SITE_NAME?></h1>
    </a>
    <div id="right">
        <img src="<?= $user['image']?>" alt="User image">
        <span><?= $user['login']?></span>
        <a href="<?= app\core\Route::url('index', 'create')?>" id="add-button" label="New post">+</a>
        <a href="<?= app\core\Route::url('authorization', 'logout')?>" label="Logout">Exit</a>
    </div>
</header>