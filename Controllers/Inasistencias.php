<?php 

class Inasistencias extends Controllers{
    public function __construct(){
        parent::__construct();
    }
    public function inasistencias(){


        $data['page_title'] = "Página de inasistencias";
        $data['page_name'] = "inasistencias";
        $this->views->getView($this,"inasistencias", $data);
    }
}