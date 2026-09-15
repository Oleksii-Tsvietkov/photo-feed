<?php

namespace app\models;

class Photo extends AbstractModel    // rename
{
    /**
     * Name of table in Data Base
     */
    private const TABLE_NAME = "posts";

    public function getPosts(int $userId, int $offSet, int $rowCount = 5): array    // ToDo: придумать как выводить в начале меньше 5 фотографий
    {
        $posts = [];
        $query = $this->db->prepare("SELECT u.image as 'user_image', u.login AS 'username', p.image, p.description, p.published_at AS 'date', COUNT(l.id) AS likes_count, MAX(IF(l.user_id = ?, 1, 0)) AS 'like_status' FROM posts AS p LEFT JOIN users AS u ON p.user_id = u.id LEFT JOIN likes AS l ON p.id = l.post_id GROUP BY u.image, u.login, p.image, p.description, p.published_at ORDER BY p.published_at DESC LIMIT ?, ?;");    
        if($query->bind_param("iii", $userId, $offSet, $rowCount)){
            if($query->execute()){
                $result = $query->get_result();
                $posts = $result->fetch_all(MYSQLI_ASSOC); 
            }
        }
        return $posts;
    }
    
    public function addPost()
    {
        "INSERT INTO posts(user_id, image, description) VALUES(13, '/shared/storage/images/IMG_20251221_002248.jpg', 'ботанический сад'), (1, '/shared/storage/images/IMG_20260524_211533.jpg', 'Днепровская набережная'), (1, '/shared/storage/images/IMG_20260803_200419.jpg', null), (1, '/shared/storage/images/IMG_20260623_115408.jpg', null), (13, '/shared/storage/images/scan0036.jpg', '#chaika-II #kodakvision2250d'), (13, '/shared/storage/images/scan0014.jpg', '#chaika-II #kodakvision2250d'), (13, '/shared/storage/images/138900663_p0.png', 'xilmo');";
    }
    public function addLike()
    {
        "INSERT INTO likes(post_id, user_id) VALUES(1, 1), (1, 13), (1, 14), (2, 13), (3, 13), (5, 1), (5, 13), (6, 1), (6, 13), (6, 14);";
    }
}