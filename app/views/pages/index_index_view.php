<main>
    <div id="feed-div">    <!-- ToDo: использовать anchor, пересмотреть вёрстку без родительского div -->
        <?php foreach($posts as $post): ?>
            <article>
                <div id="head">
                    <img src="<?= $post['user_image']?>" alt="User avatar"><span><?= $post['username']?></span>
                    <time datetime="<?= $this->getDateTime($post['date'])?>" title="<?= $this->getTextDate($post['date'])?>">&nbsp;•&nbsp;<?= $this->getTimeInterval($post['date'])?></time>
                </div>
                <img src="<?= $post['image']?>" alt="User photo">
                <div id="bottom">
                    <span><img src="<?= $this->getLike($post['like_status'])?>" alt="Like" id="like">&nbsp;<?= $post['likes_count']?></span>
                    <p><?= $post['description']?></p>
                </div>
            </article>
        <?php endforeach; ?>
        <footer>
            
        </footer>
    </div>
</main>