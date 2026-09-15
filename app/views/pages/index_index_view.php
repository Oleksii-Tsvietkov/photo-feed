<div id="feed-div">    <!-- ToDo: использовать anchor -->
    <?php foreach($posts as $post): ?>
        <article>
            <div id="head">
                <img src="<?= $post['user_image']?>" alt="User avatar"><span><?= $post['username']?></span>
                <time datetime="<?= $this->getDateTime($post['date'])?>" title="<?= $this->getTextDate($post['date'])?>"><?= $this->getTimeInterval($post['date'])?></time>
            </div>
            <img src="<?= $post['image']?>" alt="User photo">
            <di id="bottom">
                <img src="<?= $post['like_status']?>" alt="Like"><span><?= $post['likes_count']?></span>
                <p><?= $post['description']?></p>
            </di>
        </article>
    <?php endforeach; ?>
</div>
