<?php

namespace app\core;

class View
{
    /**
     * Name of template page, 'default' by default
     */
    protected $template = 'default';
    /**
     * Initializes class property if parameter not null
     * @var string $template name of template page, null by default
     */
    public function __construct(string $template = null)
    {
        if(!is_null($template)){
            $this->template = $template;
        }
    }
    /**
     * Receives view name, extract variables from assoc array, and include file returned from class method getTemplatePath()
     * @var string $viewName name of view
     * @var array $params assoc array of variables
     */
    public function render(string $viewName, array $params = []) : void
    {
        extract($params);
        include_once $this->getTemplatePath();
    }
    /**
     * Returns path of template file from class property 
     * @return string path of template file
     */
    public function getTemplatePath() : string
    {
        return $this->getViewsDir() . 'templates' . DIRECTORY_SEPARATOR . $this->template . '.php';
    }
    /**
     * Returns views direction
     * @return string views direction
     */
    public function getViewsDir() : string
    {
        return '../app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR;
    }
    /**
     * Returns path of file from parameter view
     * @var string $view name of file
     * @return string path of file
     */
    protected function getViewsPath(string $view) : string
    {
        return $this->getViewsDir() . 'pages' . DIRECTORY_SEPARATOR . $view . '_view.php';
    }
}