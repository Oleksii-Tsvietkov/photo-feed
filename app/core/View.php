<?php

namespace app\core;

class View    // ToDo: add static?
{
    /**
     * Name of layout page, 'default' by default
     */
    protected $layout;
    /**
     * Name of default page
     */
    const DEFAULT_LAYOUT = 'default';
    /**
     * Initializes class property if parameter not null
     * @var string $layout name of layout page, null by default
     */
    public function __construct(string $layout = self::DEFAULT_LAYOUT)    // ToDo: fix constant
    {
        if(!is_null($layout)){
            $this->layout = $layout;
        }
    }
    /**
     * Receives view name, extract variables from assoc array, and include file with default layout
     * @var string $pageName name of page
     * @var array $params assoc array of variables
     */
    public function render(string $pageName, array $params = []) : void
    {
        extract($params);
        unset($params);
        include_once $this->getLayoutPath();
    }
    /**
     * Returns url to "like" method with inputted values 
     * @param int $id post id
     * @param int $page number of current page
     * @param bool $status status of "like"
     * @param string $anchor anchor of post
     * @return string returns url to "like" method with inputted values 
     */
    public function getLikeUrl(int $id, int $page, bool $status, string $anchor) : string
    {
        return Route::url("index", "like", ["id" => $id, "page" => $page, "status" => $status, "anchor" => "$anchor"]);
    }
    /**
     * Returns url to inputted page
     * @param int $page number of page
     * @return string url to inputed page
     */
    public function getPageUrl(int $page)
    {
        return Route::url("index", "index", ["page" => $page]);
    }
    /**
     * Returns path to certain image depending on inputted value
     * @param bool $like boolean flag
     * @return string path to certain image
     */
    public function getLike(bool $like) : string    // ToDo: change png to svg
    {
        return $like ? $this->getResourcesPath("like-pressed.png") : $this->getResourcesPath("like-default.png");
    }
    /**
     * Returns formatted string date of inputted TimeStamp date
     * @param string $date string value of TimeStamp type from database 
     * @return string formatted string date
     */
    public function getDateTime(string $date)
    {
        $correctDate = new \DateTime($date);
        return $correctDate->format('Y-m-d\TH:i');
    }
    /**
     * Returns text format of inputted date
     * @param string $date string value of TimeStamp type from database 
     * @return string text format (day, month) of inputted date
     */
    public function getTextDate(string $date)
    {
        $dateTime = new \DateTime($date);
        $formatter = new \IntlDateFormatter(
            'en_EN', 
            \IntlDateFormatter::NONE, 
            \IntlDateFormatter::NONE, 
            null, 
            null, 
            'd MMMM y'
        );
        return $formatter->format($dateTime); 
    }
    /**
     * Return time interval mid inputted between current date with ceil date
     * @param string $date string value of TimeStamp type from database 
     * @return string returns time interval mid inputted between current date with ceil date(weeks, days, hours, minutes, seconds)
     */
    public function getTimeInterval(string $date) : string
    {
        $inputDate = new \DateTime($date);
        $currentDate = new \DateTimeImmutable();    // ToDo: fix time zone
        $interval = date_diff($inputDate, $currentDate);
        $returnInterval = '';
        if($interval->d > 7){
            $returnInterval = intval($interval->d / 7) . ' week.';
        }else if($interval->d >= 1){
            $returnInterval = $interval->d . ' d.';
        }else if($interval->h >= 1){
            $returnInterval = $interval->h . ' h.';
        }else if($interval->i >= 1){
            $returnInterval = $interval->i . ' m.';
        }else {
            $returnInterval = $interval->s . ' s.';
        }
        return $returnInterval;
    }
    /**
     * Returns certain text depending on inputted title
     * @param string $title title name
     * @return string return site description if title matches with switch case, else - return empty string
     */
    public function getDescription(?string $title) : string
    { 
        $description = '';
        switch($title){
            case 'Welcome':
                $description = LOGIN_DESCRIPTION;
                break;
            case 'Registration':
                $description = REGISTRATION_DESCRIPTION;
                break;
            case 'New post':
                $description = CREATE_DESCRIPTION;
                break;
            case null:
                $description = MAIN_DESCRIPTION;
                break;
            default:
                // ToDo: exception?
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
     * @var string $page name of page file
     * @return string path of file
     */
    public function getPagesPath(string $page) : string
    {
        return $this->getViewsDir() . 'pages' . DIRECTORY_SEPARATOR . $page . '_view.php';
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
    /**
     * Returns path to storage directory using Symlink 
     * @return string path to directory
     */
    public function getStorageDir() : string
    {
        return DIRECTORY_SEPARATOR . 'shared' . DIRECTORY_SEPARATOR;
    }
    /**
     * Returns path to file from images directory
     * @param string $fileName name of file
     * @return string path of file
     */
    public function getImagesPath(string $fileName) : string
    {
        return $this->getStorageDir() . 'images' . DIRECTORY_SEPARATOR . $fileName;
    }
    /**
     * Returns path to file from resources directory
     * @param string $fileName name of file
     * @return string path of file
     */
    public function getResourcesPath(string $fileName) : string
    {
        return DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . $fileName;
    }
}