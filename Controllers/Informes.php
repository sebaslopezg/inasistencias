
<?php
require_once './Controllers/Reportes.php';
class Informes extends Controllers
{

    private $nombreInstructor;
    public function __construct()
    {
        $this->controller = new Reportes();
        parent::__construct();
        session_start();
        if (empty($_SESSION['login'])) {

            header('location:' . base_url() . '/login');
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
            <button type="button"  data-action="info" data-id="' . $arrData[$i]['id'] . '" class="btn btn-primary rounded-pill"><i class="bi bi-info-circle-fill"></i></button>
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

    public function generarPdf(int $data)
    {

        $arrData = $this->controller->generarPdfAprendiz($data);
        echo $arrData;
    }
    public function generarPdfAsi()
    {
        /* $nombre = $_POST['nombre'];
        $numero = $_POST['numero'];
        $nombre_completo = $_SESSION['userData']['nombre'] . " " . $_SESSION['userData']['apellido'];
        // serializamos el JSON que nos llega desde el request y utlizamos json_decode() y le indicamos que lo covierta en un array assoc
        $infoFicha = json_decode($_POST['infoGeneral'], true); */

        $arrData = $this->controller->generarPdfAsistencia(/* $nombre, $nombre_completo, $numero, $infoFicha */);
        echo $arrData;
    }

    public function getAprendices(int $idFicha)
    {
        $idInstructor = $_SESSION['idUsuarios'];
        $arrData = $this->model->selectNombreAprendices($idFicha);

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
}
