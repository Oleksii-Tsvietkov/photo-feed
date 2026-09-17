<main>
    <div id="feed-div">    <!-- ToDo: add anchor -->
        <?php if(isset($posts)): ?>
            <?php foreach($posts as $key => $post): ?>
                <article id="post<?=$key?>">
                    <div id="head">
                        <img src="<?= $post['user_image']?>" alt="User avatar"><span><?= $post['username']?></span>
                        <time datetime="<?= $this->getDateTime($post['date'])?>" title="<?= $this->getTextDate($post['date'])?>">&nbsp;•&nbsp;<?= $this->getTimeInterval($post['date'])?></time>
                    </div>
                    <img src="<?= $post['image']?>" alt="User photo">
                    <div id="bottom">
                        <span>
                            <a href="<?= $this->getLikeUrl($post['id'], $currentPage, $pagesCount, $post['like_status'], "#post$key")?>" id="like">
                                <img src="<?= $this->getLike($post['like_status'])?>" alt="Like">&nbsp;<?= $post['likes_count'] == 0 ? '' : $post['likes_count'] ?>
                            </a>
                        </span><!-- ToDo: Add button -->
                        <p><?= $post['description']?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
        <nav>
            <?php if(isset($pagesCount) && $pagesCount > 1): ?>
                <ul>
                    <?php if($currentPage != 1): ?>
                        <li><a href="<?= $this->getPageUrl(1)?>">«</a></li> 
                        <li><a href="<?= $this->getPageUrl($currentPage - 1)?>">‹</a></li>
                    <?php endif; ?>
                    <?php for($i = 1; $i <= $pagesCount && $i < $currentPage + 9; ++$i): ?><!-- ToDo: fix (number of showing buttons)-->
                        <li>
                            <?= $i != $currentPage ? "<a href=" . $this->getPageUrl($i) . ">$i</a>" : "<b>$i</b>"?>
                        </li>        
                    <?php endfor; ?>
                    <?php if($currentPage != $pagesCount): ?>
                        <li><a href="<?= $this->getPageUrl($pagesCount)?>">»</a></li>
                        <li><a href="<?= $this->getPageUrl($currentPage + 1)?>">›</a></li>
                    <?php endif; ?>
                </ul>
            <?php endif; ?>
        </nav>
    </div>
</main>