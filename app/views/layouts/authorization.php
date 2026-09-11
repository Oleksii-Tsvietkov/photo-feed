<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon" href="<?= $this->getResourcesPath("photo-feed-icon.ico")?>">
        <meta name="description" content="<?= $this->getDescription($title)?>">
        <title><?= (isset($title) ? $title . ' | ' : '') . SITE_NAME?></title>
    </head>
    <body>
        <main>
            <section>
                <header>
                    <img src="<?= $this->getResourcesPath("photo-feed-logo.png")?>" alt="Photo Feed logo">
                    <h1>See the moments from their lives that your close friends have shared.</h1>
                </header>
                <img src="<?= $this->getResourcesPath("promo-ad.png")?>" alt="Photo Feed promo">
            </section>
            <div>
                <?php include_once $this->getPagesPath($viewName)?> 
            </div>
        </main>
        <footer>&copy;Alex Walker</footer>
    </body>
</html>