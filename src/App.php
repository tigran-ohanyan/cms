<?php
class App {
    protected $router;
    protected $request;
    protected $view;

    public function __construct() {
        $this->request = $_SERVER;
        $this->view = new View();
        $this->router = new Router($this->request, $this->view);
    }

    public function run() {
        $this->router->dispatch();
    }
}
