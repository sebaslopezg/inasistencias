<?php 

class Horario extends Controllers{
    public function __construct(){
        parent::__construct();
    }
    public function horario(){

        $data['script'] = "horario";
        $data['page_name'] = "horario";
        $data['page_title'] = "Cargar Horarios";

        $this->views->getView($this,"horario", $data);
    }
}