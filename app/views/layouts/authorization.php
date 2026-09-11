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
            <?php if(isset($templateName)): ?>
                <?php include_once $this->getTemplatesPath($templateName)?>
            <?php endif; ?>
            <div>
                <?php include_once $this->getPagesPath($viewName)?> 
            </div>
        </main>
        <footer>&copy;Alex Walker</footer>
    </body>
</html>