<?php

namespace app\controllers;

use app\models\Post;

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
        $this->checkMethod(false);
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
        $this->view->render(self::DEFAULT_PAGE, $params);
    }
    public function like() : void    /* ToDo: maybe just change color. change like status only after page updating */
    {
        $this->checkMethod(false);
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
    public function create(array $params = []) : void
    {
        if($params === []){
            $this->checkMethod(false);
        }
        $this->checkLogin(false);
        
        $params['title'] = 'New Post';
        $params['templateName'] = self::CREATION_TEMPLATE;
        $this->view->render(self::CREATION_PAGE, $params);
    }
    public function add() : void
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
        }finally{
            exit();
        }
    }
    protected function validateFile(callable $path, string $name) : array
    {
        if(!isset($_FILES[$name])){
            throw new \InvalidArgumentException('No file for upload');
        }
        $file = $_FILES[$name];
        if($file['error'] !== UPLOAD_ERR_OK){
            throw new \InvalidArgumentException(FILE_UPLOAD_ERRORS[$file['error']]);
        }
        if(!is_uploaded_file($_FILES[$name]['tmp_name'])){
            throw new \InvalidArgumentException('File was uploaded using a GET method');
        }
        if (!str_starts_with($file['type'], AVAILABLE_TYPE)){
            throw new \InvalidArgumentException('Not available file type')      ;
        }
        if($file['size'] > PHOTO_MAX_FILE_SIZE){
            throw new \InvalidArgumentException('File too large.');
        }
        $fileName = $this->mkNwName($file['name']);
        $filePath = $path($fileName);
        if(!move_uploaded_file($file['tmp_name'], $filePath)){
            throw new \InvalidArgumentException('Some problems during moving uploaded file');
        }
        return ['oldFileName' => $file['name'], 'fileName' => $fileName];    // ToDo: check
    }
    /**
     * Create new file name
     * @param string file name needed to change
     * @return string new file name with same extension
     */
    private function mkNwName(string $fileName): string
    {
        $ext = strrchr($fileName, '.');
        return uniqid() . $ext;
    }
    /**
     * Returns path to file from images directory
     * @param string $fileName name of file
     * @return string path of file
     */
    private function getImagesDir(string $fileName) : string
    {
        return dirname(__DIR__) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . $fileName;
    }
}