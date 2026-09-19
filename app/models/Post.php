<?php

namespace app\models;

class Post extends AbstractModel    // rename
{
    /**
     * Name of table in Data Base
     */
    private const TABLE_NAME = "posts";

    private const PHOTO_PER_PAGE = 5;
    private const BUTTONS_PER_PAGE = 10;

    private int $postsCount;
    private int $firstPagePostsCount;
    private int $pagesCount;

    public static function getInstance(...$args) : static    // ...$args need for identical signature
    {
        $instance = parent::getInstance();
        if(!isset($instance->postsCount) || !isset($instance->pagesCount) || !isset($instance->firstPagePostsCount)){
            $instance->pagination();
        }
        return $instance;
    }

    public function getPosts(int $userId, int $offSet, int $limit) : array
    {
        $posts = [];
        $query = $this->db->prepare("SELECT u.image as 'user_image', u.login AS 'username', p.id, p.image, p.description, p.published_at AS 'date', COUNT(l.id) AS likes_count, MAX(IF(l.user_id = ?, 1, 0)) AS 'like_status' FROM posts AS p LEFT JOIN users AS u ON p.user_id = u.id LEFT JOIN likes AS l ON p.id = l.post_id GROUP BY u.image, u.login, p.id, p.image, p.description, p.published_at ORDER BY p.published_at DESC LIMIT ?, ?;");    
        if($query->bind_param("iii", $userId, $offSet, $limit)){
            if($query->execute()){
                $result = $query->get_result();
                $posts = $result->fetch_all(MYSQLI_ASSOC); 
            }
        }
        return $posts;
    }
    public function getButtonsCount(int $currentPage) : array
    {
        $currentBlock = ceil($currentPage / self::BUTTONS_PER_PAGE);
        $start = ($currentBlock - 1) * self::BUTTONS_PER_PAGE + 1;
        $end = $currentBlock * self::BUTTONS_PER_PAGE;
        $end = $end < $this->pagesCount ? $end : $this->pagesCount;
        return [
            'start' => $start, 
            'end' => $end,
        ];
    }
    public function getPagesCount() : int
    {
        return $this->pagesCount;
    }
    private function pagination() : void
    {
        $total = $this->getPostsCount();
        $remainder = $total % self::PHOTO_PER_PAGE;
        $this->firstPagePostsCount = ($remainder == 0) ? self::PHOTO_PER_PAGE : $remainder;
        $this->pagesCount = ceil($total / self::PHOTO_PER_PAGE);
    }
    public function getLimit($currentPage) : array
    {
        $limit = null;
        $offset = null;
        if($currentPage === 1){
            $limit = $this->firstPagePostsCount;
            $offset = 0;
        }else{
            $limit = self::PHOTO_PER_PAGE;
            $offset = $this->firstPagePostsCount + ($currentPage - 2) * self::PHOTO_PER_PAGE;
        }
        return ['limit' => $limit, 'offset' => $offset];
    }
    private function getPostsCount() : ?int
    {
        $count = null;
        $query = $this->db->prepare("SELECT COUNT(id) FROM posts;");
        if($query->execute()){
            $result = $query->get_result();
            $count = $result->fetch_column();
        }
        return $count;
    }
    public function add(int $userId, string $image, ?string $description) : bool    // ToDo: maybe change to adding null
    {
        $query = '';
        $isBinded = false;
        if(is_null($description)){
            $query = $this->db->prepare("INSERT INTO posts(user_id, image) VALUES(?, ?);");
            $isBinded = $query->bind_param("is", $userId, $image);
        }else{
            $query = $this->db->prepare("INSERT INTO posts(user_id, image, description) VALUES(?, ?, ?);");
            $isBinded = $query->bind_param("iss", $userId, $image, $description);
        }
        if($isBinded){
            return $query->execute();
        }
        return false;
    }
    public function like(int $postId, bool $status, int $userId) : bool
    {   
        $query = null;
        if($status){
            $query = $this->db->prepare("DELETE FROM likes WHERE post_id = ? AND user_id = ?;");
        }else{
            $query = $this->db->prepare("INSERT INTO likes(post_id, user_id) VALUES(?, ?);");
        }
        
        if($query->bind_param("ii", $postId, $userId)){
            return $query->execute();
        }
        return false;
    }
}