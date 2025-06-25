<?php 

class Home extends Controllers{
    public function __construct(){
        parent::__construct();
    }
    public function home(){
        header('Location: ' . base_url() . '/dashboard');
        $data['page_title'] = "Página principal";
        $data['page_name'] = "home";
        $this->views->getView($this,"home", $data);
    }
}