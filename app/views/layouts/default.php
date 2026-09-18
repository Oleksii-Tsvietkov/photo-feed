<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="description" content="<?= $this->getDescription($title)?>">
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon" href="<?= $this->getResourcesPath("photo-feed-icon.ico")?>">
        <title><?= (isset($title) ? $title . ' | ' : '') . SITE_NAME?></title>
    </head>
    <body>
        <?php if(isset($title)): // ToDo: temporary solution?>
            <main>
        <?php endif; ?>
            <?php if(isset($templateName)): ?>
                <?php include_once $this->getTemplatesPath($templateName)?>
            <?php endif; ?>
            <?php if(isset($pageName)): ?>
                <?php include_once $this->getPagesPath($pageName)?>
            <?php endif; ?>
        <?php if(isset($title)): // ToDo: temporary solution?>
            </main>
        <?php endif; ?>
        <footer>&copy;Alex Walker</footer>
    </body>
</html>