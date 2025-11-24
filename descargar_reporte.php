<?php
require('lib/fpdf/fpdf.php');

include("administrador/config/bd.php");

// Seguridad: Solo usuario logueado
session_start();
$idPersonaSesion = $_SESSION['usuario_id_persona'] ?? null;
if (!$idPersonaSesion) {
    header("Location: ../login.php");
    exit();
}

$idReporte = $_GET['id'] ?? null;

// Carga el reporte solo si es del usuario
$stmt = $conexion->prepare("SELECT r.*, m.nombre AS mascota_nombre, tr.tipo_reporte AS tipo 
    FROM reportes r
    LEFT JOIN mascotas m ON r.id_mascota = m.id_mascota
    LEFT JOIN tipo_reporte tr ON r.id_tipo_reporte = tr.id_tipo_reporte
    WHERE r.id_reporte=? AND r.estado=1 AND m.id_persona=?
");
$stmt->execute([$idReporte, $idPersonaSesion]);
$reporte = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reporte) {
    die('No se encontró el reporte');
}

// Generar PDF con diseño mejorado
$pdf = new FPDF();
$pdf->AddPage();

// --- LOGO ---
// --- LOGO ---
$pdf->Image('C:/xampp/htdocs/Chiapet/ChiaPets/img/chiapet4.jpg', 75, 10, 60); // Logo más grande y centrado
$pdf->SetY(75); // Posiciona el cursor debajo del logo (ajusta según tamaño del logo)


// --- TÍTULO PRINCIPAL ---
$pdf->SetFont('Arial', 'B', 18);
$pdf->SetTextColor(6, 64, 43); // RGB para #06402B (verde oscuro)[web:51]
$pdf->Cell(0, 10, utf8_decode('Reporte de Mascota'), 0, 1, 'C');
$pdf->Ln(5);

// --- LÍNEA SEPARADORA ---
$pdf->SetDrawColor(6, 64, 43);
$pdf->SetLineWidth(0.5);
$pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());
$pdf->Ln(10);

// --- INFORMACIÓN DEL REPORTE ---
$pdf->SetFont('Arial', '', 11);
$pdf->SetTextColor(0, 0, 0);

// Mascota
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 8, utf8_decode('Mascota:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, utf8_decode($reporte['mascota_nombre']), 0, 1);

// Tipo de Reporte
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 8, utf8_decode('Tipo de Reporte:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, utf8_decode($reporte['tipo']), 0, 1);

// Descripción (con MultiCell para texto largo)
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 8, utf8_decode('Descripción:'), 0, 1);
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(0, 6, utf8_decode($reporte['descripcion']), 0, 'L');
$pdf->Ln(2);

// Ubicación
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 8, utf8_decode('Ubicación:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, utf8_decode($reporte['ubicacion']), 0, 1);

// Fecha del Reporte
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 8, utf8_decode('Fecha Reporte:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, $reporte['fecha_reporte'], 0, 1);

$pdf->Ln(10);

// --- LÍNEA SEPARADORA INFERIOR ---
$pdf->SetDrawColor(6, 64, 43);
$pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());
$pdf->Ln(10);

// --- PIE DE PÁGINA ---
$pdf->SetFont('Arial', 'I', 9);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(0, 5, utf8_decode('Generado el: ') . date('d/m/Y H:i'), 0, 1, 'C');
$pdf->Cell(0, 5, utf8_decode('Sistema de Gestión de Mascotas - ChiaPet'), 0, 1, 'C');

// Salida del PDF
$pdf->Output('D', 'reporte_mascota_' . $idReporte . '.pdf');
exit;
?>
