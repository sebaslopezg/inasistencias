<?php

class Ingresos extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url() . '/login');
        }
    }
    public function ingresos()
    {

        $data['page_title'] = "Página de ingresos";
        $data['page_name'] = "ingresos";
        $this->views->getView($this, "ingresos", $data);
    }
}
