
<?php
require_once './Controllers/Reportes.php';
require_once __DIR__ . '/../Models/NotificacionesModel.php';
class Informes extends Controllers
{

    private $idInstru;
    public function __construct()
    {
        $this->controller = new Reportes();
        parent::__construct();
        session_start();
        if (empty($_SESSION['login'])) {

            header('location:' . base_url() . '/login');
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

    public function informes()
    {
        $data['page_title'] = "Página de Informes";
        $data['page_name'] = "informes";
        $data['script'] = "informes";
        $this->views->getView($this, "informes", $data);
    }

    public function getFichas()
    {
        $idInstructor = $_SESSION['idUsuarios'];
        $arrData = $this->model->selectFicha($idInstructor);

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    public function getInfoAprendicesFicha(int $idFicha)
    {
        $idInstructor = $_SESSION['idUsuarios'];
        $arrData = $this->model->selectInfoAprendicesById($idFicha, $idInstructor);

        for ($i = 0; $i < count($arrData); $i++) {
            $arrData[$i]['accion'] = '
            <button type="button" data-action="info" data-id="' . $arrData[$i]['id'] . '" class="btn btn-primary"><i class="bi bi-info-circle-fill"></i></button>
            ';
        }

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    public function getFaltas(int $idAprendiz)
    {
        $arrData = $this->model->selectFechasFaltas($idAprendiz);

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }

    public function getFechaInstructor(string $data)
    {
        $idInstructor = $_SESSION['idUsuarios'];
        $info = explode(',', $data);
        $arrData = $this->model->selectFechaHorario($info[0], $idInstructor, $info[1]);

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    public function getAsistencia(string $data)
    {
        $idInstructor = $_SESSION['idUsuarios'];
        $info = explode(',', $data);

        $arrData = $this->model->selectInfoAprendiz($idInstructor, $info[0], $info[1]);

        for ($i = 0; $i < count($arrData); $i++) {


            if ($arrData[$i]['status'] == 0 || $arrData[$i]['status'] == 2) {
                $arrData[$i]['status'] = '<span class="badge rounded-pill bg-success">Asistio</span>';
            }
            if ($arrData[$i]['status'] == 1 || $arrData[$i]['status'] == 3) {
                $arrData[$i]['status'] = '<span class="badge rounded-pill bg-danger">Falto</span>';
            }
        }

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }

    /* public function generarPdf(int $data)
    {

        $arrData = $this->controller->generarPdfAprendiz($data);
        echo $arrData;
    }
    public function generarPdfAsi(int $data)
    {

        $arrData = $this->controller->generarPdfAsistencia($data);
        echo $arrData;
    } */

    public function getAprendices(int $idFicha)
    {
        $idInstructor = $_SESSION['idUsuarios'];
        $arrData = $this->model->selectNombreAprendices($idFicha);

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
}
