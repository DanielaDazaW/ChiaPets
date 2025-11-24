<?php
include("../template/cabecera.php");
include("../config/bd.php");

// Totales simples
$totalMascotas = $conexion->query("SELECT COUNT(*) FROM mascotas WHERE estado=1")->fetchColumn();
$totalPersonas = $conexion->query("SELECT COUNT(*) FROM personas WHERE estado=1")->fetchColumn();
$totalOrganizaciones = $conexion->query("SELECT COUNT(*) FROM organizacion WHERE estado=1")->fetchColumn();
$totalReportes = $conexion->query("SELECT COUNT(*) FROM reportes WHERE estado=1")->fetchColumn();


// Mascotas por especie
$mascotasPorEspecie = $conexion->query("
    SELECT e.tipo_especie, COUNT(*) AS cantidad
    FROM mascotas m
    JOIN especie e ON m.id_especie = e.id_especie
    WHERE m.estado=1
    GROUP BY e.tipo_especie
")->fetchAll(PDO::FETCH_ASSOC);

// ... (otras consultas arriba)
$mascotasPorEdad = $conexion->query("
    SELECT 
        CASE 
            WHEN TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) <= 1 THEN 'Cachorro'
            WHEN TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) <= 4 THEN 'Joven'
            WHEN TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) <= 8 THEN 'Adulto'
            ELSE 'Senior'
        END AS grupo_edad,
        COUNT(*) AS cantidad
    FROM mascotas
    WHERE estado=1
    GROUP BY grupo_edad
")->fetchAll(PDO::FETCH_ASSOC);


// Mascotas por raza (top 5)
$mascotasPorRaza = $conexion->query("
    SELECT r.tipo_raza, COUNT(*) AS cantidad
    FROM mascotas m
    JOIN raza r ON m.id_raza = r.id_raza
    WHERE m.estado=1
    GROUP BY r.tipo_raza
    ORDER BY cantidad DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

// Mascotas por tamaño
$mascotasPorTamano = $conexion->query("
    SELECT t.tamano, COUNT(*) as cantidad
    FROM mascotas m
    JOIN tamano t ON m.id_tamano = t.id_tamano
    WHERE m.estado=1
    GROUP BY t.tamano
")->fetchAll(PDO::FETCH_ASSOC);

// Reportes por tipo
$reportesPorTipo = $conexion->query("
    SELECT tr.tipo_reporte, COUNT(*) as cantidad
    FROM reportes r
    JOIN tipo_reporte tr ON r.id_tipo_reporte = tr.id_tipo_reporte
    WHERE r.estado=1
    GROUP BY tr.tipo_reporte
")->fetchAll(PDO::FETCH_ASSOC);

// Vacunas por tipo
$vacunasPorTipo = $conexion->query("
    SELECT tv.tipo_vacuna, COUNT(*) as cantidad
    FROM vacunas v
    JOIN tipo_vacuna tv ON v.id_tipo_vacuna = tv.id_tipo_vacuna
    WHERE v.estado=1
    GROUP BY tv.tipo_vacuna
")->fetchAll(PDO::FETCH_ASSOC);

// Desparasitaciones por producto (top 5)
$desparaPorProducto = $conexion->query("
    SELECT p.producto, COUNT(*) as cantidad
    FROM desparasitaciones d
    JOIN producto p ON d.id_producto = p.id_producto
    WHERE d.estado=1
    GROUP BY p.producto
    ORDER BY cantidad DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

$campanasMasParticipaciones = $conexion->query("
    SELECT c.titulo, COUNT(*) AS total_participaciones
    FROM participacion_campania pc
    JOIN campanias c ON pc.id_campana = c.id_campana
    WHERE pc.estado = 1 AND c.estado = 1
    GROUP BY c.id_campana, c.titulo
    ORDER BY total_participaciones DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);


// Mascotas con más reportes (top 5)
$mascotasMasReportes = $conexion->query("
    SELECT m.nombre, COUNT(*) AS total_reportes
    FROM reportes r
    JOIN mascotas m ON r.id_mascota = m.id_mascota
    WHERE r.estado=1
    GROUP BY m.id_mascota
    ORDER BY total_reportes DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

// Personas con más mascotas (top 5)
$personasMasMascotas = $conexion->query("
    SELECT p.nombres, COUNT(*) AS total_mascotas
    FROM mascotas m
    JOIN personas p ON m.id_persona = p.id_persona
    WHERE m.estado=1
    GROUP BY p.id_persona
    ORDER BY total_mascotas DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);
// Organizaciones más activas por cantidad de reportes
$organizacionesMasReportes = $conexion->query("
    SELECT o.organizacion, COUNT(r.id_reporte) AS total_reportes
    FROM organizacion o
    LEFT JOIN mascotas m ON o.id_organizacion = m.id_organizacion AND m.estado=1
    LEFT JOIN reportes r ON m.id_mascota = r.id_mascota AND r.estado=1
    WHERE o.estado=1
    GROUP BY o.id_organizacion, o.organizacion
    HAVING total_reportes > 0
    ORDER BY total_reportes DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

// Mascotas en organizaciones vs personas normales
$mascotasOrgVsPers = $conexion->query("
    SELECT 
        (SELECT COUNT(*) FROM mascotas WHERE id_organizacion IS NOT NULL AND estado=1) AS mascotas_organizaciones,
        (SELECT COUNT(*) FROM mascotas WHERE id_organizacion IS NULL AND estado=1) AS mascotas_personas
")->fetch(PDO::FETCH_ASSOC);

// Tabla reportes más recientes
$reportesRecientes = $conexion->query("
    SELECT r.id_reporte, r.descripcion, r.fecha_reporte, r.estado, m.nombre AS mascota
    FROM reportes r
    JOIN mascotas m ON r.id_mascota = m.id_mascota
    WHERE r.estado=1
    ORDER BY r.fecha_reporte DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<style>
canvas {
  width: 100% !important;
  height: 300px !important;
}
</style>
<body>
<?php
date_default_timezone_set('America/Bogota');
?>

<div id="dashboardArea" class="container mt-4">
    <!-- Encabezado del reporte -->
    <div class="text-center mb-4 border-bottom pb-3">
        <h2 class="fw-bold text-primary">CHIAPET</h2>
        <h4>Dashboard de Estadísticas</h4>
        <p class="text-muted">Reporte generado el: <span id="fechaReporte"><?= date('d/m/Y H:i:s') ?></span></p>
    <div class="row my-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Mascotas</h5>
                    <p class="card-text fs-2"><?= $totalMascotas ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Personas</h5>
                    <p class="card-text fs-2"><?= $totalPersonas ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Organizaciones</h5>
                    <p class="card-text fs-2"><?= $totalOrganizaciones ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Reportes</h5>
                    <p class="card-text fs-2"><?= $totalReportes ?></p>
                </div>
            </div>
        </div>
    </div>
    <!-- Gráficas -->
    <div class="row">
    <div class="col-md-6 mb-4">
        <canvas id="mascotasEspecie"></canvas>
    </div>
    <div class="col-md-6 mb-4">
        <canvas id="mascotasEdad"></canvas>
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-4">
        <canvas id="mascotasRaza"></canvas>
    </div>
    <div class="col-md-6 mb-4">
        <canvas id="mascotasTamano"></canvas>
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-4">
        <canvas id="reportesTipo"></canvas>
    </div>
    <div class="col-md-6 mb-4">
        <canvas id="vacunasTipo"></canvas>
    </div>
</div>
<div class="row mt-4">
 <div class="row">
    <div class="col-md-6 mb-2">
        <canvas id="desparasitacionesProducto"></canvas>
    </div>
</div>

<!-- Tablas alineadas - campañas y reportes por organización -->
<div class="row align-items-start">
    <div class="col-md-6">
        <h5>Campañas con más participaciones</h5>
        <table class="table table-sm">
            <thead>
                <tr><th>Campaña</th><th>Participaciones</th></tr>
            </thead>
            <tbody>
            <?php foreach($campanasMasParticipaciones as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['titulo']) ?></td>
                    <td><?= $row['total_participaciones'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <h5>Reportes por organización</h5>
        <table class="table table-sm">
            <thead>
                <tr><th>Organización</th><th>Cantidad de reportes</th></tr>
            </thead>
            <tbody>
            <?php foreach($organizacionesMasReportes as $org): ?>
                <tr>
                    <td><?= htmlspecialchars($org['organizacion']) ?></td>
                    <td><?= $org['total_reportes'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
    <!-- Tablas resumen -->
    <div class="row">
        <div class="col-md-6">
            <h5>Mascotas con más reportes</h5>
            <table class="table table-sm">
                <thead><tr><th>Mascota</th><th>Reportes</th></tr></thead>
                <tbody>
                <?php foreach($mascotasMasReportes as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nombre']) ?></td>
                        <td><?= $row['total_reportes'] ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <h5>Usuarios con más mascotas</h5>
            <table class="table table-sm">
                <thead><tr><th>Persona</th><th>Mascotas</th></tr></thead>
                <tbody>
                <?php foreach($personasMasMascotas as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nombres']) ?></td>
                        <td><?= $row['total_mascotas'] ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h5>Reportes más recientes</h5>
            <table class="table table-sm">
                <thead><tr>
                    <th>ID</th><th>Mascota</th><th>Descripción</th><th>Fecha</th><th>Estado</th>
                </tr></thead>
                <tbody>
                <?php foreach($reportesRecientes as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id_reporte']) ?></td>
                        <td><?= htmlspecialchars($row['mascota']) ?></td>
                        <td><?= htmlspecialchars($row['descripcion']) ?></td>
                        <td><?= htmlspecialchars($row['fecha_reporte']) ?></td>
                        <td><?= htmlspecialchars($row['estado']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Mascotas por especie (Barras)
    const mascotasEspecie = new Chart(document.getElementById('mascotasEspecie'), {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($mascotasPorEspecie, 'tipo_especie')) ?>,
            datasets: [{
                label: 'Cantidad',
                data: <?= json_encode(array_column($mascotasPorEspecie, 'cantidad')) ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.7)'
            }]
        }
    });
    const mascotasEdad = new Chart(document.getElementById('mascotasEdad'), {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($mascotasPorEdad, 'grupo_edad')) ?>,
            datasets: [{
                label: 'Mascotas por grupo de edad',
                data: <?= json_encode(array_column($mascotasPorEdad, 'cantidad')) ?>,
                backgroundColor: 'rgba(52, 73, 94,0.7)'
            }]
        }
    });
    const mascotasRaza = new Chart(document.getElementById('mascotasRaza'), {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($mascotasPorRaza, 'tipo_raza')) ?>,
            datasets: [{
                label: 'Top 5 Razas',
                data: <?= json_encode(array_column($mascotasPorRaza, 'cantidad')) ?>,
                backgroundColor: 'rgba(255, 99, 132, 0.6)'
            }]
        }
    });
    const mascotasTamano = new Chart(document.getElementById('mascotasTamano'), {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($mascotasPorTamano, 'tamano')) ?>,
            datasets: [{
                label: 'Por tamaño',
                data: <?= json_encode(array_column($mascotasPorTamano, 'cantidad')) ?>,
                backgroundColor: 'rgba(241, 196, 15, 0.7)'
            }]
        }
    });
    const reportesTipo = new Chart(document.getElementById('reportesTipo'), {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($reportesPorTipo, 'tipo_reporte')) ?>,
            datasets: [{
                label: 'Reportes',
                data: <?= json_encode(array_column($reportesPorTipo, 'cantidad')) ?>,
                backgroundColor: 'rgba(52, 152, 219, 0.6)'
            }]
        }
    });
    const vacunasTipo = new Chart(document.getElementById('vacunasTipo'), {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($vacunasPorTipo, 'tipo_vacuna')) ?>,
            datasets: [{
                label: 'Vacunas aplicadas',
                data: <?= json_encode(array_column($vacunasPorTipo, 'cantidad')) ?>,
                backgroundColor: 'rgba(46, 204, 113, 0.7)'
            }]
        }
    });
    const desparasitacionesProducto = new Chart(document.getElementById('desparasitacionesProducto'), {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($desparaPorProducto, 'producto')) ?>,
            datasets: [{
                label: 'Productos más usados',
                data: <?= json_encode(array_column($desparaPorProducto, 'cantidad')) ?>,
                backgroundColor: 'rgba(155, 89, 182, 0.7)'
            }]
        }
    });
    const organizacionsActivas = new Chart(document.getElementById('organizacionsActivas'), {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($organizacionesMasReportes, 'organizacion')) ?>,
            datasets: [{
                label: 'Reporte por organización',
                data: <?= json_encode(array_column($organizacionesMasReportes, 'total_reportes')) ?>,
                backgroundColor: 'rgba(241, 196, 15, 0.6)'
            }]
        }
    });

</script>


<div class="row">
    <div class="col-md-12 text-center mb-4">
        <button id="btnDescargarPDF" class="btn btn-danger">
            Descargar Dashboard en PDF
        </button>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
document.getElementById('btnDescargarPDF').addEventListener('click', function() {
    // Selecciona SOLO el dashboard
    const dashboardDiv = document.getElementById('dashboardArea');
    // Asegura que toda el área esté visible para capturar
    html2canvas(dashboardDiv, {scrollY: -window.scrollY}).then(function(canvas) {
        const imgData = canvas.toDataURL('image/png');
        // Si el dashboard es alto, usa formato portrait y ajusta el alto:
        const pdf = new window.jspdf.jsPDF({
            orientation: 'portrait',
            unit: 'px',
            format: [canvas.width, canvas.height]
        });
        pdf.addImage(imgData, 'PNG', 0, 0, canvas.width, canvas.height);
        pdf.save('dashboard.pdf');
    });
});
</script>

</body>
</html>
<?php include("../template/pie.php"); ?>
