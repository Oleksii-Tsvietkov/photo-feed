<?php

namespace app\models;

class Photo extends AbstractModel    // rename
{
    /**
     * Name of table in Data Base
     */
    private const TABLE_NAME = "posts";

    public function getPosts()// : array //and name and photo of post user
    {
        $posts = [];
        "SELECT u.image, u.login AS 'username', p.image, p.description, p.published_at AS 'date' FROM posts AS p LEFT JOIN users AS u ON p.user_id = u.id ORDER BY p.published_at DESC LIMIT 5;";
    }
    
    public function add()
    {
        "INSERT INTO posts(user_id, image, description) VALUES(13, '/shared/storage/images/IMG_20251221_002248.jpg', 'ботанический сад'), (1, '/shared/storage/images/IMG_20260524_211533.jpg', 'Днепровская набережная'), (1, '/shared/storage/images/IMG_20260803_200419.jpg', null), (1, '/shared/storage/images/IMG_20260623_115408.jpg', null), (13, '/shared/storage/images/scan0036.jpg', '#chaika-II #kodakvision2250d'), (13, '/shared/storage/images/scan0014.jpg', '#chaika-II #kodakvision2250d'), (13, '/shared/storage/images/138900663_p0.png', 'xilmo');";
    }
}