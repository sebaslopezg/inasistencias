<?php

require_once './Libraries/Core/ReportPdf/ReportPdf.php';
require_once './Models/InformesModel.php';

class Reportes extends Controllers
{

    //private $pdf;
    private $nPdf;

    public function __construct()
    {
        $this->model = new InformesModel();
        //$this->pdf = new FPDF();
        $this->nPdf = new ReportPdf();
    }


    public function generarPdfAprendiz($idAprendiz)
    {

        $info = $this->model->selectFechasFaltas($idAprendiz);
        return  $this->nPdf->tabla("Reporte de Inasistencias del Aprendiz.", ['Nombre del Aprendiz', 'Fecha - Inasistencia'], [80, 70], $info, 'D');
    }

    public function generarPdfAsistencia($idFicha, $idInstructor, $fechaFiltro, $info)
    {

        $infoFicha = $info;
        $data = $this->model->selectInfoAprendiz($idFicha, $idInstructor, $fechaFiltro);


        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['status'] == 0 || $data[$i]['status'] == 2) {
                $data[$i]['status'] = 'Falto';
            }
            if ($data[$i]['status'] == 1 || $data[$i]['status'] == 3) {
                $data[$i]['status'] = 'No Asistio';
            }
        }
        return  $this->nPdf->formatoAsistencia($infoFicha, [50, 50, 40, 50], $data, 'D');
    }
}
