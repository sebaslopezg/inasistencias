<?php
require_once __DIR__ . '/../Models/NotificacionesModel.php';
class Excepciones extends Controllers
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
                    $arrData[$i]['action'] = '<button type="button" data-id="' . $arrData[$i]['idExcepciones'] . '" data-action="editar" class="btn btn-success rounded-circle"><i class="bi bi-pencil-square"></i></button>
                <button type="button" data-id="' . $arrData[$i]['idExcepciones'] . '" data-action="delete" class="btn btn-danger rounded-circle"><i class="bi bi-trash-fill"></i></button>';
                }


                if ($arrData[$i]['tipoExcepcion'] == 1) {
                    $arrData[$i]['tipoExcepcion'] = '<span class="badge rounded-pill bg-secondary"><i class="bi bi-person-fill"></i> Aprendiz</span>';
                } elseif ($arrData[$i]['tipoExcepcion'] == 2) {
                    $arrData[$i]['tipoExcepcion'] = '<span class="badge rounded-pill bg-info"><i class="bi bi-people"></i> Grupo</span>';
                    $arrData[$i]['aprendices'] = 'Todos los Aprendices de la Ficha';
                } elseif ($arrData[$i]['tipoExcepcion'] == 3) {
                    $arrData[$i]['tipoExcepcion'] = '<span class="badge rounded-pill bg-primary"><i class="bi bi-globe"></i> Global</span>';
                    $arrData[$i]['aprendices'] = 'Todos los Aprendices del CTA';
                    $arrData[$i]['nombreFicha'] = 'Todas las Fichas del CTA';
                }


                if ($arrData[$i]['status'] == 1) {
                    $arrData[$i]['status'] = '<span class="badge rounded-pill bg-success">Activo</span>';
                }
            }

            echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        }
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

    public function setExcepciones()
    {

        $txtTipoExcepcion = intval(strClean($_POST['txtTipoExcepcion']));
        $txtFicha         = isset($_POST['txtFicha']) ? intval($_POST['txtFicha']) : null;
        $txtFechaInicio   = date('Y-m-d H:i:s', strtotime($_POST['txtFechaInicio']));
        $txtFechaFin      = date('Y-m-d H:i:s', strtotime($_POST['txtFechaFin']));
        $txtMotivo        = strClean($_POST['txtMotivo']);
        $txtExcepcionId   = intval(strClean($_POST['idExcepcion']));


        $aprendicesSeleccionados = [];
        if (isset($_POST['aprendices']) && is_array($_POST['aprendices'])) {
            foreach ($_POST['aprendices'] as $item) {
                $aprendicesSeleccionados[] = intval(strClean($item));
            }
        }


        $arrPosts = [
            'txtTipoExcepcion',
            'txtFechaInicio',
            'txtFechaFin',
            'txtMotivo'
        ];
        if (!check_post($arrPosts)) {
            echo json_encode([
                'status' => false,
                'msg'    => 'Debe insertar todos los datos obligatorios.'
            ], JSON_UNESCAPED_UNICODE);
            return;
        }


        if ($txtFechaInicio >= $txtFechaFin) {
            echo json_encode([
                'status' => false,
                'msg'    => 'La fecha de inicio debe ser anterior a la de finalización.'
            ], JSON_UNESCAPED_UNICODE);
            return;
        }


        if ($txtTipoExcepcion === 1 && count($aprendicesSeleccionados) === 0) {
            echo json_encode([
                'status' => false,
                'msg'    => 'Para excepciones de tipo Aprendiz, debe seleccionar al menos un aprendiz.'
            ], JSON_UNESCAPED_UNICODE);
            return;
        }


        try {
            if ($txtExcepcionId === 0) {
                $resultado = $this->model->insertExcepcion(
                    $txtTipoExcepcion,
                    $txtFicha,
                    ($txtTipoExcepcion === 1 ? $aprendicesSeleccionados : null),
                    $txtFechaInicio,
                    $txtFechaFin,
                    $txtMotivo
                );
            } else {
                $resultado = $this->model->updateExcepcion(
                    $txtTipoExcepcion,
                    $txtFicha,
                    ($txtTipoExcepcion === 1 ? $aprendicesSeleccionados : null),
                    $txtFechaInicio,
                    $txtFechaFin,
                    $txtMotivo,
                    $txtExcepcionId
                );
            }
            
            if (is_array($resultado) && $resultado[0] === 'existAprendiz') {
                // Construir mensaje con nombres…
                echo json_encode([
                    'status' => false,
                    'msg'    => "El/Los aprendiz(es) X ya tiene(n) excepción en ese rango."
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            
            if (is_string($resultado)) {
                switch ($resultado) {
                    case 'existGlobal':
                        $msg = 'Ya existe una excepción Global en ese rango.';
                        break;
                    case 'existFicha':
                        $msg = 'Ya existe una excepción para esa Ficha en ese rango.';
                        break;
                    case 'existAprendiz':
                        $msg = 'Al menos uno de los aprendices ya tiene excepción en ese rango.';
                        break;
                    default:
                        
                        $msg = $resultado;
                        break;
                }
                echo json_encode([
                    'status' => false,
                    'msg'    => $msg
                ], JSON_UNESCAPED_UNICODE);
                return;
            }


            if ((is_int($resultado) && $resultado > 0) || ($resultado === true)) {
                $mensaje = ($txtExcepcionId === 0)
                    ? 'Excepción creada correctamente.'
                    : 'Excepción editada correctamente.';
                echo json_encode([
                    'status' => true,
                    'msg'    => $mensaje
                ], JSON_UNESCAPED_UNICODE);
                return;
            }


            
            $msgFallback = is_string($resultado)
                ? $resultado
                : 'Error al procesar la excepción.';
            echo json_encode([
                'status' => false,
                'msg'    => $msgFallback
            ], JSON_UNESCAPED_UNICODE);
            return;
        } catch (\Throwable $th) {
            echo json_encode([
                'status' => false,
                'msg'    => "Error inesperado: {$th->getMessage()}"
            ], JSON_UNESCAPED_UNICODE);
            return;
        }
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
        if ($intId <= 0) {
            echo json_encode([
                'status' => false,
                'msg'    => 'ID inválido'
            ], JSON_UNESCAPED_UNICODE);
            return;
        }


        $arrData = $this->model->selectExcepcionById($intId);

        // 2) Verificar existencia
        if (!empty($arrData)) {
            echo json_encode([
                'status' => true,
                'data'   => $arrData
            ], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode([
                'status' => false,
                'msg'    => 'No se encontraron datos con este id'
            ], JSON_UNESCAPED_UNICODE);
        }
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
