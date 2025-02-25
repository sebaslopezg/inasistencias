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
    public function formatoAsistencia(/* string $nombreFicha, string $nombreInstru, string $numeroFicha, array $data */$outPut)
    {

        $data = [
            [
                "aprendiz" => "Andrés Morales",
                "asistencias" => [
                    ["fecha" => "2025-11-01", "estado" => "Asistio"],
                    ["fecha" => "2025-11-06", "estado" => "Falto"],
                    ["fecha" => "2025-11-15", "estado" => "Asistio"],
                    ["fecha" => "2025-11-19", "estado" => "Asistio"]
                ]
            ],
            [
                "aprendiz" => "Felipe Yusti",
                "asistencias" => [
                    ["fecha" => "2025-11-01", "estado" => "Asistio"],
                    ["fecha" => "2025-11-06", "estado" => "Falto"],
                    ["fecha" => "2025-11-15", "estado" => "Asistio"],
                    ["fecha" => "2025-11-19", "estado" => "Asistio"]
                ]
            ],
            [
                "aprendiz" => "Daniel Salazar",
                "asistencias" => [
                    ["fecha" => "2025-11-01", "estado" => "Asistio"],
                    ["fecha" => "2025-11-06", "estado" => "Falto"],
                    ["fecha" => "2025-11-15", "estado" => "Asistio"],
                    ["fecha" => "2025-11-19", "estado" => "Asistio"]
                ]
            ]
        ];

        $this->pdf->AddPage("LANDSCAPE", "LETTER");
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTitle(title: "Formato Asistencia - Ficha");
        // Título

        $this->pdf->Cell(210, 7, 'CENTRO DE TECNOLOGIAS AGROINDUSTRIALES', 1, 0, 'C');
        $this->pdf->Cell(50, 7, 'VERSION: 3', 1, 1, 'C');
        $this->pdf->SetFont('Arial', 'B', 10);
        $this->pdf->Cell(210, 7, 'REGISTRO DE INASISTENCIA', 1, 0, 'C');
        $this->pdf->Cell(50, 7, 'Fecha: 02 de febrero de 2025', 1, 1, 'C');
        $this->pdf->Cell(210, 7, 'DOCUMENTO DE APOYO', 1, 0, 'C');
        $this->pdf->Cell(50, 7, 'Copia no controlada', 1, 1, 'C');
        $this->pdf->Ln(3);

        // Información general
        $this->pdf->SetFont('Arial', '', 12);
        $this->pdf->Ln(3);

        // Información de ficha
        $this->pdf->SetFont('Arial', '', 11);
        $this->pdf->Cell(56, 6, 'CODIGO DE FICHA:', 1);
        $this->pdf->SetFont('Arial', 'B', 11);
        $this->pdf->Cell(45, 6, "2827725", 1, 0, "C");
        $this->pdf->SetFont('Arial', '', 11);
        $this->pdf->Cell(62, 6, 'PROGRAMA DE FORMACION:', 1);
        $this->pdf->SetFont('Arial', 'B', size: 10);
        $this->pdf->Cell(97, 6, "ANALISIS Y DESARROLLO DE SOFTWARE", 1, 1, "C");
        $this->pdf->Ln(0);

        // Nombre del instructor
        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->Cell(56, 6, 'NOMBRE DEL INSTRUCTOR:', 1);
        $this->pdf->SetFont('Arial', 'B', 10);
        $this->pdf->Cell(204, 6, "JUAN CAMILO VANEGAS  GONZALEZ ", 1, 1, "C");
        $this->pdf->Ln(0);

        // Encabezado de la tabla
        $this->pdf->SetFont('Arial', 'B', 10);
        $this->pdf->Cell(10, 7, '#', 1, 0, 'C');
        $this->pdf->Cell(70, 7, 'NOMBRES APELLIDOS (APRENDIZ)', 1, 0, 'C');
        //$this->pdf->Cell(20, 7, 'Fecha', 1, 0, 'C');

        $datos = [
            ["fecha" => "2025-11-01"],
            ["fecha" => "2025-11-06"],
            ["fecha" => "2025-11-15"],
            ["fecha" => "2025-11-19"]
        ];
        foreach ($datos as $info) {

            $this->pdf->Cell(30, 7, $info['fecha'], 1, 0, 'C');
        }
        $this->pdf->Cell(30, 7, "", 1, 0, 'C');
        $this->pdf->Cell(30, 7, "", 1, 0, 'C');
        $this->pdf->Ln();

        // Contenido de la tabla
        $this->pdf->SetFont('Arial', '', 10);
        $i = 1;

        foreach ($data as $aprendiz) {
            $this->pdf->Cell(10, 7, $i, 1, 0, 'C');
            $this->pdf->Cell(70, 7, $aprendiz['aprendiz'], 1, 0, 'L');

            // Asegurar que haya 5 valores de asistencia
            $asistencias = $aprendiz['asistencias'];
            while (count($asistencias) < 5) {
                $asistencias[] = ['estado' => ''];  // Rellenar con valores vacíos
            }

            foreach ($asistencias as $asistencia) {
                $this->pdf->Cell(30, 7, $asistencia['estado'], 1, 0, 'C');
            }

            $this->pdf->Ln();
            $i++;
        }

        // Espacio para la firma
        $this->pdf->Ln(10);
        $this->pdf->Cell(25, 6, '', 0, 0);
        $this->pdf->Cell(25, 6, '_____________________________________', 0, 1, 'C');
        $this->pdf->Cell(15, 6, '', 0, 0);
        $this->pdf->Cell(50, 6, 'FIRMA DEL INSTRUCTOR', 0, 1, 'C');

        // Salida del PDF
        $this->pdf->Output($outPut, "Asistencia" . "ADSO" . ".pdf", true);


        // $this->pdf = new FPDF();
        /*  $this->pdf->AddPage();
        $this->pdf->SetFont('Arial', 'B', 16);
        $this->pdf->Cell(40, 10, '¡Hola, Mundo!');
        $this->pdf->Output($dest = $outPut, "ejemlo" . '.pdf', true); */

        // $this->pdf->Output($dest = $outPut, $tituloFormateado . '.pdf', true);
    }
}
