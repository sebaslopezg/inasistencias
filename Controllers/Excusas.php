<?php

class Excusas extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        if(isset($_SESSION['login'])){
            header('Location: ' . base_url().'/login' );
        }
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
            $arrData[$i]['instructor'] = $this->model->selectInstructor($arrData[$i]['idInstructor']);
            $arrVali[$i]['total'] = $this->model->validacionExcusa($arrData[$i]['Id']);
            $arrExcu[$i]['excusaId'] = $this->model->selectIdExcusa($arrData[$i]['Id']);

            $arrData[$i]['total'] = $arrVali[$i]['total']['total'];


            if (empty($arrData[$i]['total']) || $arrData[$i]['total'] == 0 || empty($arrExcu[$i]['excusaId'])) {
                $arrData[$i]['action'] = '
                <button type="button" data-id="' . $arrData[$i]['Id'] . '" data-action="agregar" class="btn btn-primary"><i class="bi bi-paperclip"></i></button>';
            } else {
                $arrData[$i]['excusaId'] = $arrExcu[$i]['excusaId']['excusaId'];
                $arrData[$i]['action'] = '
                <button type="button" data-id="' . $arrData[$i]['excusaId'] . '" data-action="editar" class="btn btn-success"><i class="bi bi-pencil-square"></i></button>
                <button type="button" data-id="' . $arrData[$i]['excusaId'] . '" data-action="delete" class="btn btn-danger"><i class="bi bi-trash"></i></button>';
            }

            if ($arrData[$i]['status'] == 1) {
                $arrData[$i]['status'] = '<span class="badge rounded-pill bg-success">Activo</span>';
            }
            if ($arrData[$i]['status'] == 2) {
                $arrData[$i]['status'] = '<span class="badge rounded-pill bg-danger">Inactivo</span>';
            }
        }
        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }

    public function getInasistenciaById($id)
    {

        $intId = intval(strClean($id));

        if ($intId > 0) {
            $arrData = $this->model->selectInasistenciaById($id);
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

    public function selectExcusaId($id)
    {

        $intExc = intval(strClean($id));

        if ($intExc > 0) {
            $arrData = $this->model->selectExcusaId($id);
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



    public function setExcusas()
    {
        $intInasistencia = intval(strClean($_POST['txtIdInasistencia']));
        $intIdUsuario = intval(strClean($_POST['txtIdUsuario']));
        $intIdInstructor = intval(strClean($_POST['txtIdInstructor']));
        $strArchivo = strClean($_FILES['txtArchivo']['name']);
        $intEstado = intval(strClean($_POST['txtEstado']));
        $intExcusa = intval(strClean($_POST['txtIdExcusa']));
        $imagenRuta = "";

        $arrPosts = [
            'txtIdInasistencia',
            'txtIdUsuario'
        ];
        $arrFile = [
            'txtArchivo'
        ];
        if (check_post($arrPosts) && check_file($arrFile)) {

            try {
                $imagenRuta = save_image('txtArchivo');
                if ($intExcusa == 0 || $intExcusa == "" || $intExcusa == "0") {
                    $insert = $this->model->insertExcusas(
                        $intInasistencia,
                        $intIdUsuario,
                        $intIdInstructor,
                        $imagenRuta,    
                    );
                    $option = 1;
                } else {
                    $insert = $this->model->updateExcusa(
                        $intExcusa,
                        $imagenRuta,
                    );
                    $option = 2;
                }

                if (intval($insert) > 0) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'Excusa enviada correctamente');
                    }

                    if ($option == 2) {
                        $arrResponse = array('status' => true, 'msg' => 'Excusa editada correctamente');
                    }
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'Error al insertar');
                }
            } catch (\Throwable $th) {
                $arrResponse = array('status' => false, 'msg' => "Error desconocido: $th");
            }
        } else {
            $arrResponse = array('status' => false, 'msg' => 'Debe insertar todos los datos' /* . $_POST['txtArchivo'] . $_POST['txtIdInasistencia'] . $_POST['txtIdUsuario'] */);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }


    function deleteExcusas()
    {
        if ($_POST) {
            $intIdExcusa = intval($_POST['txtIdExcusa']);
            $requestDelete = $this->model->deleteExcusa($intIdExcusa);

            if ($requestDelete) {
                $arrResponse = array('status' => true, 'msg' => 'se ha eliminado la excusa');
            } else {
                $arrResponse = array('status' => true, 'msg' => 'error al eliminar la excusa');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
}
