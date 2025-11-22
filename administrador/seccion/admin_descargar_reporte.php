<?php
require('../../lib/fpdf/fpdf.php');
include("../config/bd.php");

// SEGURIDAD: Solo usuario logueado (ajusta según tu sistema)
session_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$idReporte = $_GET['id'] ?? null;

// Cargar el reporte (sin filtro de dueño)
$stmt = $conexion->prepare("
    SELECT r.*, m.nombre AS mascota, tr.tipo_reporte, er.estado_reporte,
        CASE 
            WHEN p.nombres IS NOT NULL THEN CONCAT(p.nombres, ' ', p.apellidos, ' (', p.numero_documento, ')')
            WHEN o.organizacion IS NOT NULL THEN CONCAT(o.organizacion, ' (ORG)')
            ELSE 'Sin dueño'
        END AS duenio
    FROM reportes r
    LEFT JOIN mascotas m ON r.id_mascota = m.id_mascota
    LEFT JOIN personas p ON m.id_persona = p.id_persona
    LEFT JOIN organizacion o ON m.id_organizacion = o.id_organizacion
    LEFT JOIN tipo_reporte tr ON r.id_tipo_reporte = tr.id_tipo_reporte
    LEFT JOIN estado_reporte er ON r.id_estado_reporte = er.id_estado_reporte
    WHERE r.id_reporte = ?
");
$stmt->execute([$idReporte]);
$reporte = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reporte) {
    die('No se encontró el reporte');
}

// PDF Layout mejorado
$pdf = new FPDF();
$pdf->AddPage();
$pdf->Image('C:/xampp/htdocs/Chiapet/ChiaPets/img/chiapet4.jpg', 75, 10, 60); 
$pdf->SetY(75);
$pdf->SetFont('Arial', 'B', 18);
$pdf->SetTextColor(6, 64, 43);
$pdf->Cell(0, 10, utf8_decode('Reporte de Mascota'), 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetDrawColor(6, 64, 43);
$pdf->SetLineWidth(0.5);
$pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());
$pdf->Ln(10);

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 8, utf8_decode('Mascota:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, utf8_decode($reporte['mascota']), 0, 1);

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 8, utf8_decode('Dueño:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, utf8_decode($reporte['duenio']), 0, 1);

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 8, utf8_decode('Tipo de Reporte:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, utf8_decode($reporte['tipo_reporte']), 0, 1);

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 8, utf8_decode('Estado Reporte:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, utf8_decode($reporte['estado_reporte']), 0, 1);

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 8, utf8_decode('Descripción:'), 0, 1);
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(0, 6, utf8_decode($reporte['descripcion']), 0, 'L');
$pdf->Ln(2);

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 8, utf8_decode('Ubicación:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, utf8_decode($reporte['ubicacion']), 0, 1);

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 8, utf8_decode('Fecha Reporte:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, $reporte['fecha_reporte'], 0, 1);

$pdf->Ln(10);
$pdf->SetDrawColor(6, 64, 43);
$pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());
$pdf->Ln(10);

$pdf->SetFont('Arial', 'I', 9);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(0, 5, utf8_decode('Generado el: ') . date('d/m/Y H:i'), 0, 1, 'C');
$pdf->Cell(0, 5, utf8_decode('Sistema de Gestión de Mascotas - ChiaPet'), 0, 1, 'C');

$pdf->Output('D', 'reporte_mascota_' . $idReporte . '.pdf');
exit;
?>
