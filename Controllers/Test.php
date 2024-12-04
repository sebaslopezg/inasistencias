<?php 

class Test extends Controllers{
    public function __construct(){
        parent::__construct();
    }
    public function test(){

        $data['script'] = "test";
        $data['page_name'] = "test";
        $data['page_title'] = "Pagina de test";

        $this->views->getView($this,"test", $data);
    }
}