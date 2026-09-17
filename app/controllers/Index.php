<?php

namespace app\controllers;

use app\models\Post;

class Index extends AbstractController
{
    /**
     * Name of default view page
     */
    const DEFAULT_VIEW_PAGE = 'index_index';
    /**
     * Name of default template file
     */
    const DEFAULT_TEMPLATE = 'feed_header';

    public static function getInstance() : static
    {
        $instance = parent::getInstance();
        if(!isset($instance->model)){
            $instance->model = Post::getInstance();
        }
        return $instance;
    }
    public function index(array $params = []) : void
    {
        $this->checkLogin(false);

        if($params !== []){
            extract($params);
        }
        if(!isset($currentPage)){
            $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        }
        
        extract($this->model->getLimit($currentPage));
        $params['posts'] = $this->model->getPosts($_SESSION['user']['id'], $offset, $limit);
        session_start();
        $params['user'] = $_SESSION['user'];
        $params['templateName'] = self::DEFAULT_TEMPLATE;    // ToDo: add all to array
        $params['currentPage'] = $currentPage;
        $params['pagesCount'] = $pagesCount ?? $this->model->getPagesCount();
        $this->view->render(self::DEFAULT_VIEW_PAGE, $params);
    }
    public function like() : void    /* ToDo: maybe just change color. change like status only after page updating */
    {
        $this->checkLogin(false);

        $postId = filter_input(INPUT_GET, 'id');
        $currentPage = filter_input(INPUT_GET, 'page');
        $likeStatus = filter_input(INPUT_GET, 'status');
        $pagesCount = filter_input(INPUT_GET, 'count');
        $anchor = $_GET['anchor'];

        try{
            $postId = $this->validateInputedValue($postId, 'postId', 0, PHP_INT_MAX, true);
            $pagesCount = $this->validateInputedValue($pagesCount, 'pagesCount', 1, PHP_INT_MAX, true);    // ToDo: change max value?
            
            $likeStatus = intval($likeStatus);
            if($likeStatus != 1 && $likeStatus != 0){
                throw new InvalidArgumentException('Status not boolean');
            }
            $likeFlag = boolval($likeStatus); 
            $currentPage = $this->validateInputedValue($currentPage, 'currentPage', 1, $pagesCount, true);

            session_start();
            if(!$this->model->like($postId, $likeFlag, $_SESSION['user']['id'])){
                // ToDo: process       
            }
            $this->index([
                "currentPage" => intval($currentPage),
                "pagesCount" => intval($pagesCount),
                /*"anchor" => $anchor,*/    // ToDo: fix
            ]);
        }catch (\InvalidArgumentException $e){
            Route::unprocessibleEntity();    // ToDo: replace to response?
        }finally{
            exit();
        }
    }
}