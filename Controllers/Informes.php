<?php
class Informes extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        /* if (!empty($_SESSION['login'])) {
            header('location:'.base_url().'/login');
        } */
    }
    public function informes()
    {
        $data['page_title'] = "Página de Informes";
        $data['page_name'] = "informes";
        $data['script'] = "informes";
        $this->views->getView($this, "informes", $data);
    }



    public function getFichas()
    {
        $arrData = $this->model->selectFicha();
        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    public function getInfoAprendicesFicha(int $idFicha)
    {
        $arrData = $this->model->selectInfoAprendicesById($idFicha);

        for ($i = 0; $i < count($arrData); $i++) {
            $arrData[$i]['accion'] = '
           
            <button type="button" data-action="info" data-id="' . $arrData[$i]['id'] . '" class="btn btn-primary"><i class="bi bi-info-circle-fill"></i></button>
            ';
        }
        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
}
