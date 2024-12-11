<?php

class Fichas extends Controllers
{
    public function __construct()
    {
        parent::__construct();
    }
    public function fichas()
    {

        $data['page_title'] = "Página de Fichas";
        $data['page_name'] = "fichas";
        $data['script'] = "fichas";
        $this->views->getView($this, "fichas", $data);
    }

    public function getFichas()
    {
        $arrData = $this->model->selectFicha();

        for ($i = 0; $i < count($arrData); $i++) {
            $arrData[$i]['accion'] = '
            <button type="button" data-action="delete" data-id="' . $arrData[$i]['id'] . '" class="btn btn-danger"><i class="bi bi-trash"></i></button>
            <button type="button" data-action="edit" data-id="' . $arrData[$i]['id'] . '" class="btn btn-primary"><i class="bi bi-pencil-square"></i></button>
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
    public function getFichasPreview()
    {
        $arrData = $this->model->selectFicha();

        for ($i = 0; $i < count($arrData); $i++) {
            $arrData[$i]['accion'] = '
            <button type="button" data-action="info" data-id="' . $arrData[$i]['id'] . '" class="btn btn-primary"><i class="bi bi-exclamation-circle-fill"></i></button>
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

    public function getInfoInstructoresFicha(int $idFicha): void
    {
        $intId = intval(strClean($idFicha));
        $arrData = $this->model->selectInfoInstructoresById($intId);

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }

    public function getInfoAprendicesFicha(int $idFicha): void
    {
        $intId = intval(strClean($idFicha));
        $arrData = $this->model->selectInfoAprendicesFichaById($intId);

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    public function getFichaById($id)
    {

        $intId = intval(strClean($id));

        if ($intId > 0) {
            $arrData = $this->model->selectFichaById($id);
        } else {
            $arrResponse = array('status' => false, 'msg' => 'tipo de dato no permitido');
        }

        if (!empty($arrData)) {
            $arrResponse = array('status' => true, 'data' => $arrData);
        } else {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos con este id');
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }

    public function setFicha()
    {
        $arrPosts = [
            'txtNombre',
            'txtNumeroFicha',
            'txtIdFicha',
            'userStatus'
        ];
        if (check_post($arrPosts)) {
            $strNombre = strClean($_POST['txtNombre']);
            $intNumeroFicha = intval(strClean($_POST['txtNumeroFicha']));
            $intIdFicha = intval(strClean($_POST['txtIdFicha']));
            $intStatus = intval(strClean($_POST['userStatus']));
            try {
                if ($intIdFicha == 0 || $intIdFicha == "" || $intIdFicha == "0") {
                    $insert = $this->model->insertFicha(
                        $strNombre,
                        $intNumeroFicha
                    );
                    $option = 1;
                } else {
                    if ($intStatus == 0) {
                        $intStatus = 1;
                    } else {
                        $intStatus = 1;
                    }
                    $insert = $this->model->updateFicha(
                        $strNombre,
                        $intIdFicha,
                        $intStatus
                    );
                    $option = 2;
                }

                if (intval($insert) > 0) {

                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'Ficha insertada correctamente');
                    }

                    if ($option == 2) {
                        $arrResponse = array('status' => true, 'msg' => 'Ficha actualizada correctamente');
                    }
                } else if ($insert == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => 'Ya existe una Ficha con el mismo numero');
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'Error al insertar');
                }
            } catch (\Throwable $th) {
                $arrResponse = array('status' => false, 'msg' => "Error desconocido: $th");
            }
        } else {
            $arrResponse = array('status' => false, 'msg' => 'Debe insertar todos los datos');
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }

    function deleteFicha()
    {
        if ($_POST) {
            $idFicha = intval($_POST['idFicha']);
            $requestDelete = $this->model->deleteFicha($idFicha);

            if ($requestDelete) {
                $arrResponse = array('status' => true, 'msg' => 'Se ha eliminado la Ficha');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al eliminar la Ficha');
            }

            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
}
