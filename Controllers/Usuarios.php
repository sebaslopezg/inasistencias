<?php 

class Usuarios extends Controllers{
    public function __construct(){
        parent::__construct();
    }
    public function usuarios(){

        $data['page_title'] = "Página de usuarios";
        $data['page_id_name'] = "usuarios";
        $this->views->getView($this,"usuarios", $data);
    }
}