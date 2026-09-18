<div id="create-post">
    <a href="<?= app\core\Route::url("authorization", "index")?>" id="button"><img src="<?= $this->getResourcesPath("left-arrow.png")?>" alt="Left arrow" label="Back"></a><!-- ToDo: if user changing mind about creating post - delete loaded photo -->
    <?php if(!isset($inputedImage)): ?>
        <div id="adding-logo">
            <img src="<?= $this->getResourcesPath("photo-add-logo.png")?>" alt="Image of media">
            <h2>Add here your photo</h2>
        </div>
    <?php endif; ?>
    <?php if(isset($errorMessage)): ?>
        <?php include_once $this->getTemplatesPath("error_message")?>
    <?php endif; ?>
    <?php isset($inputedImage) ? $action = 'store' : $action = 'add' ?>
    <form action="<?= app\core\Route::url("index", $action)?>" method="post" enctype="multipart/form-data">
        <label class="btn" for="image"><?= $oldFileName ?? 'Select on device'?></label>
        <input id="image" type="file" name="image" accept="image/*" <?= isset($inputedImage) ? '' : 'required' ?>><!-- ToDo: change accept, make option to change photo during of creation post (with JS) -->
        <?php if(isset($inputedImage)): ?>
            <img src="<?= $this->getImagesPath($inputedImage)?>" alt="User selected image">
            <input type="text" name="description" maxlength="<?= DESCRIPTION_MAX?>" placeholder="Your text">
        <?php endif; ?>
        <input class="btn" type="submit" value="<?= isset($inputedImage) ? 'Submit' : 'Next'?>">
    </form>
</div>
