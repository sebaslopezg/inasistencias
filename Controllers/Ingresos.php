<?php 

class Ingresos extends Controllers{
    public function __construct(){
        parent::__construct();
    }
    public function ingresos(){

        $data['page_title'] = "Página de ingresos";
        $data['page_name'] = "ingresos";
        $this->views->getView($this,"ingresos", $data);
    }
}