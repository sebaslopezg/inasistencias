<?php

class Excusas extends Controllers
{
    public function __construct()
    {
        parent::__construct();
    }

    public function excusas()
    {
        $data['page_title'] = "Pagina de excusas";
        $data['page_name'] = "excusas";
        $data['script'] = "excusas";
        $this->views->getView($this, "excusas", $data);
    }

    public function getExcusas()
    {
        $arrData = $this->model->selectExcusas();

        for ($i = 0; $i < count($arrData); $i++) {
            $arrData[$i]['action'] = '<button type="button" data-action="agregar" data-id="' . $arrData[$i]['id'] . '" class="btn btn-primary"><i class="bi bi-paperclip"></i></button>
            <button type="button" data-action="edit" data-id="' . $arrData[$i]['id'] . '" class="btn btn-success"><i class="bi bi-pencil-square"></i></button>
            ';

            if ($arrData[$i]['status'] == 1) {
                $arrData[$i]['status'] = '<span class="badge rounded-pill bg-success">Activo</span>';
            }
            if ($arrData[$i]['status'] == 2) {
                $arrData[$i]['status'] = '<span class="badge rounded-pill bg-danger">Inactivo</span>';
            }
        }
        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
}
