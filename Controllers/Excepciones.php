<?php

class Excepciones extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url() . '/login');
        }
    }

    public function excepciones()
    {
        $data['page_title'] = "Pagina de excepciones";
        $data['page_name'] = "excepciones";
        $data['script'] = "excepciones";
        $this->views->getView($this, "excepciones", $data);
    }

    public function getExcepciones()
    {
        if ($_SESSION['userData']['rol'] == 'COORDINADOR') {
            $arrData = $this->model->selectExcepciones();
            for ($i = 0; $i < count($arrData); $i++) {


                if ($arrData[$i]['status'] > 0) {
                    $arrData[$i]['action'] = '<button type="button" data-id="' . $arrData[$i]['idExcepciones'] . '" data-action="editar" class="btn btn-success"><i class="bi bi-pencil-square"></i></button>
                    <button type="button" data-id="' . $arrData[$i]['idExcepciones'] . '" data-action="delete" class="btn btn-danger"><i class="bi bi-trash-fill"></i></button>';
                }

                if ($arrData[$i]['tipoExcepcion'] == 1) {
                    $arrData[$i]['tipoExcepcion'] = '<span class="badge rounded-pill bg-secondary"><i class="bi bi-person-fill"></i>    Aprendiz</span>';
                } elseif ($arrData[$i]['tipoExcepcion'] == 2) {
                    $arrData[$i]['nombreUsu'] = 'Todos los Aprendices de la ficha';
                    $arrData[$i]['tipoExcepcion'] = '<span class="badge rounded-pill bg-info"><i class="bi bi-people"></i>  Grupo</span>';
                } elseif ($arrData[$i]['tipoExcepcion'] == 3) {
                    $arrData[$i]['tipoExcepcion'] = '<span class="badge rounded-pill bg-primary"><i class="bi bi-globe"></i>  Global</span>';
                    $arrData[$i]['nombreUsu'] = 'Todos los Aprendices del CTA';
                    $arrData[$i]['nombreFicha'] = 'Todas las Fichas del CTA';
                }

                if ($arrData[$i]['status'] == 1) {
                    $arrData[$i]['status'] = '<span class="badge rounded-pill bg-success">Activo</span>';
                }
            }
            echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        }
    }

    public function setExcepciones()
    {
        $txtTipoExcepcion = intval(strClean($_POST['txtTipoExcepcion']));
        $txtFicha = isset($_POST['txtFicha']) ? intval($_POST['txtFicha']) : null;
        $txtAprendiz = isset($_POST['txtAprendiz']) ? intval($_POST['txtAprendiz']) : null;
        $txtFechaInicio = date('Y-m-d H:i:s', strtotime($_POST['txtFechaInicio']));
        $txtFechaFin = date('Y-m-d H:i:s', strtotime($_POST['txtFechaFin']));
        $txtMotivo = strClean($_POST['txtMotivo']);
        $txtExcepcionId = intval(strClean($_POST['idExcepcion']));

        $arrPosts = [
            'txtTipoExcepcion',
            'txtFechaInicio',
            'txtFechaFin',
            'txtMotivo'
        ];
        if (check_post($arrPosts)) {
            if ($txtFechaInicio < $txtFechaFin) {
                try {

                    if ($txtExcepcionId == 0 || $txtExcepcionId == "" || $txtExcepcionId == "0") {
                        $insert = $this->model->insertExcepcion(
                            $txtTipoExcepcion,
                            $txtFicha,
                            $txtAprendiz,
                            $txtFechaInicio,
                            $txtFechaFin,
                            $txtMotivo
                        );
                        $option = 1;
                    } else {
                        $insert = $this->model->updateExcepcion(
                            $txtTipoExcepcion,
                            $txtFicha,
                            $txtAprendiz,
                            $txtFechaInicio,
                            $txtFechaFin,
                            $txtMotivo,
                            $txtExcepcionId
                        );
                        $option = 2;
                    }

                    if (intval($insert) > 0) {

                        if ($option == 1) {
                            $arrResponse = array('status' => true, 'msg' => 'Excepcion creada correctamente');
                        }

                        if ($option == 2) {
                            $arrResponse = array('status' => true, 'msg' => 'Excepcion editada correctamente');
                        }
                        
                    } else if ($insert == 'existGlobal') {

                        $arrResponse = array('status' => false, 'msg' => 'Ya se encuentra registrada una excepcion Global en este rango de fechas');
                    
                    } elseif ($insert == 'existFicha') {

                        $arrResponse = array('status' => false, 'msg' => 'Ya se encuentra registrada una excepcion de esta Ficha en este rango de fechas');
                    
                    } elseif ($insert == 'existAprendiz') {

                        $arrResponse = array('status' => false, 'msg' => 'Ya se encuentra registrada una excepcion de este Aprendiz en este rango de fechas');
                    
                    } else {
                        $arrResponse = array('status' => false, 'msg' => 'Error al insertar');
                    }
                } catch (\Throwable $th) {
                    $arrResponse = array('status' => false, 'msg' => "Error desconocido: $th");
                }
            } else {
                $arrResponse = array('status' => false, 'msg' => 'El rango de fechas ingresado no es válido. La fecha de inicio debe ser anterior a la fecha de finalización.');
            }
        } else {
            $arrResponse = array('status' => false, 'msg' => 'Debe insertar todos los datos');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }


    public function selectAprendisById($id)
    {

        $intApre = intval(strClean($id));

        if ($intApre > 0) {
            $arrData = $this->model->selectAprendiz($id);
        } else {
            $arrResponse = array('status' => false, 'msg' => 'tipo de dato no permitido');
        }

        if (!empty($arrData)) {
            $arrResponse = array('status' => true, 'data' => $arrData);
        } else {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron aprendices en esta ficha');
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }

    public function getExcepcionById($id)
    {

        $intId = intval(strClean($id));

        if ($intId > 0) {
            $arrData = $this->model->selectExcepcionById($id);
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

    public function getFicha()
    {
        $arrData = $this->model->selectFicha();
        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }

    function deleteExcepciones()
    {
        if ($_POST) {
            $intIdExcepcion = intval($_POST['idExcepcion']);
            $requestDelete = $this->model->deleteExcepcion($intIdExcepcion);

            if ($requestDelete) {
                $arrResponse = array('status' => true, 'msg' => 'Se ha eliminado la excepcion');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al eliminar la excepcion');
            }

            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
}
