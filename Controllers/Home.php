<?php 

class Home extends Controllers{
    public function __construct(){
        parent::__construct();
    }
    public function home(){

        $data['page_title'] = "Página principal";
        $data['page_name'] = "home";
        $this->views->getView($this,"home", $data);
    }
    public function getSaludo(){
        echo "saludos!!!!!!";
    }
}