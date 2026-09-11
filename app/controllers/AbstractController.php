<?php

namespace app\controllers;

use app\core\controllerable;
use app\core\View;

abstract class AbstractController implements controllerable
{
    /**
     * Object of class View
     */
    protected View $view;
    /**
     * Initialize properties
     * @param string $layout name of layout page, null by default
     */
    public function __construct(string $layout = null)
    {
        $this->view = new View($layout);
    }
}