<main>
    <div id="feed-div">
        <?php if(isset($posts)): ?>
            <?php foreach($posts as $key => $post): ?>
                <article class="flx" id="post<?=$key?>">
                    <div class="head flx cntr">
                        <img src="<?= $post['user_image']?>" alt="User avatar"><span><?= $post['username']?></span>
                        <time datetime="<?= $this->getDateTime($post['date'])?>" title="<?= $this->getTextDate($post['date'])?>">&nbsp;•&nbsp;<?= $this->getTimeInterval($post['date'])?></time>
                    </div>
                    <img src="<?= $post['image']?>" alt="User photo">
                    <div id="bottom">
                        <span class="flx cntr">
                            <a href="<?= $this->getLikeUrl($post['id'], $currentPage, $post['like_status'], "post$key")?>" class="like flx cntr">
                                <img src="<?= $this->getLike($post['like_status'])?>" alt="Like">&nbsp;<?= $post['likes_count'] == 0 ? '' : $post['likes_count'] ?>
                            </a>
                        </span>
                        <p><?= $post['description']?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
        <nav class="flx cntr">
            <?php if(isset($pagesCount) && $pagesCount > 1): ?>
                <ul class="flx">
                    <?php if($currentPage != 1): ?>
                        <li><a href="<?= $this->getPageUrl(1)?>">«</a></li>
                        <li><a href="<?= $this->getPageUrl($currentPage - 1)?>">‹</a></li>
                    <?php endif; ?>
                    <?php for($i = $startPage; $i <= $endPage; ++$i): ?>
                        <li>
                            <?= $i != $currentPage ? "<a href=" . $this->getPageUrl($i) . ">$i</a>" : "<b>$i</b>"?>
                        </li>
                    <?php endfor; ?>
                    <?php if($currentPage != $pagesCount): ?>
                        <li><a href="<?= $this->getPageUrl($currentPage + 1)?>">›</a></li>
                        <li><a href="<?= $this->getPageUrl($pagesCount)?>">»</a></li>
                    <?php endif; ?>
                </ul>
            <?php endif; ?>
        </nav>
    </div>
</main>