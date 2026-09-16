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
     * Name of default template file
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

        $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        extract($this->model->getLimit($currentPage));

        $params = [];
        $params['posts'] = $this->model->getPosts($_SESSION['user']['id'], $offset, $limit);
        session_start();
        $params['user'] = $_SESSION['user'];
        $params['templateName'] = self::DEFAULT_TEMPLATE;    // ToDo: add all to array
        $params['currentPage'] = $currentPage;
        $params['pagesCount'] = $this->model->getPagesCount();

        $this->view->render(self::DEFAULT_VIEW_PAGE, $params);
    }
}