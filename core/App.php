<?php

class App
{
    private $controller = 'home';
    private $method = 'index';
    private $param = [];

    public function __construct()
    {
        if (isset($_GET['url'])) {
            $url = $_GET['url'];
            $url = $this->split($url);
            $this->controller = $url[0];
            unset($url[0]);
            if (isset($url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
            $this->param = array_values($url);
        }
        $path = "controller/" . $this->controller . ".php";
        if (is_file($path)) {
            include_once $path;
            $new = new $this->controller();
            $new->model($this->controller);
            if (method_exists($new, $this->method)) {
                call_user_func_array([$new, $this->method], $this->param);
            } else {
                Model::webUrl("home/error404");
            }
        } else {
            Model::webUrl("home/error404");
        }
    }

    public static function split($url)
    {
        $url = rtrim($url, '/');
        return explode('/', $url);
    }
}
