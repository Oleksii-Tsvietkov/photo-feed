<?php foreach($posts as $key => $post): ?>
    <article id="post<?=$key?>">
        <div id="head">
            <img src="<?= $post['user_image']?>" alt="User avatar"><span><?= $post['username']?></span>
            <time datetime="<?= $this->getDateTime($post['date'])?>" title="<?= $this->getTextDate($post['date'])?>">&nbsp;•&nbsp;<?= $this->getTimeInterval($post['date'])?></time>
        </div>
        <img src="<?= $post['image']?>" alt="User photo">
        <div id="bottom">
            <span>
                <a href="<?= $this->getLikeUrl($post['id'], $currentPage, $post['like_status'], "post$key")?>" id="like">
                    <img src="<?= $this->getLike($post['like_status'])?>" alt="Like">&nbsp;<?= $post['likes_count'] == 0 ? '' : $post['likes_count'] ?>
                </a>
            </span>
            <p><?= $post['description']?></p>
        </div>
    </article>
<?php endforeach; ?>