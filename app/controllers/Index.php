<?php

namespace app\controllers;

use app\models\Photo;

class Index extends AbstractController
{
    /**
     * Name of default view page
     */
    const DEFAULT_VIEW_PAGE = 'index_index';
    /**
     * Name of defaut template file
     */
    const DEFAULT_TEMPLATE = 'feed_header';
    
    public static function getInstance() : static
    {
        $instance = parent::getInstance();
        if(!isset($instance->model)){
            $instance->model = Photo::getInstance();
        }
        return $instance;
    }
    public function index() : void
    {
        $this->checkLogin(false);

        $params = [];
        
        /*$params['posts'] = $this->model->getPosts();*/
        session_start();
        $params['user'] = $_SESSION['user'];
        $params['title'] = '';
        $params['templateName'] = self::DEFAULT_TEMPLATE;

        $this->view->render(self::DEFAULT_VIEW_PAGE, $params);
    }
}