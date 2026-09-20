<?php

namespace app\models;

class Post extends AbstractModel
{
    /**
     * Name of table in Data Base
     */
    private const TABLE_NAME = "posts";
    /**
     * Count of posts on page
     */
    private const POSTS_PER_PAGE = 5;
    /**
     * Count of pages buttons on page
     */
    private const BUTTONS_PER_PAGE = 5;
    /**
     * Total posts count
     */
    private int $postsTotal;
    /**
     * Count of posts on first page
     */
    private int $firstPagePosts;
    /**
     * Count of pages
     */
    private int $pagesTotal;
    /**
     * Returns instance of this class from parent method, initializes properties if it's not initialized
     * @param $args needed for identical signature with parent method
     * @return static returns instance of this class
     */
    public static function getInstance(...$args) : static
    {
        $instance = parent::getInstance();
        if(!isset($instance->postsTotal) || !isset($instance->pagesTotal) || !isset($instance->firstPagePosts)){
            $instance->pagination();
        }
        return $instance;
    }
    /**
     * Returns array of posts from database table if query executed success
     * @param int $userId id of current user, required for data about likes
     * @param int $currentPage number of current page
     * @return array returns array of posts if query executed success, else return empty array
     */
    public function getPosts(int $userId, int $currentPage) : array
    {
        extract($this->getLimit($currentPage));
        $posts = [];
        $query = $this->db->prepare("SELECT u.image as 'user_image', u.login AS 'username', p.id, p.image, p.description, p.published_at AS 'date', COUNT(l.id) AS likes_count, MAX(IF(l.user_id = ?, 1, 0)) AS 'like_status' FROM posts AS p LEFT JOIN users AS u ON p.user_id = u.id LEFT JOIN likes AS l ON p.id = l.post_id GROUP BY u.image, u.login, p.id, p.image, p.description, p.published_at ORDER BY p.published_at DESC LIMIT ?, ?;");    
        if($query->bind_param("iii", $userId, $offset, $limit)){
            if($query->execute()){
                $result = $query->get_result();
                $posts = $result->fetch_all(MYSQLI_ASSOC); 
            }
        }
        return $posts;
    }
    /**
     * Adds new post to database table
     * @param int $userId id of current user
     * @param string $image path to image to be added
     * @param ?string $description post text, may be absent
     * @return bool returns true if query executed success, else return false
     */
    public function add(int $userId, string $image, ?string $description) : bool
    {
        $query = $this->db->prepare("INSERT INTO posts(user_id, image, description) VALUES(?, ?, ?);");
        if($query->bind_param("iss", $userId, $image, $description)){
            return $query->execute();
        }
        return false;
    }
    /**
     * Adds or removess "like" for post in database table
     * @param int $postId id of post
     * @param bool $status status of "like" mark, it already added or not, determines which request is used.
     * @param int $userId id of current user
     * @return bool returns true if query executed success, else return false
     */
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
    /**
     * Returns count of pages buttons
     * @param int $currentPage number of current page
     * @return array returns two values: numbers of first and of last page button on page
     */
    public function getButtonsCount(int $currentPage) : array
    {
        $currentBlock = ceil($currentPage / self::BUTTONS_PER_PAGE);
        $start = ($currentBlock - 1) * self::BUTTONS_PER_PAGE + 1;
        $end = $currentBlock * self::BUTTONS_PER_PAGE;
        $end = $end < $this->pagesTotal ? $end : $this->pagesTotal;
        return [
            'start' => $start, 
            'end' => $end,
        ];
    }
    /**
     * Returns value of property pagesTotal
     * @return int value of property pagesTotal
     */
    public function getpagesTotal() : int
    {
        return $this->pagesTotal;
    }
    /**
     * Calculating and initializing values of total posts count, count of posts on first page and total pages count
     */
    private function pagination() : void
    {
        $total = $this->getPostsTotal();
        $remainder = $total % self::POSTS_PER_PAGE;
        $this->firstPagePosts = ($remainder == 0) ? self::POSTS_PER_PAGE : $remainder;
        $this->pagesTotal = ceil($total / self::POSTS_PER_PAGE);
    }
    /**
     * Calculating and returns two values: counts of skipped and selected posts for series of posts
     * @param int $currentPage number of current page
     * @return array return counts of skipped and selected posts
     */
    private function getLimit(int $currentPage) : array
    {
        $limit = null;
        $offset = null;
        if($currentPage === 1){
            $limit = $this->firstPagePosts;
            $offset = 0;
        }else{
            $limit = self::POSTS_PER_PAGE;
            $offset = $this->firstPagePosts + ($currentPage - 2) * self::POSTS_PER_PAGE;
        }
        return ['limit' => $limit, 'offset' => $offset];
    }
    /**
     * Returns total posts count, received from database table
     * @return ?int returns total posts count or null if query executed not success
     */
    private function getPostsTotal() : ?int
    {
        $count = null;
        $query = $this->db->prepare("SELECT COUNT(id) FROM posts;");
        if($query->execute()){
            $result = $query->get_result();
            $count = $result->fetch_column();
        }
        return $count;
    }
}