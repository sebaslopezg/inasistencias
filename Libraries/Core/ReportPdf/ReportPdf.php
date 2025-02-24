<?php

include_once './Libraries/Core/fpdf/fpdf.php';

class ReportPdf
{

    private $pdf;

    public function __construct()
    {
        $this->pdf = new FPDF();
    }

    public function tabla(String $titulo, array $CabeceraTabla, array $anchosCelda, array $data, $outPut)
    {

        $this->pdf->AddPage();
        $this->pdf->SetFont('Arial', 'B', 16);
        $this->pdf->SetTitle($titulo);

        $this->pdf->Cell(190, 6, $titulo, 0, 0, 'C');
        $this->pdf->Ln();
        $this->pdf->Ln();
        // Colores, ancho de línea y fuente en negrita
        $this->pdf->SetFillColor(60, 60, 60);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetFont('Arial', 'B', 13);

        // Calculamos el ancho de la tabla.
        $anchoTotalTabla = array_sum($anchosCelda);
        $xPos = ($this->pdf->GetPageWidth() - $anchoTotalTabla) / 2;

        $this->pdf->SetX($xPos); // Mover a la posición centrada
        // Cabecera
        $w = $anchosCelda;
        for ($i = 0; $i < count($CabeceraTabla); $i++)
            $this->pdf->Cell($w[$i], 9, $CabeceraTabla[$i], 1, 0, 'C', true);
        $this->pdf->Ln();

        // Restauración de colores y fuentes
        $this->pdf->SetFillColor(230, 230, 230);
        $this->pdf->SetTextColor(0);
        $this->pdf->SetFont('Arial', "", 13);

        // Datos
        $fill = false;
        foreach ($data as $row) {
            $this->pdf->SetX($xPos);
            $contador = 0;
            foreach ($row as $campo) {
                $this->pdf->Cell($w[$contador], 7, $campo, 'LR', 0, 'J', $fill);
                $contador++;
            }
            $this->pdf->Ln();
            $fill = !$fill;
        }
        // Línea de cierre
        $this->pdf->SetX($xPos);
        $this->pdf->Cell($anchoTotalTabla, 0, '', 'T');
        $tituloFormateado = str_replace(' ', '_', $titulo);
        $this->pdf->Output($dest = $outPut, $tituloFormateado . '.pdf', true);
    }

    public function formatoAsistencia( array $infoFicha, array $anchosCelda, array $data, $outPut)
    {

        // Logo
        $this->pdf->Image('sena_logo.png', 10, 10, 30); // Reemplazar con la imagen del logo
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->Cell(190, 6, 'CENTRO DE TECNOLOGIAS AGROINDUSTRIALES', 0, 1, 'C');
        $this->pdf->SetFont('Arial', 'B', 10);
        $this->pdf->Cell(190, 6, 'REGISTRO DE INASISTENCIA', 0, 1, 'C');
        $this->pdf->Cell(190, 6, 'DOCUMENTO DE APOYO', 0, 1, 'C');
        $this->pdf->Ln(3);

        // Encabezado tabla
        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->Cell(95, 6, 'VERSION: 3', 1, 0, 'L');
        $this->pdf->Cell(95, 6, 'Fecha: 15 de marzo de 2017', 1, 1, 'L');
        $this->pdf->Cell(190, 6, 'Copia no controlada', 1, 1, 'L');
        $this->pdf->Ln(3);

        $this->pdf->AddPage();
        $this->pdf->SetFont('Arial', '', 9);

        // Encabezado de ficha
        $this->pdf->Cell(50, 6, 'CODIGO DE FICHA:', 1);
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->Cell(50, 6, '2827725', 1);
        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->Cell(50, 6, 'PROGRAMA DE FORMACION:', 1);
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->Cell(40, 6, 'ADSO', 1, 1);
        $this->pdf->Ln(2);

        // Nombre del instructor
        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->Cell(50, 6, 'NOMBRE DEL INSTRUCTOR:', 1);
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->Cell(140, 6, 'JUAN CAMILO VANEGAS GONZALEZ', 1, 1);
        $this->pdf->Ln(2);

        // Encabezado de la tabla
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->Cell(10, 7, '#', 1, 0, 'C');
        $this->pdf->Cell(80, 7, 'NOMBRES APELLIDOS (APRENDIZ)', 1, 0, 'C');
        for ($i = 0; $i < 5; $i++) {
            $this->pdf->Cell(20, 7, 'Fecha', 1, 0, 'C');
        }
        $this->pdf->Ln();

        // Datos de ejemplo
        $this->pdf->SetFont('Arial', '', 9);
        $nombres = [
            "DIEGO ALEJANDRO VALDES RENTERIA",
            "FELIPE YUSTI MOSQUERA",
            "JHANCALO ROSADA MONTANCHEZ",
            "JUAN DANIEL SALAZAR HERNANDEZ",
            "JUAN DAVID LUNA RAMIREZ",
            "JUAN STEBAN GONZALEZ DIAZ",
            "LUNA LOPEZ GALLEGO",
            "NICOLAS MORALES CONTRERAS",
            "SEAN PAUL MORENO FLOREZ",
            "SEBASTIAN LOPEZ GALLEGO",
            "VICTOR HUGO GALEANO CARVAJAL"
        ];

        for ($i = 0; $i < count($nombres); $i++) {
            $this->pdf->Cell(10, 6, ($i + 1), 1, 0, 'C');
            $this->pdf->Cell(80, 6, $nombres[$i], 1, 0, 'L');
            for ($j = 0; $j < 5; $j++) {
                $this->pdf->Cell(20, 6, '', 1, 0, 'C'); // Espacio vacío para fechas
            }
            $this->pdf->Ln();
        }

        // Espacio para la firma
        $this->pdf->Ln(10);
        $this->pdf->Cell(80, 6, '', 0, 0);
        $this->pdf->Cell(50, 6, '__________________________', 0, 1, 'C');
        $this->pdf->Cell(80, 6, '', 0, 0);
        $this->pdf->Cell(50, 6, 'FIRMA DEL INSTRUCTOR', 0, 1, 'C');

        $this->pdf->Output($dest = $outPut, 'Asistencia' . '.pdf', true);
    }
}
