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
        return  $this->nPdf->tabla("Reporte de Inasistencias del Aprendiz", ['Nombre del Aprendiz', 'Fecha'], [40, 90, 60], $info, 'D');
    }

    /*  public function generarPdfAsistencia(int $Ficha)
    {
        $this->Ficha->$Ficha;
        $info = $this->model->getAprendicesReporte($this->Ficha);
        return  $this->nPdf->tabla("Formato de Asistencia", ['Nombre', 'Apellido', 'Documento', 'Correo'], [50, 50, 40, 50], $aprendicesData, 'D');
    } */
}
