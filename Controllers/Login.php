<?php 

class Login extends Controllers{
    public function __construct(){
        parent::__construct();
    }
    public function login(){


        $data['page_title'] = "Página de inasistencias";
        $data['page_name'] = "login";
        $data['script'] = "login";
        $this->views->getView($this,"login", $data);
    }
}