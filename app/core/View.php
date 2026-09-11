<?php

namespace app\core;

class View
{
    /**
     * Name of layout page, 'default' by default
     */
    protected $layout = 'default';
    /**
     * Initializes class property if parameter not null
     * @var string $layout name of layout page, null by default
     */
    public function __construct(string $layout = null)
    {
        if(!is_null($layout)){
            $this->layout = $layout;
        }
    }
    /**
     * Receives view name, extract variables from assoc array, and include file returned from class method getLayoutPath()
     * @var string $viewName name of view
     * @var array $params assoc array of variables
     */
    public function render(string $viewName, array $params = []) : void
    {
        extract($params);
        include_once $this->getLayoutPath();
    }
    public function getDescription(string $title){
        $description = '';
        switch($title){
            case 'login':
                $description = LOGIN_DESCRIPTION;
                break;
            case 'registration':
                $description = REGISTRATION_DESCRIPTION;
                break;
            case 'main':
                $description = MAIN_DESCRIPTION;
                break;
            case 'create':
                $description = CREATE_DESCRIPTION;
                break;
            default:
                // ToDo: excetion
            break;
        }
        return $description;
    }
    /**
     * Returns path of layout file from class property 
     * @return string path of layout file
     */
    public function getLayoutPath() : string
    {
        return $this->getViewsDir() . 'layouts' . DIRECTORY_SEPARATOR . $this->layout . '.php';
    }
    /**
     * Returns views direction
     * @return string views direction
     */
    public function getViewsDir() : string
    {
        return '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR;
    }
    /**
     * Returns path to file from pages directory
     * @var string $view name of file
     * @return string path of file
     */
    protected function getPagesPath(string $view) : string    // ToDo: why protected?
    {
        return $this->getViewsDir() . 'pages' . DIRECTORY_SEPARATOR . $view . '_view.php';
    }
    /**
     * Returns path to file from templates directory
     * @param string $template name of file
     * @return string path of file
     */
    public function getTemplatesPath(string $template) : string
    {
        return $this->getViewsDir() . 'templates' . DIRECTORY_SEPARATOR . $template . '.php';
    }
    // ToDo: add comment
    public function getStorageDir() : string
    {
        return '/public/storage/';
    }
    // ToDo: add comment
    public function getResourcesPath(string $fileName) : string
    {
        return $this->getStorageDir() . 'resources' . DIRECTORY_SEPARATOR . $filename;
    }
}