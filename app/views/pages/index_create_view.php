<div class="create-post cntr">
    <a href="<?= app\core\Route::url("authorization", "index")?>" id="button" tabindex="-1"><img src="<?= $this->getResourcesPath("left-arrow.png")?>" alt="Left arrow" label="Back" tabindex="-1"></a><!-- ToDo: if user changing mind about creating post - delete loaded photo or preview -->
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
        <?php if(isset($inputedImage)): ?>
            <img src="<?= $this->getImagesPath($inputedImage)?>" alt="User selected image">
            <input type="text" name="description" maxlength="<?= DESCRIPTION_MAX?>" placeholder="Your text" autofocus>
            <input type="hidden" name="image" value="<?= $inputedImage?>">
        <?php else: ?>
            <label class="btn" for="image" tabindex="1" autofocus><?= $oldFileName ?? 'Select on device'?></label>
            <input id="image" type="file" name="image" accept="image/*" <?= isset($inputedImage) ? '' : 'required' ?>><!-- ToDo: make option to change photo during of creation post (with JS) -->
        <?php endif; ?>
        <input class="btn" type="submit" value="<?= isset($inputedImage) ? 'Submit' : 'Next' ?>" tabindex="0">
    </form>
</div>
