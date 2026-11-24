<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/fdl/public/vendor/fpdf/fpdf.php");

class ficha {
    
    public function pdf($data) {
        $registro = $data['registro'];
        $formacion = $data['formacion_academica'] ?? [];
        $documentos = $data['documentos'] ?? [];
        $hijos = $data['hijos'] ?? [];

        $pdf = new FPDF('P', 'mm', 'Letter');
        $pdf->AddPage();
        $pdf->SetMargins(20, 20, 20);

        // --- ENCABEZADO ---
        $pdf->SetFillColor(44, 62, 80); 
        $pdf->Rect(0, 0, 216, 35, 'F'); 
        
        $pdf->SetY(10);
        $pdf->SetFont('Arial', 'B', 18);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(0, 10, utf8_decode("FICHA DE REGISTRO LABORAL"), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 5, utf8_decode("SISTEMA DE GESTIÓN FDL"), 0, 1, 'C');
        
        $pdf->Ln(15);
        $pdf->SetTextColor(0, 0, 0);

        // --- SECCIÓN: DATOS PERSONALES ---
        $this->drawSectionTitle($pdf, "DATOS PERSONALES");
        $y_inicio_datos = $pdf->GetY();

        // ESPACIO PARA LA FOTO (35x40mm)
        $foto_ancho = 35;
        $foto_alto = 40;
        $foto_x = 160; 
        $foto_y = $y_inicio_datos + 2;

        $fotoPath = $_SERVER['DOCUMENT_ROOT'] . "/fdl/documentos/" . $registro['id'] . "/" . $registro['foto'];
        
        if (!empty($registro['foto']) && file_exists($fotoPath)) {
            $pdf->Image($fotoPath, $foto_x, $foto_y, $foto_ancho, $foto_alto);
        } else {
            $pdf->SetDrawColor(200, 200, 200);
            $pdf->Rect($foto_x, $foto_y, $foto_ancho, $foto_alto);
            $pdf->SetXY($foto_x, $foto_y + ($foto_alto/2) - 2);
            $pdf->SetFont('Arial', 'I', 8);
            $pdf->Cell($foto_ancho, 5, "Sin Foto", 0, 0, 'C');
        }

        // TEXTO DATOS PERSONALES
        $pdf->SetY($y_inicio_datos + 2);
        $txt_col_ancho = 135; 

        $this->drawRow($pdf, "Cédula:", $registro['cedula'], $txt_col_ancho);
        $this->drawRow($pdf, "Nombres:", utf8_decode($registro['nombres']), $txt_col_ancho);
        $this->drawRow($pdf, "Apellidos:", utf8_decode($registro['apellidos']), $txt_col_ancho);
        $this->drawRow($pdf, "Sexo:", ($registro['sexo'] === 'M' ? 'Masculino' : 'Femenino'), $txt_col_ancho);
        $this->drawRow($pdf, "Teléfono:", $registro['telefono'], $txt_col_ancho);
        
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(40, 7, utf8_decode("Dirección:"), 0, 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->MultiCell(95, 7, utf8_decode($registro['direccion']), 0, 'L');
        
        $y_final_seccion = max($pdf->GetY(), $foto_y + $foto_alto);
        $pdf->SetY($y_final_seccion + 10);

        // --- SECCIÓN: FORMACIÓN ACADÉMICA ---
        $this->drawSectionTitle($pdf, "FORMACIÓN ACADÉMICA");
        // Usamos el índice 'experiencia' que viene del alias en tu modelo
        $this->drawRow($pdf, "Título:", utf8_decode($formacion['titulo_obtenido'] ?? 'N/A'));
        $this->drawRow($pdf, "Experiencia:", (($formacion['experiencia'] ?? '0') . " años"));
        $this->drawRow($pdf, "Certificados:", utf8_decode($formacion['certificados'] ?? 'N/A'));

        $pdf->Ln(8);

        // --- SECCIÓN: DOCUMENTACIÓN ---
        $this->drawSectionTitle($pdf, "DOCUMENTOS CONSIGNADOS");
        $pdf->SetFont('Arial', '', 10);
        
        $docs_mapeo = [
            'sintesis'  => utf8_decode('Síntesis Curricular'),
            'cedula'    => utf8_decode('Copia de Cédula'),
            'rif'       => 'RIF Actualizado',
            'nacimiento'=> 'Partida de Nacimiento',
            'titulo'    => utf8_decode('Título Académico'),
            'permisos'  => 'Permisos Laborales',
            'salud'     => 'Certificado de Salud'
        ];

        $col = 0;
        foreach ($docs_mapeo as $campo => $label) {
            $entregado = (!empty($documentos[$campo]) && $documentos[$campo] !== 'Ninguno') ? "[ ENTREGADO ]" : "[ PENDIENTE ]";
            
            if($entregado == "[ ENTREGADO ]") {
                $pdf->SetFont('Arial', 'B', 10);
            } else {
                $pdf->SetFont('Arial', '', 10);
            }

            $pdf->Cell(85, 7, $label . ": " . $entregado, 0, ($col % 2 == 0 ? 0 : 1));
            $col++;
        }

        $pdf->Output('I', 'Ficha_' . $registro['cedula'] . '.pdf');
        exit;
    }

    private function drawSectionTitle($pdf, $title) {
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetDrawColor(44, 62, 80);
        $pdf->Cell(0, 8, utf8_decode("  " . $title), "L", 1, 'L', true);
        $pdf->Ln(2);
    }

    private function drawRow($pdf, $label, $value, $limit = 0) {
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(40, 7, utf8_decode($label), 0, 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell($limit, 7, utf8_decode($value), 0, 1);
    }
}