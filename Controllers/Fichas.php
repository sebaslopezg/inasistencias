<?php
require_once __DIR__ . '/../Models/NotificacionesModel.php';
class Fichas extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url() . '/login');
        }
        $this->NotificacionesModel = new NotificacionesModel();
    }

    function getNotificaciones()
    {

        $arrData = $this->NotificacionesModel->NotiSelect($_SESSION['userData']['idUsuarios']);

        for ($i = 0; $i < count($arrData); $i++) {
            if ($arrData[$i]['tipoNovedad'] == 'registro_inasistencia') {
                $arrData[$i]['tipoNovedad'] = 'Inasistencia';
                $arrData[$i]['icono'] = ' <i class="bi bi-person-x text-warning"></i>';
                $arrData[$i]['link'] = 'http://localhost/inasistencias/excusas';
                $arrData[$i]['action'] = $arrData[$i]['idNotificaciones'];
            } else  if ($arrData[$i]['tipoNovedad'] == 'Nueva_excusa') {
                $arrData[$i]['tipoNovedad'] = 'Excusa';
                $arrData[$i]['icono'] = '<i class="bi bi-file-earmark-text text-primary"></i>';
                $arrData[$i]['link'] = 'http://localhost/inasistencias/excusas';
                $arrData[$i]['action'] = $arrData[$i]['idNotificaciones'];
            } else  if ($arrData[$i]['tipoNovedad'] == 'Nueva_observacion') {
                $arrData[$i]['tipoNovedad'] = 'Observacion';
                $arrData[$i]['icono'] = ' <i class="bi bi-chat-left-text text-primary"></i>';
                $arrData[$i]['link'] = 'http://localhost/inasistencias/excusas';
                $arrData[$i]['action'] = $arrData[$i]['idNotificaciones'];
            } else  if ($arrData[$i]['tipoNovedad'] == 'Nueva_Denegacion') {
                $arrData[$i]['tipoNovedad'] = 'Excusa Denegada';
                $arrData[$i]['icono'] = '<i class="bi bi-file-earmark-x text-danger"></i>';
                $arrData[$i]['link'] = 'http://localhost/inasistencias/excusas';
                $arrData[$i]['action'] = $arrData[$i]['idNotificaciones'];
            } else  if ($arrData[$i]['tipoNovedad'] == 'Nueva_Aprobacion') {
                $arrData[$i]['tipoNovedad'] = 'Excusa Aprovada';
                $arrData[$i]['icono'] = '<i class="bi bi-file-earmark-check text-primary"></i>';
                $arrData[$i]['link'] = 'http://localhost/inasistencias/excusas';
                $arrData[$i]['action'] = $arrData[$i]['idNotificaciones'];
            } else  if ($arrData[$i]['tipoNovedad'] == 'plazo_excusas') {
                $arrData[$i]['tipoNovedad'] = 'Recordatorio Excusa';
                $arrData[$i]['icono'] = '<i class="bi bi-alarm text-danger"></i>';
                $arrData[$i]['link'] = 'http://localhost/inasistencias/excusas';
                $arrData[$i]['action'] = $arrData[$i]['idNotificaciones'];
            }
        }
        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }

    function eliminarNoti()
    {
        if ($_POST) {

            $idNoti = intval($_POST['idNoti']);
            $requestDelete = $this->NotificacionesModel->NotiDelet($idNoti);

            if ($requestDelete) {
                $arrResponse = array('status' => true, 'msg' => 'Se ha eliminado la notificacion');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al eliminar la notificacion');
            }

            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
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

        for ($i = 0; $i < count($arrData); $i++) {

            if ($arrData[$i]['status'] == 1) {
                $arrData[$i]['accion'] = ' <input class="switchStatus form-check-input" type="checkbox"  aria-checked="true" name="switch_status[]" role="switch" data-id="' . $arrData[$i]['idUsuarios'] . '" id="switch_status" checked>
            ';
            }
            if ($arrData[$i]['status'] == 2) {
                $arrData[$i]['accion'] = ' <input class="switchStatus form-check-input" type="checkbox"  aria-checked="false" name="switch_status[]" role="switch" data-id="' . $arrData[$i]['idUsuarios'] . '" id="switch_status">
            ';
            }
        }
        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }

    public function getInstDisponibles(int $idFicha): void
    {
        $intId = intval(strClean($idFicha));
        $arrData = $this->model->selectInstDispo($intId);

        for ($i = 0; $i < count($arrData); $i++) {
            $arrData[$i]['checkBox'] = '<input class="instruCheck form-check-input" type="checkbox" value="' . $arrData[$i]['idUsuarios'] . '" name="selecInstru[]" id="checkInstru">';
        };
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
            'txtNumeroFicha'
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
                        $arrResponse = array('status' => true, 'msg' => 'Ficha Registrada correctamente');
                    }

                    if ($option == 2) {
                        $arrResponse = array('status' => true, 'msg' => 'Ficha Actualizada correctamente');
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
            $arrResponse = array('status' => false, 'msg' => 'Debe insertar todos los datos  ');
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }

    public function setInstructor()
    {

        $arrPosts = [
            'txtIdInstructor',
            'txtIdFicha',
            'accion'
        ];

        if (check_post($arrPosts)) {


            $txtIdInstructor = strClean($_POST['txtIdInstructor']);

            $intIdFicha = intval(strClean($_POST['txtIdFicha']));

            $strAccion = strClean($_POST['accion']);

            $arrIds = explode(",", $txtIdInstructor);


            try {
                if ($strAccion == "insert") {

                    for ($i = 0; $i < count($arrIds); $i++) {
                        $insert = $this->model->insertInstructor(
                            $intIdFicha,
                            $arrIds[$i]
                        );
                    }
                    $option = 1;
                } else if ($strAccion == "update-status-2") {

                    $insert = $this->model->updateInstructor(
                        $intIdFicha,
                        $arrIds[0],
                        2
                    );

                    $option = 2;
                } elseif ($strAccion == "update-status-1") {
                    $insert = $this->model->updateInstructor(
                        $intIdFicha,
                        $arrIds[0],
                        1
                    );
                }

                if (intval($insert) > 0) {

                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'El personal ha sido asignado correctamente');
                    }
                    if ($option == 2) {
                        $arrResponse = array('status' => true, 'msg' => 'Se realizo la modificacion correctamene');
                    }
                } else if ($insert == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => 'Ya ha sido asignado a esta ficha, elija uno diferente ');
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'Error al insertar');
                }
            } catch (\Throwable $th) {
                $arrResponse = array('status' => false, 'msg' => "Error desconocido: $th");
            }
        } else {
            $arrResponse = array('status' => false, 'msg' => 'Debe insertar todos los datos  ');
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
