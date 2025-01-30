<?php
class Informes extends Controllers
{

    private $idInstru;
    public function __construct()
    {
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

    public function getFechaInstructor(int $numeroFicha)
    {
        $idInstructor = $_SESSION['idUsuarios'];
        $arrData = $this->model->selectFechaHorario($numeroFicha, $idInstructor);
        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    public function getAsistencia(int $idFicha)
    {
        $idInstructor = $_SESSION['idUsuarios'];
        $arrData = $this->model->selectInfoAprendiz($idInstructor, $idFicha);


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

    public function getAprendices(int $idFicha)
    {
        $idInstructor = $_SESSION['idUsuarios'];
        $arrData = $this->model->selectNombreAprendices($idFicha);

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
}
