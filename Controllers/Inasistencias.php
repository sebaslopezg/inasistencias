<?php

class Inasistencias extends Controllers
{
    public function __construct()
    {

        parent::__construct();
        session_start();
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url() . '/login');
        }
    }
    public function inasistencias()
    {


        $data['page_title'] = "Página de inasistencias";
        $data['page_name'] = "inasistencias";
        $data['script'] = "inasistencias";
        $this->views->getView($this, "inasistencias", $data);
    }

    public function setInasistencia($codigo)
    {
        $srtCodigo = strClean($codigo);

        $usuarioEncontrado = $this->model->selectCodigo($srtCodigo);

        if (!empty($usuarioEncontrado)) {
            $registro = $this->model->insertInasistencia($usuarioEncontrado['idUsuarios']);
            if ($registro) {
                $arrResponse = array('status' => true, 'msg' => 'Se registró la asistencia de ' . $usuarioEncontrado['nombre'] . ' ' . $usuarioEncontrado['apellido']);
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al intentar insertar la asistencia de ' . $usuarioEncontrado['nombre'] . ' ' . $usuarioEncontrado['apellido']);
            }
        } else {
            $arrResponse = array('status' => false, 'msg' => 'No se encontró ningún usuario con ese código');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }
}
