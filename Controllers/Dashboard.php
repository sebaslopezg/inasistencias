<?php 

class Dashboard extends Controllers{
    public function __construct(){
        parent::__construct();
    }
    public function dashboard(){

        $data['page_title'] = "Página de dashboard";
        $data['page_name'] = "dashboard";
        $this->views->getView($this,"dashboard", $data);
    }
}