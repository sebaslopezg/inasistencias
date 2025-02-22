<?php
require_once __DIR__ . '/../Models/NotificacionesModel.php';
class Dashboard extends Controllers
{
    private $NotificacionesModel;
    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url() . '/login');
        }
        $this->NotificacionesModel = new NotificacionesModel();
    }
    public function dashboard()
    {
        $data['page_title'] = "Página de dashboard";
        $data['page_name'] = "dashboard";
        $data['script'] = "dashboard";

        $this->views->getView($this, "dashboard", $data);
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
}
