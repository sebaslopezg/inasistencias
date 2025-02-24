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

    public function generarPdfAsistencia($nombreFicha, $nombre_completo, $numeroFicha, $data)
    {

        return $this->nPdf->formatoAsistencia($nombreFicha, $nombre_completo, $numeroFicha, $data, 'D');
    }
}
