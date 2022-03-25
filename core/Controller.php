<?php

class Controller
{
    public $modelDb;
    public function view($viewURL, $data = [])
    {
        include_once "view/" . $viewURL . ".php";
    }
    public function footer($footerURL)
    {
        include_once "view/" . $footerURL . ".php";
    }
    public function header($headerURL)
    {
        include_once "view/" . $headerURL . ".php";
    }
    public function model($modelURL)
    {
        include_once "model/model_".$modelURL.".php";
        $model = "Model".$modelURL;
        $this->modelDb = new $model;
    }
}
