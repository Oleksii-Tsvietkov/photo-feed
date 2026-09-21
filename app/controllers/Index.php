<?php

namespace app\controllers;

use app\models\Post;
use app\core\Route;

class Index extends AbstractController
{
    /**
     * Name of default page
     */
    const DEFAULT_PAGE = 'index_index';
    /**
     * Name of post creation page
     */
    const CREATION_PAGE = 'index_create';
    /**
     * Name of default template file
     */
    const DEFAULT_TEMPLATE = 'feed_header';
    /**
     * Name of creation template file
     */
    const CREATION_TEMPLATE = 'creation_header';
    /**
     * Error message, showing if error happened during add like
     */
    const LIKE_ERROR = 'Some problems during add like.';
    /**
     * Error message, showing if error happened during add post
     */
    const POST_ERROR = 'Some problems during add post.';
    /**
     * Returns instance of this class from parent method, initializes property model if it not initialized
     * @param static returns instance of this class
     */
    public static function getInstance() : static
    {
        $instance = parent::getInstance();
        if(!isset($instance->model)){
            $instance->model = Post::getInstance();
        }
        return $instance;
    }
    /**
     * Checks method being used and whether the user is logged in, receives pagination params, array of posts, and user data, calls method to render default page with those params
     * @param array $params optional params to be added to render method, empty array by default
     */
    public function index(array $params = []) : void
    {
        $this->checkMethod(false);
        $this->checkLogin(false);
        
        //$currentPage = $_GET['currentPage'] ?? null;    // ToDo: temporary solution before replace like method
        if($params !== []){
            extract($params);
        }
        if(!isset($currentPage)){
            $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        }

        extract($this->model->getButtonsCount($currentPage));
        session_start();
        $params += [
            'title' => '',
            'posts' => $this->model->getPosts($_SESSION['user']['id'], $currentPage),
            'user' => $_SESSION['user'],
            'templateName' => self::DEFAULT_TEMPLATE,
            'currentPage' => $currentPage,
            'pagesCount' => $pagesCount ?? $this->model->getpagesTotal(),
            'startPage' => $start,
            'endPage' => $end,
        ];
        $this->view->render(self::DEFAULT_PAGE, $params);
    }
    /**
     * Checks method being used and whether the user is logged in, receives params of post from GET, validates it and trying to change "like" status in database table, if error happen return status code 422, in event of success "like" status and redirect to same page with anchor
     */
    public function like() : void    /* ToDo: maybe just change color and change like status only after page updating or replace method to post */
    {
        $this->checkMethod(false);
        $this->checkLogin(false);

        $postId = filter_input(INPUT_GET, 'id');
        $currentPage = filter_input(INPUT_GET, 'page');
        $likeStatus = filter_input(INPUT_GET, 'status');
        $anchor = filter_input(INPUT_GET, 'anchor');
        try{
            $postId = $this->validateInputedValue($postId, 'postId', false, 0, PHP_INT_MAX, true);
            
            $likeStatus = intval($likeStatus);
            if($likeStatus != 1 && $likeStatus != 0){
                throw new InvalidArgumentException('Status not boolean');
            }
            $likeFlag = boolval($likeStatus); 
            session_start();
            if(!$this->model->like($postId, $likeFlag, $_SESSION['user']['id'])){
                throw new InvalidArgumentException(self::LIKE_ERROR);
            }
            Route::redirect(Route::url('index', 'index', ['page' => "$currentPage", "#$anchor"]));    // ToDo: temporary solution before replace 'like' method
        }catch (\InvalidArgumentException $e){
            Route::unprocessibleEntity();    // ToDo: change for responce?
        }
        exit();
    }
    /**
     * Checks method being used and whether the user is logged in, receives optional params, adds to him some values and calls method to render creation page with those params
     * @param array $params optional params to be added to render method, empty array by default
     */
    public function create(array $params = []) : void
    {
        if($params === []){
            $this->checkMethod(false);
        }
        $this->checkLogin(false);
        
        $params += [
            'title' => 'New Post',
            'templateName' => self::CREATION_TEMPLATE,
        ];
        $this->view->render(self::CREATION_PAGE, $params);
    }
    /**
     * Checks method being used and whether the user is logged in, trying to validate inputtedfile and receive params, returns to creation page with with those params or with error message if error occured 
     */
    public function add() : void    // ToDo: must show preview of inputtedimage; change method after adding JS
    {
        $this->checkMethod();
        $this->checkLogin(false);
        try{
            extract($this->validateFile($this->getImagesDir(...), 'image'));
            $this->create([
                'inputedImage' => $fileName,
                'oldFileName' => $oldFileName,
            ]);
        }catch (\InvalidArgumentException $e){
            $this->create([
                'errorMessage' => $e->getMessage(),
            ]);
        }
        exit();
    }
    /**
     * Checks method being used and whether the user is logged in, receives inputtedfile from POST, trying to receive and validate description and add this data to database table, if error occured - returns back to creation page with error message, in event of success redirect to default page
     */
    public function store() : void
    {
        $this->checkMethod();
        $this->checkLogin(false);

        // ToDo: validate input file if it not almost loaded
        $image = filter_input(INPUT_POST, 'image');  
        $description = null;  
        try{
            if(!empty($_POST['description'])){
                $description = $_POST['description'];
                $description = $this->validateInputedValue($description, 'Description', false, 1, DESCRIPTION_MAX);
            }
            session_start();
            $userId = $_SESSION['user']['id'];

            $imagePath = $this->view->getImagesPath($image);    // ToDo: if file almost loaded, change that after adding JS
            if(!$this->model->add($userId, $imagePath, $description)){
                throw new InvalidArgumentException(self::POST_ERROR);
            }   
            Route::redirect(Route::url());
        }catch (\InvalidArgumentException $e){
            $this->create([
                'errorMessage' => $e->getMessage(),
            ]);
        }
        exit();
    }
}